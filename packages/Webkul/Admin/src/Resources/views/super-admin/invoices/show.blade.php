<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.super_admin.invoices.detail_title', ['number' => $invoice->invoice_number])
    </x-slot>

    <div class="flex flex-col gap-4">
        {{-- Header --}}
        <div class="flex items-center justify-between rounded-xl border border-gray-200 bg-white px-6 py-5 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-1">
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                    @lang('admin::app.super_admin.invoices.detail_title', ['number' => $invoice->invoice_number])
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    @lang('admin::app.super_admin.invoices.detail_desc', ['name' => $invoice->company->name ?? '-'])
                </p>
            </div>
            <div class="flex items-center gap-3">
                {{-- Mark as Paid (only for non-paid invoices) --}}
                @if($invoice->status !== 'paid')
                    <form action="{{ route('super_admin.invoices.mark_paid', ['id' => $invoice->id]) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('admin::app.super_admin.invoices.mark_paid_confirm', ['number' => $invoice->invoice_number]) }}')">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white shadow-lg shadow-emerald-200 transition-all hover:bg-emerald-700 dark:shadow-none">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @lang('admin::app.super_admin.invoices.mark_as_paid')
                        </button>
                    </form>
                @endif

                <a href="{{ route('super_admin.invoices.index') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-xs font-bold text-gray-700 shadow-sm transition-all hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    @lang('admin::app.super_admin.invoices.back')
                </a>
                <span class="inline-flex items-center gap-1.5 rounded-full border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 dark:border-blue-800 dark:bg-blue-950 dark:text-blue-300">Super Admin Mode</span>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            {{-- Main Invoice Info --}}
            <div class="space-y-6 lg:col-span-2">
                {{-- Billing Details Card --}}
                <div class="space-y-6 rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                    <div class="flex items-start justify-between border-b border-gray-100 pb-4 dark:border-gray-800">
                        <div>
                            <h3 class="text-base font-bold text-gray-800 dark:text-white">@lang('admin::app.super_admin.invoices.billing_info')</h3>
                            <p class="mt-1 text-xs text-gray-400">@lang('admin::app.super_admin.invoices.billing_info_sub')</p>
                        </div>
                        <div>
                            @if($invoice->status === 'paid')
                                <span class="rounded-full border border-emerald-100 bg-emerald-50/80 px-3 py-1.5 text-xs font-bold text-emerald-600 dark:border-emerald-900 dark:bg-emerald-950 dark:text-emerald-300">@lang('admin::app.super_admin.invoices.paid')</span>
                            @elseif($invoice->status === 'pending')
                                <span class="rounded-full border border-amber-100 bg-amber-50/80 px-3 py-1.5 text-xs font-bold text-amber-600 dark:border-amber-900 dark:bg-amber-950 dark:text-amber-300">@lang('admin::app.super_admin.invoices.pending')</span>
                            @elseif($invoice->status === 'expired')
                                <span class="rounded-full border border-gray-100 bg-gray-50/80 px-3 py-1.5 text-xs font-bold text-gray-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">@lang('admin::app.super_admin.invoices.expired')</span>
                            @else
                                <span class="rounded-full border border-red-100 bg-red-50/80 px-3 py-1.5 text-xs font-bold text-red-600 dark:border-red-900 dark:bg-red-950 dark:text-red-300">@lang('admin::app.super_admin.invoices.failed')</span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400">@lang('admin::app.super_admin.invoices.company_tenant')</h4>
                            <div class="mt-2 space-y-1">
                                <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $invoice->company->name ?? '-' }}</p>
                                <p class="text-sm">{{ $invoice->company->email ?? '-' }}</p>
                                <p class="text-sm">{{ $invoice->company->phone ?? '-' }}</p>
                                <p class="mt-1 text-xs text-gray-500">{{ $invoice->company->address ?? '-' }}</p>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400">@lang('admin::app.super_admin.invoices.payment_details')</h4>
                            <div class="mt-2 space-y-1.5">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">@lang('admin::app.super_admin.invoices.payment_method')</span>
                                    <span class="font-semibold text-gray-800 dark:text-white">{{ $invoice->payment_method }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">@lang('admin::app.super_admin.invoices.currency')</span>
                                    <span class="font-semibold text-gray-800 dark:text-white">{{ $invoice->currency }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">@lang('admin::app.super_admin.invoices.amount')</span>
                                    <span class="font-extrabold text-blue-600 dark:text-blue-400">{{ $invoice->currency }} {{ number_format($invoice->amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between border-t border-gray-100 pt-1.5 text-sm dark:border-gray-800">
                                    <span class="text-gray-500">@lang('admin::app.super_admin.invoices.created_date')</span>
                                    <span class="text-xs text-gray-700 dark:text-gray-400">{{ $invoice->created_at->format('d M Y, H:i') }}</span>
                                </div>
                                @if($invoice->due_date)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">@lang('admin::app.super_admin.invoices.due_date')</span>
                                    <span class="text-xs text-gray-700 dark:text-gray-400">{{ $invoice->due_date->format('d M Y, H:i') }}</span>
                                </div>
                                @endif
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">@lang('admin::app.super_admin.invoices.paid_date')</span>
                                    <span class="text-xs text-gray-700 dark:text-gray-400">{{ $invoice->paid_at ? $invoice->paid_at->format('d M Y, H:i') : '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Xendit Callback Raw Response Card --}}
                <div class="space-y-4 rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                    <div>
                        <h3 class="text-base font-bold text-gray-800 dark:text-white">@lang('admin::app.super_admin.invoices.raw_metadata')</h3>
                        <p class="mt-1 text-xs text-gray-400">@lang('admin::app.super_admin.invoices.raw_metadata_desc')</p>
                    </div>
                    
                    @if($invoice->notes)
                        <pre class="max-h-80 overflow-x-auto rounded-xl border border-gray-150 bg-gray-50 p-4 font-mono text-xs text-gray-700 dark:border-gray-800 dark:bg-gray-950 dark:text-gray-300">{{ json_encode(json_decode($invoice->notes), JSON_PRETTY_PRINT) }}</pre>
                    @else
                        <p class="text-sm italic text-gray-400">@lang('admin::app.super_admin.invoices.no_metadata')</p>
                    @endif
                </div>
            </div>

            {{-- Sidebar Info --}}
            <div class="space-y-6">
                {{-- Plan Info Card --}}
                <div class="space-y-4 rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                    <h3 class="text-base font-bold text-gray-800 dark:text-white">@lang('admin::app.super_admin.invoices.subscription_plan')</h3>
                    @if($invoice->subscription && $invoice->subscription->plan)
                        @php $plan = $invoice->subscription->plan; @endphp
                        <div class="space-y-3">
                            <div class="rounded-xl border border-blue-100 bg-blue-50/50 p-3 dark:border-blue-900 dark:bg-blue-950/30">
                                <h4 class="text-sm font-bold text-blue-600 dark:text-blue-400">{{ $plan->name }}</h4>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $plan->description }}</p>
                            </div>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">@lang('admin::app.super_admin.invoices.base_price')</span>
                                    <span class="font-semibold text-gray-800 dark:text-white">Rp {{ number_format($plan->price, 0, ',', '.') }}/bulan</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">@lang('admin::app.super_admin.plans.user_limit')</span>
                                    <span class="font-semibold text-gray-800 dark:text-white">{{ $plan->max_users }} User</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">@lang('admin::app.super_admin.plans.lead_limit')</span>
                                    <span class="font-semibold text-gray-800 dark:text-white">{{ number_format($plan->max_leads) }} Leads</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">@lang('admin::app.super_admin.plans.storage_limit')</span>
                                    <span class="font-semibold text-gray-800 dark:text-white">{{ number_format($plan->max_storage_mb) }} MB</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-sm italic text-gray-400">@lang('admin::app.super_admin.invoices.no_subscription')</p>
                    @endif
                </div>

                {{-- Xendit System Connection Card --}}
                <div class="space-y-3 rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                    <h3 class="text-base font-bold text-gray-800 dark:text-white">@lang('admin::app.super_admin.invoices.payment_gateway')</h3>
                    <div class="space-y-2 text-xs">
                        <div>
                            <span class="block font-semibold uppercase text-gray-500">Xendit Invoice ID</span>
                            <span class="mt-1 block select-all font-mono font-bold dark:text-white">{{ $invoice->xendit_invoice_id ?: '-' }}</span>
                        </div>
                        <div>
                            <span class="block font-semibold uppercase text-gray-500">Xendit Invoice URL</span>
                            @if($invoice->xendit_invoice_url)
                                <a href="{{ $invoice->xendit_invoice_url }}" target="_blank" class="mt-1 block break-all font-bold text-blue-600 hover:underline dark:text-blue-400">
                                    {{ $invoice->xendit_invoice_url }}
                                </a>
                            @else
                                <span class="mt-1 block font-semibold text-gray-400">-</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin::layouts>
