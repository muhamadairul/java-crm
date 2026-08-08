<!DOCTYPE html>
@php
    $currentLocale = session('locale', app()->getLocale());
    $isEn = $currentLocale === 'en';
@endphp
<html lang="{{ $currentLocale }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isEn ? 'Select Plan - JavaCRM' : 'Pilih Paket Langganan - JavaCRM' }}</title>
    
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
                    <a href="{{ route('tenant.register.step1') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold px-3.5 py-2 rounded-xl transition-all dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
                        ← {{ $isEn ? 'Back to Step 1' : 'Kembali ke Step 1' }}
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

                <!-- Step 2 Active -->
                <div class="flex items-center gap-2">
                    <div class="h-7 w-7 rounded-full bg-sky-600 text-white flex items-center justify-center text-xs font-extrabold shadow-sm shadow-sky-500/30">
                        2
                    </div>
                    <span class="text-xs font-bold text-slate-900 dark:text-white">
                        {{ $isEn ? 'Plan' : 'Paket' }}
                    </span>
                </div>

                <!-- Divider 2-3 -->
                <div class="flex-1 h-[2px] mx-3 bg-slate-200 dark:bg-slate-800"></div>

                <!-- Step 3 Inactive -->
                <div class="flex items-center gap-2">
                    <div class="h-7 w-7 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-xs font-bold dark:bg-slate-800 dark:text-slate-500">
                        3
                    </div>
                    <span class="text-xs font-semibold text-slate-400 dark:text-slate-500">
                        {{ $isEn ? 'Activation' : 'Aktivasi' }}
                    </span>
                </div>
            </div>

            <div class="text-center mb-8 border-b border-slate-100 pb-5 dark:border-slate-800">
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight leading-tight dark:text-white">
                    {{ $isEn ? 'Choose Subscription Plan' : 'Pilih Paket Langganan' }}
                </h1>
                <p class="mt-1 text-xs text-slate-500 font-medium max-w-md mx-auto leading-relaxed mb-4 dark:text-slate-400">
                    {{ $isEn ? 'Select the best plan suited for your sales team scale' : 'Pilih paket yang paling sesuai dengan kebutuhan tim bisnis Anda' }}
                </p>
                
                <!-- Currency Selector -->
                <div class="inline-flex items-center gap-2.5 bg-slate-50 px-3.5 py-1.5 rounded-xl border border-slate-200/80 shadow-sm dark:bg-slate-950 dark:border-slate-800">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider dark:text-slate-400">{{ $isEn ? 'Currency:' : 'Mata Uang:' }}</span>
                    <select onchange="window.location.href = '?currency=' + this.value" class="rounded-lg border-slate-200 border px-2.5 py-1 text-xs focus:border-sky-500 focus:ring-sky-500 shadow-sm transition-colors bg-white font-bold text-slate-700 dark:bg-slate-900 dark:border-slate-800 dark:text-white">
                        @foreach($currencies as $code => $info)
                            <option value="{{ $code }}" {{ $selectedCurrency === $code ? 'selected' : '' }}>
                                {{ $code }} ({{ $info['symbol'] }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Form for Plan Submission -->
            <form action="{{ route('tenant.register.step2.post') }}" method="POST" id="plan-form">
                @csrf
                
                <input type="hidden" name="plan_code" id="selected-plan-code" value="{{ $selectedPlanCode }}">
                <input type="hidden" name="currency" value="{{ $selectedCurrency }}">

                <!-- Pricing Grid Cards inside main card -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 items-stretch mb-6">
                    
                    @foreach($plans as $plan)
                        @php
                            $isPro = $plan->code === 'pro';
                            $isSelected = $selectedPlanCode === $plan->code;
                        @endphp
                        
                        <div type="button" onclick="selectPlan('{{ $plan->code }}')" class="cursor-pointer bg-slate-50 p-5 rounded-2xl border flex flex-col justify-between transition-all duration-300 relative select-none dark:bg-slate-950 {{ $isSelected ? 'border-sky-600 ring-2 ring-sky-500/20 shadow-md' : 'border-slate-200/80 hover:border-sky-400 dark:border-slate-800' }}">
                            
                            @if($isPro)
                                <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-sky-600 text-white text-[9px] font-extrabold px-3 py-0.5 rounded-full uppercase tracking-wider shadow-sm">
                                    {{ $isEn ? 'Most Popular' : 'Paling Populer' }}
                                </span>
                            @endif

                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider {{ $isPro ? 'text-sky-600 dark:text-sky-400' : 'text-slate-400' }}">
                                    {{ $plan->code === 'free' ? ($isEn ? 'Starter' : 'Awal') : ($isPro ? ($isEn ? 'Growth' : 'Pertumbuhan') : ($isEn ? 'Enterprise' : 'Enterprise')) }}
                                </span>
                                <h3 class="text-lg font-extrabold text-slate-800 mt-1 dark:text-white">{{ $plan->name }}</h3>
                                <div class="mt-2 flex items-baseline">
                                    <span class="text-xl font-extrabold text-slate-900 dark:text-white">{{ $currencies[$selectedCurrency]['symbol'] }}{{ number_format($plan->converted_price, $selectedCurrency === 'USD' || $selectedCurrency === 'EUR' ? 2 : 0) }}</span>
                                    <span class="text-slate-400 text-xs font-semibold ml-1">/{{ $plan->billing_cycle === 'yearly' ? ($isEn ? 'year' : 'tahun') : ($isEn ? 'month' : 'bulan') }}</span>
                                </div>
                                <p class="mt-2 text-[11px] text-slate-500 font-medium leading-relaxed dark:text-slate-400">
                                    {{ $plan->description ?: ($isEn ? 'Complete CRM features for your sales team.' : 'Fitur CRM lengkap untuk tim penjualan Anda.') }}
                                </p>

                                <!-- Feature list -->
                                <div class="mt-4 pt-3 border-t border-slate-200/60 space-y-2 text-xs text-slate-600 dark:border-slate-800 dark:text-slate-300">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-3.5 w-3.5 text-sky-600 dark:text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        <span><strong>{{ $plan->max_users }}</strong> {{ $isEn ? 'Team Users' : 'User Tim' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="h-3.5 w-3.5 text-sky-600 dark:text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        <span><strong>{{ number_format($plan->max_leads) }}</strong> Leads</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="h-3.5 w-3.5 text-sky-600 dark:text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        <span><strong>{{ number_format($plan->max_storage_mb) }} MB</strong> Storage</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5">
                                <div class="w-full py-2 rounded-xl font-bold text-xs text-center transition-all shadow-sm {{ $isSelected ? 'bg-sky-600 text-white shadow-sky-600/25' : 'bg-white text-slate-700 hover:bg-slate-100 dark:bg-slate-900 dark:text-slate-300' }}">
                                    {{ $isSelected ? ($isEn ? 'Selected Plan' : 'Paket Terpilih') : ($isEn ? 'Select Plan' : 'Pilih Paket') }}
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>

                <!-- Submit Controls -->
                <div class="flex items-center justify-between pt-5 border-t border-slate-200/80 dark:border-slate-800">
                    <a href="{{ route('tenant.register.step1') }}" class="bg-white hover:bg-slate-50 border border-slate-200 text-slate-800 font-bold py-2.5 px-6 rounded-xl text-xs transition-all shadow-sm dark:bg-slate-950 dark:border-slate-800 dark:text-slate-200 dark:hover:bg-slate-900">
                        ← {{ $isEn ? 'Back' : 'Kembali' }}
                    </a>
                    
                    <button type="submit" class="bg-sky-600 hover:bg-sky-700 text-white font-bold py-2.5 px-6 rounded-xl text-xs transition-all shadow-md shadow-sky-600/20 flex items-center gap-2 hover:scale-[1.01]">
                        <span>{{ $isEn ? 'Next: Payment & Activation' : 'Lanjut: Pembayaran & Aktivasi' }}</span>
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </form>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200/80 py-4 text-center text-xs text-slate-400 font-medium dark:bg-slate-900 dark:border-slate-800 dark:text-slate-500">
        <p>&copy; {{ date('Y') }} JavaCRM. {{ $isEn ? 'All rights reserved.' : 'Hak cipta dilindungi undang-undang.' }}</p>
    </footer>

    <script>
        function selectPlan(code) {
            document.getElementById('selected-plan-code').value = code;
            document.querySelectorAll('[onclick^="selectPlan"]').forEach(el => {
                el.classList.remove('border-sky-600', 'ring-2', 'ring-sky-500/20', 'shadow-md');
                el.classList.add('border-slate-200/80');
                const btn = el.querySelector('.rounded-xl');
                if (btn) {
                    btn.classList.remove('bg-sky-600', 'text-white', 'shadow-sky-600/25');
                    btn.classList.add('bg-white', 'text-slate-700');
                    btn.innerText = '{{ $isEn ? "Select Plan" : "Pilih Paket" }}';
                }
            });

            event.currentTarget.classList.remove('border-slate-200/80');
            event.currentTarget.classList.add('border-sky-600', 'ring-2', 'ring-sky-500/20', 'shadow-md');
            const selectedBtn = event.currentTarget.querySelector('.rounded-xl');
            if (selectedBtn) {
                selectedBtn.classList.remove('bg-white', 'text-slate-700');
                selectedBtn.classList.add('bg-sky-600', 'text-white', 'shadow-sky-600/25');
                selectedBtn.innerText = '{{ $isEn ? "Selected Plan" : "Paket Terpilih" }}';
            }
        }
    </script>
</body>
</html>
