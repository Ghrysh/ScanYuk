@extends('layouts.app')

@section('content')

<section class="w-full pt-24 py-20 md:pt-32 md:pb-24 px-6 bg-slate-50 relative overflow-hidden">
    
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full max-w-6xl pointer-events-none z-0 opacity-40">
        <div class="absolute top-20 left-10 w-96 h-96 bg-teal-100 rounded-full mix-blend-multiply filter blur-3xl animate-blob"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-purple-100 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000"></div>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto text-center mb-16">
        
        <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-6 tracking-tight">
            Coba <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-500 to-indigo-600">AR Demo</span>
        </h1>
        
        <p class="text-lg md:text-xl text-slate-500 leading-relaxed mb-10 max-w-2xl mx-auto">
            Tidak perlu login. Scan QR code di bawah ini dan rasakan pengalaman AR langsung.
        </p>

        <div class="bg-white rounded-3xl shadow-xl shadow-slate-300 border border-slate-100 p-8 md:p-10 inline-block mx-auto transform transition-transform duration-300">
            
            <div class="flex justify-center text-teal-500">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-qr-code w-40 h-40 md:w-56 md:h-56 mx-auto mb-4">
                    <rect width="5" height="5" x="3" y="3" rx="1"></rect>
                    <rect width="5" height="5" x="16" y="3" rx="1"></rect>
                    <rect width="5" height="5" x="3" y="16" rx="1"></rect>
                    <path d="M21 16h-3a2 2 0 0 0-2 2v3"></path>
                    <path d="M21 21v.01"></path>
                    <path d="M12 7v3a2 2 0 0 1-2 2H7"></path>
                    <path d="M3 12h.01"></path>
                    <path d="M12 3h.01"></path>
                    <path d="M12 16v.01"></path>
                    <path d="M16 12h1"></path>
                    <path d="M21 12v.01"></path>
                    <path d="M12 21v-1"></path>
                </svg>
            </div>

            <p class="text-slate-500 text-sm mt-4 font-medium">
                Scan QR code ini dengan kamera HP Anda
            </p>
        </div>

    </div>

    <div class="max-w-5xl mx-auto">
        
        <div class="text-center mb-12">
            <h2 class="text-2xl md:text-3xl font-bold text-slate-900">
                Apa yang terjadi saat Anda scan?
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <div class="bg-white p-8 rounded-2xl border border-slate-200 text-center hover:shadow-lg transition-shadow">
                <div class="w-16 h-16 mx-auto bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">1. Scan QR</h3>
                <p class="text-slate-500 text-sm">Arahkan kamera ke QR code</p>
            </div>

            <div class="bg-white p-8 rounded-2xl border border-slate-200 text-center hover:shadow-lg transition-shadow">
                <div class="w-16 h-16 mx-auto bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">2. AR Muncul</h3>
                <p class="text-slate-500 text-sm">Gambar/infografis tampil sebagai AR overlay</p>
            </div>

            <div class="bg-white p-8 rounded-2xl border border-slate-200 text-center hover:shadow-lg transition-shadow">
                <div class="w-16 h-16 mx-auto bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">3. Audio Plays</h3>
                <p class="text-slate-500 text-sm">Narasi suara diputar otomatis</p>
            </div>

        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-16">
            <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 rounded-lg btn-gradient text-white font-bold shadow-lg shadow-indigo-200 hover:scale-105 transition-transform text-center">
                Buat AR Anda Sendiri
            </a>
            <a href="{{ route('how-it-works') }}" class="w-full sm:w-auto px-8 py-4 rounded-lg bg-white text-teal-600 border border-teal-200 font-bold hover:bg-teal-50 transition-colors text-center">
                Lihat Cara Kerja
            </a>
        </div>

    </div>
</section>

@endsection