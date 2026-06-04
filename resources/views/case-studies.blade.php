@extends('layouts.app') @section('content')
<main class="bg-slate-50 min-h-screen pb-24">
    
    <div class="pt-20 pb-12 px-6">
        <h1 class="text-4xl md:text-5xl font-extrabold text-center mb-4 tracking-tight">
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-teal-500 to-indigo-500">Studi Kasus</span>
        </h1>
        <p class="text-slate-500 text-center text-base md:text-lg max-w-2xl mx-auto font-medium">
            Lihat bagaimana ScanYuk digunakan di berbagai industri.
        </p>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 space-y-6">

        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start gap-4 mb-6">
                <div class="w-12 h-12 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-teal-600 uppercase tracking-widest">Pendidikan</span>
                    <h2 class="text-lg md:text-xl font-bold text-slate-900 mt-1">SD Harapan Bangsa — Buku AR Interaktif</h2>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
                <div>
                    <h3 class="text-[10px] font-bold text-rose-500 uppercase tracking-widest mb-2">Tantangan</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Siswa kelas 4-6 kesulitan memahami konsep sains dari buku teks biasa.
                    </p>
                </div>
                <div>
                    <h3 class="text-[10px] font-bold text-teal-500 uppercase tracking-widest mb-2">Solusi</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Setiap bab dilengkapi QR Code yang menampilkan diagram AR 3D dan narasi audio penjelasan.
                    </p>
                </div>
                <div>
                    <h3 class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest mb-2">Hasil</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Peningkatan keterlibatan siswa 40%. Guru melaporkan pemahaman materi lebih baik.
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap gap-2 mt-8">
                <span class="px-3 py-1.5 bg-slate-100 text-slate-500 text-xs font-medium rounded-full">200+ QR Code</span>
                <span class="px-3 py-1.5 bg-slate-100 text-slate-500 text-xs font-medium rounded-full">5.000+ scan/bulan</span>
                <span class="px-3 py-1.5 bg-slate-100 text-slate-500 text-xs font-medium rounded-full">12 buku AR</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start gap-4 mb-6">
                <div class="w-12 h-12 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-teal-600 uppercase tracking-widest">Ritel</span>
                    <h2 class="text-lg md:text-xl font-bold text-slate-900 mt-1">Merek Kosmetik Lokal — Pengalaman Produk AR</h2>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
                <div>
                    <h3 class="text-[10px] font-bold text-rose-500 uppercase tracking-widest mb-2">Tantangan</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Pelanggan di toko kesulitan memahami cara pakai dan kandungan produk baru.
                    </p>
                </div>
                <div>
                    <h3 class="text-[10px] font-bold text-teal-500 uppercase tracking-widest mb-2">Solusi</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        QR Code di setiap kemasan yang memunculkan tutorial AR dan info kandungan.
                    </p>
                </div>
                <div>
                    <h3 class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest mb-2">Hasil</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Peningkatan konversi 25%. Keterlibatan pelanggan naik signifikan.
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap gap-2 mt-8">
                <span class="px-3 py-1.5 bg-slate-100 text-slate-500 text-xs font-medium rounded-full">50 produk</span>
                <span class="px-3 py-1.5 bg-slate-100 text-slate-500 text-xs font-medium rounded-full">10.000+ scan</span>
                <span class="px-3 py-1.5 bg-slate-100 text-slate-500 text-xs font-medium rounded-full">3 bulan kampanye</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start gap-4 mb-6">
                <div class="w-12 h-12 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-teal-600 uppercase tracking-widest">Museum & Pariwisata</span>
                    <h2 class="text-lg md:text-xl font-bold text-slate-900 mt-1">Museum Nasional — Pemandu Wisata AR</h2>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
                <div>
                    <h3 class="text-[10px] font-bold text-rose-500 uppercase tracking-widest mb-2">Tantangan</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Pengunjung tidak mendapatkan konteks mendalam tentang artefak yang dipamerkan.
                    </p>
                </div>
                <div>
                    <h3 class="text-[10px] font-bold text-teal-500 uppercase tracking-widest mb-2">Solusi</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        QR Code di setiap artefak dengan overlay AR gambar historis dan narasi audio multibahasa.
                    </p>
                </div>
                <div>
                    <h3 class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest mb-2">Hasil</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Waktu kunjungan rata-rata naik 35%. Rating pengalaman pengunjung naik ke 4,8/5.
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap gap-2 mt-8">
                <span class="px-3 py-1.5 bg-slate-100 text-slate-500 text-xs font-medium rounded-full">150 artefak</span>
                <span class="px-3 py-1.5 bg-slate-100 text-slate-500 text-xs font-medium rounded-full">20.000+ scan</span>
                <span class="px-3 py-1.5 bg-slate-100 text-slate-500 text-xs font-medium rounded-full">3 bahasa</span>
            </div>
        </div>

        <div class="flex justify-center pt-8 pb-4">
            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl bg-gradient-to-r from-teal-500 to-indigo-500 text-white text-sm font-bold shadow-lg shadow-indigo-200 hover:opacity-90 transition-all hover:-translate-y-0.5">
                Diskusikan Proyek Anda
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
            </a>
        </div>

    </div>
</main>
@endsection