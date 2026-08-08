<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.super_admin.companies.create_title')
    </x-slot>

    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between rounded-xl border border-gray-200 bg-white px-6 py-5 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-1">
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">@lang('admin::app.super_admin.companies.create_title')</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">@lang('admin::app.super_admin.companies.create_desc')</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('super_admin.companies.index') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-xs font-bold text-gray-700 shadow-sm transition-all hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    @lang('admin::app.super_admin.companies.back')
                </a>
                <span class="inline-flex items-center gap-1.5 rounded-full border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 dark:border-blue-800 dark:bg-blue-950 dark:text-blue-300">Super Admin Mode</span>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700 dark:border-red-800 dark:bg-red-950 dark:text-red-300">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 dark:border-red-800 dark:bg-red-950">
                <ul class="list-disc pl-5 text-sm font-medium text-red-700 dark:text-red-300">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('super_admin.companies.store') }}" method="POST" class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            @csrf

            {{-- Main Form --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Company Information --}}
                <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="mb-5 flex items-center gap-2 text-base font-bold text-gray-900 dark:text-white">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-950 dark:text-blue-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </span>
                        @lang('admin::app.super_admin.companies.company_info')
                    </h3>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label for="name" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">@lang('admin::app.super_admin.companies.name') <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" required value="{{ old('name') }}" placeholder="PT. Contoh Jaya" class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm transition-colors focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500">
                            </div>
                            <div>
                                <label for="slug" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">@lang('admin::app.super_admin.companies.slug_domain') <span class="text-red-500">*</span></label>
                                <input type="text" name="slug" id="slug" required value="{{ old('slug') }}" placeholder="contoh-jaya" class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm font-mono transition-colors focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500">
                                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">@lang('admin::app.super_admin.companies.slug_hint')</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label for="email" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">@lang('admin::app.super_admin.companies.company_email')</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="info@contoh-jaya.com" class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm transition-colors focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500">
                            </div>
                            <div>
                                <label for="phone" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">@lang('admin::app.super_admin.companies.phone')</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="021-12345678" class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm transition-colors focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500">
                            </div>
                        </div>

                        <div>
                            <label for="address" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">@lang('admin::app.super_admin.companies.address')</label>
                            <textarea name="address" id="address" rows="2" placeholder="Jl. Contoh No. 1, Jakarta" class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm transition-colors focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500">{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Admin User --}}
                <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="mb-5 flex items-center gap-2 text-base font-bold text-gray-900 dark:text-white">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-violet-50 text-violet-600 dark:bg-violet-950 dark:text-violet-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </span>
                        @lang('admin::app.super_admin.companies.initial_admin')
                    </h3>

                    <div class="mb-4 rounded-lg border border-amber-200 bg-amber-50 p-3 dark:border-amber-800 dark:bg-amber-950">
                        <p class="text-xs font-medium text-amber-700 dark:text-amber-300">
                            <svg class="mr-1 inline h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @lang('admin::app.super_admin.companies.initial_admin_note')
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label for="admin_name" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">@lang('admin::app.super_admin.companies.admin_name') <span class="text-red-500">*</span></label>
                            <input type="text" name="admin_name" id="admin_name" required value="{{ old('admin_name') }}" placeholder="John Doe" class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm transition-colors focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500">
                        </div>
                        <div>
                            <label for="admin_email" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">@lang('admin::app.super_admin.companies.admin_email') <span class="text-red-500">*</span></label>
                            <input type="email" name="admin_email" id="admin_email" required value="{{ old('admin_email') }}" placeholder="admin@contoh-jaya.com" class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm transition-colors focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Plan Selection --}}
                <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="mb-4 flex items-center gap-2 text-base font-bold text-gray-900 dark:text-white">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </span>
                        @lang('admin::app.super_admin.companies.subscription_plan')
                    </h3>

                    <div class="space-y-3">
                        @foreach($plans as $plan)
                            <label for="plan_{{ $plan->id }}" class="block cursor-pointer rounded-xl border-2 p-4 transition-all {{ old('plan_id') == $plan->id ? 'border-blue-500 bg-blue-50/50 dark:border-blue-600 dark:bg-blue-950/30' : 'border-gray-200 hover:border-blue-300 dark:border-gray-700 dark:hover:border-blue-700' }}">
                                <div class="flex items-start gap-3">
                                    <input type="radio" name="plan_id" id="plan_{{ $plan->id }}" value="{{ $plan->id }}" required {{ old('plan_id') == $plan->id ? 'checked' : '' }} class="mt-1 h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <h4 class="text-sm font-bold text-gray-900 dark:text-white">{{ $plan->name }}</h4>
                                            <span class="text-sm font-extrabold text-blue-600 dark:text-blue-400">Rp {{ number_format($plan->price, 0, ',', '.') }}</span>
                                        </div>
                                        <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">{{ $plan->max_users }} User • {{ number_format($plan->max_leads) }} Leads • {{ number_format($plan->max_storage_mb) }} MB</p>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Submit Actions --}}
                <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-200 transition-all hover:bg-blue-700 hover:shadow-blue-300 dark:shadow-none">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        @lang('admin::app.super_admin.companies.save')
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-admin::layouts>
