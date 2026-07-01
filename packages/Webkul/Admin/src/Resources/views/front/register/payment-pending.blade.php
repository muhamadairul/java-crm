<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Pending - JavaCRM</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    {{ vite()->set(['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js']) }}
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col justify-between">

    <!-- Header -->
    <header class="bg-white border-b border-gray-100 py-6">
        <div class="max-w-7xl mx-auto px-6 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="h-9 w-9 bg-blue-600 rounded-xl flex items-center justify-center shadow-md shadow-blue-100">
                    <svg class="h-4.5 w-4.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <span class="text-xl font-bold tracking-tight text-slate-900">JavaCRM</span>
            </div>
            <div>
                <span class="text-xs font-bold text-amber-600 bg-amber-50 px-3 py-1.5 rounded-full border border-amber-100 uppercase tracking-wider">Menunggu Pembayaran</span>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 max-w-4xl mx-auto py-12 px-6 w-full flex items-center justify-center">
        <div class="w-full max-w-2xl bg-white rounded-3xl shadow-xl shadow-slate-100/50 border border-slate-100/80 p-8 lg:p-10 text-center space-y-8">
            
            <!-- Icon Pending -->
            <div class="mx-auto h-20 w-20 bg-amber-50 text-amber-500 rounded-3xl flex items-center justify-center animate-pulse">
                <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <div>
                <h1 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">Menunggu Pembayaran Anda</h1>
                <p class="text-sm text-slate-400 mt-2 font-semibold">Silakan selesaikan pembayaran untuk mengaktifkan akun JavaCRM Perusahaan Anda.</p>
            </div>

            <!-- Invoice Summary Card -->
            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6 text-left space-y-4">
                <div class="flex items-center justify-between border-b border-slate-200/50 pb-4">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">No. Invoice</span>
                        <p class="text-sm font-bold text-slate-800">{{ $invoice->invoice_number }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Metode Pembayaran</span>
                        <p class="text-sm font-bold text-slate-800 uppercase">{{ str_replace('_', ' ', $invoice->payment_method) }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Paket Pilihan</span>
                        <p class="text-sm font-bold text-slate-800">{{ $invoice->subscription->plan->name ?? 'Pro' }} Plan</p>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Pembayaran</span>
                        <p class="text-lg font-extrabold text-blue-600">
                            {{ $invoice->currency === 'IDR' ? 'Rp' : ($invoice->currency === 'USD' ? '$' : ($invoice->currency === 'EUR' ? '€' : 'S$')) }}
                            {{ number_format($invoice->amount, $invoice->currency === 'USD' || $invoice->currency === 'EUR' ? 2 : 0) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Payment Instructions dynamically by payment method -->
            <div class="border-t border-slate-100 pt-6">
                
                @if($invoice->payment_method === 'VIRTUAL_ACCOUNT')
                    @php $va = $paymentDetails['virtual_account'] ?? []; @endphp
                    <div class="space-y-4">
                        <p class="text-sm font-bold text-slate-700">Silakan transfer ke nomor Virtual Account berikut:</p>
                        <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-5 flex flex-col items-center justify-center gap-1.5 relative overflow-hidden">
                            <span class="text-xs font-bold text-blue-600 uppercase tracking-widest">{{ $va['channel_code'] ?? 'MANDIRI' }} VIRTUAL ACCOUNT</span>
                            <span class="text-3xl font-black text-slate-900 tracking-wider">{{ $va['virtual_account'] ?? '8800000000000000' }}</span>
                            <span class="text-xs font-medium text-slate-400">Atas Nama: {{ $va['customer_name'] ?? 'JavaCRM Customer' }}</span>
                        </div>
                        <div class="text-left text-xs text-slate-500 font-medium space-y-2 mt-4 max-w-md mx-auto leading-relaxed">
                            <p class="font-bold text-slate-700">Petunjuk Pembayaran ATM/M-Banking:</p>
                            <ol class="list-decimal list-inside space-y-1.5 pl-1">
                                <li>Pilih menu <span class="font-bold">Transfer / Bayar</span> di ATM atau M-Banking Anda.</li>
                                <li>Pilih opsi <span class="font-bold">Virtual Account</span>.</li>
                                <li>Masukkan nomor Virtual Account di atas.</li>
                                <li>Periksa kesesuaian nama dan jumlah pembayaran, lalu konfirmasi transfer.</li>
                            </ol>
                        </div>
                    </div>

                @elseif($invoice->payment_method === 'QR_CODE')
                    @php $qr = $paymentDetails['qr_code'] ?? []; @endphp
                    <div class="space-y-4 flex flex-col items-center">
                        <p class="text-sm font-bold text-slate-700">Silakan pindai kode QRIS berikut menggunakan aplikasi e-wallet Anda:</p>
                        <div class="bg-white p-4 border border-slate-200 rounded-3xl shadow-sm inline-block">
                            <img src="{{ $qr['qr_image_url'] ?? 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=https://github.com' }}" alt="QRIS Code" class="h-60 w-60">
                        </div>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Mendukung GoPay, OVO, DANA, LinkAja, ShopeePay, BCA, dll.</p>
                    </div>

                @elseif($invoice->payment_method === 'EWALLET')
                    @php 
                        $actions = $paymentDetails['actions'] ?? [];
                        $checkoutUrl = null;
                        foreach($actions as $action) {
                            if($action['action'] === 'CHECKOUT_URL') {
                                $checkoutUrl = $action['url'];
                            }
                        }
                    @endphp
                    <div class="space-y-4">
                        <p class="text-sm font-bold text-slate-700">Selesaikan Pembayaran via E-Wallet Anda:</p>
                        @if($checkoutUrl)
                            <a href="{{ $checkoutUrl }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 px-8 rounded-2xl shadow-lg shadow-indigo-100 transition-all text-sm">
                                <span>Bayar Sekarang (Buka Aplikasi E-Wallet)</span>
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        @else
                            <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-5">
                                <p class="text-xs font-semibold text-blue-600 uppercase">Instruksi Pembayaran</p>
                                <p class="text-sm text-slate-700 mt-2 font-medium">Notifikasi pembayaran telah dikirim ke nomor handphone e-wallet Anda. Silakan buka aplikasi e-wallet Anda untuk menyelesaikan pembayaran.</p>
                            </div>
                        @endif
                    </div>

                @else
                    <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-5">
                        <p class="text-xs font-semibold text-blue-600 uppercase">Menunggu Validasi</p>
                        <p class="text-sm text-slate-700 mt-2 font-medium">Pembayaran Anda sedang kami proses dan verifikasi secara otomatis.</p>
                    </div>
                @endif

            </div>

            <!-- Actions and Simulator for Test Environment -->
            <div class="border-t border-slate-100 pt-8 flex flex-col items-center gap-4">
                <div class="flex flex-col sm:flex-row gap-3 w-full max-w-md">
                    <!-- Check Status Button -->
                    <a href="{{ route('tenant.register.payment_check', ['invoice_id' => $invoice->id]) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl text-xs transition-colors flex items-center justify-center gap-1.5 shadow-md shadow-blue-100">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H18" />
                        </svg>
                        <span>Cek Status Pembayaran</span>
                    </a>
                </div>

                <!-- Simulation tools -->
                <div class="w-full max-w-md bg-amber-50/50 border border-amber-200/60 rounded-2xl p-5 text-center space-y-3 mt-4">
                    <span class="text-[10px] font-bold text-amber-700 uppercase tracking-widest bg-amber-100/80 px-2.5 py-1 rounded-full border border-amber-200/50">Simulator Sandbox</span>
                    <p class="text-xs text-slate-500 font-medium leading-relaxed">Gunakan tombol simulasi di bawah untuk mensimulasikan notifikasi sukses pembayaran dari server Xendit.</p>
                    <form action="{{ route('api.xendit.simulate_success', ['invoice_id' => $invoice->id]) }}" method="POST" class="pt-1">
                        @csrf
                        <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-4 rounded-xl text-xs transition-all shadow-md shadow-amber-100">
                            Simulasikan Pembayaran Sukses (Webhook)
                        </button>
                    </form>
                </div>
            </div>

            <div class="text-center pt-2">
                <a href="{{ route('java-crm.home') }}" class="text-slate-400 hover:text-slate-650 font-bold text-sm">
                    Kembali ke Halaman Utama
                </a>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-6 text-center text-xs text-slate-400 font-medium">
        <p>&copy; 2026 JavaCRM Inc. All rights reserved.</p>
    </footer>

</body>
</html>
