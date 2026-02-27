@extends('layouts.app')

@section('content')

<section class="w-full flex flex-col items-center justify-center px-6 text-center pt-24 pb-16 md:pt-32 md:pb-24 bg-gradient-to-b from-white to-slate-50/50 relative overflow-hidden">
    
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full max-w-4xl pointer-events-none z-0 opacity-40">
        <div class="absolute top-20 left-10 w-72 h-72 bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl animate-blob"></div>
        <div class="absolute top-20 right-10 w-72 h-72 bg-teal-100 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000"></div>
    </div>

    <div class="relative z-10 flex flex-col items-center">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white border border-slate-200 shadow-sm mb-8 animate-fade-in-up">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <span class="text-sm font-semibold text-slate-700">Solusi Enterprise</span>
        </div>

        <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight mb-6 text-slate-900 leading-tight max-w-4xl">
            AR QR Code untuk <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-500 to-indigo-600">Bisnis</span>
        </h1>

        <p class="text-lg md:text-xl text-slate-500 max-w-3xl leading-relaxed mb-10">
            Tingkatkan engagement pelanggan dengan augmented reality. Solusi scalable untuk retail, museum, edukasi, dan enterprise.
        </p>

        <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
            <a href="{{ route('contact') }}" class="w-full sm:w-auto px-8 py-4 rounded-lg btn-gradient text-white font-semibold text-base shadow-lg shadow-indigo-200 hover:scale-105 transition-transform duration-200">
                Minta Demo
            </a>
            
            <a href="{{ route('contact') }}" class="w-full sm:w-auto px-8 py-4 rounded-lg bg-white text-teal-600 border border-teal-200 font-semibold text-base hover:bg-teal-50 transition-colors">
                Hubungi Tim Penjualan
            </a>
        </div>
    </div>
</section>

<section class="w-full px-6 md:px-12 py-16 md:py-24 bg-slate-100">
    <div class="max-w-[90rem] mx-auto">
        
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">
                Contoh Penggunaan <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-500 to-indigo-600">Komersial</span>
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Ritel</h3>
                <p class="text-slate-500 text-sm leading-relaxed">Informasi produk AR, kemasan interaktif, pengalaman belanja di toko.</p>
            </div>

            <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Museum & Pariwisata</h3>
                <p class="text-slate-500 text-sm leading-relaxed">Pameran imersif, pemandu wisata AR, overlay historis.</p>
            </div>

            <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.499 5.24 50.534 50.534 0 00-2.658.813m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Pendidikan & Pelatihan</h3>
                <p class="text-slate-500 text-sm leading-relaxed">Buku teks interaktif, materi pelatihan AR, pembelajaran visual.</p>
            </div>

             <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Acara & Pameran</h3>
                <p class="text-slate-500 text-sm leading-relaxed">Tampilan booth AR, brosur acara, program interaktif.</p>
            </div>

            <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Profil Perusahaan</h3>
                <p class="text-slate-500 text-sm leading-relaxed">Profil perusahaan interaktif, kartu nama AR, laporan tahunan.</p>
            </div>

        </div>
    </div>
</section>

<section class="w-full px-6 md:px-12 py-16 md:py-24 bg-slate-50/50">
    <div class="max-w-[90rem] mx-auto">
        
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">
                Fitur Khusus <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-500 to-indigo-600">Komersial</span>
            </h2>
            <p class="text-slate-500 max-w-2xl mx-auto">
                Fitur enterprise-grade yang dirancang untuk skala bisnis Anda.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <div class="bg-white p-8 rounded-2xl border border-slate-200">
                <div class="w-10 h-10 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Scan Kustom / Tak Terbatas</h3>
                <p class="text-slate-500 text-sm">Tanpa batasan scan untuk kampanye enterprise.</p>
            </div>

            <div class="bg-white p-8 rounded-2xl border border-slate-200">
                <div class="w-10 h-10 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Dasbor Multi-Pengguna</h3>
                <p class="text-slate-500 text-sm">Manajemen tim dengan kontrol akses berbasis peran.</p>
            </div>

            <div class="bg-white p-8 rounded-2xl border border-slate-200">
                <div class="w-10 h-10 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Pembuatan QR Massal</h3>
                <p class="text-slate-500 text-sm">Buat ratusan QR Code dalam satu kampanye.</p>
            </div>

            <div class="bg-white p-8 rounded-2xl border border-slate-200">
                <div class="w-10 h-10 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Branding Kustom</h3>
                <p class="text-slate-500 text-sm">QR Code dengan logo dan warna perusahaan Anda.</p>
            </div>

            <div class="bg-white p-8 rounded-2xl border border-slate-200">
                <div class="w-10 h-10 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Analitik Lanjutan</h3>
                <p class="text-slate-500 text-sm">Analitik berbasis lokasi, waktu, dan ekspor laporan.</p>
            </div>

             <div class="bg-white p-8 rounded-2xl border border-slate-200">
                <div class="w-10 h-10 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.56.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.893.149c-.425.07-.765.383-.93.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 01-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.397.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 01-.12-1.45l.527-.737c.25-.35.273-.806.108-1.204-.165-.397-.505-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.107-1.204l-.527-.738a1.125 1.125 0 01.12-1.45l.773-.773a1.125 1.125 0 011.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">SLA & Dukungan Khusus</h3>
                <p class="text-slate-500 text-sm">Dukungan prioritas dengan jaminan waktu respons.</p>
            </div>

        </div>
    </div>
</section>

<section class="w-full px-6 py-20 bg-slate-50 border-t border-slate-200">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 mb-6">
            Siap Meningkatkan <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-500 to-indigo-600">Bisnis Anda</span>?
        </h2>
        <p class="text-lg text-slate-500 mb-10 leading-relaxed">
            Hubungi tim kami untuk mendapatkan demo dan penawaran khusus sesuai kebutuhan bisnis Anda.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('contact') }}" class="px-8 py-4 rounded-lg btn-gradient text-white font-semibold shadow-lg shadow-indigo-200 hover:scale-105 transition-transform">
                Minta Demo
            </a>
            <a href="{{ route('contact') }}" class="px-8 py-4 rounded-lg bg-white text-teal-600 border border-teal-200 font-semibold hover:bg-teal-50 transition-colors">
                Hubungi Tim Penjualan
            </a>
        </div>
    </div>
</section>

@endsection