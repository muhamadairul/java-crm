<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.super_admin.plans.title')
    </x-slot>

    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between rounded-xl border border-gray-200 bg-white px-6 py-5 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-1">
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">@lang('admin::app.super_admin.plans.title')</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">@lang('admin::app.super_admin.plans.description')</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('super_admin.plans.create') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-blue-600 px-4 py-2.5 text-xs font-bold text-white shadow-lg shadow-blue-200 transition-all hover:bg-blue-700 hover:shadow-blue-300 dark:shadow-none">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    @lang('admin::app.super_admin.plans.add_btn')
                </a>
                <span class="inline-flex items-center gap-1.5 rounded-full border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 dark:border-blue-800 dark:bg-blue-950 dark:text-blue-300">
                    <span class="h-1.5 w-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                    {{ $plans->count() }} Paket
                </span>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center justify-between border-b border-gray-100 p-6 dark:border-gray-800">
                <h3 class="text-base font-bold text-gray-800 dark:text-white">@lang('admin::app.super_admin.plans.plan_list')</h3>
                <span class="text-xs font-semibold text-gray-400 dark:text-gray-500">{{ $plans->count() }} Paket</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/50 text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:border-gray-800 dark:bg-gray-800/50 dark:text-gray-500">
                            <th class="px-6 py-4">@lang('admin::app.super_admin.plans.name')</th>
                            <th class="px-6 py-4">@lang('admin::app.super_admin.plans.code')</th>
                            <th class="px-6 py-4">@lang('admin::app.super_admin.plans.price')</th>
                            <th class="px-6 py-4">@lang('admin::app.super_admin.plans.user_limit')</th>
                            <th class="px-6 py-4">@lang('admin::app.super_admin.plans.lead_limit')</th>
                            <th class="px-6 py-4">@lang('admin::app.super_admin.plans.storage_limit')</th>
                            <th class="px-6 py-4">@lang('admin::app.super_admin.plans.tenants_count')</th>
                            <th class="px-6 py-4">@lang('admin::app.super_admin.plans.status')</th>
                            <th class="px-6 py-4 text-right">@lang('admin::app.super_admin.plans.actions')</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm font-medium dark:divide-gray-800">
                        @foreach($plans as $plan)
                            <tr class="transition-colors hover:bg-gray-50/30 dark:hover:bg-gray-800/30">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-800 dark:text-white">{{ $plan->name }}</div>
                                    <div class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">{{ Str::limit($plan->description, 50) }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="rounded-lg border border-slate-200 bg-slate-100 px-2.5 py-1 font-mono text-xs font-bold text-slate-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">{{ $plan->code }}</span>
                                </td>
                                <td class="px-6 py-4 font-extrabold text-blue-600 dark:text-blue-400">
                                    Rp {{ number_format($plan->price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $plan->max_users }} User</td>
                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ number_format($plan->max_leads) }} Leads</td>
                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ number_format($plan->max_storage_mb) }} MB</td>
                                <td class="px-6 py-4">
                                    <span class="rounded-full border border-gray-200 bg-gray-50 px-2.5 py-1 text-xs font-bold text-gray-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                        {{ $planUsage[$plan->id] ?? 0 }} tenant
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($plan->is_active)
                                        <span class="rounded-full border border-emerald-100 bg-emerald-50/80 px-2.5 py-1 text-xs font-bold text-emerald-600 dark:border-emerald-900 dark:bg-emerald-950 dark:text-emerald-300">@lang('admin::app.super_admin.companies.active')</span>
                                    @else
                                        <span class="rounded-full border border-red-100 bg-red-50/80 px-2.5 py-1 text-xs font-bold text-red-600 dark:border-red-900 dark:bg-red-950 dark:text-red-300">@lang('admin::app.super_admin.companies.inactive')</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        {{-- Toggle Status --}}
                                        <form action="{{ route('super_admin.plans.toggle_status', ['id' => $plan->id]) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="rounded-xl border px-3 py-1.5 text-xs font-bold transition-all {{ $plan->is_active ? 'border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100 dark:border-amber-800 dark:bg-amber-950 dark:text-amber-300 dark:hover:bg-amber-900' : 'border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 dark:border-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 dark:hover:bg-emerald-900' }}">
                                                {{ $plan->is_active ? __('admin::app.super_admin.companies.deactivate') : __('admin::app.super_admin.companies.activate') }}
                                            </button>
                                        </form>

                                        {{-- Edit --}}
                                        <a href="{{ route('super_admin.plans.edit', ['id' => $plan->id]) }}" class="rounded-xl border border-gray-200 bg-white px-3 py-1.5 text-xs font-bold text-gray-700 shadow-sm transition-all hover:border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:border-gray-600">
                                            @lang('admin::app.super_admin.plans.edit_limits')
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin::layouts>
