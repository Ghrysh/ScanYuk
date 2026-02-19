@extends('layouts.app')

@section('content')

<section class="w-full pt-24 pb-12 md:pt-32 md:pb-16 px-6 bg-white text-center">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-6 tracking-tight">
            Cara Kerja <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-500 to-indigo-600">ScanYuk</span>
        </h1>
        <p class="text-lg md:text-xl text-slate-500 leading-relaxed max-w-2xl mx-auto">
            Hanya 4 langkah untuk membuat pengalaman AR QR code Anda sendiri.
        </p>
    </div>
</section>

<section class="w-full px-6 pb-24 bg-white">
    <div class="max-w-4xl mx-auto flex flex-col items-center gap-6">

        <div class="w-full bg-white border border-slate-200 rounded-2xl p-8 md:p-10 shadow-sm">
            <div class="flex flex-col md:flex-row gap-6">
                <div class="flex-shrink-0">
                    <div class="relative w-14 h-14 rounded-xl btn-gradient text-white flex items-center justify-center shadow-lg shadow-teal-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        <span class="absolute -bottom-6 text-[10px] font-bold text-teal-600 tracking-widest uppercase">01</span>
                    </div>
                </div>
                <div class="flex-grow">
                    <h3 class="text-2xl font-bold text-slate-900 mb-3">Upload Image</h3>
                    <p class="text-slate-500 mb-6">Upload gambar atau infografis yang akan menjadi konten AR Anda. Mendukung JPG dan PNG.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 bg-slate-100 rounded-xl border border-slate-100">
                            <span class="text-xs font-bold text-teal-600 flex items-center gap-1.5 mb-2 uppercase">👤 Consumer</span>
                            <p class="text-xs text-slate-600">Foto kenangan, undangan, poster.</p>
                        </div>
                        <div class="p-4 bg-slate-100 rounded-xl border border-slate-100">
                            <span class="text-xs font-bold text-indigo-600 flex items-center gap-1.5 mb-2 uppercase">🏢 Business</span>
                            <p class="text-xs text-slate-600">Product info, company profile, training material.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-teal-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
            </svg>
        </div>

        <div class="w-full bg-white border border-slate-200 rounded-2xl p-8 md:p-10 shadow-sm">
            <div class="flex flex-col md:flex-row gap-6">
                <div class="flex-shrink-0">
                    <div class="relative w-14 h-14 rounded-xl btn-gradient text-white flex items-center justify-center shadow-lg shadow-teal-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="absolute -bottom-6 text-[10px] font-bold text-teal-600 tracking-widest uppercase">02</span>
                    </div>
                </div>
                <div class="flex-grow">
                    <h3 class="text-2xl font-bold text-slate-900 mb-3">Write Narration</h3>
                    <p class="text-slate-500 mb-6">Tulis teks narasi yang akan dikonversi menjadi suara (TTS). Audio diputar otomatis saat scan.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 bg-slate-100 rounded-xl border border-slate-100">
                            <span class="text-xs font-bold text-teal-600 flex items-center gap-1.5 mb-2 uppercase">👤 Consumer</span>
                            <p class="text-xs text-slate-600">Pesan personal, cerita, penjelasan.</p>
                        </div>
                        <div class="p-4 bg-slate-100 rounded-xl border border-slate-100">
                            <span class="text-xs font-bold text-indigo-600 flex items-center gap-1.5 mb-2 uppercase">🏢 Business</span>
                            <p class="text-xs text-slate-600">Product description, guided tour, training script.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-teal-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
            </svg>
        </div>

        <div class="w-full bg-white border border-slate-200 rounded-2xl p-8 md:p-10 shadow-sm">
            <div class="flex flex-col md:flex-row gap-6">
                <div class="flex-shrink-0">
                    <div class="relative w-14 h-14 rounded-xl btn-gradient text-white flex items-center justify-center shadow-lg shadow-teal-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                        <span class="absolute -bottom-6 text-[10px] font-bold text-teal-600 tracking-widest uppercase">03</span>
                    </div>
                </div>
                <div class="flex-grow">
                    <h3 class="text-2xl font-bold text-slate-900 mb-3">Generate QR Code</h3>
                    <p class="text-slate-500 mb-6">Sistem otomatis membuat QR code unik yang terhubung ke konten AR Anda. Siap cetak.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 bg-slate-100 rounded-xl border border-slate-100">
                            <span class="text-xs font-bold text-teal-600 flex items-center gap-1.5 mb-2 uppercase">👤 Consumer</span>
                            <p class="text-xs text-slate-600">Download dan tempel di undangan/kado.</p>
                        </div>
                        <div class="p-4 bg-slate-100 rounded-xl border border-slate-100">
                            <span class="text-xs font-bold text-indigo-600 flex items-center gap-1.5 mb-2 uppercase">🏢 Business</span>
                            <p class="text-xs text-slate-600">Bulk generate untuk packaging/brochure.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-teal-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
            </svg>
        </div>

        <div class="w-full bg-white border border-slate-200 rounded-2xl p-8 md:p-10 shadow-sm">
            <div class="flex flex-col md:flex-row gap-6">
                <div class="flex-shrink-0">
                    <div class="relative w-14 h-14 rounded-xl btn-gradient text-white flex items-center justify-center shadow-lg shadow-teal-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <span class="absolute -bottom-6 text-[10px] font-bold text-teal-600 tracking-widest uppercase">04</span>
                    </div>
                </div>
                <div class="flex-grow">
                    <h3 class="text-2xl font-bold text-slate-900 mb-3">Scan → AR Appears</h3>
                    <p class="text-slate-500 mb-6">Saat QR code di-scan, konten AR muncul — gambar overlay dan narasi suara otomatis diputar.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 bg-slate-100 rounded-xl border border-slate-100">
                            <span class="text-xs font-bold text-teal-600 flex items-center gap-1.5 mb-2 uppercase">👤 Consumer</span>
                            <p class="text-xs text-slate-600">Tamu/penerima scan dan nikmati AR.</p>
                        </div>
                        <div class="p-4 bg-slate-100 rounded-xl border border-slate-100">
                            <span class="text-xs font-bold text-indigo-600 flex items-center gap-1.5 mb-2 uppercase">🏢 Business</span>
                            <p class="text-xs text-slate-600">Pelanggan scan dan lihat product AR.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-12">
            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 rounded-lg btn-gradient text-white font-bold text-base shadow-lg shadow-indigo-200 hover:scale-105 transition-transform duration-200">
                Mulai Sekarang
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>

    </div>
</section>

@endsection