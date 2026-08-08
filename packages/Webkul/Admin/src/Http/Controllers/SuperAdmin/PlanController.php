<?php

namespace Webkul\Admin\Http\Controllers\SuperAdmin;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Core\Models\Plan;

class PlanController extends Controller
{
    /**
     * Display a listing of the plans.
     */
    public function index(): View
    {
        $plans = Plan::orderBy('sort_order', 'asc')->get();

        // Count companies per plan for usage info
        $planUsage = DB::table('companies')
            ->select('plan_id', DB::raw('count(*) as count'))
            ->groupBy('plan_id')
            ->pluck('count', 'plan_id')
            ->toArray();

        return view('admin::super-admin.plans.index', compact('plans', 'planUsage'));
    }

    /**
     * Show the form for creating a new plan.
     */
    public function create(): View
    {
        return view('admin::super-admin.plans.create');
    }

    /**
     * Store a newly created plan.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'code'           => 'required|string|max:50|unique:plans,code|alpha_dash',
            'price'          => 'required|numeric|min:0',
            'billing_cycle'  => 'required|in:monthly,yearly',
            'max_users'      => 'required|integer|min:1',
            'max_leads'      => 'required|integer|min:1',
            'max_storage_mb' => 'required|integer|min:1',
            'description'    => 'nullable|string|max:1000',
        ]);

        $maxSort = Plan::max('sort_order') ?? 0;

        Plan::create([
            'name'           => $request->name,
            'code'           => $request->code,
            'price'          => $request->price,
            'billing_cycle'  => $request->billing_cycle,
            'max_users'      => $request->max_users,
            'max_leads'      => $request->max_leads,
            'max_storage_mb' => $request->max_storage_mb,
            'description'    => $request->description,
            'is_active'      => true,
            'sort_order'     => $maxSort + 1,
        ]);

        session()->flash('success', "Paket plan '{$request->name}' berhasil dibuat.");

        return redirect()->route('super_admin.plans.index');
    }

    /**
     * Show the form for editing the plan.
     */
    public function edit($id): View
    {
        $plan = Plan::findOrFail($id);
        return view('admin::super-admin.plans.edit', compact('plan'));
    }

    /**
     * Update the plan details.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $plan = Plan::findOrFail($id);

        $request->validate([
            'name'           => 'required|string|max:255',
            'price'          => 'required|numeric|min:0',
            'max_users'      => 'required|integer|min:1',
            'max_leads'      => 'required|integer|min:1',
            'max_storage_mb' => 'required|integer|min:1',
            'description'    => 'nullable|string',
        ]);

        $plan->update([
            'name'           => $request->name,
            'price'          => $request->price,
            'max_users'      => $request->max_users,
            'max_leads'      => $request->max_leads,
            'max_storage_mb' => $request->max_storage_mb,
            'description'    => $request->description,
        ]);

        session()->flash('success', "Paket plan {$plan->name} berhasil diperbarui.");

        return redirect()->route('super_admin.plans.index');
    }

    /**
     * Toggle the active/inactive status of a plan.
     */
    public function toggleStatus($id): RedirectResponse
    {
        $plan = Plan::findOrFail($id);
        $plan->is_active = !$plan->is_active;
        $plan->save();

        $statusText = $plan->is_active ? 'diaktifkan' : 'dinonaktifkan';
        session()->flash('success', "Paket {$plan->name} berhasil {$statusText}.");

        return redirect()->back();
    }
}
