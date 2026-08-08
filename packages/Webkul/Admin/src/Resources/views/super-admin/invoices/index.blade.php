<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.super_admin.invoices.title')
    </x-slot>

    <div class="flex flex-col gap-4">
        {{-- Title Header --}}
        <div class="flex items-center justify-between rounded-xl border border-gray-200 bg-white px-6 py-5 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-1">
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">@lang('admin::app.super_admin.invoices.title')</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">@lang('admin::app.super_admin.invoices.description')</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 rounded-full border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 dark:border-blue-800 dark:bg-blue-950 dark:text-blue-300">
                    <span class="h-1.5 w-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                    {{ $invoices->count() }} Invoice
                </span>
            </div>
        </div>

        {{-- Filters Section --}}
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <form action="{{ route('super_admin.invoices.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
                <div class="flex flex-col gap-1 min-w-[200px]">
                    <label for="company_id" class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">@lang('admin::app.super_admin.invoices.filter_company')</label>
                    <select name="company_id" id="company_id" class="rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm focus:border-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        <option value="">@lang('admin::app.super_admin.invoices.all_companies')</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col gap-1 min-w-[150px]">
                    <label for="status" class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">@lang('admin::app.super_admin.invoices.filter_status')</label>
                    <select name="status" id="status" class="rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm focus:border-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        <option value="">@lang('admin::app.super_admin.invoices.all_statuses')</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>@lang('admin::app.super_admin.invoices.pending')</option>
                        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>@lang('admin::app.super_admin.invoices.paid')</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>@lang('admin::app.super_admin.invoices.failed')</option>
                        <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>@lang('admin::app.super_admin.invoices.expired')</option>
                    </select>
                </div>

                <div class="flex items-end h-full pt-5 gap-2">
                    <button type="submit" class="rounded-xl bg-blue-600 px-6 py-2 text-xs font-bold text-white shadow-md shadow-blue-100 transition-all hover:bg-blue-700 dark:shadow-none">
                        @lang('admin::app.super_admin.invoices.filter')
                    </button>
                    @if(request()->has('company_id') || request()->has('status'))
                        <a href="{{ route('super_admin.invoices.index') }}" class="rounded-xl border border-gray-200 bg-white px-6 py-2 text-xs font-bold text-gray-700 shadow-sm transition-colors hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                            @lang('admin::app.super_admin.invoices.reset')
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Invoices List Table --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            @if($invoices->isEmpty())
                <div class="p-12 text-center">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100 dark:bg-gray-800">
                        <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                    </div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('admin::app.super_admin.invoices.empty')</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-left">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50/50 text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:border-gray-800 dark:bg-gray-800/50 dark:text-gray-500">
                                <th class="px-6 py-4">@lang('admin::app.super_admin.invoices.invoice_no')</th>
                                <th class="px-6 py-4">@lang('admin::app.super_admin.invoices.company_tenant')</th>
                                <th class="px-6 py-4">@lang('admin::app.super_admin.invoices.subscription_plan')</th>
                                <th class="px-6 py-4">@lang('admin::app.super_admin.invoices.amount')</th>
                                <th class="px-6 py-4">@lang('admin::app.super_admin.invoices.status')</th>
                                <th class="px-6 py-4">@lang('admin::app.super_admin.invoices.created_date')</th>
                                <th class="px-6 py-4 text-right">@lang('admin::app.super_admin.invoices.actions')</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm font-medium dark:divide-gray-800">
                            @foreach($invoices as $invoice)
                                <tr class="transition-colors hover:bg-gray-50/30 dark:hover:bg-gray-800/30">
                                    <td class="px-6 py-4 font-mono text-xs font-bold text-gray-800 dark:text-white">
                                        {{ $invoice->invoice_number }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-800 dark:text-white">
                                        {{ $invoice->company->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($invoice->subscription && $invoice->subscription->plan)
                                            <span class="rounded-full border border-blue-100 bg-blue-50/80 px-2.5 py-1 text-xs font-bold text-blue-600 dark:border-blue-900 dark:bg-blue-950 dark:text-blue-300">
                                                {{ $invoice->subscription->plan->name }}
                                            </span>
                                        @else
                                            <span class="font-semibold text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-extrabold text-gray-800 dark:text-white">
                                        {{ $invoice->currency }} {{ number_format($invoice->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($invoice->status === 'paid')
                                            <span class="rounded-full border border-emerald-100 bg-emerald-50/80 px-2.5 py-1 text-xs font-bold text-emerald-600 dark:border-emerald-900 dark:bg-emerald-950 dark:text-emerald-300">@lang('admin::app.super_admin.invoices.paid')</span>
                                        @elseif($invoice->status === 'pending')
                                            <span class="rounded-full border border-amber-100 bg-amber-50/80 px-2.5 py-1 text-xs font-bold text-amber-600 dark:border-amber-900 dark:bg-amber-950 dark:text-amber-300">@lang('admin::app.super_admin.invoices.pending')</span>
                                        @elseif($invoice->status === 'expired')
                                            <span class="rounded-full border border-gray-100 bg-gray-50/80 px-2.5 py-1 text-xs font-bold text-gray-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">@lang('admin::app.super_admin.invoices.expired')</span>
                                        @else
                                            <span class="rounded-full border border-red-100 bg-red-50/80 px-2.5 py-1 text-xs font-bold text-red-600 dark:border-red-900 dark:bg-red-950 dark:text-red-300">@lang('admin::app.super_admin.invoices.failed')</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400">
                                        {{ $invoice->created_at->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            {{-- Mark as Paid (only for non-paid invoices) --}}
                                            @if($invoice->status !== 'paid')
                                                <form action="{{ route('super_admin.invoices.mark_paid', ['id' => $invoice->id]) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('admin::app.super_admin.invoices.mark_paid_confirm', ['number' => $invoice->invoice_number]) }}')">
                                                    @csrf
                                                    <button type="submit" class="rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700 transition-all hover:bg-emerald-100 dark:border-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 dark:hover:bg-emerald-900">
                                                        @lang('admin::app.super_admin.invoices.mark_paid')
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- Detail --}}
                                            <a href="{{ route('super_admin.invoices.show', ['id' => $invoice->id]) }}" class="rounded-xl border border-gray-200 bg-white px-3 py-1.5 text-xs font-bold text-gray-700 shadow-sm transition-all hover:border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-600">
                                                @lang('admin::app.super_admin.companies.detail')
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-admin::layouts>
