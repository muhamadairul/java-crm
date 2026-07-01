<?php

namespace Webkul\Admin\Http\Controllers\SuperAdmin;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Core\Models\Company;
use Webkul\Core\Models\Plan;

class CompanyController extends Controller
{
    /**
     * Display a listing of the companies.
     */
    public function index(): View
    {
        $companies = Company::with('plan')->orderBy('created_at', 'desc')->get();
        return view('admin::super-admin.companies.index', compact('companies'));
    }

    /**
     * Toggle the active/inactive status of a company.
     */
    public function toggleStatus($id): RedirectResponse
    {
        $company = Company::findOrFail($id);
        
        // Prevent disabling main platform host or default seeded company if desired,
        // but here we toggle it dynamically
        $company->is_active = !$company->is_active;
        $company->save();

        $statusText = $company->is_active ? 'diaktifkan' : 'dinonaktifkan';
        session()->flash('success', "Perusahaan {$company->name} berhasil {$statusText}.");

        return redirect()->back();
    }

    /**
     * Show the form for editing the company.
     */
    public function edit($id): View
    {
        $company = Company::findOrFail($id);
        $plans = Plan::where('is_active', true)->get();

        return view('admin::super-admin.companies.edit', compact('company', 'plans'));
    }

    /**
     * Update the company details.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $company = Company::findOrFail($id);

        $request->validate([
            'name'    => 'required|string|max:255',
            'plan_id' => 'required|exists:plans,id',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:50',
        ]);

        $company->update([
            'name'    => $request->name,
            'plan_id' => $request->plan_id,
            'email'   => $request->email,
            'phone'   => $request->phone,
        ]);

        // If company plan changed, also update the active subscription to match
        $activeSub = $company->activeSubscription();
        if ($activeSub && $activeSub->plan_id != $request->plan_id) {
            $activeSub->plan_id = $request->plan_id;
            $activeSub->save();
        }

        session()->flash('success', "Detail perusahaan {$company->name} berhasil diperbarui.");

        return redirect()->route('super_admin.companies.index');
    }

    /**
     * Display the specified company details.
     */
    public function show($id): View
    {
        $company = Company::with(['plan', 'subscriptions'])->findOrFail($id);
        
        // Count limits and current usage
        $userCount = DB::table('users')->where('company_id', $id)->count();
        $leadCount = DB::table('leads')->where('company_id', $id)->count();
        
        $plan = $company->plan;
        
        $quota = [
            'users' => [
                'used'  => $userCount,
                'limit' => $plan ? $plan->max_users : 0,
                'pct'   => $plan && $plan->max_users > 0 ? min(100, round(($userCount / $plan->max_users) * 100)) : 0,
            ],
            'leads' => [
                'used'  => $leadCount,
                'limit' => $plan ? $plan->max_leads : 0,
                'pct'   => $plan && $plan->max_leads > 0 ? min(100, round(($leadCount / $plan->max_leads) * 100)) : 0,
            ],
        ];

        $users = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->select('users.*', 'roles.name as role_name')
            ->where('users.company_id', $id)
            ->get();

        $invoices = DB::table('invoices')
            ->where('company_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        $activeSubscription = $company->activeSubscription();

        return view('admin::super-admin.companies.show', compact('company', 'quota', 'users', 'invoices', 'activeSubscription'));
    }
}
