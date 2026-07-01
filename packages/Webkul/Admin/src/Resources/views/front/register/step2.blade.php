<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Plan - JavaCRM</title>
    
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
                <span class="text-sm text-slate-500 font-medium font-semibold">Support</span>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 max-w-6xl mx-auto py-12 px-6 w-full">
        <div class="mb-8">
            <div class="flex justify-between text-xs font-bold text-slate-400 tracking-wider uppercase mb-2">
                <span class="text-blue-600">Step 2 of 3: Plan Selection</span>
                <span>66% Complete</span>
            </div>
            <!-- Progress Bar -->
            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                <div class="bg-blue-600 h-full w-2/3 rounded-full transition-all duration-300"></div>
            </div>
        </div>

        <div class="text-center mb-12">
            <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">Choose the right path for your growth</h1>
            <p class="mt-4 text-slate-400 font-medium max-w-xl mx-auto leading-relaxed mb-6">Scale your sales operations with powerful CRM tools. Start free or unlock advanced features with our Pro and Enterprise plans.</p>
            
            <!-- Currency Selector -->
            <div class="inline-flex items-center gap-3 bg-white px-5 py-2.5 rounded-2xl border border-slate-200/80 shadow-sm">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Mata Uang:</span>
                <select onchange="window.location.href = '?currency=' + this.value" class="rounded-xl border-slate-200 border px-3 py-1.5 text-xs focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors bg-white font-bold text-slate-700">
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

            <!-- Pricing Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto items-stretch mb-12">
                
                @foreach($plans as $plan)
                    @php
                        $isPro = $plan->code === 'pro';
                        $isSelected = $selectedPlanCode === $plan->code;
                    @endphp
                    
                    <div type="button" onclick="selectPlan('{{ $plan->code }}')" class="cursor-pointer bg-white p-8 rounded-3xl border flex flex-col justify-between transition-all duration-300 relative select-none {{ $isSelected ? 'border-blue-600 ring-2 ring-blue-50' : 'border-slate-200/60 hover:border-slate-300' }} {{ $isPro ? 'shadow-xl shadow-blue-50/20' : '' }}">
                        
                        @if($isPro)
                            <span class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-blue-600 text-white text-[10px] font-bold px-4 py-1.5 rounded-full uppercase tracking-wider">Most Popular</span>
                        @endif

                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider {{ $isPro ? 'text-blue-600' : 'text-slate-400' }}">
                                {{ $plan->code === 'free' ? 'Starter' : ($isPro ? 'Growth' : 'Scale') }}
                            </span>
                            <h3 class="text-2xl font-bold text-slate-800 mt-2">{{ $plan->name }}</h3>
                            <div class="mt-4 flex items-baseline">
                                <span class="text-3xl font-extrabold text-slate-900">{{ $currencies[$selectedCurrency]['symbol'] }}{{ number_format($plan->converted_price, $selectedCurrency === 'USD' || $selectedCurrency === 'EUR' ? 2 : 0) }}</span>
                                <span class="text-slate-400 text-sm ml-1">/month</span>
                            </div>
                            <p class="mt-4 text-sm text-slate-500 font-medium leading-relaxed">
                                {{ $plan->description ?? 'Nikmati fitur lengkap dengan performa penjualan terbaik.' }}
                            </p>
                            
                            <!-- Features list -->
                            <ul class="mt-8 space-y-3.5 text-sm font-medium text-slate-600">
                                <li class="flex items-center gap-3">
                                    <span class="h-5 w-5 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-[10px] font-bold">✓</span>
                                    {{ $plan->max_leads > 0 ? "Hingga {$plan->max_leads} Leads" : 'Tanpa Batas Leads' }}
                                </li>
                                <li class="flex items-center gap-3">
                                    <span class="h-5 w-5 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-[10px] font-bold">✓</span>
                                    {{ $plan->max_users > 0 ? "Maksimum {$plan->max_users} Anggota Tim" : 'Anggota Tim Tanpa Batas' }}
                                </li>
                                <li class="flex items-center gap-3">
                                    <span class="h-5 w-5 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-[10px] font-bold">✓</span>
                                    Penyimpanan {{ $plan->max_storage_mb }} MB
                                </li>
                                @if($plan->features)
                                    @foreach($plan->features as $featureName => $isEnabled)
                                        @if($isEnabled)
                                            <li class="flex items-center gap-3">
                                                <span class="h-5 w-5 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-[10px] font-bold">✓</span>
                                                {{ ucfirst($featureName) }} Support
                                            </li>
                                        @endif
                                    @endforeach
                                @endif
                            </ul>
                        </div>

                        <div class="mt-8">
                            <div class="plan-btn text-center font-bold py-3.5 px-4 rounded-xl text-sm transition-all {{ $isSelected ? 'bg-blue-600 text-white shadow-lg shadow-blue-100 hover:shadow-blue-200' : 'bg-slate-50 hover:bg-slate-100 text-slate-800 border border-slate-200' }}">
                                {{ $plan->code === 'free' ? 'Select Free' : ($plan->code === 'pro' ? 'Select Pro' : 'Select Enterprise') }}
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

            <!-- Footer navigation -->
            <div class="max-w-5xl mx-auto flex items-center justify-between border-t border-slate-100 pt-8">
                <a href="{{ route('tenant.register.step1') }}" class="bg-white hover:bg-slate-50 border border-slate-200 text-slate-800 font-bold py-3 px-8 rounded-xl text-sm transition-colors shadow-sm">
                    Previous
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl text-sm transition-all shadow-lg shadow-blue-100 hover:shadow-blue-200 flex items-center gap-2">
                    <span>Next: Payment</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </form>

        <p class="text-center text-slate-400 text-xs mt-8 font-medium">Prices shown in USD. Billed monthly unless selected otherwise in the next step. Terms of Service apply.</p>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-6 text-center text-xs text-slate-400 font-medium">
        <p>&copy; 2026 JavaCRM Inc. All rights reserved.</p>
    </footer>

    <script>
        function selectPlan(planCode) {
            document.getElementById('selected-plan-code').value = planCode;
            document.getElementById('plan-form').submit();
        }
    </script>

</body>
</html>
