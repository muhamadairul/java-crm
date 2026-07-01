<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment & Activation - JavaCRM</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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
            <div class="flex items-center gap-3">
                <span class="text-sm text-slate-500 font-medium">Need help? Contact support</span>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 max-w-5xl mx-auto py-12 px-6 w-full">
        <div class="mb-8">
            <div class="flex justify-between text-xs font-bold text-slate-400 tracking-wider uppercase mb-2">
                <span class="text-blue-600">Step 3 of 3: Payment & Activation</span>
                <span>100%</span>
            </div>
            <!-- Progress Bar -->
            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                <div class="bg-blue-600 h-full w-full rounded-full transition-all duration-300"></div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm font-medium flex items-center">
                <svg class="h-5 w-5 mr-3 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('tenant.register.step3.post') }}" method="POST" id="checkout-form">
            @csrf
            
            <input type="hidden" name="payment_method_type" id="payment-method-type" value="CARD">

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-start">
                
                <!-- Left Side: Invoice details & payment inputs -->
                <div class="lg:col-span-3 space-y-6">
                    
                    <!-- Order Summary -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="h-12 w-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-800">{{ $plan->name }} Plan</h3>
                                <p class="text-xs text-slate-400 font-medium">Billed monthly • Cancel anytime</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xl font-bold text-slate-900">{{ $currencySymbol }}{{ number_format($plan->converted_price, $selectedCurrency === 'USD' || $selectedCurrency === 'EUR' ? 2 : 0) }}</span>
                            <p class="text-xs text-slate-400 font-medium">per month</p>
                        </div>
                    </div>

                    <!-- Payment Method Panel -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-6">
                        <h3 class="text-base font-bold text-slate-800">Payment Method</h3>
                        
                        <!-- Selection tabs -->
                        <div class="grid grid-cols-4 gap-3">
                            <button type="button" id="tab-CARD" onclick="switchPaymentMethod('CARD')" class="border-2 border-blue-600 rounded-2xl p-4 flex flex-col items-center justify-center gap-1.5 focus:outline-none bg-blue-50/10 text-blue-600 transition-all">
                                <span class="text-xs font-bold uppercase tracking-wider">Card</span>
                            </button>
                            <button type="button" id="tab-VIRTUAL_ACCOUNT" onclick="switchPaymentMethod('VIRTUAL_ACCOUNT')" class="border border-slate-200 rounded-2xl p-4 flex flex-col items-center justify-center gap-1.5 focus:outline-none bg-white text-slate-800 hover:border-slate-300 transition-all">
                                <span class="text-xs font-bold uppercase tracking-wider">Bank VA</span>
                            </button>
                            <button type="button" id="tab-EWALLET" onclick="switchPaymentMethod('EWALLET')" class="border border-slate-200 rounded-2xl p-4 flex flex-col items-center justify-center gap-1.5 focus:outline-none bg-white text-slate-800 hover:border-slate-300 transition-all">
                                <span class="text-xs font-bold uppercase tracking-wider">E-Wallet</span>
                            </button>
                            <button type="button" id="tab-QR_CODE" onclick="switchPaymentMethod('QR_CODE')" class="border border-slate-200 rounded-2xl p-4 flex flex-col items-center justify-center gap-1.5 focus:outline-none bg-white text-slate-800 hover:border-slate-300 transition-all">
                                <span class="text-xs font-bold uppercase tracking-wider">QRIS</span>
                            </button>
                        </div>

                        <!-- Card Payment Panel -->
                        <div id="panel-CARD" class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Card Number</label>
                                <input type="text" name="card_number" placeholder="4000 0000 0000 1091" class="block w-full rounded-xl border-slate-200 border px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Expiry Date</label>
                                    <input type="text" name="expiry_date" placeholder="12/28" class="block w-full rounded-xl border-slate-200 border px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">CVV</label>
                                    <input type="password" name="cvv" placeholder="123" class="block w-full rounded-xl border-slate-200 border px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors">
                                </div>
                            </div>
                        </div>

                        <!-- Bank Virtual Account Panel -->
                        <div id="panel-VIRTUAL_ACCOUNT" class="space-y-4 hidden">
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Pilih Bank Virtual Account</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="border border-slate-200 rounded-xl p-3 flex items-center gap-3 cursor-pointer hover:bg-slate-50">
                                    <input type="radio" name="va_bank" value="MANDIRI" checked class="h-4 w-4 text-blue-600 border-slate-300">
                                    <span class="text-sm font-semibold text-slate-750">Mandiri VA</span>
                                </label>
                                <label class="border border-slate-200 rounded-xl p-3 flex items-center gap-3 cursor-pointer hover:bg-slate-50">
                                    <input type="radio" name="va_bank" value="BRI" class="h-4 w-4 text-blue-600 border-slate-300">
                                    <span class="text-sm font-semibold text-slate-750">BRI VA</span>
                                </label>
                                <label class="border border-slate-200 rounded-xl p-3 flex items-center gap-3 cursor-pointer hover:bg-slate-50">
                                    <input type="radio" name="va_bank" value="BNI" class="h-4 w-4 text-blue-600 border-slate-300">
                                    <span class="text-sm font-semibold text-slate-750">BNI VA</span>
                                </label>
                                <label class="border border-slate-200 rounded-xl p-3 flex items-center gap-3 cursor-pointer hover:bg-slate-50">
                                    <input type="radio" name="va_bank" value="PERMATA" class="h-4 w-4 text-blue-600 border-slate-300">
                                    <span class="text-sm font-semibold text-slate-750">Permata VA</span>
                                </label>
                            </div>
                        </div>

                        <!-- E-Wallet Panel -->
                        <div id="panel-EWALLET" class="space-y-4 hidden">
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Pilih Dompet Digital</label>
                            <div class="grid grid-cols-3 gap-3 mb-4">
                                <label class="border border-slate-200 rounded-xl p-3 flex flex-col items-center gap-2 cursor-pointer hover:bg-slate-50 text-center">
                                    <input type="radio" name="ewallet_channel" value="OVO" checked onchange="toggleOvoPhone(true)" class="h-4 w-4 text-blue-600 border-slate-300">
                                    <span class="text-xs font-bold">OVO</span>
                                </label>
                                <label class="border border-slate-200 rounded-xl p-3 flex flex-col items-center gap-2 cursor-pointer hover:bg-slate-50 text-center">
                                    <input type="radio" name="ewallet_channel" value="DANA" onchange="toggleOvoPhone(false)" class="h-4 w-4 text-blue-600 border-slate-300">
                                    <span class="text-xs font-bold">DANA</span>
                                </label>
                                <label class="border border-slate-200 rounded-xl p-3 flex flex-col items-center gap-2 cursor-pointer hover:bg-slate-50 text-center">
                                    <input type="radio" name="ewallet_channel" value="SHOPEEPAY" onchange="toggleOvoPhone(false)" class="h-4 w-4 text-blue-600 border-slate-300">
                                    <span class="text-xs font-bold">ShopeePay</span>
                                </label>
                            </div>

                            <div id="phone-input-wrapper">
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Nomor Handphone Terdaftar OVO</label>
                                <input type="text" name="ewallet_phone" placeholder="081234567890" class="block w-full rounded-xl border-slate-200 border px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors">
                            </div>
                        </div>

                        <!-- QRIS Panel -->
                        <div id="panel-QR_CODE" class="space-y-4 hidden">
                            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 flex items-start gap-4">
                                <div class="h-10 w-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">QRIS Instant Checkout</p>
                                    <p class="text-xs text-slate-500 font-medium leading-relaxed mt-1">Gunakan aplikasi e-wallet (GoPay, OVO, DANA, LinkAja, ShopeePay) atau mobile banking Anda untuk memindai kode QRIS dinamis yang akan ditampilkan di langkah berikutnya.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Side: Checkout summary & actions -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <div class="bg-blue-600 p-8 rounded-3xl text-white shadow-xl shadow-blue-100/50 flex flex-col justify-between h-72">
                        <div>
                            <p class="text-sm font-semibold text-blue-200">Total Due Today</p>
                            <h2 class="text-4xl font-extrabold mt-2">{{ $currencySymbol }}{{ number_format($plan->converted_price, $selectedCurrency === 'USD' || $selectedCurrency === 'EUR' ? 2 : 0) }} <span class="text-lg font-medium text-blue-200">{{ $selectedCurrency }}</span></h2>
                        </div>
                        
                        <button type="submit" class="w-full bg-white hover:bg-slate-50 text-blue-600 font-bold py-4 px-4 rounded-2xl shadow-lg transition-all flex items-center justify-center gap-2">
                            <span>Complete Registration</span>
                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                        
                        <p class="text-[10px] text-blue-200 leading-normal font-medium">By clicking the button above, you agree to our Terms of Service and Privacy Policy.</p>
                    </div>

                    <!-- Trust signals -->
                    <div class="space-y-4 px-2">
                        <div class="flex items-start gap-3 text-sm">
                            <span class="text-emerald-500 text-lg mt-0.5">🔒</span>
                            <div>
                                <p class="font-bold text-slate-800">Secure Encrypted Payment</p>
                                <p class="text-xs text-slate-400 font-medium">Your data is protected by 256-bit SSL encryption</p>
                            </div>
                        </div>
                        
                        <div class="border-t border-slate-100 pt-4 flex flex-col items-center">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Powered by</span>
                            <span class="text-xl font-black text-slate-900 tracking-tight mt-1 flex items-center gap-1.5">
                                <span class="text-indigo-600">Xendit</span>
                            </span>
                            <p class="text-[10px] text-slate-400 text-center font-medium mt-2 leading-relaxed">JavaCRM uses Xendit to ensure your payment experience is fast and secure.</p>
                        </div>

                        <div class="text-center pt-2">
                            <a href="{{ route('tenant.register.step2') }}" class="text-slate-400 hover:text-slate-600 font-bold text-sm flex items-center justify-center gap-1.5 transition-colors">
                                ← Back to Plan Selection
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        </form>

        <script>
            function switchPaymentMethod(method) {
                document.getElementById('payment-method-type').value = method;
                
                ['CARD', 'VIRTUAL_ACCOUNT', 'EWALLET', 'QR_CODE'].forEach(m => {
                    const btn = document.getElementById('tab-' + m);
                    if (btn) {
                        btn.className = 'border border-slate-200 rounded-2xl p-4 flex flex-col items-center justify-center gap-1.5 focus:outline-none bg-white text-slate-850 hover:border-slate-350 transition-all';
                    }
                    const panel = document.getElementById('panel-' + m);
                    if (panel) {
                        panel.classList.add('hidden');
                    }
                });

                const activeBtn = document.getElementById('tab-' + method);
                if (activeBtn) {
                    activeBtn.className = 'border-2 border-blue-600 rounded-2xl p-4 flex flex-col items-center justify-center gap-1.5 focus:outline-none bg-blue-50/10 text-blue-600 transition-all';
                }

                const activePanel = document.getElementById('panel-' + method);
                if (activePanel) {
                    activePanel.classList.remove('hidden');
                }
            }

            function toggleOvoPhone(show) {
                const wrapper = document.getElementById('phone-input-wrapper');
                if (wrapper) {
                    if (show) {
                        wrapper.classList.remove('hidden');
                    } else {
                        wrapper.classList.add('hidden');
                    }
                }
            }
        </script>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-6 text-center text-xs text-slate-400 font-medium">
        <p>&copy; 2026 JavaCRM Inc. All rights reserved.</p>
    </footer>

</body>
</html>
