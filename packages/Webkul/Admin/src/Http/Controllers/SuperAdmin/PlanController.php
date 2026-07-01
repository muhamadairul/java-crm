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
        return view('admin::super-admin.plans.index', compact('plans'));
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
}
