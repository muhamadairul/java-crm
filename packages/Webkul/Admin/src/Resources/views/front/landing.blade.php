<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JavaCRM - Sistem CRM Multi-Tenant & Manajemen Penjualan Terintegrasi</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    {{ vite()->set(['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js']) }}
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            scroll-behavior: smooth;
        }
        
        /* Smooth Micro-interactions & Custom CSS */
        .glass-nav {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            background-color: rgba(255, 255, 255, 0.85);
        }
        
        .tab-btn.active {
            color: #4f46e5;
            border-bottom-color: #4f46e5;
        }

        details summary::-webkit-details-marker {
            display: none;
        }
        
        /* Entrance Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }

        .animate-fade-in-delayed {
            animation: fadeIn 0.8s ease-out 0.2s forwards;
            opacity: 0;
        }
    </style>
</head>
<body class="bg-[#fafbfc] text-[#2c3e50] antialiased overflow-x-hidden">

    <!-- Header / Navbar -->
    <header class="glass-nav border-b border-slate-100 sticky top-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-9 w-9 bg-indigo-600 rounded-lg flex items-center justify-center shadow-md shadow-indigo-200">
                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <span class="text-xl font-bold tracking-tight text-slate-900">Java<span class="text-indigo-600">CRM</span></span>
            </div>
            
            <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-600">
                <a href="#fitur" class="hover:text-indigo-600 transition-colors">Fitur</a>
                <a href="#demo" class="hover:text-indigo-600 transition-colors">Demo Papan</a>
                <a href="#keunggulan" class="hover:text-indigo-600 transition-colors">Keunggulan</a>
                <a href="#harga" class="hover:text-indigo-600 transition-colors">Harga</a>
                <a href="#faq" class="hover:text-indigo-600 transition-colors">FAQ</a>
            </nav>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.session.create') }}" class="text-sm font-bold text-slate-600 hover:text-indigo-600 transition-colors px-3 py-2">Masuk</a>
                <a href="{{ route('tenant.register.step1') }}" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-4 py-2.5 rounded-lg text-sm transition-all shadow-sm">Uji Coba Gratis</a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-6 pt-16 pb-20 lg:pt-24 lg:pb-32 text-center relative">
        <div class="max-w-4xl mx-auto">
            <div class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-800 rounded-full px-3.5 py-1 text-xs font-semibold mb-6 border border-slate-200/50">
                <span>Optimalkan Pipeline Penjualan Anda</span>
            </div>
            
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight leading-[1.1] mb-6 animate-fade-in">
                Manajemen Pipeline Penjualan & <br>
                Data Customer dalam Satu Sistem Terpusat
            </h1>
            
            <p class="text-base sm:text-lg lg:text-xl text-slate-500 max-w-2xl mx-auto leading-relaxed mb-10 font-medium animate-fade-in-delayed">
                Sistem CRM Multi-Tenant yang didesain untuk menyederhanakan pelacakan prospek, memantau kinerja tim sales, dan mengamankan data pelanggan secara terisolasi.
            </p>
            
            <div class="flex flex-col sm:flex-row justify-center items-center gap-3 animate-fade-in-delayed" style="animation-delay: 0.3s">
                <a href="{{ route('tenant.register.step1') }}" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-3.5 rounded-xl transition-all text-sm flex items-center justify-center gap-2 shadow-lg shadow-indigo-100">
                    <span>Mulai Pendaftaran Tenant</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
                <a href="#demo" class="w-full sm:w-auto bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-bold px-6 py-3.5 rounded-xl transition-all text-sm flex items-center justify-center">
                    Lihat Papan Demo
                </a>
            </div>
        </div>

        <!-- High-Fidelity Light-Theme CRM App Interface Mockup -->
        <div class="mt-16 lg:mt-24 max-w-5xl mx-auto rounded-2xl border border-slate-200 bg-white p-2.5 shadow-xl shadow-slate-100 ring-1 ring-slate-200/50 animate-fade-in-delayed" style="animation-delay: 0.4s">
            <div class="rounded-xl border border-slate-200/80 bg-slate-50 overflow-hidden shadow-inner text-left">
                <!-- Browser Window Header -->
                <div class="flex items-center justify-between px-4 py-2.5 border-b border-slate-200/80 bg-slate-100/70">
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-slate-300 inline-block"></span>
                        <span class="w-3 h-3 rounded-full bg-slate-300 inline-block"></span>
                        <span class="w-3 h-3 rounded-full bg-slate-300 inline-block"></span>
                    </div>
                    <div class="px-6 py-0.5 bg-white border border-slate-200/50 text-slate-400 text-[10px] rounded font-mono w-72 truncate text-center">
                        https://demo-corp.javacrm.com/tenant/leads/pipeline
                    </div>
                    <div class="w-8"></div>
                </div>
                
                <!-- Mockup Content -->
                <div class="grid grid-cols-12 h-[480px]">
                    <!-- Sidebar Mockup -->
                    <aside class="col-span-3 border-r border-slate-200/80 bg-white p-4 hidden md:block">
                        <div class="flex items-center gap-2 px-2 py-2 border border-slate-100 rounded-lg mb-6 bg-slate-50">
                            <div class="w-6 h-6 rounded bg-indigo-150 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0">DC</div>
                            <span class="text-xs font-bold text-slate-700 truncate">Demo Corporation</span>
                        </div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider px-2 mb-2">Penjualan</div>
                        <nav class="space-y-1">
                            <span class="flex items-center gap-2.5 px-2.5 py-2 text-slate-500 rounded-lg text-xs font-medium cursor-pointer hover:bg-slate-50">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" /></svg>
                                Dasbor Utama
                            </span>
                            <span class="flex items-center gap-2.5 px-2.5 py-2 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-bold">
                                <svg class="h-4 w-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                Pipeline / Prospek
                            </span>
                            <span class="flex items-center gap-2.5 px-2.5 py-2 text-slate-500 rounded-lg text-xs font-medium cursor-pointer hover:bg-slate-50">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                Penawaran (Quotes)
                            </span>
                            <span class="flex items-center gap-2.5 px-2.5 py-2 text-slate-500 rounded-lg text-xs font-medium cursor-pointer hover:bg-slate-50">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                Email Masuk
                            </span>
                        </nav>
                        
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider px-2 mt-6 mb-2">Sistem</div>
                        <nav class="space-y-1">
                            <span class="flex items-center gap-2.5 px-2.5 py-2 text-slate-500 rounded-lg text-xs font-medium cursor-pointer hover:bg-slate-50">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                Anggota Tim
                            </span>
                            <span class="flex items-center gap-2.5 px-2.5 py-2 text-slate-500 rounded-lg text-xs font-medium cursor-pointer hover:bg-slate-50">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                Integrasi & API
                            </span>
                        </nav>
                    </aside>
                    
                    <!-- Main CRM Workspace -->
                    <main class="col-span-12 md:col-span-9 p-5 bg-[#f3f5f8] overflow-y-auto space-y-5 flex flex-col justify-between">
                        <!-- Top Bar inside app -->
                        <div class="flex items-center justify-between">
                            <h2 class="text-sm font-bold text-slate-800">Pipeline Penjualan</h2>
                            <div class="flex items-center gap-2">
                                <button class="bg-white border border-slate-200 text-slate-700 px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-slate-50 transition-colors flex items-center gap-1.5">
                                    <span>Filter</span>
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                                </button>
                                <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm transition-colors">
                                    + Tambah Prospek
                                </button>
                            </div>
                        </div>

                        <!-- Kanban Board columns -->
                        <div class="grid grid-cols-4 gap-4 items-stretch grow mt-2">
                            <!-- Column 1 -->
                            <div class="bg-slate-100 border border-slate-200/50 rounded-xl p-3 flex flex-col gap-3 min-h-[300px]">
                                <div class="flex items-center justify-between text-[10px] font-bold text-slate-500 uppercase tracking-wide px-1">
                                    <span>Kualifikasi</span>
                                    <span class="bg-slate-250 text-slate-600 px-1.5 py-0.5 rounded">3</span>
                                </div>
                                <div class="space-y-2.5">
                                    <div class="bg-white p-3 rounded-lg border border-slate-200/80 shadow-sm hover:border-slate-350 transition-colors">
                                        <div class="text-[11px] font-bold text-slate-700">PT Industri Sandang</div>
                                        <div class="text-[10px] text-slate-500 mt-1">Lead Source: Website</div>
                                        <div class="flex items-center justify-between mt-3 pt-2 border-t border-slate-100">
                                            <span class="text-xs font-bold text-slate-800">Rp 12.000.000</span>
                                            <span class="w-5 h-5 rounded-full bg-indigo-100 text-[10px] font-bold text-indigo-700 flex items-center justify-center">AS</span>
                                        </div>
                                    </div>
                                    <div class="bg-white p-3 rounded-lg border border-slate-200/80 shadow-sm hover:border-slate-350 transition-colors">
                                        <div class="text-[11px] font-bold text-slate-700">CV Dirgantara Indah</div>
                                        <div class="text-[10px] text-slate-500 mt-1">Lead Source: Referal</div>
                                        <div class="flex items-center justify-between mt-3 pt-2 border-t border-slate-100">
                                            <span class="text-xs font-bold text-slate-800">Rp 4.500.000</span>
                                            <span class="w-5 h-5 rounded-full bg-purple-100 text-[10px] font-bold text-purple-700 flex items-center justify-center">IA</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Column 2 -->
                            <div class="bg-slate-100 border border-slate-200/50 rounded-xl p-3 flex flex-col gap-3">
                                <div class="flex items-center justify-between text-[10px] font-bold text-slate-500 uppercase tracking-wide px-1">
                                    <span>Presentasi</span>
                                    <span class="bg-slate-250 text-slate-600 px-1.5 py-0.5 rounded">2</span>
                                </div>
                                <div class="space-y-2.5">
                                    <div class="bg-white p-3 rounded-lg border border-slate-200/80 shadow-sm hover:border-slate-350 transition-colors">
                                        <div class="text-[11px] font-bold text-slate-700">PT Sinar Sosro</div>
                                        <div class="text-[10px] text-slate-500 mt-1">Demo Produk Dijadwalkan</div>
                                        <div class="flex items-center justify-between mt-3 pt-2 border-t border-slate-100">
                                            <span class="text-xs font-bold text-slate-800">Rp 28.500.000</span>
                                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-[10px] font-bold text-emerald-700 flex items-center justify-center">HN</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Column 3 -->
                            <div class="bg-slate-100 border border-slate-200/50 rounded-xl p-3 flex flex-col gap-3">
                                <div class="flex items-center justify-between text-[10px] font-bold text-slate-500 uppercase tracking-wide px-1">
                                    <span>Proposal</span>
                                    <span class="bg-slate-250 text-slate-600 px-1.5 py-0.5 rounded">1</span>
                                </div>
                                <div class="space-y-2.5">
                                    <div class="bg-white p-3 rounded-lg border border-slate-200/80 shadow-sm hover:border-slate-350 transition-colors">
                                        <div class="text-[11px] font-bold text-slate-700">PT Krakatau Steel</div>
                                        <div class="text-[10px] text-slate-500 mt-1">Proposal Dikirim</div>
                                        <div class="flex items-center justify-between mt-3 pt-2 border-t border-slate-100">
                                            <span class="text-xs font-bold text-slate-800">Rp 85.000.000</span>
                                            <span class="w-5 h-5 rounded-full bg-indigo-100 text-[10px] font-bold text-indigo-700 flex items-center justify-center">AS</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Column 4 -->
                            <div class="bg-slate-100 border border-slate-200/50 rounded-xl p-3 flex flex-col gap-3">
                                <div class="flex items-center justify-between text-[10px] font-bold text-slate-500 uppercase tracking-wide px-1">
                                    <span>Deal Menang</span>
                                    <span class="bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded">2</span>
                                </div>
                                <div class="space-y-2.5">
                                    <div class="bg-white p-3 rounded-lg border border-emerald-200 bg-emerald-50/10 shadow-sm">
                                        <div class="text-[11px] font-bold text-slate-700">CV Global Abadi</div>
                                        <div class="text-[10px] text-emerald-600 font-medium mt-1">Invoice Lunas Xendit</div>
                                        <div class="flex items-center justify-between mt-3 pt-2 border-t border-slate-100">
                                            <span class="text-xs font-bold text-emerald-700">Rp 15.000.000</span>
                                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-[10px] font-bold text-emerald-700 flex items-center justify-center">HN</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </section>

    <!-- Key Features Section -->
    <section id="fitur" class="bg-white border-y border-slate-100 py-20 lg:py-28">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-20">
                <span class="text-xs font-semibold text-indigo-600 tracking-wider uppercase">Fitur Fungsional</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mt-3">Segala Modul Penting Untuk Menunjang Pipeline Tim Sales</h2>
                <p class="mt-4 text-slate-500 font-medium leading-relaxed">Dirancang khusus untuk memotong birokrasi penawaran harga, pelacakan leads, dan koordinasi harian.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Card 1 -->
                <div class="bg-[#fafbfc] border border-slate-200/60 p-8 rounded-2xl transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                    <div class="h-10 w-10 bg-indigo-50 border border-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 mb-6">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2.5">Pipeline Kanban</h3>
                    <p class="text-slate-500 text-xs sm:text-sm leading-relaxed font-medium">Lacak perjalanan prospek penjualan secara visual. Tahap deals yang transparan memudahkan evaluasi mingguan performa penjualan.</p>
                </div>
                
                <!-- Card 2 -->
                <div class="bg-[#fafbfc] border border-slate-200/60 p-8 rounded-2xl transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                    <div class="h-10 w-10 bg-indigo-50 border border-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 mb-6">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2.5">Isolasi Keamanan</h3>
                    <p class="text-slate-500 text-xs sm:text-sm leading-relaxed font-medium">Setiap data perusahaan tenant diisolasi secara logis pada database layer. Proteksi kuat mencegah kebocoran data pelanggan antar-tenant.</p>
                </div>
                
                <!-- Card 3 -->
                <div class="bg-[#fafbfc] border border-slate-200/60 p-8 rounded-2xl transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                    <div class="h-10 w-10 bg-indigo-50 border border-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 mb-6">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2.5">Otomatisasi Aktivitas</h3>
                    <p class="text-slate-500 text-xs sm:text-sm leading-relaxed font-medium">Kirim e-mail follow-up atau buat rencana tugas tindak lanjut otomatis saat tim memindahkan stage deals di kanban.</p>
                </div>
                
                <!-- Card 4 -->
                <div class="bg-[#fafbfc] border border-slate-200/60 p-8 rounded-2xl transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                    <div class="h-10 w-10 bg-indigo-50 border border-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 mb-6">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2.5">Pembayaran VA & QRIS</h3>
                    <p class="text-slate-500 text-xs sm:text-sm leading-relaxed font-medium">Integrasi tagihan paket secara real-time via Xendit API. Aktivasi akun instan setelah konfirmasi sukses dari bank atau e-wallet.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Interactive Live Demo Showcase Section -->
    <section id="demo" class="py-20 lg:py-28 bg-slate-50 border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
                <!-- Description -->
                <div class="lg:col-span-5">
                    <span class="text-xs font-semibold text-indigo-600 tracking-wider uppercase">Demo Antarmuka</span>
                    <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight mt-3 mb-6">Eksplorasi Kemudahan Manajemen Data</h2>
                    <p class="text-slate-500 leading-relaxed font-medium mb-8">
                        Klik tab di bawah ini untuk melihat bagaimana JavaCRM mengorganisir data modul utama Anda dengan tata letak yang bersih dan terstruktur.
                    </p>
                    
                    <!-- Tabs -->
                    <div class="space-y-3" id="demo-tabs-container">
                        <button onclick="switchDemoTab(1, this)" class="w-full text-left px-5 py-4 bg-white border-2 border-indigo-600 rounded-xl shadow-sm transition-all focus:outline-none flex items-center justify-between">
                            <div>
                                <h4 class="font-bold text-sm text-slate-900">Manajemen Leads & Deals</h4>
                                <p class="text-slate-500 text-xs mt-1 font-medium">Kanban board yang membagi prospek penjualan.</p>
                            </div>
                            <span class="text-indigo-600">→</span>
                        </button>
                        <button onclick="switchDemoTab(2, this)" class="w-full text-left px-5 py-4 bg-white border border-slate-200/80 rounded-xl transition-all focus:outline-none flex items-center justify-between hover:bg-slate-50">
                            <div>
                                <h4 class="font-bold text-sm text-slate-800">Manajemen Gudang & Produk</h4>
                                <p class="text-slate-500 text-xs mt-1 font-medium">Stok barang terintegrasi dengan penawaran harga.</p>
                            </div>
                            <span class="text-slate-400">→</span>
                        </button>
                        <button onclick="switchDemoTab(3, this)" class="w-full text-left px-5 py-4 bg-white border border-slate-200/80 rounded-xl transition-all focus:outline-none flex items-center justify-between hover:bg-slate-50">
                            <div>
                                <h4 class="font-bold text-sm text-slate-800">Template Email & Kampanye</h4>
                                <p class="text-slate-500 text-xs mt-1 font-medium">Format pesan tindak lanjut instan untuk tim sales.</p>
                            </div>
                            <span class="text-slate-400">→</span>
                        </button>
                    </div>
                </div>
                
                <!-- Display Mockup Screen -->
                <div class="lg:col-span-7 bg-white border border-slate-200 p-6 rounded-2xl shadow-xl shadow-slate-100/50 min-h-[360px] flex flex-col justify-between" id="demo-mockup-display">
                    <!-- Tab Content 1: Leads Kanban -->
                    <div id="tab-content-1" class="space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <span class="text-xs font-bold text-slate-800">Board Pipeline Penjualan</span>
                            <span class="text-[10px] bg-indigo-500/10 text-indigo-600 px-2 py-0.5 rounded-full font-bold">Aktif</span>
                        </div>
                        <div class="space-y-2.5">
                            <div class="flex items-center justify-between bg-slate-50 p-3 rounded-lg border border-slate-200/50">
                                <div>
                                    <div class="text-xs font-bold text-slate-800">PT Telkom Indonesia (Deal Pro)</div>
                                    <span class="text-[10px] text-slate-500 font-medium">Ditugaskan ke: Rian Kusuma</span>
                                </div>
                                <div class="text-right">
                                    <div class="text-xs font-bold text-indigo-600">Rp 120.000.000</div>
                                    <span class="text-[9px] bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded font-bold">Tahap: Proposal</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between bg-slate-50 p-3 rounded-lg border border-slate-200/50">
                                <div>
                                    <div class="text-xs font-bold text-slate-800">CV Inti Makmur (Deal Growth)</div>
                                    <span class="text-[10px] text-slate-500 font-medium">Ditugaskan ke: Sarah Siregar</span>
                                </div>
                                <div class="text-right">
                                    <div class="text-xs font-bold text-indigo-600">Rp 45.000.000</div>
                                    <span class="text-[9px] bg-indigo-100 text-indigo-700 px-1.5 py-0.5 rounded font-bold">Tahap: Negosiasi</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tab Content 2: Warehouses & Products (Hidden by default) -->
                    <div id="tab-content-2" class="space-y-4 hidden">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <span class="text-xs font-bold text-slate-800">Daftar Gudang & Stok Produk</span>
                            <span class="text-[10px] bg-indigo-500/10 text-indigo-600 px-2 py-0.5 rounded-full font-bold">Inventory</span>
                        </div>
                        <div class="space-y-2.5">
                            <div class="bg-slate-50 p-3 rounded-lg border border-slate-200/50 flex items-center justify-between">
                                <div>
                                    <div class="text-xs font-bold text-slate-800">Gudang Utama Jakarta (Cabang Barat)</div>
                                    <span class="text-[10px] text-slate-500 font-medium">Penanggung Jawab: Budi Santoso</span>
                                </div>
                                <div class="text-right">
                                    <div class="text-xs font-bold text-slate-700">12.450 unit</div>
                                    <span class="text-[9px] bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded font-bold">Normal</span>
                                </div>
                            </div>
                            <div class="bg-slate-50 p-3 rounded-lg border border-slate-200/50 flex items-center justify-between">
                                <div>
                                    <div class="text-xs font-bold text-slate-800">Produk A - Lisensi Paket Premium SaaS</div>
                                    <span class="text-[10px] text-slate-500 font-medium">Kategori: Cloud Software</span>
                                </div>
                                <div class="text-right">
                                    <div class="text-xs font-bold text-slate-700">Rp 12.500.000</div>
                                    <span class="text-[9px] bg-slate-200 text-slate-700 px-1.5 py-0.5 rounded font-bold">Ready</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Content 3: Email Templates (Hidden by default) -->
                    <div id="tab-content-3" class="space-y-4 hidden">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <span class="text-xs font-bold text-slate-800">Template Pesan Tindak Lanjut</span>
                            <span class="text-[10px] bg-indigo-500/10 text-indigo-600 px-2 py-0.5 rounded-full font-bold">Automated</span>
                        </div>
                        <div class="space-y-2.5">
                            <div class="bg-slate-50 p-3 rounded-lg border border-slate-200/50">
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-xs font-bold text-slate-800">Follow-up Penawaran Baru</span>
                                    <span class="text-[9px] bg-indigo-100 text-indigo-700 px-1.5 py-0.5 rounded font-bold">HTML</span>
                                </div>
                                <p class="text-[10px] text-slate-500 font-mono tracking-tight bg-white p-2 rounded border border-slate-150">
                                    Halo @{{ contact.name }}, terima kasih atas ketertarikan Anda terhadap produk @{{ product.name }} dari JavaCRM. Kami telah melampirkan proposal penawaran harga Anda...
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Foot details -->
                    <div class="border-t border-slate-100 pt-3 flex items-center justify-between text-[10px] text-slate-400 font-semibold">
                        <span>JavaCRM Platform Mockup</span>
                        <span>Update 1 menit yang lalu</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Key Advantages (Professional Proof) -->
    <section id="keunggulan" class="py-20 lg:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <!-- Left Details -->
                <div>
                    <span class="text-xs font-semibold text-indigo-600 tracking-wider uppercase">Standar Profesional</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mt-3 mb-6">Mengapa JavaCRM Dipilih Untuk Skala Korporasi?</h2>
                    <p class="text-slate-500 leading-relaxed font-medium mb-8">
                        Kami memisahkan data tenant secara logis untuk menjamin kepatuhan regulasi privasi data lokal, serta mengintegrasikan metode pembayaran VA & e-wallet native di dalam platform demi kemudahan penagihan.
                    </p>
                    
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <div class="h-9 w-9 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 text-sm font-bold">1</div>
                            <div>
                                <h4 class="text-base font-bold text-slate-900 mb-1">Mata Uang Fleksibel</h4>
                                <p class="text-slate-500 text-xs sm:text-sm leading-relaxed font-medium">Buat proposal penawaran (Quotes) dalam Rupiah atau valuta asing dengan konversi nilai tukar real-time.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="h-9 w-9 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 text-sm font-bold">2</div>
                            <div>
                                <h4 class="text-base font-bold text-slate-900 mb-1">Pemberlakuan Batasan Quota</h4>
                                <p class="text-slate-500 text-xs sm:text-sm leading-relaxed font-medium">Pencegahan otomatis kelebihan kuota penambahan pengguna, leads baru, dan kapasitas unggahan file media.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="h-9 w-9 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 text-sm font-bold">3</div>
                            <div>
                                <h4 class="text-base font-bold text-slate-900 mb-1">SaaS Berlangganan Aman</h4>
                                <p class="text-slate-500 text-xs sm:text-sm leading-relaxed font-medium">Ubah paket Anda secara fleksibel saat operasional tim penjualan berkembang. Proses migrasi kuota terjadi secara otomatis.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Box Mockup -->
                <div class="relative bg-slate-50 border border-slate-200/80 p-8 rounded-2xl shadow-sm">
                    <h3 class="text-xl font-bold text-slate-900 mb-3 font-display">Isolasi Data Aman</h3>
                    <p class="text-slate-500 text-xs sm:text-sm leading-relaxed font-medium mb-6">
                        Desain multi-tenancy JavaCRM tidak menggunakan tabel terpisah untuk setiap perusahaan demi efisiensi query, namun mengisolasi kueri sql secara logis melalui *global scopes* model Eloquent.
                    </p>
                    <div class="border-t border-slate-200/50 pt-6">
                        <div class="flex items-center justify-between text-xs font-bold text-slate-900 mb-2.5">
                            <span>Kepatuhan Isolasi Kueri SQL</span>
                            <span class="text-indigo-600 font-mono">CompanyScope Active</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-1.5">
                            <div class="bg-indigo-600 h-1.5 rounded-full w-full"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="harga" class="bg-slate-50 border-y border-slate-150 py-20 lg:py-28">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <span class="text-xs font-semibold text-indigo-600 tracking-wider uppercase font-display">Struktur Tarif</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mt-3">Investasi Jelas & Transparan untuk Tim Sales Anda</h2>
                <p class="mt-4 text-slate-500 font-medium leading-relaxed">Pilih paket yang paling sesuai dengan kapasitas departemen penjualan Anda.</p>
            </div>

            <!-- Currency Selector -->
            <div class="flex justify-center items-center gap-2.5 mb-16">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Mata Uang Tampilan:</span>
                <select id="currency-selector" onchange="convertPrices(this.value)" class="bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="USD">USD ($)</option>
                    <option value="IDR" selected>IDR (Rp)</option>
                    <option value="EUR">EUR (€)</option>
                    <option value="SGD">SGD (S$)</option>
                </select>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto items-stretch">
                
                @php
                    $displayPlans = $plans->isEmpty() ? collect([
                        (object)[
                            'name' => 'Starter',
                            'code' => 'free',
                            'price' => 0.0,
                            'description' => 'Sempurna untuk mengeksplorasi modul inti JavaCRM.',
                            'max_users' => 1,
                            'max_leads' => 500,
                            'max_storage_mb' => 500,
                        ],
                        (object)[
                            'name' => 'Growth',
                            'code' => 'pro',
                            'price' => 29.0,
                            'description' => 'Alat canggih untuk mengelola prospek dan tim penjualan berkembang.',
                            'max_users' => 10,
                            'max_leads' => 10000,
                            'max_storage_mb' => 5000,
                        ],
                        (object)[
                            'name' => 'Scale',
                            'code' => 'enterprise',
                            'price' => 99.0,
                            'description' => 'Kontrol total dan integrasi khusus untuk organisasi berskala besar.',
                            'max_users' => 100,
                            'max_leads' => 100000,
                            'max_storage_mb' => 20000,
                        ],
                    ]) : $plans;
                @endphp

                @foreach ($displayPlans as $plan)
                    @php
                        $isPro = strtolower($plan->name) === 'growth' || strtolower($plan->name) === 'pro';
                        $cardBg = $isPro ? 'bg-white border-2 border-indigo-600 shadow-lg' : 'bg-white border border-slate-200';
                        $btnStyle = $isPro ? 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-md' : 'bg-slate-900 hover:bg-slate-800 text-white shadow-sm';
                    @endphp

                    <div class="relative p-8 rounded-2xl flex flex-col justify-between transition-all duration-300 hover:shadow-xl {{ $cardBg }}">
                        @if ($isPro)
                            <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-indigo-600 text-white text-[9px] font-bold px-3.5 py-1 rounded-full uppercase tracking-wider">Rekomendasi</span>
                        @endif

                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider {{ $isPro ? 'text-indigo-600' : 'text-slate-400' }}">{{ $plan->name }}</span>
                            <h3 class="text-xl font-bold text-slate-800 mt-1">{{ $plan->name }} Plan</h3>
                            
                            <div class="mt-4 flex items-baseline border-b border-slate-100 pb-5">
                                <span class="text-3xl font-extrabold text-slate-900 tracking-tight font-display plan-price" data-usd-price="{{ $plan->price }}">
                                    Rp {{ number_format($plan->price * 16000, 0, ',', '.') }}
                                </span>
                                <span class="text-slate-400 text-xs ml-1 font-semibold">/bulan</span>
                            </div>
                            
                            <p class="mt-4 text-xs sm:text-sm text-slate-500 font-medium leading-relaxed">{{ $plan->description }}</p>
                            
                            <!-- Features -->
                            <ul class="mt-6 space-y-3 text-xs sm:text-sm font-medium text-slate-600">
                                <li class="flex items-start gap-2.5">
                                    <span class="text-emerald-500 font-bold shrink-0">✓</span>
                                    <span>Batas {{ $plan->max_leads >= 100000 ? 'Tanpa Batas' : number_format($plan->max_leads, 0, ',', '.') }} Leads</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <span class="text-emerald-500 font-bold shrink-0">✓</span>
                                    <span>Hingga {{ $plan->max_users }} Akun Pengguna</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <span class="text-emerald-500 font-bold shrink-0">✓</span>
                                    <span>Storage {{ $plan->max_storage_mb >= 1000 ? ($plan->max_storage_mb / 1000) . ' GB' : $plan->max_storage_mb . ' MB' }}</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <span class="text-emerald-500 font-bold shrink-0">✓</span>
                                    <span>Dukungan Tim Sales</span>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="mt-8">
                            <a href="{{ route('tenant.register.step1', ['plan' => strtolower($plan->code)]) }}" class="block w-full text-center font-bold py-3 px-4 rounded-lg text-sm transition-all {{ $btnStyle }}">
                                Pilih Paket {{ $plan->name }}
                            </a>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-20 lg:py-28 bg-white border-b border-slate-100">
        <div class="max-w-3xl mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-xs font-semibold text-indigo-600 tracking-wider uppercase">Tanya Jawab</span>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight mt-3">FAQ JavaCRM</h2>
                <p class="mt-4 text-slate-500 font-medium">Jawaban ringkas terkait model modular SaaS dan alur pendaftaran tenant.</p>
            </div>
            
            <div class="space-y-4">
                <!-- Item 1 -->
                <details class="group border border-slate-200 rounded-xl p-5 [&_summary::-webkit-details-marker]:hidden cursor-pointer transition-all hover:border-slate-300">
                    <summary class="flex items-center justify-between text-slate-900 focus:outline-none">
                        <h3 class="text-sm sm:text-base font-bold">Bagaimana isolasi data tim pada model multi-tenant?</h3>
                        <span class="text-slate-400 group-open:rotate-180 transition-transform">▼</span>
                    </summary>
                    <p class="mt-3 text-xs sm:text-sm text-slate-500 leading-relaxed font-medium">
                        Setiap tenant (perusahaan) memiliki data penyimpanan yang terpisah. Eloquent Global Scope membatasi kueri sql agar secara otomatis memfilter data berdasarkan `company_id` dari administrator tenant yang terautentikasi.
                    </p>
                </details>
                
                <!-- Item 2 -->
                <details class="group border border-slate-200 rounded-xl p-5 [&_summary::-webkit-details-marker]:hidden cursor-pointer transition-all hover:border-slate-300">
                    <summary class="flex items-center justify-between text-slate-900 focus:outline-none">
                        <h3 class="text-sm sm:text-base font-bold">Bagaimana proses integrasi pembayaran Xendit terjadi?</h3>
                        <span class="text-slate-400 group-open:rotate-180 transition-transform">▼</span>
                    </summary>
                    <p class="mt-3 text-xs sm:text-sm text-slate-500 leading-relaxed font-medium">
                        Saat mendaftar di Step 3, Anda memilih metode pembayaran. Sistem langsung mengirim request ke Xendit V2 Payment Request API. Webhook callback dari Xendit akan langsung mengaktifkan tabel `subscriptions` saat pembayaran terkonfirmasi lunas.
                    </p>
                </details>

                <!-- Item 3 -->
                <details class="group border border-slate-200 rounded-xl p-5 [&_summary::-webkit-details-marker]:hidden cursor-pointer transition-all hover:border-slate-300">
                    <summary class="flex items-center justify-between text-slate-900 focus:outline-none">
                        <h3 class="text-sm sm:text-base font-bold">Apakah batas kuota leads memblokir data masuk?</h3>
                        <span class="text-slate-400 group-open:rotate-180 transition-transform">▼</span>
                    </summary>
                    <p class="mt-3 text-xs sm:text-sm text-slate-500 leading-relaxed font-medium">
                        Ya. Middleware pembatas kuota kami akan mendeteksi penambahan prospek, jika prospek melebihi kuota plan langganan, request store akan dialihkan kembali dengan status flash error berisi pesan kuota penuh.
                    </p>
                </details>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-16 border-t border-slate-800 text-xs sm:text-sm">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="md:col-span-2">
                <div class="flex items-center gap-3 mb-6">
                    <div class="h-8 w-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                        <svg class="h-4.5 w-4.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span class="text-lg font-bold tracking-tight text-white font-display">Java<span class="text-indigo-500">CRM</span></span>
                </div>
                <p class="text-slate-500 text-xs font-medium leading-relaxed max-w-sm">
                    Platform SaaS CRM Multi-Tenant premium yang menyederhanakan pelacakan deal penjualan dan mengamankan data prospek secara terintegrasi.
                </p>
            </div>
            
            <div>
                <h4 class="text-white font-bold mb-4">Navigasi</h4>
                <ul class="space-y-2 font-medium text-slate-500 text-xs">
                    <li><a href="#fitur" class="hover:text-white transition-colors">Fitur Utama</a></li>
                    <li><a href="#demo" class="hover:text-white transition-colors">Demo Papan</a></li>
                    <li><a href="#harga" class="hover:text-white transition-colors">Harga Paket</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold mb-4">Perusahaan</h4>
                <ul class="space-y-2 font-medium text-slate-500 text-xs">
                    <li><span class="text-slate-600">Javatekno Mitra Solusi</span></li>
                    <li><a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a></li>
                </ul>
            </div>
        </div>
        
        <div class="max-w-7xl mx-auto px-6 border-t border-slate-800 mt-12 pt-8 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-slate-600">
            <p>&copy; 2026 JavaCRM Inc. Semua hak dilindungi.</p>
            <p>Xendit payment partnership.</p>
        </div>
    </footer>

    <!-- Interactive JS Tab Switcher & Currency Selector -->
    <script>
        function switchDemoTab(tabId, btnElement) {
            // Remove active border/color classes from all tabs
            const tabsContainer = document.getElementById('demo-tabs-container');
            const buttons = tabsContainer.getElementsByTagName('button');
            for (let i = 0; i < buttons.length; i++) {
                buttons[i].classList.remove('border-indigo-600', 'border-2');
                buttons[i].classList.add('border-slate-200/80', 'border');
                buttons[i].getElementsByTagName('h4')[0].classList.remove('text-indigo-600');
                buttons[i].getElementsByTagName('h4')[0].classList.add('text-slate-800');
                buttons[i].getElementsByTagName('span')[0].classList.remove('text-indigo-600');
                buttons[i].getElementsByTagName('span')[0].classList.add('text-slate-400');
            }

            // Set current tab button as active
            btnElement.classList.remove('border-slate-200/80', 'border');
            btnElement.classList.add('border-indigo-600', 'border-2');
            btnElement.getElementsByTagName('h4')[0].classList.remove('text-slate-800');
            btnElement.getElementsByTagName('h4')[0].classList.add('text-indigo-600');
            btnElement.getElementsByTagName('span')[0].classList.remove('text-slate-400');
            btnElement.getElementsByTagName('span')[0].classList.add('text-indigo-600');

            // Toggle Tab Content inside Display Screen
            const displayContainer = document.getElementById('demo-mockup-display');
            const contents = displayContainer.querySelectorAll('[id^="tab-content-"]');
            contents.forEach(content => {
                content.classList.add('hidden');
            });
            
            document.getElementById('tab-content-' + tabId).classList.remove('hidden');
        }

        // Currency rates conversion logic
        const currencySymbols = {
            USD: '$',
            IDR: 'Rp ',
            EUR: '€',
            SGD: 'S$'
        };

        const conversionRates = {
            USD: 1.0,
            IDR: 16000.0,
            EUR: 0.92,
            SGD: 1.35
        };

        function convertPrices(currency) {
            const elements = document.querySelectorAll('.plan-price');
            const symbol = currencySymbols[currency] || '$';
            const rate = conversionRates[currency] || 1.0;

            elements.forEach(el => {
                const usdPrice = parseFloat(el.getAttribute('data-usd-price'));
                const converted = usdPrice * rate;
                
                if (currency === 'IDR') {
                    el.textContent = symbol + converted.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
                } else {
                    el.textContent = symbol + converted.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 2 });
                }
            });
        }
    </script>

</body>
</html>
