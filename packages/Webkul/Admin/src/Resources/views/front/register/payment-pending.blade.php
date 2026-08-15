<!DOCTYPE html>
@php
    $currentLocale = session('locale', app()->getLocale());
    $isEn = $currentLocale === 'en';

    $paymentMethod = $invoice->payment_method ?? 'VIRTUAL_ACCOUNT';
    $bankCode = strtoupper($invoice->bank_code ?? 'BCA');

    // Extract VA Number or QR String
    $xenditUrl = $invoice->xendit_invoice_url;
    $rawNotes = json_decode($invoice->response_request, true) ?: (json_decode($invoice->notes, true) ?: []);

    $vaNumber = $xenditUrl;
    if (empty($vaNumber) || str_starts_with($vaNumber, '00020101')) {
        $vaNumber = $rawNotes['virtual_account']['virtual_account'] ?? ($rawNotes['channel_properties']['virtual_account_number'] ?? null);
    }

    if (empty($vaNumber)) {
        $vaPrefixes = [
            'MANDIRI' => '88000',
            'BCA'     => '80000',
            'BRI'     => '12400',
            'BNI'     => '98800',
            'PERMATA' => '85550',
        ];
        $prefix = $vaPrefixes[$bankCode] ?? '80000';
        $vaNumber = $prefix . '92837162541';
    }

    // QR String resolution
    $qrString = $xenditUrl;
    if (empty($qrString) || !str_starts_with($qrString, '00020101')) {
        $qrString = $rawNotes['qr_code']['qr_string'] ?? ($rawNotes['actions'][0]['value'] ?? '00020101021226300016ID.CO.XENDIT.WWW01189360000201100000035204531153033605802ID5910JavaCRM6007Jakarta6105123456304ABCD');
    }
    $qrImageUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qrString);
@endphp
<html lang="{{ $currentLocale }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isEn ? 'Payment Pending - JavaCRM' : 'Menunggu Pembayaran - JavaCRM' }}</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- CSS Assets -->
    {{ vite()->set(['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js']) }}
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col justify-between dark:bg-slate-950 dark:text-slate-200">

    <!-- Header Navigation -->
    <header class="bg-white/90 backdrop-blur-md border-b border-slate-200/80 py-4 dark:bg-slate-900/90 dark:border-slate-800 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-6 flex items-center justify-between">
            <a href="{{ route('java-crm.home') }}" class="flex items-center gap-3 group">
                <img src="{{ vite()->asset('images/logo.svg') }}" class="h-9 w-auto transition-transform group-hover:scale-105" alt="JavaCRM Logo" />
            </a>
            <div class="flex items-center gap-4">
                <!-- Simple 1-Click Language Switcher -->
                <div class="flex items-center rounded-xl border border-slate-200 bg-slate-100 p-1 dark:border-slate-800 dark:bg-slate-900">
                    <a href="{{ route('admin.switch_locale', 'id') }}" 
                       title="Bahasa Indonesia"
                       class="rounded-lg px-2.5 py-1 text-xs font-extrabold transition-all {{ !$isEn ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white' }}">
                        ID
                    </a>
                    <a href="{{ route('admin.switch_locale', 'en') }}" 
                       title="English"
                       class="rounded-lg px-2.5 py-1 text-xs font-extrabold transition-all {{ $isEn ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white' }}">
                        EN
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex items-center justify-center py-10 px-4 sm:px-6">
        <div class="w-full max-w-xl bg-white rounded-3xl shadow-xl border border-slate-200/80 p-6 sm:p-10 dark:bg-slate-900 dark:border-slate-800">
            
            <!-- Status Header Icon -->
            <div class="text-center mb-6">
                <div class="h-14 w-14 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center mx-auto mb-3 border border-amber-100 dark:bg-amber-950/40 dark:border-amber-900">
                    <svg class="h-7 w-7 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-[11px] font-extrabold uppercase tracking-wider bg-amber-50 text-amber-700 px-3 py-1 rounded-full border border-amber-200/60 dark:bg-amber-950 dark:text-amber-300 dark:border-amber-900">
                    {{ $isEn ? 'Invoice Pending' : 'Menunggu Pembayaran' }}
                </span>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-2.5 dark:text-white">
                    {{ $isEn ? 'Complete Your Payment' : 'Selesaikan Pembayaran Anda' }}
                </h1>
                <p class="text-xs text-slate-500 mt-1 font-medium dark:text-slate-400">
                    {{ $isEn ? 'Invoice #' . $invoice->invoice_number : 'Nomor Tagihan #' . $invoice->invoice_number }}
                </p>
            </div>

            <!-- Details Card -->
            <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200/80 space-y-2.5 text-xs mb-6 dark:bg-slate-950 dark:border-slate-800">
                <div class="flex justify-between">
                    <span class="text-slate-500 dark:text-slate-400">{{ $isEn ? 'Company Tenant:' : 'Nama Tenant Perusahaan:' }}</span>
                    <span class="font-bold text-slate-900 dark:text-white">{{ $invoice->company->name ?? 'Company' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500 dark:text-slate-400">{{ $isEn ? 'Subscription Plan:' : 'Paket Langganan:' }}</span>
                    <span class="font-bold text-slate-900 dark:text-white">Paket {{ $invoice->plan->name ?? 'Plan' }}</span>
                </div>
                <div class="flex justify-between border-t border-slate-200/80 pt-2.5 dark:border-slate-800">
                    <span class="text-sm font-extrabold text-slate-900 dark:text-white">{{ $isEn ? 'Total Amount:' : 'Total Tagihan:' }}</span>
                    <span class="text-lg font-extrabold text-sky-600 dark:text-sky-400">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Payment Method Display Area -->
            @if($paymentMethod === 'QR_CODE')
                <!-- JavaCRM Branded QRIS Display Frame -->
                <div class="bg-gradient-to-b from-sky-50/50 to-slate-50 p-6 rounded-3xl border border-sky-100 shadow-sm text-center mb-6 dark:from-slate-950 dark:to-slate-900 dark:border-sky-950">
                    
                    <!-- Frame Header: JavaCRM & QRIS Branding -->
                    <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-200/80 dark:border-slate-800">
                        <div class="flex items-center gap-2">
                            <img src="{{ vite()->asset('images/logo.svg') }}" class="h-6 w-auto" alt="JavaCRM Logo" />
                            <span class="text-[10px] font-black text-slate-400 tracking-wider">PAYMENT</span>
                        </div>
                        <div class="bg-red-600 text-white font-black text-[10px] px-2.5 py-0.5 rounded-md uppercase tracking-wider">
                            QRIS
                        </div>
                    </div>

                    <!-- QR Code Display Image -->
                    <div class="bg-white p-4 rounded-2xl shadow-md border border-slate-200/80 inline-block mx-auto dark:bg-white">
                        <img src="{{ $qrImageUrl }}" id="qris-img" class="h-56 w-56 object-contain rounded-lg" alt="QRIS Code JavaCRM" />
                    </div>

                    <p class="text-xs font-bold text-slate-700 mt-4 dark:text-slate-200">
                        {{ $isEn ? 'Scan this QRIS using your mobile banking or e-wallet app:' : 'Pindai QRIS ini menggunakan aplikasi Mobile Banking atau E-Wallet:' }}
                    </p>

                    <!-- Supported App Badges -->
                    <div class="flex flex-wrap justify-center gap-1.5 mt-2 text-[10px] font-extrabold text-slate-500">
                        <span class="bg-white px-2 py-1 rounded-md border border-slate-200 shadow-2xs dark:bg-slate-900 dark:border-slate-800">BCA</span>
                        <span class="bg-white px-2 py-1 rounded-md border border-slate-200 shadow-2xs dark:bg-slate-900 dark:border-slate-800">Mandiri</span>
                        <span class="bg-white px-2 py-1 rounded-md border border-slate-200 shadow-2xs dark:bg-slate-900 dark:border-slate-800">BRI</span>
                        <span class="bg-white px-2 py-1 rounded-md border border-slate-200 shadow-2xs dark:bg-slate-900 dark:border-slate-800">BNI</span>
                        <span class="bg-white px-2 py-1 rounded-md border border-slate-200 shadow-2xs dark:bg-slate-900 dark:border-slate-800 text-emerald-600">GoPay</span>
                        <span class="bg-white px-2 py-1 rounded-md border border-slate-200 shadow-2xs dark:bg-slate-900 dark:border-slate-800 text-purple-600">OVO</span>
                        <span class="bg-white px-2 py-1 rounded-md border border-slate-200 shadow-2xs dark:bg-slate-900 dark:border-slate-800 text-sky-600">DANA</span>
                        <span class="bg-white px-2 py-1 rounded-md border border-slate-200 shadow-2xs dark:bg-slate-900 dark:border-slate-800 text-orange-600">ShopeePay</span>
                    </div>

                    <!-- Download QRIS Button -->
                    <div class="mt-5">
                        <button type="button" onclick="downloadQrisImage()" class="inline-flex items-center gap-2 bg-sky-600 hover:bg-sky-700 text-white font-bold py-2.5 px-5 rounded-xl text-xs transition-all shadow-md shadow-sky-600/20 hover:scale-[1.02]">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span>{{ $isEn ? 'Download QRIS Image' : 'Unduh QRIS' }}</span>
                        </button>
                    </div>
                </div>

            @else
                <!-- Virtual Account Display Card -->
                <div class="bg-slate-50 p-6 rounded-3xl border border-slate-200/80 mb-6 dark:bg-slate-950 dark:border-slate-800">
                    <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-200/80 dark:border-slate-800">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-extrabold text-slate-500 uppercase tracking-wider dark:text-slate-400">Virtual Account:</span>
                            <span class="bg-sky-100 text-sky-700 font-extrabold text-xs px-3 py-0.5 rounded-lg border border-sky-200 uppercase dark:bg-sky-950 dark:text-sky-300 dark:border-sky-900">
                                Bank {{ $bankCode }}
                            </span>
                        </div>
                        <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md dark:bg-emerald-950 dark:text-emerald-300">Auto Verification</span>
                    </div>

                    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 flex items-center justify-between shadow-xs dark:bg-slate-900 dark:border-slate-800">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 block uppercase">Nomor Virtual Account</span>
                            <span class="text-xl font-black text-slate-900 tracking-wider dark:text-white select-all" id="va-number-text">{{ $vaNumber }}</span>
                        </div>
                        <button type="button" onclick="copyVaNumber('{{ $vaNumber }}')" class="bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold px-3.5 py-2 rounded-xl text-xs transition-colors flex items-center gap-1.5 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            <span id="copy-btn-label">{{ $isEn ? 'Copy VA' : 'Salin Nomor VA' }}</span>
                        </button>
                    </div>

                    <p class="text-[11px] text-slate-400 font-medium mt-3 leading-relaxed dark:text-slate-500">
                        {{ $isEn ? 'Transfer exact amount to the VA number above via Mobile Banking or ATM.' : 'Transfer nominal tepat sesuai total tagihan di atas ke nomor VA melalui Mobile Banking atau ATM.' }}
                    </p>
                </div>
            @endif

            <!-- Action & Manual Check -->
            <div class="space-y-3">
                <button type="button" onclick="manualCheckStatus()" class="w-full bg-sky-600 hover:bg-sky-700 text-white font-bold py-3.5 px-4 rounded-2xl text-xs transition-all flex items-center justify-center gap-2 shadow-lg shadow-sky-600/20">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>{{ $isEn ? 'Check Payment Status' : 'Cek Status Pembayaran' }}</span>
                </button>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200/80 py-4 text-center text-xs text-slate-400 font-medium dark:bg-slate-900 dark:border-slate-800 dark:text-slate-500">
        <p>&copy; {{ date('Y') }} JavaCRM. {{ $isEn ? 'All rights reserved.' : 'Hak cipta dilindungi undang-undang.' }}</p>
    </footer>

    <!-- Payment Status Modal (Popup) -->
    <div id="payment-status-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden transition-opacity duration-300">
        <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl border border-slate-200/80 p-8 text-center transform transition-transform duration-300 scale-95 dark:bg-slate-900 dark:border-slate-800">
            
            <!-- Icon Container -->
            <div id="modal-icon-container" class="h-16 w-16 rounded-3xl flex items-center justify-center mx-auto mb-4 border">
                <!-- Icon injected dynamically -->
            </div>

            <!-- Modal Title & Message -->
            <h2 id="modal-title" class="text-xl font-extrabold text-slate-900 tracking-tight dark:text-white"></h2>
            <p id="modal-message" class="text-xs text-slate-500 mt-2 font-medium leading-relaxed dark:text-slate-400"></p>

            <!-- CTA Action Button -->
            <div class="mt-6">
                <a id="modal-cta-btn" href="#" class="w-full font-bold py-3.5 px-5 rounded-2xl text-xs transition-all flex items-center justify-center gap-2 shadow-md">
                    <span id="modal-cta-label"></span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>
    </div>

    <script>
        // Copy VA Number helper
        function copyVaNumber(number) {
            navigator.clipboard.writeText(number).then(() => {
                const label = document.getElementById('copy-btn-label');
                if (label) {
                    label.innerText = 'Tersalin!';
                    setTimeout(() => { label.innerText = '{{ $isEn ? "Copy VA" : "Salin Nomor VA" }}'; }, 2000);
                }
            });
        }

        // Download QRIS Image helper
        function downloadQrisImage() {
            const qrImgUrl = "{{ $qrImageUrl }}";
            fetch(qrImgUrl)
                .then(response => response.blob())
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    a.download = 'QRIS_JavaCRM_{{ $invoice->invoice_number }}.png';
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                })
                .catch(() => alert('Gagal mengunduh gambar QRIS. Silakan simpan gambar secara manual.'));
        }

        // Payment Status Modal Handler
        function showPaymentModal(status, redirectUrl, message) {
            const modal = document.getElementById('payment-status-modal');
            const iconContainer = document.getElementById('modal-icon-container');
            const titleEl = document.getElementById('modal-title');
            const messageEl = document.getElementById('modal-message');
            const ctaBtn = document.getElementById('modal-cta-btn');
            const ctaLabel = document.getElementById('modal-cta-label');

            if (status === 'paid') {
                iconContainer.className = 'h-16 w-16 bg-emerald-50 text-emerald-600 rounded-3xl flex items-center justify-center mx-auto mb-4 border border-emerald-200 dark:bg-emerald-950/40 dark:border-emerald-900';
                iconContainer.innerHTML = '<svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>';
                
                titleEl.innerText = "{{ $isEn ? 'Payment Successful!' : 'Pembayaran Berhasil!' }}";
                messageEl.innerText = message || "{{ $isEn ? 'Your company subscription and admin account have been successfully activated.' : 'Akun Perusahaan dan Administrator Anda telah aktif secara otomatis.' }}";
                
                ctaBtn.className = 'w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 px-5 rounded-2xl text-xs transition-all flex items-center justify-center gap-2 shadow-lg shadow-emerald-600/20 hover:scale-[1.01]';
                ctaBtn.href = redirectUrl;
                ctaLabel.innerText = "{{ $isEn ? 'Go to Admin Dashboard' : 'Masuk ke Dashboard Admin' }}";
            } else if (status === 'failed') {
                iconContainer.className = 'h-16 w-16 bg-rose-50 text-rose-600 rounded-3xl flex items-center justify-center mx-auto mb-4 border border-rose-200 dark:bg-rose-950/40 dark:border-rose-900';
                iconContainer.innerHTML = '<svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>';
                
                titleEl.innerText = "{{ $isEn ? 'Payment Failed or Cancelled' : 'Pembayaran Gagal / Dibatalkan' }}";
                messageEl.innerText = message || "{{ $isEn ? 'Your payment could not be completed. Please re-attempt payment with another method.' : 'Pembayaran Anda tidak dapat diproses. Silakan coba kembali dengan metode lain.' }}";
                
                ctaBtn.className = 'w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-3.5 px-5 rounded-2xl text-xs transition-all flex items-center justify-center gap-2 shadow-lg shadow-rose-600/20 hover:scale-[1.01]';
                ctaBtn.href = redirectUrl;
                ctaLabel.innerText = "{{ $isEn ? 'Re-attempt Payment (Back to Step 3)' : 'Ulangi Pembayaran (Kembali ke Step 3)' }}";
            }

            modal.classList.remove('hidden');
        }

        // Server-Friendly Polling Mechanism (10s interval with backoff)
        let isPolling = true;
        let pollInterval = 10000;

        function checkPaymentStatus() {
            if (!isPolling) return;

            fetch("{{ route('tenant.register.payment_check', ['invoice_id' => $invoice->id]) }}", {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'paid') {
                    isPolling = false;
                    showPaymentModal('paid', data.redirect_url, data.message);
                } else if (data.status === 'failed') {
                    isPolling = false;
                    showPaymentModal('failed', data.redirect_url, data.message);
                } else {
                    if (pollInterval < 15000) pollInterval += 1000;
                    setTimeout(checkPaymentStatus, pollInterval);
                }
            })
            .catch(() => {
                setTimeout(checkPaymentStatus, 10000);
            });
        }

        function manualCheckStatus() {
            checkPaymentStatus();
        }

        // Start initial check after 3 seconds
        setTimeout(checkPaymentStatus, 3000);
    </script>

</body>
</html>
