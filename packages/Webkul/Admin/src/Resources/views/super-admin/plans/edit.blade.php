<x-admin::layouts>
    <x-slot:title>
        Edit Konfigurasi Plan
    </x-slot>

    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-4 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-2">
                <div class="text-xl font-bold dark:text-white">
                    Edit Konfigurasi Plan
                </div>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-xs bg-blue-50 text-blue-700 dark:bg-blue-950 dark:text-blue-300 px-3 py-1 rounded-full font-semibold border border-blue-100 dark:border-blue-900">Super Admin Mode</span>
            </div>
        </div>

    <div class="max-w-2xl bg-white rounded-lg border border-gray-200 dark:border-gray-800 dark:bg-gray-900 shadow-sm p-8 space-y-6">
        <h3 class="text-base font-bold text-gray-800 dark:text-white">Ubah Batasan Paket (Plan): {{ $plan->name }}</h3>
        
        <form action="{{ route('super_admin.plans.update', ['id' => $plan->id]) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Nama Paket</label>
                <input type="text" name="name" id="name" required value="{{ old('name', $plan->name) }}" class="block w-full rounded-xl border-gray-250 dark:border-gray-800 dark:bg-gray-950 dark:text-white border px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="price" class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Harga Per Bulan (USD)</label>
                    <input type="number" step="0.01" name="price" id="price" required value="{{ old('price', $plan->price) }}" class="block w-full rounded-xl border-gray-250 dark:border-gray-800 dark:bg-gray-950 dark:text-white border px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors">
                </div>
                <div>
                    <label for="max_users" class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Batas Pengguna / Anggota</label>
                    <input type="number" name="max_users" id="max_users" required value="{{ old('max_users', $plan->max_users) }}" class="block w-full rounded-xl border-gray-250 dark:border-gray-800 dark:bg-gray-950 dark:text-white border px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="max_leads" class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Batas Prospek (Leads)</label>
                    <input type="number" name="max_leads" id="max_leads" required value="{{ old('max_leads', $plan->max_leads) }}" class="block w-full rounded-xl border-gray-250 dark:border-gray-800 dark:bg-gray-950 dark:text-white border px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors">
                </div>
                <div>
                    <label for="max_storage_mb" class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Kapasitas Penyimpanan (MB)</label>
                    <input type="number" name="max_storage_mb" id="max_storage_mb" required value="{{ old('max_storage_mb', $plan->max_storage_mb) }}" class="block w-full rounded-xl border-gray-250 dark:border-gray-800 dark:bg-gray-950 dark:text-white border px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors">
                </div>
            </div>

            <div>
                <label for="description" class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Deskripsi Paket</label>
                <textarea name="description" id="description" rows="3" class="block w-full rounded-xl border-gray-250 dark:border-gray-800 dark:bg-gray-950 dark:text-white border px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors">{{ old('description', $plan->description) }}</textarea>
            </div>

            <div class="flex items-center gap-3 border-t border-gray-100 dark:border-gray-800 pt-6">
                <a href="{{ route('super_admin.plans.index') }}" class="bg-white hover:bg-gray-50 dark:bg-gray-900 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-gray-800 border border-gray-200 text-gray-750 font-bold py-3 px-6 rounded-xl text-xs transition-colors shadow-sm">
                    Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl text-xs transition-all shadow-md shadow-blue-100 dark:shadow-none">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
    </div>
</x-admin::layouts>
