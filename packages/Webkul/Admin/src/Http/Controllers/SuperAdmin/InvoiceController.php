<?php

namespace Webkul\Admin\Http\Controllers\SuperAdmin;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Core\Models\Invoice;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the invoices.
     */
    public function index(Request $request): View
    {
        $query = Invoice::with(['company', 'subscription.plan'])->orderBy('created_at', 'desc');

        // Simple filtering if needed
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('company_id') && $request->company_id != '') {
            $query->where('company_id', $request->company_id);
        }

        $invoices = $query->get();
        $companies = \Webkul\Core\Models\Company::all();

        return view('admin::super-admin.invoices.index', compact('invoices', 'companies'));
    }

    /**
     * Display the specified invoice.
     */
    public function show($id): View
    {
        $invoice = Invoice::with(['company', 'subscription.plan'])->findOrFail($id);

        return view('admin::super-admin.invoices.show', compact('invoice'));
    }
}
