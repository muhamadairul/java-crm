<!DOCTYPE html>
@php
    $currentLocale = session('locale', app()->getLocale());
    $isEn = $currentLocale === 'en';
@endphp
<html lang="{{ $currentLocale }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isEn ? 'JavaCRM - Integrated Customer Management & Sales Pipeline Platform' : 'JavaCRM - Aplikasi Manajemen Pelanggan & Pipeline Penjualan Terintegrasi' }}</title>
    <meta name="description" content="{{ $isEn ? 'JavaCRM is a centralized CRM application that empowers businesses to manage leads, track sales pipelines, automate price quotes, and evaluate sales team performance.' : 'JavaCRM adalah aplikasi CRM terpusat untuk membantu bisnis mengelola prospek, memantau pipeline penjualan, mengotomatiskan penawaran harga, dan mengukur kinerja tim sales.' }}">
    
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
        
        .glass-nav {
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            background-color: rgba(255, 255, 255, 0.88);
        }

        .dark .glass-nav {
            background-color: rgba(15, 23, 42, 0.88);
        }

        .hero-glow {
            background: radial-gradient(circle at 50% 0%, rgba(14, 144, 217, 0.12) 0%, rgba(248, 250, 252, 0) 70%);
        }

        .dark .hero-glow {
            background: radial-gradient(circle at 50% 0%, rgba(14, 144, 217, 0.2) 0%, rgba(15, 23, 42, 0) 70%);
        }

        .glow-card {
            box-shadow: 0 20px 40px -15px rgba(14, 144, 217, 0.15);
        }

        /* Micro-Animations & Interactivity */
        @keyframes floatSlow {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }

        @keyframes pulseGlow {
            0%, 100% { opacity: 0.6; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.05); }
        }

        .animate-float {
            animation: floatSlow 4s ease-in-out infinite;
        }

        .animate-glow {
            animation: pulseGlow 3s ease-in-out infinite;
        }

        /* Hover Card Lift */
        .interactive-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .interactive-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 30px -10px rgba(14, 144, 217, 0.2);
        }

        details summary::-webkit-details-marker {
            display: none;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased selection:bg-sky-500 selection:text-white dark:bg-slate-950 dark:text-slate-200">

    <!-- Header / Navigation Bar -->
    <header class="glass-nav sticky top-0 z-50 border-b border-slate-200/80 transition-all duration-300 dark:border-slate-800">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-6">
            <!-- Brand Logo -->
            <a href="{{ route('java-crm.home') }}" class="flex items-center gap-3 group">
                <img 
                    src="{{ request()->cookie('dark_mode') ? vite()->asset('images/dark-logo.svg') : vite()->asset('images/logo.svg') }}" 
                    class="h-10 w-auto transition-transform group-hover:scale-105" 
                    alt="JavaCRM Logo"
                />
            </a>
            
            <!-- Navigation Links -->
            <nav class="hidden items-center gap-8 text-sm font-semibold text-slate-600 md:flex dark:text-slate-300">
                <a href="#fitur" class="transition-colors hover:text-sky-600 dark:hover:text-sky-400">{{ $isEn ? 'Features' : 'Fitur CRM' }}</a>
                <a href="#solusi" class="transition-colors hover:text-sky-600 dark:hover:text-sky-400">{{ $isEn ? 'Solutions' : 'Solusi Bisnis' }}</a>
                <a href="#alur-kerja" class="transition-colors hover:text-sky-600 dark:hover:text-sky-400">{{ $isEn ? 'Workflow' : 'Alur Kerja' }}</a>
                <a href="#keamanan" class="transition-colors hover:text-sky-600 dark:hover:text-sky-400">{{ $isEn ? 'Security' : 'Keamanan' }}</a>
                <a href="#harga" class="transition-colors hover:text-sky-600 dark:hover:text-sky-400">{{ $isEn ? 'Pricing' : 'Harga' }}</a>
                <a href="#faq" class="transition-colors hover:text-sky-600 dark:hover:text-sky-400">FAQ</a>
            </nav>

            <!-- Action Buttons & Language Switcher -->
            <div class="flex items-center gap-3">
                <!-- Simple 1-Click Language Switcher Pill -->
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

                <a href="{{ route('admin.session.create') }}" class="rounded-xl px-4 py-2 text-sm font-bold text-slate-700 transition-colors hover:text-sky-600 dark:text-slate-300 dark:hover:text-sky-400">
                    {{ $isEn ? 'Sign In' : 'Masuk' }}
                </a>
                <a href="{{ route('tenant.register.step1') }}" class="inline-flex items-center gap-2 rounded-xl bg-sky-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-sky-600/25 transition-all hover:bg-sky-700 hover:shadow-sky-600/35 hover:scale-105">
                    <span>{{ $isEn ? 'Start Free Trial' : 'Mulai Uji Coba' }}</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-glow relative overflow-hidden px-6 pt-16 pb-20 lg:pt-24 lg:pb-32">
        <div class="mx-auto max-w-5xl text-center">
            <!-- Animated Badge -->
            <div class="mb-6 inline-flex items-center gap-2.5 rounded-full border border-sky-200 bg-sky-50/80 px-4 py-1.5 text-xs font-bold text-sky-700 shadow-sm dark:border-sky-900/50 dark:bg-sky-950/50 dark:text-sky-300">
                <span class="flex h-2.5 w-2.5 rounded-full bg-sky-500 animate-pulse"></span>
                <span>{{ $isEn ? '#1 Integrated Customer & Sales Management Platform' : 'Aplikasi Manajemen Pelanggan & Penjualan Terintegrasi' }}</span>
            </div>
            
            <!-- Main Headline -->
            <h1 class="mb-6 text-4xl font-extrabold tracking-tight text-slate-900 sm:text-5xl lg:text-6xl lg:leading-[1.15] dark:text-white">
                @if($isEn)
                    Manage Customer Relationships & <br class="hidden sm:inline">
                    Boost Your <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-600 to-blue-600">Business Sales</span>
                @else
                    Kelola Hubungan Pelanggan & <br class="hidden sm:inline">
                    Tingkatkan <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-600 to-blue-600">Penjualan Bisnis</span> Anda
                @endif
            </h1>
            
            <!-- Supporting Description -->
            <p class="mx-auto mb-10 max-w-3xl text-base font-medium leading-relaxed text-slate-600 sm:text-lg lg:text-xl dark:text-slate-400">
                {{ $isEn ? 'JavaCRM empowers businesses to monitor leads, accelerate deal cycles, automate price quotes, and evaluate sales team performance in real-time.' : 'JavaCRM hadir membantu bisnis memantau prospek (leads), mempercepat siklus transaksi, mengotomatiskan penawaran harga, dan memantau kinerja tim sales secara real-time.' }}
            </p>
            
            <!-- Action Buttons -->
            <div class="flex flex-col items-center justify-center gap-4 sm:flex-row">
                <a href="{{ route('tenant.register.step1') }}" class="flex w-full items-center justify-center gap-2.5 rounded-xl bg-sky-600 px-8 py-4 text-sm font-bold text-white shadow-xl shadow-sky-600/30 transition-all hover:bg-sky-700 hover:shadow-sky-600/40 hover:scale-105 sm:w-auto">
                    <span>{{ $isEn ? 'Start Free Trial Now' : 'Mulai Uji Coba Sekarang' }}</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
                <a href="#fitur" class="flex w-full items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-7 py-4 text-sm font-bold text-slate-700 shadow-sm transition-all hover:bg-slate-100 hover:border-sky-300 sm:w-auto dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
                    <svg class="h-4 w-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ $isEn ? 'Explore CRM Features' : 'Pelajari Fitur CRM' }}</span>
                </a>
            </div>
        </div>

        <!-- Interactive Animated Feature Cards -->
        <div class="mx-auto mt-16 max-w-6xl">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Interactive Card 1: Pipeline Kanban -->
                <div class="interactive-card rounded-2xl border border-slate-200/90 bg-white p-6 shadow-xl dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between mb-4">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-50 text-sky-600 font-bold dark:bg-sky-950 dark:text-sky-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </span>
                        <span class="text-xs bg-sky-50 text-sky-700 px-2.5 py-1 rounded-full font-bold dark:bg-sky-950 dark:text-sky-300">Live Kanban</span>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ $isEn ? 'Interactive Sales Pipeline' : 'Pipeline Sales Interaktif' }}</h3>
                    <p class="text-xs text-slate-500 mt-1 dark:text-slate-400">{{ $isEn ? 'Drag & drop deal stages (Lead → Proposal → Won Deal) seamlessly.' : 'Pindahkan stage transaksi (Prospek → Proposal → Deal Menang) dengan mudah.' }}</p>
                    <div class="mt-4 p-3 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-slate-800 space-y-2">
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-bold text-slate-800 dark:text-white">{{ $isEn ? 'Deal Tracker' : 'Pelacak Transaksi' }}</span>
                            <span class="text-sky-600 font-bold">{{ $isEn ? 'Deal Revenue' : 'Deal Omzet' }}</span>
                        </div>
                        <div class="w-full bg-slate-200 dark:bg-slate-800 h-2 rounded-full overflow-hidden">
                            <div class="bg-sky-500 h-full w-4/5 animate-pulse"></div>
                        </div>
                        <span class="text-[10px] text-slate-400 block text-right font-semibold">{{ $isEn ? 'Stage: Proposal Sent' : 'Status: Tahap Penawaran' }}</span>
                    </div>
                </div>

                <!-- Interactive Card 2: Contact Management -->
                <div class="interactive-card rounded-2xl border border-slate-200/90 bg-white p-6 shadow-xl dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between mb-4">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 font-bold dark:bg-emerald-950 dark:text-emerald-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </span>
                        <span class="text-xs bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-full font-bold dark:bg-emerald-950 dark:text-emerald-300">Client CRM</span>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ $isEn ? 'Centralized Customer Database' : 'Database Pelanggan Terpusat' }}</h3>
                    <p class="text-xs text-slate-500 mt-1 dark:text-slate-400">{{ $isEn ? 'Store contact profiles, meeting history, email notes, and deal logs.' : 'Simpan kontak, riwayat pertemuan, catatan email, dan transaksi klien.' }}</p>
                    <div class="mt-4 p-3 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-slate-800 space-y-2">
                        <div class="flex items-center gap-2 text-xs">
                            <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center text-[10px]">KL</div>
                            <div class="truncate">
                                <span class="font-bold block text-slate-800 dark:text-white">{{ $isEn ? 'Client Contact Profile' : 'Profil Kontak Client' }}</span>
                                <span class="text-[10px] text-slate-400">{{ $isEn ? 'Position • Customer Company' : 'Jabatan • Perusahaan Pelanggan' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Interactive Card 3: Instant Quotes PDF -->
                <div class="interactive-card rounded-2xl border border-slate-200/90 bg-white p-6 shadow-xl dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between mb-4">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600 font-bold dark:bg-violet-950 dark:text-violet-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </span>
                        <span class="text-xs bg-violet-50 text-violet-700 px-2.5 py-1 rounded-full font-bold dark:bg-violet-950 dark:text-violet-300">PDF Quotes</span>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ $isEn ? 'Price Quote PDF Automation' : 'Otomatisasi Penawaran PDF' }}</h3>
                    <p class="text-xs text-slate-500 mt-1 dark:text-slate-400">{{ $isEn ? 'Generate official price quote PDFs directly from your product catalog.' : 'Terbit surat penawaran harga resmi langsung dari katalog produk.' }}</p>
                    <div class="mt-4 p-3 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-slate-800 flex justify-between items-center text-xs">
                        <div class="flex items-center gap-2">
                            <span class="text-red-500 font-bold">PDF</span>
                            <span class="font-bold text-slate-800 dark:text-white">{{ $isEn ? 'Price_Quote_Document.pdf' : 'Surat_Penawaran_Harga.pdf' }}</span>
                        </div>
                        <span class="text-[10px] bg-emerald-50 text-emerald-600 font-bold px-2 py-0.5 rounded">{{ $isEn ? 'Sent' : 'Terkirim' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Key Business Metrics / Trust Section -->
    <section class="border-y border-slate-200 bg-white py-10 dark:border-slate-800 dark:bg-slate-900">
        <div class="mx-auto max-w-7xl px-6">
            <div class="grid grid-cols-2 gap-6 md:grid-cols-4">
                <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 transition-all hover:scale-105">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-sky-600 dark:bg-sky-950 dark:text-sky-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">{{ $isEn ? '100% Data Security' : 'Keamanan Data 100%' }}</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $isEn ? 'Customer Data Protected' : 'Data Pelanggan Terproteksi' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 transition-all hover:scale-105">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">{{ $isEn ? 'Pipeline Acceleration' : 'Percepatan Pipeline' }}</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $isEn ? 'Real-time Deal Tracking' : 'Pantau Deal Real-time' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 transition-all hover:scale-105">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-violet-600 dark:bg-violet-950 dark:text-violet-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">{{ $isEn ? 'Access Management' : 'Manajemen Hak Akses' }}</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $isEn ? 'Role-Based Access Control' : 'Otorisasi Berbasis Peran (RBAC)' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 transition-all hover:scale-105">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-600 dark:bg-amber-950 dark:text-amber-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">{{ $isEn ? 'PDF Quote Automation' : 'Otomatisasi PDF Quotes' }}</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $isEn ? 'Fast & Professional' : 'Cepat & Profesional' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Features Section -->
    <section id="fitur" class="py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mx-auto mb-16 max-w-3xl text-center">
                <span class="text-xs font-bold uppercase tracking-wider text-sky-600 dark:text-sky-400">{{ $isEn ? 'Key JavaCRM Modules' : 'Modul Unggulan JavaCRM' }}</span>
                <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl dark:text-white">
                    {{ $isEn ? 'All-in-One CRM Features in a Single App' : 'Semua Fitur CRM dalam Satu Aplikasi Terpusat' }}
                </h2>
                <p class="mt-4 text-base font-medium text-slate-600 dark:text-slate-400">
                    {{ $isEn ? 'JavaCRM is specifically built to empower sales teams to track leads, accelerate quotes, and accurately analyze business revenue.' : 'JavaCRM dirancang khusus untuk mempermudah tim penjualan memantau prospek, mempercepat penawaran, dan menganalisis omzet bisnis secara akurat.' }}
                </p>
            </div>

            <!-- 6 Feature Cards Grid -->
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                <!-- Feature 1: Pipeline & Leads -->
                <div class="interactive-card rounded-2xl border border-slate-200 bg-white p-8 dark:border-slate-800 dark:bg-slate-900">
                    <div class="mb-6 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-sky-50 text-sky-600 dark:bg-sky-950 dark:text-sky-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-slate-900 dark:text-white">{{ $isEn ? 'Leads Pipeline & Kanban' : 'Pipeline Prospek & Kanban' }}</h3>
                    <p class="text-sm leading-relaxed text-slate-600 dark:text-slate-400">
                        {{ $isEn ? 'Track deal progress from new leads to won transactions. Monitor revenue potential, deal priority, and lead sources.' : 'Lacak pergerakan transaksi dari prospek baru hingga deal dimenangkan. Pantau potensi omzet, prioritas deal, dan sumber prospek.' }}
                    </p>
                </div>

                <!-- Feature 2: Contacts & Organizations -->
                <div class="interactive-card rounded-2xl border border-slate-200 bg-white p-8 dark:border-slate-800 dark:bg-slate-900">
                    <div class="mb-6 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-slate-900 dark:text-white">{{ $isEn ? 'Contact & Client Management' : 'Manajemen Kontak & Client' }}</h3>
                    <p class="text-sm leading-relaxed text-slate-600 dark:text-slate-400">
                        {{ $isEn ? 'Manage individual and corporate client records. Access activity history, phone numbers, emails, and meeting notes.' : 'Kelola data perorangan dan perusahaan klien secara rapi. Akses riwayat aktivitas, nomor telepon, alamat email, dan catatan rapat.' }}
                    </p>
                </div>

                <!-- Feature 3: Quotes & Products -->
                <div class="interactive-card rounded-2xl border border-slate-200 bg-white p-8 dark:border-slate-800 dark:bg-slate-900">
                    <div class="mb-6 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950 dark:text-indigo-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-slate-900 dark:text-white">{{ $isEn ? 'Product Catalog & PDF Quotes' : 'Katalog Produk & Penawaran PDF' }}</h3>
                    <p class="text-sm leading-relaxed text-slate-600 dark:text-slate-400">
                        {{ $isEn ? 'Manage products, pricing, and discounts. Issue official PDF price quotes in just a few clicks.' : 'Kelola barang, harga, dan diskon. Terbitkan surat penawaran harga (Quotes) resmi berformat PDF dalam beberapa klik.' }}
                    </p>
                </div>

                <!-- Feature 4: Activities & Meetings -->
                <div class="interactive-card rounded-2xl border border-slate-200 bg-white p-8 dark:border-slate-800 dark:bg-slate-900">
                    <div class="mb-6 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600 dark:bg-amber-950 dark:text-amber-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-slate-900 dark:text-white">{{ $isEn ? 'Team Activity Scheduling' : 'Penjadwalan Aktivitas Tim' }}</h3>
                    <p class="text-sm leading-relaxed text-slate-600 dark:text-slate-400">
                        {{ $isEn ? 'Schedule meetings, phone calls, follow-up emails, and reminders so no deal falls through the cracks.' : 'Jadwalkan rapat, panggilan telepon, email follow-up, dan pengingat (reminder) agar tidak ada prospek yang terbengkalai.' }}
                    </p>
                </div>

                <!-- Feature 5: WebForms -->
                <div class="interactive-card rounded-2xl border border-slate-200 bg-white p-8 dark:border-slate-800 dark:bg-slate-900">
                    <div class="mb-6 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-violet-50 text-violet-600 dark:bg-violet-950 dark:text-violet-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-slate-900 dark:text-white">{{ $isEn ? 'Lead Capture WebForms' : 'WebForm Penangkapan Prospek' }}</h3>
                    <p class="text-sm leading-relaxed text-slate-600 dark:text-slate-400">
                        {{ $isEn ? 'Embed contact forms on your website to automatically capture prospective leads directly into the CRM pipeline.' : 'Pasang formulir pendaftaran di website Anda. Setiap data prospek baru yang diisi calon pelanggan otomatis masuk ke pipeline CRM.' }}
                    </p>
                </div>

                <!-- Feature 6: Dashboard Analytics -->
                <div class="interactive-card rounded-2xl border border-slate-200 bg-white p-8 dark:border-slate-800 dark:bg-slate-900">
                    <div class="mb-6 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-rose-50 text-rose-600 dark:bg-rose-950 dark:text-rose-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-slate-900 dark:text-white">{{ $isEn ? 'Sales Reports & Analytics' : 'Laporan & Analisis Penjualan' }}</h3>
                    <p class="text-sm leading-relaxed text-slate-600 dark:text-slate-400">
                        {{ $isEn ? 'Monitor revenue metrics, conversion rates, and sales quota achievements with interactive dashboard charts.' : 'Pantau metrik omzet, tingkat konversi transaksi, dan pencapaian kuota masing-masing anggota tim sales dalam grafik interaktif.' }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Business Solutions Section -->
    <section id="solusi" class="bg-white py-20 lg:py-28 dark:bg-slate-900">
        <div class="mx-auto max-w-7xl px-6">
            <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-2">
                <div>
                    <div class="mb-4 inline-flex items-center gap-2 rounded-full bg-sky-50 px-3.5 py-1 text-xs font-bold text-sky-700 dark:bg-sky-950 dark:text-sky-300">
                        <span>{{ $isEn ? 'Business Efficiency Solution' : 'Solusi Efisiensi Bisnis' }}</span>
                    </div>
                    <h2 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl dark:text-white">
                        {{ $isEn ? 'Boost Your Sales Team Productivity' : 'Tingkatkan Produktivitas Tim Penjualan Bisnis Anda' }}
                    </h2>
                    <p class="mt-4 text-base font-medium leading-relaxed text-slate-600 dark:text-slate-400">
                        {{ $isEn ? 'JavaCRM streamlines operations for sales representatives and management. All communications, price quotes, and contact records are safely stored in one central system.' : 'JavaCRM memberikan kemudahan kerja bagi sales representative dan manajemen perusahaan. Semua komunikasi, dokumen penawaran harga, dan data kontak tersimpan aman dalam satu sistem terpusat.' }}
                    </p>

                    <div class="mt-8 space-y-4">
                        <div class="flex items-start gap-3.5">
                            <div class="mt-1 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-sky-100 text-sky-600 dark:bg-sky-950 dark:text-sky-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900 dark:text-white">{{ $isEn ? 'Sales Pipeline Transparency' : 'Transparansi Pipeline Penjualan' }}</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $isEn ? 'Management gains full visibility over every deal status and expected revenue.' : 'Manajemen dapat melihat posisi setiap deal dan perkiraan nilai omzet mendatang.' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5">
                            <div class="mt-1 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-sky-100 text-sky-600 dark:bg-sky-950 dark:text-sky-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900 dark:text-white">{{ $isEn ? 'Faster Client Response Time' : 'Respons Lebih Cepat Kepada Klien' }}</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $isEn ? 'Issuing official PDF price quotes takes less than a minute.' : 'Penerbitan surat penawaran harga (Quotes PDF) hanya butuh waktu kurang dari 1 menit.' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5">
                            <div class="mt-1 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-sky-100 text-sky-600 dark:bg-sky-950 dark:text-sky-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900 dark:text-white">{{ $isEn ? 'Complete Interaction History' : 'Histori Interaksi Lengkap' }}</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $isEn ? 'Reassigned accounts preserve complete interaction records for seamless handovers.' : 'Setiap pergantian penanggung jawab sales tetap mempertahankan catatan histori klien.' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Interactive Solusi Card / Generic Illustrative Mockup -->
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6 shadow-xl dark:border-slate-800 dark:bg-slate-950 space-y-4">
                    <div class="flex items-center justify-between p-4 bg-white dark:bg-slate-900 rounded-xl border border-slate-100 dark:border-slate-800 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 font-bold flex items-center justify-center dark:bg-sky-950 dark:text-sky-400">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <h5 class="text-sm font-bold text-slate-900 dark:text-white">{{ $isEn ? 'Client Qualification Meeting' : 'Rapat Kualifikasi Klien' }}</h5>
                                <p class="text-xs text-slate-400">{{ $isEn ? 'Team Activity Schedule' : 'Penjadwalan Aktivitas Tim' }}</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold bg-amber-50 text-amber-600 px-3 py-1 rounded-full border border-amber-100 dark:bg-amber-950 dark:text-amber-300 dark:border-amber-900">Meeting</span>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-white dark:bg-slate-900 rounded-xl border border-slate-100 dark:border-slate-800 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 font-bold flex items-center justify-center dark:bg-emerald-950 dark:text-emerald-400">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <h5 class="text-sm font-bold text-slate-900 dark:text-white">{{ $isEn ? 'Price Quote Document (PDF)' : 'Surat Penawaran Harga (Quotes)' }}</h5>
                                <p class="text-xs text-slate-400">{{ $isEn ? 'Sent to Customer Email (PDF)' : 'Terkirim ke Email Pelanggan (PDF)' }}</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full border border-emerald-100 dark:bg-emerald-950 dark:text-emerald-300 dark:border-emerald-900">{{ $isEn ? 'Approved' : 'Disetujui' }}</span>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-white dark:bg-slate-900 rounded-xl border border-slate-100 dark:border-slate-800 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 font-bold flex items-center justify-center dark:bg-indigo-950 dark:text-indigo-400">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            </div>
                            <div>
                                <h5 class="text-sm font-bold text-slate-900 dark:text-white">{{ $isEn ? 'Won Deal (Closed Won)' : 'Deal Selesai (Closed Won)' }}</h5>
                                <p class="text-xs text-slate-400">{{ $isEn ? 'Status: Deal Successfully Won' : 'Status: Transaksi Dimenangkan' }}</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold bg-sky-50 text-sky-600 px-3 py-1 rounded-full border border-sky-100 dark:bg-sky-950 dark:text-sky-300 dark:border-sky-900">{{ $isEn ? 'Sales' : 'Penjualan' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Workflow Section -->
    <section id="alur-kerja" class="py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mx-auto mb-16 max-w-3xl text-center">
                <span class="text-xs font-bold uppercase tracking-wider text-sky-600 dark:text-sky-400">{{ $isEn ? 'Structured Workflow' : 'Alur Kerja Terstruktur' }}</span>
                <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl dark:text-white">
                    {{ $isEn ? '4 Steps to More Efficient Sales' : '4 Langkah Penjualan yang Lebih Efisien' }}
                </h2>
                <p class="mt-4 text-base font-medium text-slate-600 dark:text-slate-400">
                    {{ $isEn ? 'How JavaCRM guides your sales team systematically from capturing leads to closing revenue.' : 'Bagaimana JavaCRM mengarahkan tim sales Anda bekerja secara teratur dari penangkapan prospek hingga omzet tercapai.' }}
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
                <!-- Step 1 -->
                <div class="interactive-card relative rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-sky-600 font-extrabold text-white shadow-md shadow-sky-600/30">1</div>
                    <h3 class="mb-2 text-lg font-bold text-slate-900 dark:text-white">{{ $isEn ? 'Capture Leads' : 'Tangkap Prospek' }}</h3>
                    <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-400">
                        {{ $isEn ? 'Inbound leads arrive automatically from website WebForms or are entered manually by sales reps.' : 'Prospek masuk dari formulir kontak WebForm di website atau diinput manual oleh tim sales.' }}
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="interactive-card relative rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-sky-600 font-extrabold text-white shadow-md shadow-sky-600/30">2</div>
                    <h3 class="mb-2 text-lg font-bold text-slate-900 dark:text-white">{{ $isEn ? 'Qualification & Kanban' : 'Kualifikasi & Kanban' }}</h3>
                    <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-400">
                        {{ $isEn ? 'Determine deal value, lead sources, and move deal stages on the interactive Kanban board.' : 'Tentukan potensi deal, sumber prospek, dan pindahkan posisi deal pada papan Kanban interaktif.' }}
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="interactive-card relative rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-sky-600 font-extrabold text-white shadow-md shadow-sky-600/30">3</div>
                    <h3 class="mb-2 text-lg font-bold text-slate-900 dark:text-white">{{ $isEn ? 'Send Price Quotes' : 'Kirim Penawaran (Quotes)' }}</h3>
                    <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-400">
                        {{ $isEn ? 'Select products from the catalog, apply custom discounts, and generate official PDF quote documents.' : 'Pilih produk dari katalog, beri diskon, lalu terbitkan dokumen penawaran harga PDF profesional.' }}
                    </p>
                </div>

                <!-- Step 4 -->
                <div class="interactive-card relative rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-sky-600 font-extrabold text-white shadow-md shadow-sky-600/30">4</div>
                    <h3 class="mb-2 text-lg font-bold text-slate-900 dark:text-white">{{ $isEn ? 'Performance Analytics' : 'Analisis Kinerja' }}</h3>
                    <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-400">
                        {{ $isEn ? 'Close transactions as won deals and monitor revenue growth & individual sales quotas.' : 'Tutup transaksi sebagai deal menang dan pantau grafik omzet serta performa anggota tim sales Anda.' }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Security Section -->
    <section id="keamanan" class="py-20 lg:py-28 bg-white dark:bg-slate-900">
        <div class="mx-auto max-w-7xl px-6">
            <div class="rounded-3xl bg-slate-900 p-8 lg:p-14 text-white shadow-2xl relative overflow-hidden">
                <div class="grid grid-cols-1 gap-10 lg:grid-cols-2 items-center">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-sky-400">{{ $isEn ? 'Security & Reliability' : 'Keamanan & Keandalan' }}</span>
                        <h2 class="mt-2 text-3xl font-extrabold tracking-tight sm:text-4xl text-white">
                            {{ $isEn ? 'Guaranteed Business Data & Customer Privacy Protection' : 'Perlindungan Data Bisnis & Privasi Pelanggan Terjamin' }}
                        </h2>
                        <p class="mt-4 text-sm font-medium leading-relaxed text-slate-300">
                            {{ $isEn ? 'We understand how valuable customer records and business deals are. JavaCRM features encrypted credentials, Role-Based Access Control (RBAC), and team permission scoping.' : 'Kami memahami betapa berharganya data pelanggan dan catatan transaksi bisnis Anda. JavaCRM dilengkapi dengan pengamanan kredensial terenkripsi, kontrol izin peran (RBAC), serta pemisahan hak akses staf.' }}
                        </p>

                        <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-bold text-slate-200">
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-800/80 border border-slate-700">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-sky-500/20 text-sky-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                </div>
                                <span>{{ $isEn ? 'User Credential Encryption' : 'Enkripsi Kredensial User' }}</span>
                            </div>

                            <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-800/80 border border-slate-700">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-500/20 text-emerald-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                </div>
                                <span>{{ $isEn ? 'Team Access Control (RBAC)' : 'Kontrol Hak Akses Tim (RBAC)' }}</span>
                            </div>

                            <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-800/80 border border-slate-700">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-violet-500/20 text-violet-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/></svg>
                                </div>
                                <span>{{ $isEn ? 'Secure Client Data Isolation' : 'Isolasi Data Klien Aman' }}</span>
                            </div>

                            <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-800/80 border border-slate-700">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-500/20 text-amber-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <span>{{ $isEn ? 'Audit Logs & Activity Trail' : 'Audit Log & Histori Aktivitas' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-center">
                        <div class="rounded-2xl border border-slate-800 bg-slate-950 p-6 shadow-inner text-left space-y-4 w-full max-w-md">
                            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                                <span class="text-xs font-bold text-white uppercase tracking-wider">{{ $isEn ? 'User Roles & Permissions' : 'Peran & Otorisasi Pengguna' }}</span>
                                <span class="text-[10px] bg-emerald-950 text-emerald-300 border border-emerald-900 px-2 py-0.5 rounded font-bold">{{ $isEn ? 'Protected' : 'Terproteksi' }}</span>
                            </div>
                            
                            <div class="space-y-2 text-xs">
                                <div class="flex justify-between items-center p-2.5 rounded bg-slate-900 border border-slate-800">
                                    <span class="font-bold text-slate-200">{{ $isEn ? 'Company Admin' : 'Admin Perusahaan' }}</span>
                                    <span class="text-[10px] text-sky-400 font-semibold">{{ $isEn ? 'Full Access to All Modules' : 'Akses Penuh Seluruh Modul' }}</span>
                                </div>
                                <div class="flex justify-between items-center p-2.5 rounded bg-slate-900 border border-slate-800">
                                    <span class="font-bold text-slate-200">Sales Manager</span>
                                    <span class="text-[10px] text-emerald-400 font-semibold">{{ $isEn ? 'Manage Pipeline & Team' : 'Kelola Pipeline & Tim' }}</span>
                                </div>
                                <div class="flex justify-between items-center p-2.5 rounded bg-slate-900 border border-slate-800">
                                    <span class="font-bold text-slate-200">Sales Representative</span>
                                    <span class="text-[10px] text-amber-400 font-semibold">{{ $isEn ? 'Manage Assigned Leads' : 'Kelola Prospek Sendiri' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Dynamic Pricing Section -->
    <section id="harga" class="py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mx-auto mb-16 max-w-3xl text-center">
                <span class="text-xs font-bold uppercase tracking-wider text-sky-600 dark:text-sky-400">{{ $isEn ? 'Subscription Pricing Plans' : 'Pilihan Paket Langganan' }}</span>
                <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl dark:text-white">
                    {{ $isEn ? 'Transparent Pricing Plans Tailored to Your Business Scale' : 'Skema Harga Transparan Sesuai Skala Bisnis Anda' }}
                </h2>
                <p class="mt-4 text-base font-medium text-slate-600 dark:text-slate-400">
                    {{ $isEn ? 'Choose the best subscription plan suited for your sales team size and transaction capacity.' : 'Pilih paket yang paling sesuai dengan kebutuhan jumlah tim dan kapasitas transaksi perusahaan Anda.' }}
                </p>
            </div>

            <!-- Pricing Grid -->
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                @forelse($plans as $plan)
                    <div class="interactive-card relative flex flex-col justify-between rounded-2xl border border-slate-200 bg-white p-8 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        @if($loop->iteration === 2)
                            <div class="absolute -top-3 left-1/2 -translate-x-1/2 rounded-full bg-sky-600 px-4 py-1 text-[11px] font-extrabold uppercase tracking-wider text-white shadow-md">
                                {{ $isEn ? 'Most Popular' : 'Paling Populer' }}
                            </div>
                        @endif

                        <div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ $plan->name }}</h3>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $plan->description ?: ($isEn ? 'Complete subscription plan for your sales team.' : 'Paket langganan lengkap untuk tim bisnis Anda.') }}</p>
                            
                            <div class="mt-6 mb-6">
                                <span class="text-3xl font-extrabold text-slate-900 dark:text-white">Rp {{ number_format($plan->price, 0, ',', '.') }}</span>
                                <span class="text-xs font-semibold text-slate-400">/ {{ $plan->billing_cycle === 'yearly' ? ($isEn ? 'year' : 'tahun') : ($isEn ? 'month' : 'bulan') }}</span>
                            </div>

                            <ul class="space-y-3 border-t border-slate-200/80 pt-6 text-xs text-slate-600 dark:border-slate-800 dark:text-slate-300">
                                <li class="flex items-center gap-2.5">
                                    <svg class="h-4 w-4 text-sky-600 dark:text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <span>{{ $isEn ? 'Up to' : 'Maksimal' }} <strong>{{ $plan->max_users }} {{ $isEn ? 'Team Users' : 'User Tim' }}</strong></span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <svg class="h-4 w-4 text-sky-600 dark:text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <span>{{ $isEn ? 'Up to' : 'Kapasitas' }} <strong>{{ number_format($plan->max_leads) }} Leads</strong></span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <svg class="h-4 w-4 text-sky-600 dark:text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <span>Storage <strong>{{ number_format($plan->max_storage_mb) }} MB</strong></span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <svg class="h-4 w-4 text-sky-600 dark:text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <span>{{ $isEn ? 'Unlimited PDF Quotes' : 'Penawaran PDF Unlimited' }}</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <svg class="h-4 w-4 text-sky-600 dark:text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <span>{{ $isEn ? 'Lead Capture WebForms' : 'Form Penangkapan WebForms' }}</span>
                                </li>
                            </ul>
                        </div>

                        <div class="mt-8">
                            <a href="{{ route('tenant.register.step1', ['plan_id' => $plan->id]) }}" class="block w-full rounded-xl bg-slate-900 py-3 text-center text-xs font-bold text-white transition-all hover:bg-sky-600 shadow-md dark:bg-slate-800 dark:hover:bg-sky-600">
                                {{ $isEn ? 'Choose ' . $plan->name . ' Plan' : 'Pilih Paket ' . $plan->name }}
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 text-slate-400 italic">
                        {{ $isEn ? 'No active subscription plans configured yet.' : 'Belum ada paket langganan aktif yang dikonfigurasi.' }}
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- FAQ Accordion Section -->
    <section id="faq" class="bg-white py-20 lg:py-28 dark:bg-slate-900">
        <div class="mx-auto max-w-4xl px-6">
            <div class="mb-16 text-center">
                <span class="text-xs font-bold uppercase tracking-wider text-sky-600 dark:text-sky-400">{{ $isEn ? 'Frequently Asked Questions' : 'Tanya Jawab' }}</span>
                <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl dark:text-white">
                    {{ $isEn ? 'Frequently Asked Questions (FAQ)' : 'Pertanyaan yang Sering Diajukan (FAQ)' }}
                </h2>
            </div>

            <div class="space-y-4">
                <!-- FAQ 1 -->
                <details class="group rounded-2xl border border-slate-200 bg-slate-50/50 p-6 dark:border-slate-800 dark:bg-slate-950">
                    <summary class="flex cursor-pointer items-center justify-between font-bold text-slate-900 dark:text-white">
                        <span>{{ $isEn ? 'What is JavaCRM and how does it help my business?' : 'Apa itu JavaCRM dan bagaimana fungsinya untuk bisnis saya?' }}</span>
                        <span class="ml-4 shrink-0 transition-transform group-open:rotate-180 text-sky-600">↓</span>
                    </summary>
                    <p class="mt-4 text-xs leading-relaxed text-slate-600 dark:text-slate-400">
                        {{ $isEn ? 'JavaCRM is a customer relationship management (CRM) and sales pipeline application. It empowers your sales team to track leads, monitor transaction stages, generate price quotes (PDFs), and evaluate revenue performance in one centralized platform.' : 'JavaCRM adalah aplikasi manajemen hubungan pelanggan (CRM) dan pipeline penjualan. Aplikasi ini membantu tim sales bisnis Anda mengelola prospek, memantau posisi deal transaksi, menerbitkan dokumen penawaran harga (Quotes PDF), dan mengukur pencapaian omzet dalam satu sistem terpusat.' }}
                    </p>
                </details>

                <!-- FAQ 2 -->
                <details class="group rounded-2xl border border-slate-200 bg-slate-50/50 p-6 dark:border-slate-800 dark:bg-slate-950">
                    <summary class="flex cursor-pointer items-center justify-between font-bold text-slate-900 dark:text-white">
                        <span>{{ $isEn ? 'What sales features are included in JavaCRM?' : 'Fitur penjualan apa saja yang tersedia di JavaCRM?' }}</span>
                        <span class="ml-4 shrink-0 transition-transform group-open:rotate-180 text-sky-600">↓</span>
                    </summary>
                    <p class="mt-4 text-xs leading-relaxed text-slate-600 dark:text-slate-400">
                        {{ $isEn ? 'JavaCRM includes a Leads Pipeline Kanban board, Contact & Organization Management, Product Catalog, PDF Quote Generator, Team Activity & Meeting Scheduler, Lead Capture WebForms, and Sales Analytics Dashboards.' : 'JavaCRM mencakup papan Kanban Pipeline Prospek, Manajemen Kontak Client & Organisasi, Katalog Produk, Pembuatan Surat Penawaran Harga (Quotes PDF), Penjadwalan Aktivitas & Meeting, Form Penangkapan Prospek (WebForm), dan Dasbor Analytics Penjualan.' }}
                    </p>
                </details>

                <!-- FAQ 3 -->
                <details class="group rounded-2xl border border-slate-200 bg-slate-50/50 p-6 dark:border-slate-800 dark:bg-slate-950">
                    <summary class="flex cursor-pointer items-center justify-between font-bold text-slate-900 dark:text-white">
                        <span>{{ $isEn ? 'How do I add my sales team members?' : 'Bagaimana cara mendaftarkan tim sales perusahaan kami?' }}</span>
                        <span class="ml-4 shrink-0 transition-transform group-open:rotate-180 text-sky-600">↓</span>
                    </summary>
                    <p class="mt-4 text-xs leading-relaxed text-slate-600 dark:text-slate-400">
                        {{ $isEn ? 'After registering, Company Admins can add new sales team members from the User Management menu, assign roles, and grant permissions according to each staff member authority.' : 'Setelah mendaftar, Admin Perusahaan dapat menambahkan anggota tim sales baru dari menu Manajemen Pengguna, menentukan peran (Role), serta memberikan hak akses sesuai wewenang masing-masing staf.' }}
                    </p>
                </details>

                <!-- FAQ 4 -->
                <details class="group rounded-2xl border border-slate-200 bg-slate-50/50 p-6 dark:border-slate-800 dark:bg-slate-950">
                    <summary class="flex cursor-pointer items-center justify-between font-bold text-slate-900 dark:text-white">
                        <span>{{ $isEn ? 'Is our customer data secure in JavaCRM?' : 'Apakah data pelanggan bisnis kami aman tersimpan di JavaCRM?' }}</span>
                        <span class="ml-4 shrink-0 transition-transform group-open:rotate-180 text-sky-600">↓</span>
                    </summary>
                    <p class="mt-4 text-xs leading-relaxed text-slate-600 dark:text-slate-400">
                        {{ $isEn ? 'Absolutely. Each company record is isolated and protected by encryption and Role-Based Access Control (RBAC) authorization rules. Only authorized staff members can access specific records.' : 'Sangat aman. Setiap data perusahaan terisolasi penuh dan dilindungi oleh enkripsi serta sistem otorisasi Role-Based Access Control (RBAC). Hanya staf berwenang yang dapat mengakses data tertentu.' }}
                    </p>
                </details>
            </div>
        </div>
    </section>

    <!-- Final Call to Action -->
    <section class="py-20 lg:py-24 bg-gradient-to-br from-sky-600 to-blue-700 text-white text-center relative overflow-hidden">
        <div class="mx-auto max-w-4xl px-6 relative z-10">
            <h2 class="text-3xl font-extrabold tracking-tight sm:text-4xl lg:text-5xl text-white">
                {{ $isEn ? 'Take Your Sales Operations to the Next Level' : 'Bawa Operasi Penjualan Bisnis Anda ke Tingkat Berikutnya' }}
            </h2>
            <p class="mx-auto mt-4 max-w-2xl text-base font-medium text-sky-100">
                {{ $isEn ? 'Start managing leads and boosting your business sales conversion with JavaCRM today.' : 'Mulai kelola prospek dan tingkatkan konversi penjualan bisnis Anda dengan JavaCRM hari ini.' }}
            </p>
            <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
                <a href="{{ route('tenant.register.step1') }}" class="w-full rounded-xl bg-white px-8 py-4 text-sm font-bold text-sky-600 shadow-xl transition-all hover:bg-sky-50 hover:scale-105 sm:w-auto">
                    {{ $isEn ? 'Start Free Trial' : 'Mulai Uji Coba Gratis' }}
                </a>
                <a href="{{ route('admin.session.create') }}" class="w-full rounded-xl border border-sky-300/40 bg-sky-700/50 px-8 py-4 text-sm font-bold text-white transition-all hover:bg-sky-700 sm:w-auto">
                    {{ $isEn ? 'Sign In to Account' : 'Masuk ke Akun' }}
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-slate-200 bg-slate-900 py-14 text-slate-400 dark:border-slate-800">
        <div class="mx-auto max-w-7xl px-6">
            <div class="grid grid-cols-1 gap-10 md:grid-cols-4">
                <!-- Col 1: Brand -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <img 
                            src="{{ vite()->asset('images/dark-logo.svg') }}" 
                            class="h-9 w-auto" 
                            alt="JavaCRM Logo"
                        />
                    </div>
                    <p class="text-xs leading-relaxed text-slate-400">
                        {{ $isEn ? 'Centralized Customer Relationship Management (CRM) & Sales application empowering businesses to optimize revenue and sales team performance.' : 'Aplikasi Manajemen Hubungan Pelanggan (CRM) & Penjualan terpusat untuk membantu bisnis mengoptimalkan omzet dan performa tim sales.' }}
                    </p>
                </div>

                <!-- Col 2: Navigation -->
                <div>
                    <h4 class="mb-4 text-xs font-bold uppercase tracking-wider text-white">{{ $isEn ? 'Product & Features' : 'Produk & Fitur' }}</h4>
                    <ul class="space-y-2.5 text-xs">
                        <li><a href="#fitur" class="hover:text-white transition-colors">{{ $isEn ? 'Leads Pipeline' : 'Pipeline Prospek' }}</a></li>
                        <li><a href="#fitur" class="hover:text-white transition-colors">{{ $isEn ? 'Client Management' : 'Manajemen Client' }}</a></li>
                        <li><a href="#fitur" class="hover:text-white transition-colors">{{ $isEn ? 'Quotes & Products' : 'Quotes & Produk' }}</a></li>
                        <li><a href="#fitur" class="hover:text-white transition-colors">{{ $isEn ? 'Team Activity Log' : 'Log Aktivitas Tim' }}</a></li>
                    </ul>
                </div>

                <!-- Col 3: Business Solution -->
                <div>
                    <h4 class="mb-4 text-xs font-bold uppercase tracking-wider text-white">{{ $isEn ? 'CRM Solutions' : 'Solusi CRM' }}</h4>
                    <ul class="space-y-2.5 text-xs">
                        <li><a href="#solusi" class="hover:text-white transition-colors">{{ $isEn ? 'Sales Efficiency' : 'Efisiensi Sales' }}</a></li>
                        <li><a href="#keamanan" class="hover:text-white transition-colors">{{ $isEn ? 'Client Data Security' : 'Keamanan Data Client' }}</a></li>
                        <li><a href="#harga" class="hover:text-white transition-colors">{{ $isEn ? 'Subscription Plans' : 'Paket Langganan' }}</a></li>
                        <li><a href="{{ route('tenant.register.step1') }}" class="hover:text-white transition-colors">{{ $isEn ? 'Start Free Trial' : 'Mulai Uji Coba' }}</a></li>
                    </ul>
                </div>

                <!-- Col 4: Info -->
                <div>
                    <h4 class="mb-4 text-xs font-bold uppercase tracking-wider text-white">{{ $isEn ? 'Help & Support' : 'Bantuan' }}</h4>
                    <ul class="space-y-2.5 text-xs">
                        <li><a href="#faq" class="hover:text-white transition-colors">{{ $isEn ? 'FAQ & Help' : 'FAQ & Tanya Jawab' }}</a></li>
                        <li><a href="{{ route('admin.session.create') }}" class="hover:text-white transition-colors">{{ $isEn ? 'Sign In Portal' : 'Portal Login' }}</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 border-t border-slate-800 pt-6 text-center text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} JavaCRM. {{ $isEn ? 'All Rights Reserved.' : 'Hak Cipta Dilindungi Undang-Undang.' }}</p>
            </div>
        </div>
    </footer>

</body>
</html>
