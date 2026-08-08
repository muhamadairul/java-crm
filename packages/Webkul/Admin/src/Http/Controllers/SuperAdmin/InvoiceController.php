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

    /**
     * Mark an invoice as paid manually.
     */
    public function markAsPaid($id): RedirectResponse
    {
        $invoice = Invoice::findOrFail($id);

        if ($invoice->status === 'paid') {
            session()->flash('error', 'Invoice ini sudah berstatus Lunas (Paid).');
            return redirect()->back();
        }

        $invoice->update([
            'status'         => 'paid',
            'paid_at'        => now(),
            'payment_method' => $invoice->payment_method ?: 'manual',
            'notes'          => json_encode(array_merge(
                json_decode($invoice->notes ?? '{}', true) ?: [],
                ['manual_paid_at' => now()->toISOString(), 'marked_by' => 'super_admin']
            )),
        ]);

        // Also activate the subscription if it exists and isn't active
        if ($invoice->subscription_id) {
            $subscription = \Webkul\Core\Models\Subscription::find($invoice->subscription_id);
            if ($subscription && $subscription->status !== 'active') {
                $subscription->update([
                    'status'    => 'active',
                    'starts_at' => now(),
                    'ends_at'   => now()->addMonth(),
                ]);
            }
        }

        session()->flash('success', "Invoice #{$invoice->invoice_number} berhasil ditandai sebagai Lunas (Paid).");

        return redirect()->back();
    }
}
