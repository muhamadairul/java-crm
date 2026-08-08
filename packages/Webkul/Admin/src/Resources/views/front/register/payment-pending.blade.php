<!DOCTYPE html>
@php
    $currentLocale = session('locale', app()->getLocale());
    $isEn = $currentLocale === 'en';
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
    <header class="bg-white/90 backdrop-blur-md border-b border-slate-200/80 py-4 dark:bg-slate-900/90 dark:border-slate-800 sticky top-0 z-50">
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
    <main class="flex-1 flex items-center justify-center py-12 px-6">
        <div class="w-full max-w-xl bg-white rounded-3xl shadow-xl border border-slate-200/80 p-8 lg:p-10 dark:bg-slate-900 dark:border-slate-800">
            
            <!-- Status Header Icon -->
            <div class="text-center mb-8">
                <div class="h-16 w-16 bg-amber-50 text-amber-500 rounded-3xl flex items-center justify-center mx-auto mb-4 border border-amber-100 dark:bg-amber-950/40 dark:border-amber-900">
                    <svg class="h-8 w-8 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-xs font-extrabold uppercase tracking-wider bg-amber-50 text-amber-700 px-3.5 py-1 rounded-full border border-amber-200/60 dark:bg-amber-950 dark:text-amber-300 dark:border-amber-900">
                    {{ $isEn ? 'Invoice Pending' : 'Menunggu Pembayaran' }}
                </span>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-3 dark:text-white">
                    {{ $isEn ? 'Complete Your Payment' : 'Selesaikan Pembayaran Anda' }}
                </h1>
                <p class="text-xs text-slate-500 mt-1 font-medium dark:text-slate-400">
                    {{ $isEn ? 'Invoice #' . $invoice->invoice_number : 'Nomor Tagihan #' . $invoice->invoice_number }}
                </p>
            </div>

            <!-- Details Card -->
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200/80 space-y-3 text-xs mb-8 dark:bg-slate-950 dark:border-slate-800">
                <div class="flex justify-between">
                    <span class="text-slate-500 dark:text-slate-400">{{ $isEn ? 'Company Tenant:' : 'Nama Tenant:' }}</span>
                    <span class="font-bold text-slate-900 dark:text-white">{{ $invoice->company->name ?? 'Company' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500 dark:text-slate-400">{{ $isEn ? 'Subscription Plan:' : 'Paket Langganan:' }}</span>
                    <span class="font-bold text-slate-900 dark:text-white">{{ $invoice->plan->name ?? 'Plan' }}</span>
                </div>
                <div class="flex justify-between border-t border-slate-200/80 pt-3 dark:border-slate-800">
                    <span class="text-sm font-extrabold text-slate-900 dark:text-white">{{ $isEn ? 'Total Amount:' : 'Total Tagihan:' }}</span>
                    <span class="text-lg font-extrabold text-sky-600 dark:text-sky-400">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <a href="{{ route('tenant.register.payment_check', ['invoice_id' => $invoice->id]) }}" class="w-full bg-sky-600 hover:bg-sky-700 text-white font-bold py-3.5 px-4 rounded-2xl text-xs transition-all flex items-center justify-center gap-2 shadow-lg shadow-sky-600/25">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>{{ $isEn ? 'Check Payment Status' : 'Cek Status Pembayaran' }}</span>
                </a>

                @if($invoice->payment_method === 'EWALLET')
                    <a href="{{ route('tenant.register.simulate_ewallet', ['invoice_id' => $invoice->id]) }}" class="w-full bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 font-bold py-3 px-4 rounded-2xl text-xs transition-all flex items-center justify-center gap-2 dark:bg-amber-950/40 dark:border-amber-900 dark:text-amber-300">
                        <span>{{ $isEn ? 'Simulate E-Wallet Payment (Testing)' : 'Simulasi Pembayaran E-Wallet (UAT)' }}</span>
                    </a>
                @endif
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200/80 py-6 text-center text-xs text-slate-400 font-medium dark:bg-slate-900 dark:border-slate-800 dark:text-slate-500">
        <p>&copy; {{ date('Y') }} JavaCRM. {{ $isEn ? 'All rights reserved.' : 'Hak cipta dilindungi undang-undang.' }}</p>
    </footer>

</body>
</html>
