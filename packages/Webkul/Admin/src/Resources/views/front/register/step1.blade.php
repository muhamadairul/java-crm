<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Your Account - JavaCRM</title>
    
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
                <span class="text-sm text-slate-500 font-medium">Already have an account?</span>
                <a href="{{ route('admin.session.create') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold px-4 py-2 rounded-xl text-xs transition-colors">Sign In</a>
            </div>
        </div>
    </header>

    <!-- Main Wizard Card -->
    <main class="flex-1 flex items-center justify-center py-12 px-6">
        <div class="w-full max-w-2xl bg-white rounded-3xl shadow-xl shadow-slate-100/50 border border-slate-100/80 p-8 lg:p-10">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Create your account</h1>
                <p class="text-sm text-slate-400 mt-1.5 font-medium">Join thousands of companies growing with JavaCRM</p>
            </div>

            <!-- Progress Indicator -->
            <div class="mb-8">
                <div class="flex justify-between text-xs font-bold text-slate-400 tracking-wider uppercase mb-2">
                    <span class="text-blue-600">Step 1 of 3</span>
                    <span>33% Complete</span>
                </div>
                <!-- Progress Bar -->
                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                    <div class="bg-blue-600 h-full w-1/3 rounded-full transition-all duration-300"></div>
                </div>
                <!-- Wizard Steps Icons -->
                <div class="flex items-center justify-between mt-6 max-w-sm mx-auto">
                    <div class="flex flex-col items-center gap-1.5">
                        <span class="h-8 w-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold ring-4 ring-blue-50">1</span>
                        <span class="text-[10px] font-bold text-slate-600 uppercase tracking-wider">Company</span>
                    </div>
                    <div class="flex-1 h-[2px] bg-slate-100 mx-4"></div>
                    <div class="flex flex-col items-center gap-1.5">
                        <span class="h-8 w-8 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-xs font-bold">2</span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Plan</span>
                    </div>
                    <div class="flex-1 h-[2px] bg-slate-100 mx-4"></div>
                    <div class="flex flex-col items-center gap-1.5">
                        <span class="h-8 w-8 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-xs font-bold">3</span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Activate</span>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('tenant.register.step1.post') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Grid company -->
                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 space-y-4">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Profil Perusahaan</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="company_name" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Nama Perusahaan</label>
                            <input type="text" name="company_name" id="company_name" required value="{{ $sessionData['company']['name'] ?? '' }}" placeholder="e.g. Acme Corp" class="block w-full rounded-xl border-slate-200 border px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors">
                        </div>
                        <div>
                            <label for="company_phone" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">No. Telepon Perusahaan</label>
                            <input type="text" name="company_phone" id="company_phone" required value="{{ $sessionData['company']['phone'] ?? '' }}" placeholder="+62 (555) 000-0000" class="block w-full rounded-xl border-slate-200 border px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors">
                        </div>
                    </div>
                    
                    <div>
                        <label for="company_email" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Email Perusahaan</label>
                        <input type="email" name="company_email" id="company_email" required value="{{ $sessionData['company']['email'] ?? '' }}" placeholder="info@company.com" class="block w-full rounded-xl border-slate-200 border px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors">
                    </div>

                    <div>
                        <label for="company_address" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Alamat Perusahaan</label>
                        <textarea name="company_address" id="company_address" required rows="2" placeholder="Alamat lengkap perusahaan..." class="block w-full rounded-xl border-slate-200 border px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors">{{ $sessionData['company']['address'] ?? '' }}</textarea>
                    </div>
                </div>

                <!-- Grid admin -->
                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 space-y-4">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Akun Administrator Utama</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="admin_email" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Email Admin</label>
                            <input type="email" name="admin_email" id="admin_email" required value="{{ $sessionData['admin']['email'] ?? '' }}" placeholder="admin@company.com" class="block w-full rounded-xl border-slate-200 border px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors">
                        </div>
                        <div>
                            <label for="admin_password" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Kata Sandi</label>
                            <input type="password" name="admin_password" id="admin_password" required placeholder="••••••••" class="block w-full rounded-xl border-slate-200 border px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors">
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-4 rounded-2xl shadow-lg shadow-blue-100 hover:shadow-blue-200 transition-all flex items-center justify-center gap-2">
                    <span>Next: Choose Plan</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>

            <p class="text-center text-slate-400 text-xs mt-6 font-medium leading-relaxed">
                By creating an account, you agree to our 
                <a href="#" class="text-blue-500 hover:underline">Terms of Service</a> and 
                <a href="#" class="text-blue-500 hover:underline">Privacy Policy</a>.
            </p>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-6 text-center text-xs text-slate-400 font-medium">
        <p>&copy; 2026 JavaCRM Inc. All rights reserved.</p>
    </footer>

</body>
</html>
