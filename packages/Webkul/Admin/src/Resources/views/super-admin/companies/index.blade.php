<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.super_admin.companies.title')
    </x-slot>

    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between rounded-xl border border-gray-200 bg-white px-6 py-5 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-1">
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">@lang('admin::app.super_admin.companies.title')</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">@lang('admin::app.super_admin.companies.description')</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('super_admin.companies.create') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-blue-600 px-4 py-2.5 text-xs font-bold text-white shadow-lg shadow-blue-200 transition-all hover:bg-blue-700 hover:shadow-blue-300 dark:shadow-none">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    @lang('admin::app.super_admin.companies.add_btn')
                </a>
                <span class="inline-flex items-center gap-1.5 rounded-full border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 dark:border-blue-800 dark:bg-blue-950 dark:text-blue-300">
                    <span class="h-1.5 w-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                    {{ $companies->count() }} Perusahaan
                </span>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700 dark:border-red-800 dark:bg-red-950 dark:text-red-300">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center justify-between border-b border-gray-100 p-6 dark:border-gray-800">
                <h3 class="text-base font-bold text-gray-800 dark:text-white">@lang('admin::app.super_admin.companies.company_list')</h3>
                <span class="text-xs font-semibold text-gray-400 dark:text-gray-500">{{ $companies->count() }} Perusahaan</span>
            </div>

            @if($companies->isEmpty())
                <div class="p-12 text-center">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100 dark:bg-gray-800">
                        <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('admin::app.super_admin.companies.empty')</p>
                    <a href="{{ route('super_admin.companies.create') }}" class="mt-3 inline-flex items-center gap-1 text-sm font-bold text-blue-600 hover:text-blue-700 dark:text-blue-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        @lang('admin::app.super_admin.companies.create_first')
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-left">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50/50 text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:border-gray-800 dark:bg-gray-800/50 dark:text-gray-500">
                                <th class="px-6 py-4">@lang('admin::app.super_admin.companies.name')</th>
                                <th class="px-6 py-4">@lang('admin::app.super_admin.companies.slug_domain')</th>
                                <th class="px-6 py-4">@lang('admin::app.super_admin.companies.subscription_plan')</th>
                                <th class="px-6 py-4">@lang('admin::app.super_admin.companies.account_status')</th>
                                <th class="px-6 py-4">@lang('admin::app.super_admin.companies.registered_date')</th>
                                <th class="px-6 py-4 text-right">@lang('admin::app.super_admin.companies.actions')</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm font-medium dark:divide-gray-800">
                            @foreach($companies as $company)
                                <tr class="transition-colors hover:bg-gray-50/30 dark:hover:bg-gray-800/30">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-800 dark:text-white">{{ $company->name }}</div>
                                        <div class="mt-0.5 text-xs font-semibold text-gray-400 dark:text-gray-500">{{ $company->email ?? '-' }} • {{ $company->phone ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="rounded-lg border border-slate-200 bg-slate-100 px-2.5 py-1 font-mono text-xs font-bold text-slate-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">{{ $company->slug }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($company->plan)
                                            <span class="rounded-full border border-blue-100 bg-blue-50/80 px-2.5 py-1 text-xs font-bold text-blue-600 dark:border-blue-900 dark:bg-blue-950 dark:text-blue-300">{{ $company->plan->name }}</span>
                                        @else
                                            <span class="font-semibold text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($company->is_active)
                                            <span class="rounded-full border border-emerald-100 bg-emerald-50/80 px-2.5 py-1 text-xs font-bold text-emerald-600 dark:border-emerald-900 dark:bg-emerald-950 dark:text-emerald-300">@lang('admin::app.super_admin.companies.active')</span>
                                        @else
                                            <span class="rounded-full border border-red-100 bg-red-50/80 px-2.5 py-1 text-xs font-bold text-red-600 dark:border-red-900 dark:bg-red-950 dark:text-red-300">@lang('admin::app.super_admin.companies.inactive')</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400">
                                        {{ $company->created_at->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            {{-- Toggle Status --}}
                                            <form action="{{ route('super_admin.companies.toggle_status', ['id' => $company->id]) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="rounded-xl border px-3 py-1.5 text-xs font-bold transition-all {{ $company->is_active ? 'border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100 dark:border-amber-800 dark:bg-amber-950 dark:text-amber-300 dark:hover:bg-amber-900' : 'border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 dark:border-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 dark:hover:bg-emerald-900' }}">
                                                    {{ $company->is_active ? __('admin::app.super_admin.companies.deactivate') : __('admin::app.super_admin.companies.activate') }}
                                                </button>
                                            </form>

                                            {{-- Detail --}}
                                            <a href="{{ route('super_admin.companies.show', ['id' => $company->id]) }}" class="rounded-xl border border-blue-100 bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700 transition-all hover:bg-blue-100 dark:border-blue-900 dark:bg-blue-950 dark:text-blue-300 dark:hover:bg-blue-900">
                                                @lang('admin::app.super_admin.companies.detail')
                                            </a>

                                            {{-- Edit --}}
                                            <a href="{{ route('super_admin.companies.edit', ['id' => $company->id]) }}" class="rounded-xl border border-gray-200 bg-white px-3 py-1.5 text-xs font-bold text-gray-700 shadow-sm transition-all hover:border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:border-gray-600">
                                                @lang('admin::app.super_admin.companies.edit')
                                            </a>

                                            {{-- Delete --}}
                                            <form action="{{ route('super_admin.companies.destroy', ['id' => $company->id]) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('admin::app.super_admin.companies.delete_confirm', ['name' => $company->name]) }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-xl border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-bold text-red-600 transition-all hover:bg-red-100 dark:border-red-800 dark:bg-red-950 dark:text-red-300 dark:hover:bg-red-900">
                                                    @lang('admin::app.super_admin.companies.delete')
                                                </button>
                                            </form>
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
