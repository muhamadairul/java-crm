<x-admin::layouts>
    <x-slot:title>
        Kelola Paket (Plans)
    </x-slot>

    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-4 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-2">
                <div class="text-xl font-bold dark:text-white">
                    Kelola Paket (Plans)
                </div>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-xs bg-blue-50 text-blue-700 dark:bg-blue-950 dark:text-blue-300 px-3 py-1 rounded-full font-semibold border border-blue-100 dark:border-blue-900">Super Admin Mode</span>
                <span class="text-sm text-gray-500 dark:text-gray-400 font-semibold">{{ $plans->count() }} Paket</span>
            </div>
        </div>

    <div class="bg-white rounded-2xl border border-gray-150 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-base font-bold text-gray-800">Daftar Paket Langganan</h3>
            <span class="text-xs text-gray-400 font-semibold">{{ $plans->count() }} Paket</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50 text-[10px] text-gray-400 font-bold uppercase tracking-wider">
                        <th class="py-4 px-6">Nama Paket</th>
                        <th class="py-4 px-6">Kode Paket</th>
                        <th class="py-4 px-6">Harga Bulan</th>
                        <th class="py-4 px-6">Batas Anggota</th>
                        <th class="py-4 px-6">Batas Prospek (Leads)</th>
                        <th class="py-4 px-6">Batas Penyimpanan</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm font-medium text-gray-700">
                    @foreach($plans as $plan)
                        <tr class="hover:bg-gray-50/30 transition-colors">
                            <td class="py-4 px-6">
                                <div class="font-bold text-gray-800">{{ $plan->name }}</div>
                                <div class="text-xs text-gray-400 font-semibold mt-0.5">{{ $plan->description }}</div>
                            </td>
                            <td class="py-4 px-6 font-mono text-xs font-bold">{{ $plan->code }}</td>
                            <td class="py-4 px-6 font-extrabold text-blue-600">${{ number_format($plan->price, 2) }}</td>
                            <td class="py-4 px-6">{{ $plan->max_users }} Pengguna</td>
                            <td class="py-4 px-6">{{ number_format($plan->max_leads) }} Prospek</td>
                            <td class="py-4 px-6">{{ number_format($plan->max_storage_mb) }} MB</td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('super_admin.plans.edit', ['id' => $plan->id]) }}" class="text-xs font-bold bg-white border border-gray-200 hover:border-gray-300 text-gray-700 px-3 py-1.5 rounded-xl shadow-sm transition-all inline-block">
                                    Edit Limits
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</x-admin::layouts>
