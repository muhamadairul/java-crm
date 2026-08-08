<!DOCTYPE html>
@php
    $currentLocale = session('locale', app()->getLocale());
    $isEn = $currentLocale === 'en';
@endphp
<html lang="{{ $currentLocale }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isEn ? 'Payment & Activation - JavaCRM' : 'Pembayaran & Aktivasi Akun - JavaCRM' }}</title>
    
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
    <header class="bg-white/90 backdrop-blur-md border-b border-slate-200/80 py-3.5 dark:bg-slate-900/90 dark:border-slate-800 sticky top-0 z-50">
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

                <div class="hidden sm:flex items-center gap-2 text-xs">
                    <a href="{{ route('tenant.register.step2') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold px-3.5 py-2 rounded-xl transition-all dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
                        ← {{ $isEn ? 'Back to Step 2' : 'Kembali ke Step 2' }}
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Wrapped in Card Container -->
    <main class="flex-1 flex items-center justify-center py-10 px-4">
        <div class="w-full max-w-4xl bg-white rounded-2xl shadow-lg border border-slate-200/80 p-6 sm:p-8 dark:bg-slate-900 dark:border-slate-800">
            
            <!-- Professional Single Step Wizard Bar -->
            <div class="flex items-center justify-between mb-6 max-w-md mx-auto">
                <!-- Step 1 Completed -->
                <div class="flex items-center gap-2">
                    <div class="h-7 w-7 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs font-extrabold shadow-sm">
                        ✓
                    </div>
                    <span class="text-xs font-bold text-slate-800 dark:text-white">
                        {{ $isEn ? 'Company' : 'Perusahaan' }}
                    </span>
                </div>

                <!-- Divider 1-2 -->
                <div class="flex-1 h-[2px] mx-3 bg-emerald-500"></div>

                <!-- Step 2 Completed -->
                <div class="flex items-center gap-2">
                    <div class="h-7 w-7 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs font-extrabold shadow-sm">
                        ✓
                    </div>
                    <span class="text-xs font-bold text-slate-800 dark:text-white">
                        {{ $isEn ? 'Plan' : 'Paket' }}
                    </span>
                </div>

                <!-- Divider 2-3 -->
                <div class="flex-1 h-[2px] mx-3 bg-sky-600"></div>

                <!-- Step 3 Active -->
                <div class="flex items-center gap-2">
                    <div class="h-7 w-7 rounded-full bg-sky-600 text-white flex items-center justify-center text-xs font-extrabold shadow-sm shadow-sky-500/30">
                        3
                    </div>
                    <span class="text-xs font-bold text-slate-900 dark:text-white">
                        {{ $isEn ? 'Activation' : 'Aktivasi' }}
                    </span>
                </div>
            </div>

            <!-- Flash Messages -->
            @if(session('error'))
                <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-2.5 rounded-xl text-xs font-bold flex items-center dark:bg-red-950/50 dark:border-red-900 dark:text-red-300">
                    <svg class="h-4 w-4 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('tenant.register.step3.post') }}" method="POST" id="checkout-form">
                @csrf
                
                <input type="hidden" name="payment_method_type" id="payment-method-type" value="CARD">

                <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 items-start">
                    
                    <!-- Left Side: Order summary & payment methods -->
                    <div class="lg:col-span-3 space-y-5">
                        
                        <!-- Order Summary Card -->
                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-200/80 shadow-sm flex items-center justify-between dark:bg-slate-950 dark:border-slate-800">
                            <div class="flex items-center gap-3.5">
                                <div class="h-10 w-10 bg-sky-50 text-sky-600 rounded-xl flex items-center justify-center dark:bg-sky-950 dark:text-sky-400">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-extrabold text-slate-800 dark:text-white">Paket {{ $plan->name }}</h3>
                                    <p class="text-[11px] text-slate-400 font-medium dark:text-slate-500">{{ $isEn ? 'Subscription plan for your sales team' : 'Paket langganan untuk operasi tim sales Anda' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-extrabold text-slate-900 dark:text-white">{{ $currencySymbol }}{{ number_format($plan->converted_price, $selectedCurrency === 'USD' || $selectedCurrency === 'EUR' ? 2 : 0) }}</span>
                                <p class="text-[11px] text-slate-400 font-medium dark:text-slate-500">/{{ $plan->billing_cycle === 'yearly' ? ($isEn ? 'year' : 'tahun') : ($isEn ? 'month' : 'bulan') }}</p>
                            </div>
                        </div>

                        <!-- Payment Method Panel -->
                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4 dark:bg-slate-950 dark:border-slate-800">
                            <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider dark:text-white">{{ $isEn ? 'Select Payment Method' : 'Pilih Metode Pembayaran' }}</h3>
                            
                            <!-- Selection tabs -->
                            <div class="grid grid-cols-4 gap-2.5">
                                <button type="button" id="tab-CARD" onclick="switchPaymentMethod('CARD')" class="border-2 border-sky-600 rounded-xl p-3 flex flex-col items-center justify-center gap-1 focus:outline-none bg-sky-50/20 text-sky-600 transition-all font-bold text-xs dark:bg-sky-950/30">
                                    <span>Kartu / Card</span>
                                </button>
                                <button type="button" id="tab-VIRTUAL_ACCOUNT" onclick="switchPaymentMethod('VIRTUAL_ACCOUNT')" class="border border-slate-200 rounded-xl p-3 flex flex-col items-center justify-center gap-1 focus:outline-none bg-white text-slate-800 hover:border-slate-300 transition-all font-bold text-xs dark:bg-slate-900 dark:border-slate-800 dark:text-slate-200">
                                    <span>Bank VA</span>
                                </button>
                                <button type="button" id="tab-EWALLET" onclick="switchPaymentMethod('EWALLET')" class="border border-slate-200 rounded-xl p-3 flex flex-col items-center justify-center gap-1 focus:outline-none bg-white text-slate-800 hover:border-slate-300 transition-all font-bold text-xs dark:bg-slate-900 dark:border-slate-800 dark:text-slate-200">
                                    <span>E-Wallet</span>
                                </button>
                                <button type="button" id="tab-QRIS" onclick="switchPaymentMethod('QRIS')" class="border border-slate-200 rounded-xl p-3 flex flex-col items-center justify-center gap-1 focus:outline-none bg-white text-slate-800 hover:border-slate-300 transition-all font-bold text-xs dark:bg-slate-900 dark:border-slate-800 dark:text-slate-200">
                                    <span>QRIS</span>
                                </button>
                            </div>

                            <!-- Payment Method Form Blocks -->
                            <div id="method-CARD" class="space-y-3 pt-2">
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 mb-1 dark:text-slate-400">{{ $isEn ? 'Card Number' : 'Nomor Kartu Credit / Debit' }}</label>
                                    <input type="text" placeholder="4000 0000 0000 0000" class="block w-full rounded-xl border-slate-200 border px-3.5 py-2.5 text-xs focus:border-sky-500 focus:ring-sky-500 shadow-sm transition-colors dark:bg-slate-900 dark:border-slate-800 dark:text-white">
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 mb-1 dark:text-slate-400">Expiry (MM/YY)</label>
                                        <input type="text" placeholder="12/28" class="block w-full rounded-xl border-slate-200 border px-3.5 py-2.5 text-xs focus:border-sky-500 focus:ring-sky-500 shadow-sm transition-colors dark:bg-slate-900 dark:border-slate-800 dark:text-white">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 mb-1 dark:text-slate-400">CVV</label>
                                        <input type="text" placeholder="123" class="block w-full rounded-xl border-slate-200 border px-3.5 py-2.5 text-xs focus:border-sky-500 focus:ring-sky-500 shadow-sm transition-colors dark:bg-slate-900 dark:border-slate-800 dark:text-white">
                                    </div>
                                </div>
                            </div>

                            <div id="method-VIRTUAL_ACCOUNT" class="hidden space-y-3 pt-2">
                                <label class="block text-xs font-bold text-slate-600 mb-1 dark:text-slate-400">{{ $isEn ? 'Select Bank:' : 'Pilih Bank Virtual Account:' }}</label>
                                <select name="bank_code" class="block w-full rounded-xl border-slate-200 border px-3.5 py-2.5 text-xs focus:border-sky-500 focus:ring-sky-500 shadow-sm transition-colors dark:bg-slate-900 dark:border-slate-800 dark:text-white">
                                    <option value="BCA">Bank BCA</option>
                                    <option value="MANDIRI">Bank Mandiri</option>
                                    <option value="BNI">Bank BNI</option>
                                    <option value="BRI">Bank BRI</option>
                                    <option value="PERMATA">Bank Permata</option>
                                </select>
                            </div>

                            <div id="method-EWALLET" class="hidden space-y-3 pt-2">
                                <label class="block text-xs font-bold text-slate-600 mb-1 dark:text-slate-400">{{ $isEn ? 'Select E-Wallet Channel:' : 'Pilih Channel E-Wallet:' }}</label>
                                <select name="ewallet_channel" class="block w-full rounded-xl border-slate-200 border px-3.5 py-2.5 text-xs focus:border-sky-500 focus:ring-sky-500 shadow-sm transition-colors dark:bg-slate-900 dark:border-slate-800 dark:text-white">
                                    <option value="OVO">OVO</option>
                                    <option value="DANA">DANA</option>
                                    <option value="LINKAJA">LinkAja</option>
                                    <option value="SHOPEEPAY">ShopeePay</option>
                                </select>
                            </div>

                            <div id="method-QRIS" class="hidden space-y-3 text-center py-5 bg-white rounded-xl border border-slate-200/80 dark:bg-slate-900 dark:border-slate-800">
                                <svg class="h-10 w-10 mx-auto text-sky-600 dark:text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                </svg>
                                <p class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ $isEn ? 'QRIS code will be generated upon confirmation.' : 'Kode QRIS resmi akan diterbitkan setelah konfirmasi.' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side: Order Total & Submit -->
                    <div class="lg:col-span-2 space-y-5">
                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-3 dark:bg-slate-950 dark:border-slate-800">
                            <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider border-b border-slate-200/80 pb-2.5 dark:text-white dark:border-slate-800">{{ $isEn ? 'Order Summary' : 'Rincian Pembayaran' }}</h3>
                            
                            <div class="space-y-2 text-xs">
                                <div class="flex justify-between text-slate-500 dark:text-slate-400">
                                    <span>Subtotal Paket {{ $plan->name }}</span>
                                    <span class="font-bold text-slate-800 dark:text-white">{{ $currencySymbol }}{{ number_format($plan->converted_price, $selectedCurrency === 'USD' || $selectedCurrency === 'EUR' ? 2 : 0) }}</span>
                                </div>
                                <div class="flex justify-between text-slate-500 dark:text-slate-400">
                                    <span>Pajak & Biaya Admin</span>
                                    <span class="font-bold text-emerald-600">GRATIS</span>
                                </div>
                            </div>

                            <div class="pt-3 border-t border-slate-200/80 flex justify-between items-center dark:border-slate-800">
                                <span class="text-xs font-extrabold text-slate-900 dark:text-white">Total Tagihan</span>
                                <span class="text-xl font-extrabold text-sky-600 dark:text-sky-400">{{ $currencySymbol }}{{ number_format($plan->converted_price, $selectedCurrency === 'USD' || $selectedCurrency === 'EUR' ? 2 : 0) }}</span>
                            </div>

                            <button type="submit" class="w-full bg-sky-600 hover:bg-sky-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-md shadow-sky-600/20 transition-all text-xs flex items-center justify-center gap-2 hover:scale-[1.01] mt-3">
                                <span>{{ $isEn ? 'Confirm & Activate Account' : 'Konfirmasi & Aktivasi Akun' }}</span>
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </div>

                        <div class="text-center">
                            <a href="{{ route('tenant.register.step2') }}" class="text-xs text-slate-400 hover:text-slate-600 font-bold transition-colors dark:text-slate-500 dark:hover:text-slate-300">
                                ← {{ $isEn ? 'Change selected plan' : 'Ganti pilihan paket' }}
                            </a>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200/80 py-4 text-center text-xs text-slate-400 font-medium dark:bg-slate-900 dark:border-slate-800 dark:text-slate-500">
        <p>&copy; {{ date('Y') }} JavaCRM. {{ $isEn ? 'All rights reserved.' : 'Hak cipta dilindungi undang-undang.' }}</p>
    </footer>

    <script>
        function switchPaymentMethod(method) {
            document.getElementById('payment-method-type').value = method;
            ['CARD', 'VIRTUAL_ACCOUNT', 'EWALLET', 'QRIS'].forEach(m => {
                const tab = document.getElementById('tab-' + m);
                const content = document.getElementById('method-' + m);
                if (tab) {
                    tab.classList.remove('border-2', 'border-sky-600', 'bg-sky-50/20', 'text-sky-600', 'dark:bg-sky-950/30');
                    tab.classList.add('border', 'border-slate-200', 'bg-white', 'text-slate-800', 'dark:bg-slate-900', 'dark:border-slate-800', 'dark:text-slate-200');
                }
                if (content) {
                    content.classList.add('hidden');
                }
            });

            const activeTab = document.getElementById('tab-' + method);
            const activeContent = document.getElementById('method-' + method);
            if (activeTab) {
                activeTab.classList.remove('border', 'border-slate-200', 'bg-white', 'text-slate-800', 'dark:bg-slate-950', 'dark:border-slate-800', 'dark:text-slate-200');
                activeTab.classList.add('border-2', 'border-sky-600', 'bg-sky-50/20', 'text-sky-600', 'dark:bg-sky-950/30');
            }
            if (activeContent) {
                activeContent.classList.remove('hidden');
            }
        }
    </script>
</body>
</html>
