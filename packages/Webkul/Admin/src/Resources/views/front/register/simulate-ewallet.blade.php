<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Wallet Payment Simulator - JavaCRM</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;850&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    {{ vite()->set(['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js']) }}
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl border border-slate-200/60 p-8 space-y-6">
        <!-- Logo / Brand -->
        <div class="flex items-center justify-center gap-2 text-indigo-600 font-extrabold text-lg">
            <span>🛡️ E-Wallet Payment Gateway Simulator</span>
        </div>

        <div class="text-center space-y-2">
            <h2 class="text-xl font-bold text-slate-900">Simulate Payment</h2>
            <p class="text-xs text-slate-400 font-medium">You have been redirected here to simulate a native e-wallet checkout flow.</p>
        </div>

        <!-- Invoice Details -->
        <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100 space-y-3">
            <div class="flex justify-between text-xs font-semibold text-slate-400">
                <span>Merchant</span>
                <span class="text-slate-800 font-bold">JavaCRM SaaS</span>
            </div>
            <div class="flex justify-between text-xs font-semibold text-slate-400">
                <span>Invoice Ref</span>
                <span class="text-slate-800 font-bold">{{ $invoice->invoice_number }}</span>
            </div>
            <div class="flex justify-between text-xs font-semibold text-slate-400">
                <span>Amount Due</span>
                <span class="text-indigo-600 font-black text-sm">
                    {{ $invoice->currency === 'IDR' ? 'Rp' : ($invoice->currency === 'USD' ? '$' : ($invoice->currency === 'EUR' ? '€' : 'S$')) }}
                    {{ number_format($invoice->amount, $invoice->currency === 'USD' || $invoice->currency === 'EUR' ? 2 : 0) }}
                </span>
            </div>
        </div>

        <!-- Simulation Actions -->
        <div class="space-y-3 pt-2">
            <form action="{{ route('api.xendit.simulate_success', ['invoice_id' => $invoice->id]) }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 px-4 rounded-2xl shadow-lg shadow-emerald-100 transition-all text-xs flex items-center justify-center gap-1.5">
                    <span>✅ AUTHORIZE & PAY (SUCCESS)</span>
                </button>
            </form>

            <a href="{{ route('tenant.register.payment_pending', ['invoice_id' => $invoice->id]) }}" class="w-full bg-slate-100 hover:bg-slate-250 text-slate-700 font-bold py-3.5 px-4 rounded-2xl text-xs transition-colors flex items-center justify-center">
                ❌ CANCEL TRANSACTION
            </a>
        </div>

        <p class="text-[10px] text-slate-400 text-center font-medium leading-relaxed">This screen is part of the development test sandbox and will not charge any actual account or balance.</p>
    </div>

</body>
</html>
