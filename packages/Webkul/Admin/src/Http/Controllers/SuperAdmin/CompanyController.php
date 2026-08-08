<?php

namespace Webkul\Admin\Http\Controllers\SuperAdmin;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
     * Show the form for creating a new company.
     */
    public function create(): View
    {
        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin::super-admin.companies.create', compact('plans'));
    }

    /**
     * Store a newly created company.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'required|string|max:100|unique:companies,slug|alpha_dash',
            'email'       => 'nullable|email|max:255',
            'phone'       => 'nullable|string|max:50',
            'address'     => 'nullable|string|max:500',
            'plan_id'     => 'required|exists:plans,id',
            'admin_name'  => 'required|string|max:255',
            'admin_email' => 'required|email|max:255|unique:users,email',
        ]);

        DB::beginTransaction();

        try {
            // Create the company
            $company = Company::create([
                'name'      => $request->name,
                'slug'      => $request->slug,
                'domain'    => $request->slug,
                'email'     => $request->email,
                'phone'     => $request->phone,
                'address'   => $request->address,
                'plan_id'   => $request->plan_id,
                'is_active' => true,
            ]);

            // Get Company Admin role (role_id = 2 typically)
            $adminRoleId = DB::table('roles')
                ->where('name', 'Company Admin')
                ->value('id') ?? 2;

            // Create initial admin user for this company
            DB::table('users')->insert([
                'name'       => $request->admin_name,
                'email'      => $request->admin_email,
                'password'   => Hash::make('password123'),
                'role_id'    => $adminRoleId,
                'company_id' => $company->id,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create an active subscription
            $plan = Plan::find($request->plan_id);
            DB::table('subscriptions')->insert([
                'company_id' => $company->id,
                'plan_id'    => $request->plan_id,
                'status'     => 'active',
                'starts_at'  => now(),
                'ends_at'    => now()->addMonth(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            session()->flash('success', "Perusahaan {$company->name} berhasil dibuat. Admin awal: {$request->admin_email} (password: password123)");

            return redirect()->route('super_admin.companies.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal membuat perusahaan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
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
     * Remove the specified company (soft delete approach - deactivate & archive).
     */
    public function destroy($id): RedirectResponse
    {
        $company = Company::findOrFail($id);
        $companyName = $company->name;

        DB::beginTransaction();

        try {
            // Deactivate all users of the company
            DB::table('users')->where('company_id', $id)->update(['status' => 0]);

            // Cancel active subscriptions
            DB::table('subscriptions')
                ->where('company_id', $id)
                ->where('status', 'active')
                ->update(['status' => 'cancelled', 'ends_at' => now()]);

            // Deactivate the company
            $company->is_active = false;
            $company->save();

            // Actually delete the company and related data
            DB::table('leads')->where('company_id', $id)->delete();
            DB::table('users')->where('company_id', $id)->delete();
            DB::table('subscriptions')->where('company_id', $id)->delete();
            DB::table('invoices')->where('company_id', $id)->delete();
            $company->delete();

            DB::commit();

            session()->flash('success', "Perusahaan {$companyName} dan seluruh datanya berhasil dihapus.");
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal menghapus perusahaan: ' . $e->getMessage());
        }

        return redirect()->route('super_admin.companies.index');
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
