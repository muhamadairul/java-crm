<x-admin::layouts>
    <x-slot:title>
        Dashboard Monitoring Platform
    </x-slot>

    <div class="flex flex-col gap-4">
        <!-- Title and breadcrumbs header -->
        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-4 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-2">
                <div class="text-xl font-bold dark:text-white">
                    Dashboard Monitoring Platform
                </div>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-xs bg-blue-50 text-blue-700 dark:bg-blue-950 dark:text-blue-300 px-3 py-1 rounded-full font-semibold border border-blue-100 dark:border-blue-900">Super Admin Mode</span>
                <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">{{ now()->format('d F Y') }}</span>
            </div>
        </div>

    <!-- Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Total Companies -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-gray-400">Total Tenant (Perusahaan)</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalCompanies }}</h3>
            </div>
            <div class="h-12 w-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
        </div>

        <!-- Active Companies -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-gray-400">Tenant Aktif</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalActiveCompanies }}</h3>
            </div>
            <div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- Total Users -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-gray-400">Total Pengguna CRM</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalUsers }}</h3>
            </div>
            <div class="h-12 w-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
        </div>

        <!-- Total Leads -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-gray-400">Total Prospek (Leads)</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalLeads }}</h3>
            </div>
            <div class="h-12 w-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
        </div>

    </div>

    <!-- Details Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Plans Distribution -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 lg:col-span-2">
            <h3 class="text-base font-bold text-gray-800 mb-4">Distribusi Paket Langganan Tenant</h3>
            
            @if($plansDistribution->isEmpty())
                <p class="text-sm text-gray-400">Belum ada tenant aktif yang berlangganan plan.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 text-xs text-gray-400 font-semibold uppercase tracking-wider">
                                <th class="pb-3">Nama Paket (Plan)</th>
                                <th class="pb-3 text-right">Jumlah Perusahaan (Tenant)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-sm font-medium">
                            @foreach($plansDistribution as $dist)
                                <tr>
                                    <td class="py-4 text-gray-800">{{ $dist->name }}</td>
                                    <td class="py-4 text-right text-blue-600 font-semibold">{{ $dist->count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- System Status Quick Check -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="text-base font-bold text-gray-800 mb-4">Status Layanan Platform</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500 font-medium">Sistem Operasi</span>
                    <span class="font-semibold text-gray-800">Windows</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500 font-medium">Versi PHP</span>
                    <span class="font-semibold text-gray-800">{{ PHP_VERSION }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500 font-medium">Database Server</span>
                    <span class="font-semibold text-gray-800">MySQL 8.0+</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500 font-medium">Payment Gateway</span>
                    <span class="text-blue-600 font-semibold bg-blue-50 px-2.5 py-0.5 rounded-full border border-blue-100 text-xs">Xendit Active</span>
                </div>
            </div>
        </div>

    </div>
</div>
</x-admin::layouts>
