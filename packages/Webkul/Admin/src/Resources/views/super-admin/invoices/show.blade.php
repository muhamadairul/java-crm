<x-admin::layouts>
    <x-slot:title>
        Detail Invoice #{{ $invoice->invoice_number }}
    </x-slot>

    <div class="flex flex-col gap-4">
        <!-- Header -->
        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-4 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-2">
                <div class="text-xl font-bold dark:text-white">
                    Invoice #{{ $invoice->invoice_number }}
                </div>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('super_admin.invoices.index') }}" class="bg-white hover:bg-gray-50 dark:bg-gray-900 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-gray-800 border border-gray-200 text-gray-700 py-2 px-6 rounded-xl text-xs transition-colors shadow-sm">
                    Kembali
                </a>
                <span class="text-xs bg-blue-50 text-blue-700 dark:bg-blue-950 dark:text-blue-300 px-3 py-1 rounded-full font-semibold border border-blue-100 dark:border-blue-900">Super Admin Mode</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Invoice Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Billing Details Card -->
                <div class="bg-white rounded-lg border border-gray-200 dark:border-gray-800 dark:bg-gray-900 p-6 space-y-6 dark:text-gray-300">
                    <div class="flex justify-between items-start border-b border-gray-100 dark:border-gray-800 pb-4">
                        <div>
                            <h3 class="text-base font-bold text-gray-800 dark:text-white">Informasi Tagihan</h3>
                            <p class="text-xs text-gray-400 mt-1">Detail mengenai transaksi pembayaran dan tenant.</p>
                        </div>
                        <div>
                            @if($invoice->status === 'paid')
                                <span class="text-emerald-600 font-bold bg-emerald-50/80 px-3 py-1.5 rounded-full text-xs border border-emerald-100 dark:bg-emerald-950 dark:text-emerald-300 dark:border-emerald-900">Lunas / Paid</span>
                            @elseif($invoice->status === 'pending')
                                <span class="text-amber-600 font-bold bg-amber-50/80 px-3 py-1.5 rounded-full text-xs border border-amber-100 dark:bg-amber-950 dark:text-amber-300 dark:border-amber-900">Pending</span>
                            @elseif($invoice->status === 'expired')
                                <span class="text-gray-600 font-bold bg-gray-50/80 px-3 py-1.5 rounded-full text-xs border border-gray-100 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700">Expired</span>
                            @else
                                <span class="text-red-600 font-bold bg-red-50/80 px-3 py-1.5 rounded-full text-xs border border-red-100 dark:bg-red-950 dark:text-red-300 dark:border-red-900">Gagal</span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Perusahaan (Tenant)</h4>
                            <div class="mt-2 space-y-1">
                                <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $invoice->company->name ?? '-' }}</p>
                                <p class="text-sm">{{ $invoice->company->email ?? '-' }}</p>
                                <p class="text-sm">{{ $invoice->company->phone ?? '-' }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $invoice->company->address ?? '-' }}</p>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Detail Pembayaran</h4>
                            <div class="mt-2 space-y-1.5">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Metode</span>
                                    <span class="font-semibold text-gray-800 dark:text-white">{{ $invoice->payment_method }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Mata Uang</span>
                                    <span class="font-semibold text-gray-800 dark:text-white">{{ $invoice->currency }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Nominal</span>
                                    <span class="font-extrabold text-blue-600 dark:text-blue-400">{{ $invoice->currency }} {{ number_format($invoice->amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm border-t border-gray-100 dark:border-gray-800 pt-1.5">
                                    <span class="text-gray-500">Tgl. Dibuat</span>
                                    <span class="text-gray-700 dark:text-gray-400 text-xs">{{ $invoice->created_at->format('d M Y, H:i') }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Tgl. Lunas</span>
                                    <span class="text-gray-700 dark:text-gray-400 text-xs">{{ $invoice->paid_at ? $invoice->paid_at->format('d M Y, H:i') : '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Xendit Callback Raw Response Card -->
                <div class="bg-white rounded-lg border border-gray-200 dark:border-gray-800 dark:bg-gray-900 p-6 space-y-4 dark:text-gray-300">
                    <div>
                        <h3 class="text-base font-bold text-gray-800 dark:text-white">RAW Metadata Callback</h3>
                        <p class="text-xs text-gray-400 mt-1">Data mentah yang dikirim oleh gateway pembayaran Xendit.</p>
                    </div>
                    
                    @if($invoice->notes)
                        <pre class="bg-gray-50 dark:bg-gray-950 p-4 rounded-xl text-xs font-mono overflow-x-auto text-gray-700 dark:text-gray-300 border border-gray-150 dark:border-gray-800 max-h-80">{{ json_encode(json_decode($invoice->notes), JSON_PRETTY_PRINT) }}</pre>
                    @else
                        <p class="text-sm text-gray-400 italic">Tidak ada metadata callback yang disimpan.</p>
                    @endif
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                <!-- Plan Info Card -->
                <div class="bg-white rounded-lg border border-gray-200 dark:border-gray-800 dark:bg-gray-900 p-6 space-y-4 dark:text-gray-300">
                    <h3 class="text-base font-bold text-gray-800 dark:text-white">Paket Langganan (SaaS Plan)</h3>
                    @if($invoice->subscription && $invoice->subscription->plan)
                        @php $plan = $invoice->subscription->plan; @endphp
                        <div class="space-y-3">
                            <div class="p-3 bg-blue-50/50 dark:bg-blue-950/30 border border-blue-100 dark:border-blue-900 rounded-xl">
                                <h4 class="text-sm font-bold text-blue-600 dark:text-blue-400">{{ $plan->name }}</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $plan->description }}</p>
                            </div>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Harga Dasar</span>
                                    <span class="font-semibold text-gray-800 dark:text-white">${{ number_format($plan->price, 0) }}/bulan</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Batas Anggota</span>
                                    <span class="font-semibold text-gray-800 dark:text-white">{{ $plan->max_users }} Pengguna</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Batas Leads</span>
                                    <span class="font-semibold text-gray-800 dark:text-white">{{ number_format($plan->max_leads) }} Prospek</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Penyimpanan</span>
                                    <span class="font-semibold text-gray-800 dark:text-white">{{ number_format($plan->max_storage_mb) }} MB</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-gray-400 italic">Langganan tidak ditemukan.</p>
                    @endif
                </div>

                <!-- Xendit System Connection Card -->
                <div class="bg-white rounded-lg border border-gray-200 dark:border-gray-800 dark:bg-gray-900 p-6 space-y-3 dark:text-gray-300">
                    <h3 class="text-base font-bold text-gray-800 dark:text-white">Gateway Gateway</h3>
                    <div class="space-y-2 text-xs">
                        <div>
                            <span class="text-gray-500 font-semibold uppercase block">Xendit Invoice ID</span>
                            <span class="font-mono mt-1 block select-all font-bold dark:text-white">{{ $invoice->xendit_invoice_id ?: '-' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 font-semibold uppercase block">Xendit Invoice URL</span>
                            @if($invoice->xendit_invoice_url)
                                <a href="{{ $invoice->xendit_invoice_url }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline break-all mt-1 block font-bold">
                                    {{ $invoice->xendit_invoice_url }}
                                </a>
                            @else
                                <span class="mt-1 block font-semibold text-gray-400">-</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin::layouts>
