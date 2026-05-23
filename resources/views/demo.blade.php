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
            Tidak perlu login. Scan QR code di bawah ini dengan kamera HP Anda dan rasakan pengalaman AR langsung.
        </p>

        <div class="bg-white rounded-3xl shadow-xl shadow-slate-300 border border-slate-100 p-8 md:p-10 inline-block mx-auto transform transition-transform duration-300 hover:scale-[1.02]">
            <div class="flex flex-col items-center">
                <div class="bg-white p-4 rounded-3xl shadow-lg shadow-teal-500/20 border-4 border-teal-100">
                    <div id="qrcode-demo"></div>
                </div>
            </div>
            <p class="text-slate-500 text-sm mt-6 font-medium flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-teal-500"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" /><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" /></svg>
                Arahkan kamera HP ke QR Code
            </p>
        </div>

    </div>

    <div class="relative z-10 max-w-5xl mx-auto mb-24">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-2xl border border-slate-200 text-center shadow-sm">
                <div class="w-16 h-16 mx-auto bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z" /></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">1. Scan QR</h3>
                <p class="text-slate-500 text-sm">Arahkan kamera ke QR code</p>
            </div>
            <div class="bg-white p-8 rounded-2xl border border-slate-200 text-center shadow-sm">
                <div class="w-16 h-16 mx-auto bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">2. AR Muncul</h3>
                <p class="text-slate-500 text-sm">Objek 3D tampil memukau di layar</p>
            </div>
            <div class="bg-white p-8 rounded-2xl border border-slate-200 text-center shadow-sm">
                <div class="w-16 h-16 mx-auto bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75z" /></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">3. Suara Bermain</h3>
                <p class="text-slate-500 text-sm">Narasi suara diputar otomatis bersama musik</p>
            </div>
        </div>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto mb-24">
        <div class="text-center mb-8">
            <span class="px-4 py-1.5 rounded-full bg-teal-100 text-teal-700 text-xs font-bold uppercase tracking-wider mb-4 inline-block">Pengenalan</span>
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900">Kenali ScanYuk Lebih Dekat</h2>
        </div>
        
        <div class="bg-white p-2 md:p-4 rounded-[2rem] shadow-2xl shadow-slate-200 border border-slate-100">
            <div class="rounded-3xl overflow-hidden bg-slate-900 aspect-video relative">
                <video controls preload="metadata" class="w-full h-full object-cover">
                    <source src="{{ asset('video/scanyuk-intro.mp4') }}" type="video/mp4">
                    Browser Anda tidak mendukung pemutaran video.
                </video>
            </div>
        </div>
    </div>

    <div class="relative z-10 max-w-3xl mx-auto mb-20" x-data="{ activeTab: null }">
        <div class="text-center mb-10">
            <span class="px-4 py-1.5 rounded-full bg-purple-100 text-purple-700 text-xs font-bold uppercase tracking-wider mb-4 inline-block">Tutorial</span>
            <h2 class="text-3xl font-bold text-slate-900 mb-4">Panduan Penggunaan</h2>
            <p class="text-slate-500">Ikuti langkah mudah berikut untuk mulai menggunakan ScanYuk.</p>
        </div>

        <div class="space-y-4">
            
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden transition-all duration-300" :class="activeTab === 1 ? 'shadow-lg border-teal-200 ring-1 ring-teal-500' : 'hover:border-slate-300'">
                <button @click="activeTab = activeTab === 1 ? null : 1" class="w-full px-6 py-5 flex items-center justify-between bg-transparent text-left focus:outline-none">
                    <div class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center font-bold">1</div>
                        <span class="font-bold text-slate-800 text-lg">Cara Membuat Akun</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-400 transition-transform duration-300" :class="activeTab === 1 ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                </button>
                <div x-show="activeTab === 1" x-collapse x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    <div class="p-6 pt-0 border-t border-slate-100 mt-2">
                        <video controls class="w-full rounded-xl bg-slate-900 border border-slate-200">
                            <source src="{{ asset('video/scanyuk-signup.mp4') }}" type="video/mp4">
                        </video>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden transition-all duration-300" :class="activeTab === 2 ? 'shadow-lg border-teal-200 ring-1 ring-teal-500' : 'hover:border-slate-300'">
                <button @click="activeTab = activeTab === 2 ? null : 2" class="w-full px-6 py-5 flex items-center justify-between bg-transparent text-left focus:outline-none">
                    <div class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center font-bold">2</div>
                        <span class="font-bold text-slate-800 text-lg">Cara Membeli Paket</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-400 transition-transform duration-300" :class="activeTab === 2 ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                </button>
                <div x-show="activeTab === 2" x-collapse x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    <div class="p-6 pt-0 border-t border-slate-100 mt-2">
                        <video controls class="w-full rounded-xl bg-slate-900 border border-slate-200">
                            <source src="{{ asset('video/scanyuk-payment.mp4') }}" type="video/mp4">
                        </video>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden transition-all duration-300" :class="activeTab === 3 ? 'shadow-lg border-teal-200 ring-1 ring-teal-500' : 'hover:border-slate-300'">
                <button @click="activeTab = activeTab === 3 ? null : 3" class="w-full px-6 py-5 flex items-center justify-between bg-transparent text-left focus:outline-none">
                    <div class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center font-bold">3</div>
                        <span class="font-bold text-slate-800 text-lg">Cara Membuat AR</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-400 transition-transform duration-300" :class="activeTab === 3 ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                </button>
                <div x-show="activeTab === 3" x-collapse x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    <div class="p-6 pt-0 border-t border-slate-100 mt-2">
                        <video controls class="w-full rounded-xl bg-slate-900 border border-slate-200">
                            <source src="{{ asset('video/scanyuk-create.mp4') }}" type="video/mp4">
                        </video>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden transition-all duration-300" :class="activeTab === 4 ? 'shadow-lg border-teal-200 ring-1 ring-teal-500' : 'hover:border-slate-300'">
                <button @click="activeTab = activeTab === 4 ? null : 4" class="w-full px-6 py-5 flex items-center justify-between bg-transparent text-left focus:outline-none">
                    <div class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center font-bold">4</div>
                        <span class="font-bold text-slate-800 text-lg">Cara Membuat Object Marker AR</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-400 transition-transform duration-300" :class="activeTab === 4 ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                </button>
                <div x-show="activeTab === 4" x-collapse x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    <div class="p-6 pt-0 border-t border-slate-100 mt-2">
                        <video controls class="w-full rounded-xl bg-slate-900 border border-slate-200">
                            <source src="{{ asset('video/scanyuk-marker.mp4') }}" type="video/mp4">
                        </video>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden transition-all duration-300" :class="activeTab === 5 ? 'shadow-lg border-teal-200 ring-1 ring-teal-500' : 'hover:border-slate-300'">
                <button @click="activeTab = activeTab === 5 ? null : 5" class="w-full px-6 py-5 flex items-center justify-between bg-transparent text-left focus:outline-none">
                    <div class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center font-bold">5</div>
                        <span class="font-bold text-slate-800 text-lg">Cara Melakukan Scan QR</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-400 transition-transform duration-300" :class="activeTab === 5 ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                </button>
                <div x-show="activeTab === 5" x-collapse x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    <div class="p-6 pt-0 border-t border-slate-100 mt-2">
                        <video controls class="w-full rounded-xl bg-slate-900 border border-slate-200">
                            <source src="{{ asset('video/scanyuk-scan.mp4') }}" type="video/mp4">
                        </video>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden transition-all duration-300" :class="activeTab === 6 ? 'shadow-lg border-teal-200 ring-1 ring-teal-500' : 'hover:border-slate-300'">
                <button @click="activeTab = activeTab === 6 ? null : 6" class="w-full px-6 py-5 flex items-center justify-between bg-transparent text-left focus:outline-none">
                    <div class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center font-bold">6</div>
                        <span class="font-bold text-slate-800 text-lg">Cara Melakukan Scan Object AR</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-400 transition-transform duration-300" :class="activeTab === 6 ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                </button>
                <div x-show="activeTab === 6" x-collapse x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    <div class="p-6 pt-0 border-t border-slate-100 mt-2">
                        <video controls class="w-full rounded-xl bg-slate-900 border border-slate-200">
                            <source src="{{ asset('video/scanyuk-scanMarker.mp4') }}" type="video/mp4">
                        </video>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto flex flex-col sm:flex-row items-center justify-center gap-4 mt-8 border-t border-slate-200 pt-12">
        <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 rounded-lg btn-gradient text-white font-bold shadow-lg shadow-indigo-200 hover:scale-105 transition-transform text-center">
            Mulai Buat AR Sekarang
        </a>
        <a href="{{ route('pricing') }}" class="w-full sm:w-auto px-8 py-4 rounded-lg bg-white text-teal-600 border border-teal-200 font-bold hover:bg-teal-50 transition-colors text-center">
            Lihat Harga
        </a>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>

    <script>
        const qrUrl = "{{ url('/scanner/' . $demoId) }}";

        document.addEventListener('DOMContentLoaded', function() {
            new QRCode(document.getElementById("qrcode-demo"), {
                text: qrUrl,
                width: 240,   
                height: 240,
                colorDark : "#0f172a", 
                colorLight : "#ffffff", 
                correctLevel : QRCode.CorrectLevel.H 
            });
        });
    </script>
</section>

@endsection