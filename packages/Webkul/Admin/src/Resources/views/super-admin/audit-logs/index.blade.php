<x-admin::layouts>
    <x-slot:title>
        Audit Trail & Activity Logs
    </x-slot>

    <div class="flex flex-col gap-6">
        <!-- Header -->
        <div class="flex items-center justify-between rounded-xl border border-gray-200 bg-white px-6 py-5 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-1">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Audit Trail & Log Aktivitas
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Catatan histori aktivitas administratif dan perubahan keamanan oleh Super Admin.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('super_admin.dashboard.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-xs font-semibold text-gray-700 shadow-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    ← Kembali ke Dashboard
                </a>
            </div>
        </div>

        <!-- Filters -->
        <form method="GET" action="{{ route('super_admin.audit_logs.index') }}" class="flex flex-wrap items-center gap-3 rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-1 items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari deskripsi, aksi, atau IP address..." class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2 text-xs text-gray-900 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white">
            </div>

            <select name="module" class="rounded-lg border border-gray-300 bg-white px-3.5 py-2 text-xs text-gray-700 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                <option value="">Semua Modul</option>
                <option value="company" {{ request('module') === 'company' ? 'selected' : '' }}>Company</option>
                <option value="plan" {{ request('module') === 'plan' ? 'selected' : '' }}>Plan</option>
                <option value="invoice" {{ request('module') === 'invoice' ? 'selected' : '' }}>Invoice</option>
                <option value="auth" {{ request('module') === 'auth' ? 'selected' : '' }}>Auth</option>
            </select>

            <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-xs font-semibold text-white shadow-xs hover:bg-blue-700 transition-colors">
                Filter
            </button>
            
            @if(request()->anyFilled(['search', 'module']))
                <a href="{{ route('super_admin.audit_logs.index') }}" class="rounded-lg border border-gray-300 px-3 py-2 text-xs font-medium text-gray-500 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400">
                    Reset
                </a>
            @endif
        </form>

        <!-- Audit Log Table -->
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-xs dark:border-gray-800 dark:bg-gray-900">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-gray-600 dark:text-gray-300">
                    <thead class="bg-gray-50 text-[11px] font-semibold uppercase tracking-wider text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3.5">Waktu</th>
                            <th class="px-6 py-3.5">Super Admin</th>
                            <th class="px-6 py-3.5">Modul</th>
                            <th class="px-6 py-3.5">Aksi</th>
                            <th class="px-6 py-3.5">Deskripsi Detail</th>
                            <th class="px-6 py-3.5">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @forelse($logs as $log)
                            <tr class="hover:bg-gray-50/80 transition-colors dark:hover:bg-gray-800/50">
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    {{ $log->created_at->translatedFormat('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-gray-900 dark:text-white">
                                        {{ $log->superAdmin->name ?? 'System / Super Admin' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-[10px] font-bold text-blue-700 uppercase tracking-wider dark:bg-blue-950 dark:text-blue-300">
                                        {{ $log->module }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-mono font-semibold text-gray-800 dark:text-gray-200">
                                    {{ $log->action }}
                                </td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                    {{ $log->description }}
                                </td>
                                <td class="px-6 py-4 font-mono text-gray-500">
                                    {{ $log->ip_address ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                                    Belum ada catatan log aktivitas audit.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($logs->hasPages())
                <div class="border-t border-gray-200 p-4 dark:border-gray-800">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin::layouts>
