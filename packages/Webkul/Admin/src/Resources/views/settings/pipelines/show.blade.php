<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        Detail Pipeline - {{ $pipeline->name }}
    </x-slot>

    <div class="flex flex-col gap-4">
        <!-- Header Card -->
        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-3 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-1">
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.settings.pipelines.index') }}" class="text-xs font-bold text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white">
                        ← Kembali ke Daftar Pipeline
                    </a>
                </div>
                <div class="flex items-center gap-3 mt-1">
                    <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white">{{ $pipeline->name }}</h1>
                    @if($pipeline->is_default)
                        <span class="inline-flex items-center rounded-md bg-emerald-100 px-2.5 py-1 text-xs font-bold text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                            Default Pipeline
                        </span>
                    @endif
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Rincian tahapan alur penjualan (*stages*) dan statistik prospek.</p>
            </div>

            <div class="flex items-center gap-2">
                @if (bouncer()->hasPermission('settings.lead.pipelines.edit'))
                    <a href="{{ route('admin.settings.pipelines.edit', $pipeline->id) }}" class="primary-button">
                        Ubah Pipeline
                    </a>
                @endif
            </div>
        </div>

        <!-- Top Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Tahapan (Stages)</span>
                <p class="text-3xl font-extrabold text-sky-600 mt-1 dark:text-sky-400">{{ $stageStats->count() }} Stage</p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Prospek Aktif</span>
                <p class="text-3xl font-extrabold text-indigo-600 mt-1 dark:text-indigo-400">{{ $leadsCount }} Lead</p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Batas Hari Basi (Rotten Days)</span>
                <p class="text-2xl font-extrabold text-amber-600 mt-1 dark:text-amber-400">{{ $pipeline->rotten_days }} Hari</p>
            </div>
        </div>

        <!-- Split 2-Column Section: Left (Pipeline Info) & Right (Stages Breakdown) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
            
            <!-- Left Column: Pipeline Details & Info (4 cols) -->
            <div class="lg:col-span-4 rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900 space-y-4">
                <div class="border-b border-gray-100 pb-3 dark:border-gray-800">
                    <h2 class="text-base font-extrabold text-gray-900 dark:text-white">Informasi Pipeline</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Parameter alur penjualan.</p>
                </div>

                <div class="space-y-3.5 text-xs">
                    <div class="flex items-center justify-between py-1.5 border-b border-gray-100 dark:border-gray-800/60">
                        <span class="text-gray-500 font-medium dark:text-gray-400">Nama Pipeline</span>
                        <span class="font-extrabold text-gray-900 dark:text-white">{{ $pipeline->name }}</span>
                    </div>

                    <div class="flex items-center justify-between py-1.5 border-b border-gray-100 dark:border-gray-800/60">
                        <span class="text-gray-500 font-medium dark:text-gray-400">Status Default</span>
                        <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[11px] font-bold {{ $pipeline->is_default ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300' }}">
                            {{ $pipeline->is_default ? 'Ya (Default)' : 'Biasa' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between py-1.5 border-b border-gray-100 dark:border-gray-800/60">
                        <span class="text-gray-500 font-medium dark:text-gray-400">Hari Basi Prospek</span>
                        <span class="font-bold text-amber-600 dark:text-amber-400">{{ $pipeline->rotten_days }} Hari</span>
                    </div>

                    <div class="flex items-center justify-between py-1.5 border-b border-gray-100 dark:border-gray-800/60">
                        <span class="text-gray-500 font-medium dark:text-gray-400">Total Nilai Potensi</span>
                        <span class="font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($totalPipelineValue, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex items-center justify-between py-1.5">
                        <span class="text-gray-500 font-medium dark:text-gray-400">Tanggal Dibuat</span>
                        <span class="font-bold text-gray-800 dark:text-gray-200">{{ $pipeline->created_at ? $pipeline->created_at->format('d M Y, H:i') : '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Right Column: Stages & Probability Breakdown (8 cols) -->
            <div class="lg:col-span-8 rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900 space-y-4">
                <div class="border-b border-gray-100 pb-3 dark:border-gray-800 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-extrabold text-gray-900 dark:text-white">Tahapan Penjualan (Stages)</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Urutan alur dan estimasi probabilitas keberhasilan tiap tahap.</p>
                    </div>
                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400">({{ $stageStats->count() }} Stage)</span>
                </div>

                @if($stageStats->isEmpty())
                    <div class="py-8 text-center text-xs text-gray-400 font-medium">
                        Belum ada tahapan (stage) dalam pipeline ini.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="border-b border-gray-200 bg-gray-50 text-gray-600 uppercase font-bold dark:border-gray-800 dark:bg-gray-950 dark:text-gray-400">
                                <tr>
                                    <th class="px-3 py-2.5 w-12 text-center">Urutan</th>
                                    <th class="px-3 py-2.5">Nama Tahap</th>
                                    <th class="px-3 py-2.5">Kode Stage</th>
                                    <th class="px-3 py-2.5">Probabilitas</th>
                                    <th class="px-3 py-2.5 text-center">Jumlah Lead</th>
                                    <th class="px-3 py-2.5 text-right">Nilai Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @foreach($stageStats as $index => $stage)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-950/50">
                                        <td class="px-3 py-3 text-center">
                                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-gray-100 text-xs font-extrabold text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                                {{ $stage->sort_order ?: ($index + 1) }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-3 font-bold text-gray-900 dark:text-white">
                                            {{ $stage->name }}
                                        </td>
                                        <td class="px-3 py-3 text-gray-500 font-mono text-[11px]">
                                            {{ $stage->code }}
                                        </td>
                                        <td class="px-3 py-3 font-bold text-sky-600 dark:text-sky-400">
                                            <div class="flex items-center gap-2">
                                                <div class="w-16 bg-gray-200 rounded-full h-2 dark:bg-gray-700 overflow-hidden">
                                                    <div class="bg-sky-600 h-2 rounded-full" style="width: {{ min(100, max(0, $stage->probability)) }}%"></div>
                                                </div>
                                                <span>{{ $stage->probability }}%</span>
                                            </div>
                                        </td>
                                        <td class="px-3 py-3 text-center font-bold text-indigo-600 dark:text-indigo-400">
                                            {{ $stage->leads_count }} Lead
                                        </td>
                                        <td class="px-3 py-3 text-right font-bold text-emerald-600 dark:text-emerald-400">
                                            Rp {{ number_format($stage->total_value ?: 0, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-admin::layouts>
