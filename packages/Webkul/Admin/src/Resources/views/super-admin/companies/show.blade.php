<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.super_admin.companies.show_title_meta', ['name' => $company->name])
    </x-slot>

    <div class="flex flex-col gap-4">
        <!-- Header -->
        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-4 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-2">
                <div class="text-xl font-bold dark:text-white">
                    @lang('admin::app.super_admin.companies.show_title', ['name' => $company->name])
                </div>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('super_admin.companies.index') }}" class="bg-white hover:bg-gray-50 dark:bg-gray-900 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-gray-800 border border-gray-200 text-gray-750 font-bold py-2 px-6 rounded-xl text-xs transition-colors shadow-sm">
                    @lang('admin::app.super_admin.companies.back')
                </a>
                <span class="text-xs bg-blue-50 text-blue-700 dark:bg-blue-950 dark:text-blue-300 px-3 py-1 rounded-full font-semibold border border-blue-100 dark:border-blue-900">Super Admin Mode</span>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Panel: Profile and Quotas -->
            <div class="space-y-6 lg:col-span-1">
                <!-- Profile Card -->
                <div class="bg-white rounded-lg border border-gray-200 dark:border-gray-800 dark:bg-gray-900 p-6 space-y-4 dark:text-gray-300">
                    <div class="flex justify-between items-start">
                        <h3 class="text-base font-bold text-gray-800 dark:text-white">@lang('admin::app.super_admin.companies.profile')</h3>
                        @if($company->is_active)
                            <span class="text-emerald-600 font-bold bg-emerald-50/80 px-2.5 py-1 rounded-full text-xs border border-emerald-100 dark:bg-emerald-950 dark:text-emerald-300 dark:border-emerald-900">@lang('admin::app.super_admin.companies.active')</span>
                        @else
                            <span class="text-red-600 font-bold bg-red-50/80 px-2.5 py-1 rounded-full text-xs border border-red-100 dark:bg-red-950 dark:text-red-300 dark:border-red-900">@lang('admin::app.super_admin.companies.inactive')</span>
                        @endif
                    </div>
                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="text-xs text-gray-400 font-bold uppercase block">@lang('admin::app.super_admin.companies.slug_domain')</span>
                            <span class="font-mono text-xs text-gray-800 dark:text-white bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded border border-slate-200 dark:border-slate-700">{{ $company->slug }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 font-bold uppercase block">@lang('admin::app.super_admin.companies.company_email')</span>
                            <span class="text-gray-800 dark:text-white">{{ $company->email ?: '-' }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 font-bold uppercase block">@lang('admin::app.super_admin.companies.phone')</span>
                            <span class="text-gray-800 dark:text-white">{{ $company->phone ?: '-' }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 font-bold uppercase block">@lang('admin::app.super_admin.companies.address')</span>
                            <span class="text-gray-800 dark:text-white text-xs">{{ $company->address ?: '-' }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 font-bold uppercase block">@lang('admin::app.super_admin.companies.subscription_plan')</span>
                            <span class="text-blue-600 font-bold bg-blue-50/80 dark:bg-blue-950 dark:text-blue-300 px-2 py-0.5 rounded text-xs border border-blue-100 dark:border-blue-900">{{ $company->plan->name ?? __('admin::app.super_admin.companies.no_plan') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quota Usage Card -->
                <div class="bg-white rounded-lg border border-gray-200 dark:border-gray-800 dark:bg-gray-900 p-6 space-y-4 dark:text-gray-300">
                    <h3 class="text-base font-bold text-gray-800 dark:text-white">@lang('admin::app.super_admin.companies.quota_usage')</h3>
                    <div class="space-y-4">
                        <!-- Users Quota -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-xs font-bold uppercase text-gray-500">
                                <span>@lang('admin::app.super_admin.companies.team_members')</span>
                                <span class="text-gray-700 dark:text-gray-300">{{ $quota['users']['used'] }} / {{ $quota['users']['limit'] }}</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-800 h-2.5 rounded-full overflow-hidden">
                                <div class="bg-blue-600 h-full transition-all duration-300" style="width: {{ $quota['users']['pct'] }}%"></div>
                            </div>
                            <span class="text-[10px] text-gray-400 block text-right font-semibold">{{ $quota['users']['pct'] }}% @lang('admin::app.super_admin.companies.used')</span>
                        </div>

                        <!-- Leads Quota -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-xs font-bold uppercase text-gray-500">
                                <span>@lang('admin::app.super_admin.plans.lead_limit')</span>
                                <span class="text-gray-700 dark:text-gray-300">{{ number_format($quota['leads']['used']) }} / {{ number_format($quota['leads']['limit']) }}</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-800 h-2.5 rounded-full overflow-hidden">
                                <div class="bg-amber-500 h-full transition-all duration-300" style="width: {{ $quota['leads']['pct'] }}%"></div>
                            </div>
                            <span class="text-[10px] text-gray-400 block text-right font-semibold">{{ $quota['leads']['pct'] }}% @lang('admin::app.super_admin.companies.used')</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Tabs for Users and Invoices -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Users List -->
                <div class="bg-white rounded-lg border border-gray-200 dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-800">
                        <h3 class="text-base font-bold text-gray-800 dark:text-white">@lang('admin::app.super_admin.companies.users_list')</h3>
                        <p class="text-xs text-gray-400 mt-1">@lang('admin::app.super_admin.companies.users_list_sub')</p>
                    </div>

                    @if($users->isEmpty())
                        <div class="p-8 text-center text-gray-400 italic">
                            @lang('admin::app.super_admin.companies.no_users')
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 text-[10px] text-gray-400 font-bold uppercase tracking-wider">
                                        <th class="py-4 px-6">@lang('admin::app.super_admin.companies.admin_name')</th>
                                        <th class="py-4 px-6">@lang('admin::app.super_admin.companies.admin_email')</th>
                                        <th class="py-4 px-6">Role</th>
                                        <th class="py-4 px-6">@lang('admin::app.super_admin.companies.account_status')</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-sm font-medium">
                                    @foreach($users as $user)
                                        <tr class="hover:bg-gray-50/30 transition-colors">
                                            <td class="py-4 px-6 dark:text-white font-bold">
                                                {{ $user->name }}
                                            </td>
                                            <td class="py-4 px-6 dark:text-white">
                                                {{ $user->email }}
                                            </td>
                                            <td class="py-4 px-6 dark:text-white">
                                                <span class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 px-2 py-0.5 rounded border border-slate-200 dark:border-slate-700 text-xs">
                                                    {{ $user->role_name }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-6">
                                                @if($user->status)
                                                    <span class="text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded text-xs">@lang('admin::app.super_admin.companies.active')</span>
                                                @else
                                                    <span class="text-red-600 bg-red-50 px-2 py-0.5 rounded text-xs">@lang('admin::app.super_admin.companies.inactive')</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- Invoices List -->
                <div class="bg-white rounded-lg border border-gray-200 dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-800">
                        <h3 class="text-base font-bold text-gray-800 dark:text-white">@lang('admin::app.super_admin.companies.billing_history')</h3>
                        <p class="text-xs text-gray-400 mt-1">@lang('admin::app.super_admin.companies.billing_history_sub')</p>
                    </div>

                    @if($invoices->isEmpty())
                        <div class="p-8 text-center text-gray-400 italic">
                            @lang('admin::app.super_admin.companies.no_invoices')
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 text-[10px] text-gray-400 font-bold uppercase tracking-wider">
                                        <th class="py-4 px-6">@lang('admin::app.super_admin.invoices.invoice_no')</th>
                                        <th class="py-4 px-6">@lang('admin::app.super_admin.invoices.amount')</th>
                                        <th class="py-4 px-6">@lang('admin::app.super_admin.invoices.payment_method')</th>
                                        <th class="py-4 px-6">@lang('admin::app.super_admin.invoices.status')</th>
                                        <th class="py-4 px-6">@lang('admin::app.super_admin.invoices.created_date')</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-sm font-medium">
                                    @foreach($invoices as $invoice)
                                        <tr class="hover:bg-gray-50/30 transition-colors">
                                            <td class="py-4 px-6 font-mono text-xs font-bold dark:text-white">
                                                <a href="{{ route('super_admin.invoices.show', ['id' => $invoice->id]) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                                    {{ $invoice->invoice_number }}
                                                </a>
                                            </td>
                                            <td class="py-4 px-6 dark:text-white font-bold">
                                                {{ $invoice->currency }} {{ number_format($invoice->amount, 2) }}
                                            </td>
                                            <td class="py-4 px-6 dark:text-white">
                                                {{ $invoice->payment_method }}
                                            </td>
                                            <td class="py-4 px-6">
                                                @if($invoice->status === 'paid')
                                                    <span class="text-emerald-600 font-bold bg-emerald-50 px-2 py-0.5 rounded text-xs border border-emerald-100 dark:bg-emerald-950 dark:text-emerald-300 dark:border-emerald-900">@lang('admin::app.super_admin.invoices.paid')</span>
                                                @elseif($invoice->status === 'pending')
                                                    <span class="text-amber-600 font-bold bg-amber-50 px-2 py-0.5 rounded text-xs border border-amber-100 dark:bg-amber-950 dark:text-amber-300 dark:border-amber-900">@lang('admin::app.super_admin.invoices.pending')</span>
                                                @elseif($invoice->status === 'expired')
                                                    <span class="text-gray-650 font-semibold bg-gray-50 px-2 py-0.5 rounded text-xs border border-gray-200">@lang('admin::app.super_admin.invoices.expired')</span>
                                                @else
                                                    <span class="text-red-600 font-bold bg-red-50 px-2 py-0.5 rounded text-xs border border-red-100">@lang('admin::app.super_admin.invoices.failed')</span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-6 text-gray-400 dark:text-gray-500 font-semibold text-xs">
                                                {{ \Carbon\Carbon::parse($invoice->created_at)->format('d M Y, H:i') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin::layouts>
