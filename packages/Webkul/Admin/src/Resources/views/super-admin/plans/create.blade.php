<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.super_admin.plans.create_title')
    </x-slot>

    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between rounded-xl border border-gray-200 bg-white px-6 py-5 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-1">
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">@lang('admin::app.super_admin.plans.create_title')</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">@lang('admin::app.super_admin.plans.create_desc')</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('super_admin.plans.index') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-xs font-bold text-gray-700 shadow-sm transition-all hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    @lang('admin::app.super_admin.plans.back')
                </a>
                <span class="inline-flex items-center gap-1.5 rounded-full border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 dark:border-blue-800 dark:bg-blue-950 dark:text-blue-300">Super Admin Mode</span>
            </div>
        </div>

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 dark:border-red-800 dark:bg-red-950">
                <ul class="list-disc pl-5 text-sm font-medium text-red-700 dark:text-red-300">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('super_admin.plans.store') }}" method="POST" class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            @csrf

            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Info --}}
                <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="mb-5 flex items-center gap-2 text-base font-bold text-gray-900 dark:text-white">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-950 dark:text-blue-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </span>
                        @lang('admin::app.super_admin.plans.create_title')
                    </h3>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label for="name" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">@lang('admin::app.super_admin.plans.name') <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" required value="{{ old('name') }}" placeholder="Enterprise Plus" class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm transition-colors focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500">
                            </div>
                            <div>
                                <label for="code" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">@lang('admin::app.super_admin.plans.code') <span class="text-red-500">*</span></label>
                                <input type="text" name="code" id="code" required value="{{ old('code') }}" placeholder="enterprise-plus" class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm font-mono transition-colors focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500">
                                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">@lang('admin::app.super_admin.plans.code_hint')</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label for="price" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">@lang('admin::app.super_admin.plans.price') <span class="text-red-500">*</span></label>
                                <input type="number" step="1000" name="price" id="price" required value="{{ old('price') }}" placeholder="299000" class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm transition-colors focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500">
                            </div>
                            <div>
                                <label for="billing_cycle" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">@lang('admin::app.super_admin.plans.billing_cycle') <span class="text-red-500">*</span></label>
                                <select name="billing_cycle" id="billing_cycle" required class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm transition-colors focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                    <option value="monthly" {{ old('billing_cycle') === 'monthly' ? 'selected' : '' }}>@lang('admin::app.super_admin.plans.monthly')</option>
                                    <option value="yearly" {{ old('billing_cycle') === 'yearly' ? 'selected' : '' }}>@lang('admin::app.super_admin.plans.yearly')</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="description" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">@lang('admin::app.super_admin.plans.description_label')</label>
                            <textarea name="description" id="description" rows="3" placeholder="Paket lengkap untuk perusahaan besar..." class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm transition-colors focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Limits --}}
                <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="mb-5 flex items-center gap-2 text-base font-bold text-gray-900 dark:text-white">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-50 text-amber-600 dark:bg-amber-950 dark:text-amber-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </span>
                        @lang('admin::app.super_admin.plans.quota_limits')
                    </h3>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <label for="max_users" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">@lang('admin::app.super_admin.plans.max_users') <span class="text-red-500">*</span></label>
                            <input type="number" name="max_users" id="max_users" required min="1" value="{{ old('max_users', 5) }}" class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm transition-colors focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">@lang('admin::app.super_admin.plans.max_users_sub')</p>
                        </div>
                        <div>
                            <label for="max_leads" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">@lang('admin::app.super_admin.plans.max_leads') <span class="text-red-500">*</span></label>
                            <input type="number" name="max_leads" id="max_leads" required min="1" value="{{ old('max_leads', 500) }}" class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm transition-colors focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">@lang('admin::app.super_admin.plans.max_leads_sub')</p>
                        </div>
                        <div>
                            <label for="max_storage_mb" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">@lang('admin::app.super_admin.plans.max_storage') <span class="text-red-500">*</span></label>
                            <input type="number" name="max_storage_mb" id="max_storage_mb" required min="1" value="{{ old('max_storage_mb', 512) }}" class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm transition-colors focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">@lang('admin::app.super_admin.plans.max_storage_sub')</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="mb-4 text-base font-bold text-gray-900 dark:text-white">@lang('admin::app.super_admin.plans.summary')</h3>
                    <div class="rounded-lg border border-blue-100 bg-blue-50/50 p-4 dark:border-blue-900 dark:bg-blue-950/30">
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            @lang('admin::app.super_admin.plans.summary_note')
                        </p>
                    </div>

                    <button type="submit" class="mt-4 flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-200 transition-all hover:bg-blue-700 hover:shadow-blue-300 dark:shadow-none">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        @lang('admin::app.super_admin.plans.save')
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-admin::layouts>
