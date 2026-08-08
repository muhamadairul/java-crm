<!DOCTYPE html>
@php
    $currentLocale = session('locale', app()->getLocale());
    $isEn = $currentLocale === 'en';
@endphp
<html lang="{{ $currentLocale }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isEn ? 'Create Account - JavaCRM' : 'Buat Akun Perusahaan - JavaCRM' }}</title>
    
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
                    <span class="text-slate-500 font-medium">{{ $isEn ? 'Already registered?' : 'Sudah punya akun?' }}</span>
                    <a href="{{ route('admin.session.create') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold px-3.5 py-2 rounded-xl transition-all dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
                        {{ $isEn ? 'Sign In' : 'Masuk' }}
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Wrapped in Compact Sleek Card -->
    <main class="flex-1 flex items-center justify-center py-10 px-4">
        <div class="w-full max-w-xl bg-white rounded-2xl shadow-lg border border-slate-200/80 p-6 sm:p-8 dark:bg-slate-900 dark:border-slate-800">
            
            <!-- Professional Single Step Wizard Bar -->
            <div class="flex items-center justify-between mb-6 max-w-md mx-auto">
                <!-- Step 1 Active -->
                <div class="flex items-center gap-2">
                    <div class="h-7 w-7 rounded-full bg-sky-600 text-white flex items-center justify-center text-xs font-extrabold shadow-sm shadow-sky-500/30">
                        1
                    </div>
                    <span class="text-xs font-bold text-slate-900 dark:text-white">
                        {{ $isEn ? 'Company' : 'Perusahaan' }}
                    </span>
                </div>

                <!-- Divider 1-2 -->
                <div class="flex-1 h-[2px] mx-3 bg-slate-200 dark:bg-slate-800"></div>

                <!-- Step 2 Inactive -->
                <div class="flex items-center gap-2">
                    <div class="h-7 w-7 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-xs font-bold dark:bg-slate-800 dark:text-slate-500">
                        2
                    </div>
                    <span class="text-xs font-semibold text-slate-400 dark:text-slate-500">
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

            <!-- Header Title -->
            <div class="text-center mb-6 border-b border-slate-100 pb-5 dark:border-slate-800">
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight dark:text-white">
                    {{ $isEn ? 'Create Your Account' : 'Buat Akun Perusahaan' }}
                </h1>
                <p class="text-xs text-slate-500 mt-1 font-medium dark:text-slate-400">
                    {{ $isEn ? 'Fill in your company & admin details to get started' : 'Isi data perusahaan & akun admin untuk memulai' }}
                </p>
            </div>

            <!-- Form -->
            <form action="{{ route('tenant.register.step1.post') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Company Profile Section -->
                <div class="space-y-3.5">
                    <h3 class="text-[11px] font-bold text-sky-600 uppercase tracking-wider dark:text-sky-400">
                        {{ $isEn ? 'Company Profile' : 'Profil Perusahaan' }}
                    </h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label for="company_name" class="block text-xs font-bold text-slate-700 mb-1 dark:text-slate-300">
                                {{ $isEn ? 'Company Name' : 'Nama Perusahaan' }}
                            </label>
                            <input type="text" name="company_name" id="company_name" required value="{{ $sessionData['company']['name'] ?? '' }}" placeholder="{{ $isEn ? 'e.g. Acme Corp' : 'Contoh: PT. Acme Solusi' }}" class="block w-full rounded-xl border-slate-200 border px-3.5 py-2.5 text-xs focus:border-sky-500 focus:ring-sky-500 shadow-sm transition-colors dark:bg-slate-950 dark:border-slate-800 dark:text-white">
                        </div>
                        <div>
                            <label for="company_phone" class="block text-xs font-bold text-slate-700 mb-1 dark:text-slate-300">
                                {{ $isEn ? 'Company Phone' : 'No. Telepon Perusahaan' }}
                            </label>
                            <input type="text" name="company_phone" id="company_phone" required value="{{ $sessionData['company']['phone'] ?? '' }}" placeholder="+62 812-0000-0000" class="block w-full rounded-xl border-slate-200 border px-3.5 py-2.5 text-xs focus:border-sky-500 focus:ring-sky-500 shadow-sm transition-colors dark:bg-slate-950 dark:border-slate-800 dark:text-white">
                        </div>
                    </div>
                    
                    <div>
                        <label for="company_email" class="block text-xs font-bold text-slate-700 mb-1 dark:text-slate-300">
                            {{ $isEn ? 'Company Email' : 'Email Perusahaan' }}
                        </label>
                        <input type="email" name="company_email" id="company_email" required value="{{ $sessionData['company']['email'] ?? '' }}" placeholder="info@company.com" class="block w-full rounded-xl border-slate-200 border px-3.5 py-2.5 text-xs focus:border-sky-500 focus:ring-sky-500 shadow-sm transition-colors dark:bg-slate-950 dark:border-slate-800 dark:text-white">
                    </div>

                    <div>
                        <label for="company_address" class="block text-xs font-bold text-slate-700 mb-1 dark:text-slate-300">
                            {{ $isEn ? 'Company Address' : 'Alamat Perusahaan' }}
                        </label>
                        <textarea name="company_address" id="company_address" required rows="2" placeholder="{{ $isEn ? 'Full address...' : 'Alamat lengkap perusahaan...' }}" class="block w-full rounded-xl border-slate-200 border px-3.5 py-2.5 text-xs focus:border-sky-500 focus:ring-sky-500 shadow-sm transition-colors dark:bg-slate-950 dark:border-slate-800 dark:text-white">{{ $sessionData['company']['address'] ?? '' }}</textarea>
                    </div>
                </div>

                <!-- Admin Account Section -->
                <div class="pt-2 space-y-3.5 border-t border-slate-100 dark:border-slate-800">
                    <h3 class="text-[11px] font-bold text-sky-600 uppercase tracking-wider dark:text-sky-400">
                        {{ $isEn ? 'Administrator Account' : 'Akun Administrator Utama' }}
                    </h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label for="admin_email" class="block text-xs font-bold text-slate-700 mb-1 dark:text-slate-300">
                                {{ $isEn ? 'Admin Email' : 'Email Admin' }}
                            </label>
                            <input type="email" name="admin_email" id="admin_email" required value="{{ $sessionData['admin']['email'] ?? '' }}" placeholder="admin@company.com" class="block w-full rounded-xl border-slate-200 border px-3.5 py-2.5 text-xs focus:border-sky-500 focus:ring-sky-500 shadow-sm transition-colors dark:bg-slate-950 dark:border-slate-800 dark:text-white">
                        </div>
                        <div>
                            <label for="admin_password" class="block text-xs font-bold text-slate-700 mb-1 dark:text-slate-300">
                                {{ $isEn ? 'Password' : 'Kata Sandi' }}
                            </label>
                            <input type="password" name="admin_password" id="admin_password" required placeholder="••••••••" class="block w-full rounded-xl border-slate-200 border px-3.5 py-2.5 text-xs focus:border-sky-500 focus:ring-sky-500 shadow-sm transition-colors dark:bg-slate-950 dark:border-slate-800 dark:text-white">
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-sky-600 hover:bg-sky-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-md shadow-sky-600/20 transition-all flex items-center justify-center gap-2 hover:scale-[1.01] text-xs mt-4">
                    <span>{{ $isEn ? 'Next: Select Plan' : 'Lanjut: Pilih Paket' }}</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>

            <p class="text-center text-slate-400 text-[11px] mt-5 font-medium leading-relaxed dark:text-slate-500">
                {{ $isEn ? 'By creating an account, you agree to our Terms & Privacy Policy.' : 'Dengan membuat akun, Anda menyetujui Ketentuan & Kebijakan Privasi.' }}
            </p>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200/80 py-4 text-center text-xs text-slate-400 font-medium dark:bg-slate-900 dark:border-slate-800 dark:text-slate-500">
        <p>&copy; {{ date('Y') }} JavaCRM. {{ $isEn ? 'All rights reserved.' : 'Hak cipta dilindungi undang-undang.' }}</p>
    </footer>

</body>
</html>
