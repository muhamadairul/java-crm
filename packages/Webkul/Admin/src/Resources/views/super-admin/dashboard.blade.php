<x-admin::layouts>
    <x-slot:title>
        Dashboard Monitoring Platform
    </x-slot>

    <v-super-admin-dashboard></v-super-admin-dashboard>

    @pushOnce('scripts')
        <script
            type="module"
            src="{{ vite()->asset('js/chart.js') }}"
        ></script>

        <script type="text/x-template" id="v-super-admin-dashboard-template">
            <div class="flex flex-col gap-6">
                {{-- ── Header ─────────────────────────────────────────────── --}}
                <div class="flex items-center justify-between rounded-xl border border-gray-200 bg-white px-6 py-5 dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex flex-col gap-1">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                            @lang('admin::app.super_admin.dashboard.title')
                        </h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            @lang('admin::app.super_admin.dashboard.description')
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 dark:border-blue-800 dark:bg-blue-950 dark:text-blue-300">
                            <span class="h-1.5 w-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                            @lang('admin::app.super_admin.title')
                        </span>
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ now()->translatedFormat('d F Y') }}</span>
                    </div>
                </div>

                {{-- ── Metric Cards (5 cards) ─────────────────────────────── --}}
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">

                    {{-- Total Tenant --}}
                    <a href="{{ route('super_admin.companies.index') }}" class="group relative overflow-hidden rounded-xl border border-gray-200 bg-white p-5 transition-all duration-200 hover:shadow-lg hover:border-blue-300 dark:border-gray-800 dark:bg-gray-900 dark:hover:border-blue-700">
                        <div class="flex items-start justify-between">
                            <div class="flex flex-col gap-1">
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">@lang('admin::app.super_admin.dashboard.total_tenants')</p>
                                <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalCompanies }}</h3>
                                <div class="mt-1 flex items-center gap-1">
                                    @if($companiesChange >= 0)
                                        <svg class="h-3.5 w-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                                        <span class="text-xs font-semibold text-emerald-500">+{{ $companiesChange }}%</span>
                                    @else
                                        <svg class="h-3.5 w-3.5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                        <span class="text-xs font-semibold text-red-500">{{ $companiesChange }}%</span>
                                    @endif
                                    <span class="text-xs text-gray-400 dark:text-gray-500">@lang('admin::app.super_admin.dashboard.vs_last_month')</span>
                                </div>
                            </div>
                            <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-blue-50 text-blue-600 transition-colors group-hover:bg-blue-100 dark:bg-blue-950 dark:text-blue-400 dark:group-hover:bg-blue-900">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 v5m-4 0h4"/></svg>
                            </div>
                        </div>
                    </a>

                    {{-- Tenant Aktif --}}
                    <a href="{{ route('super_admin.companies.index') }}" class="group relative overflow-hidden rounded-xl border border-gray-200 bg-white p-5 transition-all duration-200 hover:shadow-lg hover:border-emerald-300 dark:border-gray-800 dark:bg-gray-900 dark:hover:border-emerald-700">
                        <div class="flex items-start justify-between">
                            <div class="flex flex-col gap-1">
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">@lang('admin::app.super_admin.dashboard.active_tenants')</p>
                                <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalActiveCompanies }}</h3>
                                <div class="mt-1 flex items-center gap-1">
                                    <span class="text-xs font-semibold text-emerald-500">
                                        {{ $totalCompanies > 0 ? round(($totalActiveCompanies / $totalCompanies) * 100) : 0 }}%
                                    </span>
                                    <span class="text-xs text-gray-400 dark:text-gray-500">@lang('admin::app.super_admin.dashboard.of_total')</span>
                                </div>
                            </div>
                            <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 transition-colors group-hover:bg-emerald-100 dark:bg-emerald-950 dark:text-emerald-400 dark:group-hover:bg-emerald-900">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                        </div>
                    </a>

                    {{-- Total Pengguna --}}
                    <div class="group relative overflow-hidden rounded-xl border border-gray-200 bg-white p-5 transition-all duration-200 hover:shadow-lg hover:border-violet-300 dark:border-gray-800 dark:bg-gray-900 dark:hover:border-violet-700">
                        <div class="flex items-start justify-between">
                            <div class="flex flex-col gap-1">
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">@lang('admin::app.super_admin.dashboard.total_users')</p>
                                <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalUsers }}</h3>
                                <div class="mt-1 flex items-center gap-1">
                                    @if($usersChange >= 0)
                                        <svg class="h-3.5 w-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                                        <span class="text-xs font-semibold text-emerald-500">+{{ $usersChange }}%</span>
                                    @else
                                        <svg class="h-3.5 w-3.5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                        <span class="text-xs font-semibold text-red-500">{{ $usersChange }}%</span>
                                    @endif
                                    <span class="text-xs text-gray-400 dark:text-gray-500">@lang('admin::app.super_admin.dashboard.vs_last_month')</span>
                                </div>
                            </div>
                            <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-violet-50 text-violet-600 transition-colors group-hover:bg-violet-100 dark:bg-violet-950 dark:text-violet-400 dark:group-hover:bg-violet-900">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                        </div>
                    </div>

                    {{-- Total Leads --}}
                    <div class="group relative overflow-hidden rounded-xl border border-gray-200 bg-white p-5 transition-all duration-200 hover:shadow-lg hover:border-amber-300 dark:border-gray-800 dark:bg-gray-900 dark:hover:border-amber-700">
                        <div class="flex items-start justify-between">
                            <div class="flex flex-col gap-1">
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">@lang('admin::app.super_admin.dashboard.total_leads')</p>
                                <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalLeads }}</h3>
                                <div class="mt-1 flex items-center gap-1">
                                    @if($leadsChange >= 0)
                                        <svg class="h-3.5 w-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                                        <span class="text-xs font-semibold text-emerald-500">+{{ $leadsChange }}%</span>
                                    @else
                                        <svg class="h-3.5 w-3.5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                        <span class="text-xs font-semibold text-red-500">{{ $leadsChange }}%</span>
                                    @endif
                                    <span class="text-xs text-gray-400 dark:text-gray-500">@lang('admin::app.super_admin.dashboard.vs_last_month')</span>
                                </div>
                            </div>
                            <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-amber-50 text-amber-600 transition-colors group-hover:bg-amber-100 dark:bg-amber-950 dark:text-amber-400 dark:group-hover:bg-amber-900">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            </div>
                        </div>
                    </div>

                    {{-- Pendapatan Bulan Ini --}}
                    <a href="{{ route('super_admin.invoices.index') }}" class="group relative overflow-hidden rounded-xl border border-gray-200 bg-white p-5 transition-all duration-200 hover:shadow-lg hover:border-cyan-300 dark:border-gray-800 dark:bg-gray-900 dark:hover:border-cyan-700">
                        <div class="flex items-start justify-between">
                            <div class="flex flex-col gap-1">
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">@lang('admin::app.super_admin.dashboard.monthly_revenue')</p>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($revenueThisMonth, 0, ',', '.') }}</h3>
                                <div class="mt-1 flex items-center gap-1">
                                    @if($revenueChange >= 0)
                                        <svg class="h-3.5 w-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                                        <span class="text-xs font-semibold text-emerald-500">+{{ $revenueChange }}%</span>
                                    @else
                                        <svg class="h-3.5 w-3.5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                        <span class="text-xs font-semibold text-red-500">{{ $revenueChange }}%</span>
                                    @endif
                                    <span class="text-xs text-gray-400 dark:text-gray-500">@lang('admin::app.super_admin.dashboard.vs_last_month')</span>
                                </div>
                            </div>
                            <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-cyan-50 text-cyan-600 transition-colors group-hover:bg-cyan-100 dark:bg-cyan-950 dark:text-cyan-400 dark:group-hover:bg-cyan-900">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- ── Charts Row 1: Tenant Growth + Plan Distribution ────── --}}
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                    {{-- Pertumbuhan Tenant (Line Chart) --}}
                    <div class="rounded-xl border border-gray-200 bg-white p-6 lg:col-span-2 dark:border-gray-800 dark:bg-gray-900">
                        <div class="mb-5 flex items-center justify-between">
                            <div>
                                <h3 class="text-base font-bold text-gray-900 dark:text-white">@lang('admin::app.super_admin.dashboard.tenant_growth')</h3>
                                <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">@lang('admin::app.super_admin.dashboard.tenant_growth_sub')</p>
                            </div>
                            <span class="rounded-lg bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-600 dark:bg-blue-950 dark:text-blue-400">6 Bulan</span>
                        </div>
                        <div class="relative h-[280px]">
                            <canvas id="tenantGrowthChart"></canvas>
                        </div>
                    </div>

                    {{-- Distribusi Plan (Doughnut Chart) --}}
                    <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                        <div class="mb-5">
                            <h3 class="text-base font-bold text-gray-900 dark:text-white">@lang('admin::app.super_admin.dashboard.plan_distribution')</h3>
                            <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">@lang('admin::app.super_admin.dashboard.plan_dist_sub')</p>
                        </div>
                        @if($plansDistribution->isEmpty())
                            <div class="flex h-[280px] items-center justify-center">
                                <p class="text-sm text-gray-400 dark:text-gray-500">Belum ada data distribusi plan</p>
                            </div>
                        @else
                            <div class="relative mx-auto h-[200px] w-[200px]">
                                <canvas id="planDistributionChart"></canvas>
                            </div>
                            {{-- Legend --}}
                            <div class="mt-4 flex flex-wrap justify-center gap-3">
                                @php
                                    $planColors = ['#3B82F6', '#10B981', '#F59E0B', '#8B5CF6', '#EF4444', '#06B6D4'];
                                @endphp
                                @foreach($plansDistribution as $index => $dist)
                                    <div class="flex items-center gap-1.5">
                                        <span class="h-2.5 w-2.5 rounded-full" style="background-color: {{ $planColors[$index % count($planColors)] }}"></span>
                                        <span class="text-xs font-medium text-gray-600 dark:text-gray-300">{{ $dist->name }} ({{ $dist->count }})</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ── Charts Row 2: Revenue + Invoice Status ─────────────── --}}
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                    {{-- Revenue per Bulan (Bar Chart) --}}
                    <div class="rounded-xl border border-gray-200 bg-white p-6 lg:col-span-2 dark:border-gray-800 dark:bg-gray-900">
                        <div class="mb-5 flex items-center justify-between">
                            <div>
                                <h3 class="text-base font-bold text-gray-900 dark:text-white">@lang('admin::app.super_admin.dashboard.revenue_overview')</h3>
                                <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">@lang('admin::app.super_admin.dashboard.revenue_sub')</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-gray-900 dark:text-white">Total: Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="relative h-[280px]">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>

                    {{-- Invoice Status Summary --}}
                    <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                        <div class="mb-5">
                            <h3 class="text-base font-bold text-gray-900 dark:text-white">@lang('admin::app.super_admin.dashboard.invoice_status')</h3>
                            <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">@lang('admin::app.super_admin.invoices.description')</p>
                        </div>
                        <div class="flex flex-col gap-4">
                            @php
                                $totalInvoices = array_sum($invoiceSummary);
                            @endphp

                            {{-- Paid --}}
                            <div class="flex flex-col gap-2">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('admin::app.super_admin.invoices.paid')</span>
                                    </div>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $invoiceSummary['paid'] }}</span>
                                </div>
                                <div class="h-2 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-gray-800">
                                    <div class="h-full rounded-full bg-emerald-500 transition-all duration-500" style="width: {{ $totalInvoices > 0 ? ($invoiceSummary['paid'] / $totalInvoices) * 100 : 0 }}%"></div>
                                </div>
                            </div>

                            {{-- Pending --}}
                            <div class="flex flex-col gap-2">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('admin::app.super_admin.invoices.pending')</span>
                                    </div>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $invoiceSummary['pending'] }}</span>
                                </div>
                                <div class="h-2 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-gray-800">
                                    <div class="h-full rounded-full bg-amber-500 transition-all duration-500" style="width: {{ $totalInvoices > 0 ? ($invoiceSummary['pending'] / $totalInvoices) * 100 : 0 }}%"></div>
                                </div>
                            </div>

                            {{-- Failed --}}
                            <div class="flex flex-col gap-2">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('admin::app.super_admin.invoices.failed')</span>
                                    </div>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $invoiceSummary['failed'] }}</span>
                                </div>
                                <div class="h-2 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-gray-800">
                                    <div class="h-full rounded-full bg-red-500 transition-all duration-500" style="width: {{ $totalInvoices > 0 ? ($invoiceSummary['failed'] / $totalInvoices) * 100 : 0 }}%"></div>
                                </div>
                            </div>

                            {{-- Expired --}}
                            <div class="flex flex-col gap-2">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="h-2.5 w-2.5 rounded-full bg-gray-400"></span>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('admin::app.super_admin.invoices.expired')</span>
                                    </div>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $invoiceSummary['expired'] }}</span>
                                </div>
                                <div class="h-2 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-gray-800">
                                    <div class="h-full rounded-full bg-gray-400 transition-all duration-500" style="width: {{ $totalInvoices > 0 ? ($invoiceSummary['expired'] / $totalInvoices) * 100 : 0 }}%"></div>
                                </div>
                            </div>

                            {{-- Total --}}
                            <div class="mt-2 flex items-center justify-between rounded-lg bg-gray-50 px-3 py-2.5 dark:bg-gray-800">
                                <span class="text-sm font-semibold text-gray-600 dark:text-gray-300">Total Invoice</span>
                                <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $totalInvoices }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Tables Row: Recent Tenants + Recent Invoices ──────── --}}
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

                    {{-- Tenant Terbaru --}}
                    <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-800">
                            <div>
                                <h3 class="text-base font-bold text-gray-900 dark:text-white">@lang('admin::app.super_admin.dashboard.recent_tenants')</h3>
                                <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">5 perusahaan terakhir bergabung</p>
                            </div>
                            <a href="{{ route('super_admin.companies.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                @lang('admin::app.super_admin.dashboard.view_all') →
                            </a>
                        </div>
                        @if($recentTenants->isEmpty())
                            <div class="px-6 py-10 text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 v5m-4 0h4"/></svg>
                                <p class="mt-2 text-sm text-gray-400 dark:text-gray-500">Belum ada tenant terdaftar</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="border-b border-gray-100 dark:border-gray-800">
                                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">@lang('admin::app.super_admin.companies.name')</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">@lang('admin::app.super_admin.companies.subscription_plan')</th>
                                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">@lang('admin::app.super_admin.companies.account_status')</th>
                                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">@lang('admin::app.super_admin.companies.registered_date')</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                                        @foreach($recentTenants as $tenant)
                                            <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                                <td class="px-6 py-3.5">
                                                    <a href="{{ route('super_admin.companies.show', $tenant->id) }}" class="text-sm font-semibold text-gray-900 hover:text-blue-600 dark:text-white dark:hover:text-blue-400">
                                                        {{ $tenant->name }}
                                                    </a>
                                                </td>
                                                <td class="px-4 py-3.5">
                                                    <span class="inline-flex rounded-md bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                                        {{ $tenant->plan_name ?? '-' }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3.5 text-center">
                                                    @if($tenant->is_active)
                                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-700 dark:bg-emerald-950 dark:text-emerald-400">
                                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                            Aktif
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-0.5 text-xs font-semibold text-red-700 dark:bg-red-950 dark:text-red-400">
                                                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                                            Nonaktif
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-3.5 text-right text-xs text-gray-500 dark:text-gray-400">
                                                    {{ \Carbon\Carbon::parse($tenant->created_at)->translatedFormat('d M Y') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    {{-- Invoice Terbaru --}}
                    <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-800">
                            <div>
                                <h3 class="text-base font-bold text-gray-900 dark:text-white">Invoice Terbaru</h3>
                                <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">5 invoice terakhir dibuat</p>
                            </div>
                            <a href="{{ route('super_admin.invoices.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                Lihat Semua →
                            </a>
                        </div>
                        @if($recentInvoices->isEmpty())
                            <div class="px-6 py-10 text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p class="mt-2 text-sm text-gray-400 dark:text-gray-500">Belum ada invoice</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="border-b border-gray-100 dark:border-gray-800">
                                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Invoice</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Perusahaan</th>
                                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Nominal</th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                                        @foreach($recentInvoices as $invoice)
                                            <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                                <td class="px-6 py-3.5">
                                                    <a href="{{ route('super_admin.invoices.show', $invoice->id) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                        {{ $invoice->invoice_number }}
                                                    </a>
                                                </td>
                                                <td class="px-4 py-3.5 text-sm text-gray-700 dark:text-gray-300">
                                                    {{ $invoice->company_name ?? '-' }}
                                                </td>
                                                <td class="px-4 py-3.5 text-right text-sm font-semibold text-gray-900 dark:text-white">
                                                    Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                                                </td>
                                                <td class="px-6 py-3.5 text-center">
                                                    @switch($invoice->status)
                                                        @case('paid')
                                                            <span class="inline-flex rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-700 dark:bg-emerald-950 dark:text-emerald-400">Lunas</span>
                                                            @break
                                                        @case('pending')
                                                            <span class="inline-flex rounded-full bg-amber-50 px-2 py-0.5 text-xs font-semibold text-amber-700 dark:bg-amber-950 dark:text-amber-400">Pending</span>
                                                            @break
                                                        @case('failed')
                                                            <span class="inline-flex rounded-full bg-red-50 px-2 py-0.5 text-xs font-semibold text-red-700 dark:bg-red-950 dark:text-red-400">Gagal</span>
                                                            @break
                                                        @case('expired')
                                                            <span class="inline-flex rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-600 dark:bg-gray-800 dark:text-gray-400">Expired</span>
                                                            @break
                                                    @endswitch
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ── Status Platform ────────────────────────────────────── --}}
                <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
                    <h3 class="mb-4 text-base font-bold text-gray-900 dark:text-white">Status Layanan Platform</h3>
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                        <div class="flex items-center gap-3 rounded-lg bg-gray-50 px-4 py-3 dark:bg-gray-800">
                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 dark:text-gray-500">Sistem</p>
                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ PHP_OS }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 rounded-lg bg-gray-50 px-4 py-3 dark:bg-gray-800">
                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-violet-100 text-violet-600 dark:bg-violet-900 dark:text-violet-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 dark:text-gray-500">PHP</p>
                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ PHP_VERSION }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 rounded-lg bg-gray-50 px-4 py-3 dark:bg-gray-800">
                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-100 text-amber-600 dark:bg-amber-900 dark:text-amber-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 dark:text-gray-500">Database</p>
                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">MySQL 8.0+</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 rounded-lg bg-gray-50 px-4 py-3 dark:bg-gray-800">
                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 dark:bg-emerald-900 dark:text-emerald-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 dark:text-gray-500">Payment</p>
                                <p class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">Xendit Active</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-super-admin-dashboard', {
                template: '#v-super-admin-dashboard-template',

                data() {
                    return {
                        tenantGrowth: @json($tenantGrowth),
                        revenueChart: @json($revenueChart),
                        plansDistribution: @json($plansDistribution),
                    }
                },

                mounted() {
                    this.initCharts();
                },

                methods: {
                    initCharts() {
                        if (typeof window.Chart === 'undefined') {
                            setTimeout(() => this.initCharts(), 100);
                            return;
                        }

                        this.renderTenantGrowthChart();
                        this.renderPlanDistributionChart();
                        this.renderRevenueChart();
                    },

                    renderTenantGrowthChart() {
                        const canvas = document.getElementById('tenantGrowthChart');
                        if (!canvas) return;

                        const isDark = document.documentElement.classList.contains('dark');
                        const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';
                        const textColor = isDark ? '#9CA3AF' : '#6B7280';

                        const ctx = canvas.getContext('2d');
                        const tgGradient = ctx.createLinearGradient(0, 0, 0, 280);
                        tgGradient.addColorStop(0, 'rgba(59, 130, 246, 0.15)');
                        tgGradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

                        new window.Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: this.tenantGrowth.map(item => item.label),
                                datasets: [{
                                    label: 'Total Tenant',
                                    data: this.tenantGrowth.map(item => item.count),
                                    borderColor: '#3B82F6',
                                    backgroundColor: tgGradient,
                                    borderWidth: 2.5,
                                    fill: true,
                                    tension: 0.4,
                                    pointBackgroundColor: '#3B82F6',
                                    pointBorderColor: '#fff',
                                    pointBorderWidth: 2,
                                    pointRadius: 4,
                                    pointHoverRadius: 6,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        backgroundColor: isDark ? '#1F2937' : '#fff',
                                        titleColor: isDark ? '#F9FAFB' : '#111827',
                                        bodyColor: isDark ? '#D1D5DB' : '#4B5563',
                                        borderColor: isDark ? '#374151' : '#E5E7EB',
                                        borderWidth: 1,
                                        cornerRadius: 8,
                                        padding: 10,
                                        displayColors: false,
                                        callbacks: {
                                            label: ctx => ctx.parsed.y + ' perusahaan',
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        grid: { display: false },
                                        ticks: { color: textColor, font: { size: 11, weight: 500 } },
                                        border: { display: false },
                                    },
                                    y: {
                                        beginAtZero: true,
                                        grid: { color: gridColor },
                                        ticks: {
                                            color: textColor,
                                            font: { size: 11 },
                                            stepSize: 1,
                                        },
                                        border: { display: false },
                                    }
                                }
                            }
                        });
                    },

                    renderPlanDistributionChart() {
                        const canvas = document.getElementById('planDistributionChart');
                        if (!canvas) return;

                        const isDark = document.documentElement.classList.contains('dark');
                        const planColors = ['#3B82F6', '#10B981', '#F59E0B', '#8B5CF6', '#EF4444', '#06B6D4'];

                        new window.Chart(canvas, {
                            type: 'doughnut',
                            data: {
                                labels: this.plansDistribution.map(p => p.name),
                                datasets: [{
                                    data: this.plansDistribution.map(p => p.count),
                                    backgroundColor: planColors.slice(0, this.plansDistribution.length),
                                    borderWidth: 0,
                                    hoverOffset: 6,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '68%',
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        backgroundColor: isDark ? '#1F2937' : '#fff',
                                        titleColor: isDark ? '#F9FAFB' : '#111827',
                                        bodyColor: isDark ? '#D1D5DB' : '#4B5563',
                                        borderColor: isDark ? '#374151' : '#E5E7EB',
                                        borderWidth: 1,
                                        cornerRadius: 8,
                                        padding: 10,
                                        callbacks: {
                                            label: ctx => ctx.label + ': ' + ctx.parsed + ' tenant',
                                        }
                                    }
                                }
                            }
                        });
                    },

                    renderRevenueChart() {
                        const canvas = document.getElementById('revenueChart');
                        if (!canvas) return;

                        const isDark = document.documentElement.classList.contains('dark');
                        const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';
                        const textColor = isDark ? '#9CA3AF' : '#6B7280';

                        new window.Chart(canvas, {
                            type: 'bar',
                            data: {
                                labels: this.revenueChart.map(item => item.label),
                                datasets: [{
                                    label: 'Revenue',
                                    data: this.revenueChart.map(item => item.amount),
                                    backgroundColor: isDark ? 'rgba(6, 182, 212, 0.7)' : 'rgba(6, 182, 212, 0.8)',
                                    hoverBackgroundColor: '#06B6D4',
                                    borderRadius: 6,
                                    borderSkipped: false,
                                    barPercentage: 0.6,
                                    categoryPercentage: 0.7,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        backgroundColor: isDark ? '#1F2937' : '#fff',
                                        titleColor: isDark ? '#F9FAFB' : '#111827',
                                        bodyColor: isDark ? '#D1D5DB' : '#4B5563',
                                        borderColor: isDark ? '#374151' : '#E5E7EB',
                                        borderWidth: 1,
                                        cornerRadius: 8,
                                        padding: 10,
                                        displayColors: false,
                                        callbacks: {
                                            label: ctx => 'Rp ' + new Intl.NumberFormat('id-ID').format(ctx.parsed.y),
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        grid: { display: false },
                                        ticks: { color: textColor, font: { size: 11, weight: 500 } },
                                        border: { display: false },
                                    },
                                    y: {
                                        beginAtZero: true,
                                        grid: { color: gridColor },
                                        ticks: {
                                            color: textColor,
                                            font: { size: 11 },
                                            callback: val => 'Rp ' + new Intl.NumberFormat('id-ID').format(val),
                                        },
                                        border: { display: false },
                                    }
                                }
                            }
                        });
                    }
                }
            });
        </script>
    @endPushOnce
</x-admin::layouts>
