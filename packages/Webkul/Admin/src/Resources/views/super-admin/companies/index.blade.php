<x-admin::layouts>
    <x-slot:title>
        Kelola Perusahaan (Tenants)
    </x-slot>

    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-4 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-2">
                <div class="text-xl font-bold dark:text-white">
                    Kelola Perusahaan (Tenants)
                </div>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-xs bg-blue-50 text-blue-700 dark:bg-blue-950 dark:text-blue-300 px-3 py-1 rounded-full font-semibold border border-blue-100 dark:border-blue-900">Super Admin Mode</span>
                <span class="text-sm text-gray-500 dark:text-gray-400 font-semibold">{{ $companies->count() }} Perusahaan</span>
            </div>
        </div>

    <div class="bg-white rounded-2xl border border-gray-150 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-base font-bold text-gray-800">Daftar Perusahaan Terdaftar</h3>
            <span class="text-xs text-gray-400 font-semibold">{{ $companies->count() }} Perusahaan</span>
        </div>

        @if($companies->isEmpty())
            <div class="p-8 text-center text-gray-400 font-medium">
                Belum ada perusahaan/tenant yang terdaftar di platform.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/50 text-[10px] text-gray-400 font-bold uppercase tracking-wider">
                            <th class="py-4 px-6">Nama Perusahaan</th>
                            <th class="py-4 px-6">Domain / Slug</th>
                            <th class="py-4 px-6">Paket Langganan</th>
                            <th class="py-4 px-6">Status Akun</th>
                            <th class="py-4 px-6">Tanggal Daftar</th>
                            <th class="py-4 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm font-medium">
                        @foreach($companies as $company)
                            <tr class="hover:bg-gray-50/30 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="font-bold text-gray-800">{{ $company->name }}</div>
                                    <div class="text-xs text-gray-400 font-semibold mt-0.5">{{ $company->email ?? '-' }} • {{ $company->phone ?? '-' }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="bg-slate-100 text-slate-700 px-2.5 py-1 rounded-lg border border-slate-200 text-xs font-mono font-bold">{{ $company->slug }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    @if($company->plan)
                                        <span class="text-blue-600 font-bold bg-blue-50/80 px-2.5 py-1 rounded-full text-xs border border-blue-100">{{ $company->plan->name }}</span>
                                    @else
                                        <span class="text-gray-400 font-semibold">Tidak Ada Paket</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    @if($company->is_active)
                                        <span class="text-emerald-600 font-bold bg-emerald-50/80 px-2.5 py-1 rounded-full text-xs border border-emerald-100">Aktif</span>
                                    @else
                                        <span class="text-red-600 font-bold bg-red-50/80 px-2.5 py-1 rounded-full text-xs border border-red-100">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-gray-450 font-semibold text-xs">
                                    {{ $company->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Toggle Status Form -->
                                        <form action="{{ route('super_admin.companies.toggle_status', ['id' => $company->id]) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-xs font-bold px-3 py-1.5 rounded-xl transition-all border {{ $company->is_active ? 'bg-amber-50 border-amber-200 text-amber-700 hover:bg-amber-100' : 'bg-emerald-50 border-emerald-200 text-emerald-700 hover:bg-emerald-100' }}">
                                                {{ $company->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>

                                        <!-- Detail button -->
                                        <a href="{{ route('super_admin.companies.show', ['id' => $company->id]) }}" class="text-xs font-bold bg-blue-50 dark:bg-blue-950 hover:bg-blue-100 dark:hover:bg-blue-900 text-blue-700 dark:text-blue-300 px-3 py-1.5 rounded-xl transition-all border border-blue-100 dark:border-blue-900">
                                            Detail
                                        </a>

                                        <!-- Edit button -->
                                        <a href="{{ route('super_admin.companies.edit', ['id' => $company->id]) }}" class="text-xs font-bold bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 text-gray-700 dark:text-gray-300 px-3 py-1.5 rounded-xl shadow-sm transition-all">
                                            Edit
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
</x-admin::layouts>
