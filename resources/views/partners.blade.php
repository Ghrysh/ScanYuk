@extends('layouts.app') @section('content')
<main class="bg-slate-50 min-h-screen pb-24">
    
    <div class="pt-24 pb-16 px-6 text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-5 tracking-tight">
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-teal-500 to-indigo-500">Kemitraan</span>
            <span class="text-slate-900">& Reseller</span>
        </h1>
        <p class="text-slate-500 text-base md:text-lg max-w-2xl mx-auto font-medium leading-relaxed">
            Platform ScanYuk dapat diskalakan dan dijual kembali. Bergabunglah<br class="hidden md:block"> sebagai mitra kami.
        </p>
    </div>

    <div class="max-w-[1100px] mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] hover:shadow-lg hover:border-teal-200 transition-all">
                <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.054-2.072.016-1.57.155-2.935.53-3.957 1.055-1.01.516-1.636 1.162-1.782 1.884-.138.685.292 1.488 1.15 2.14.857.653 2.158 1.158 3.633 1.446M10.34 15.84A8.963 8.963 0 0112 15a8.964 8.964 0 013.732 1.055M10.34 15.84L12 10.5m-3.957 6.395c-.382-.416-.62-1.026-.62-1.735 0-1.767 1.83-3.264 4.092-3.344M6.383 16.895A9.969 9.969 0 015.25 12c0-5.523 4.477-10 10-10s10 4.477 10 10-4.477 10-10 10c-1.895 0-3.666-.527-5.187-1.432M12 10.5c1.47 0 2.82.493 3.869 1.325m-3.869-1.325c-1.47 0-2.82.493-3.869 1.325" />
                    </svg>
                </div>
                <h3 class="text-[17px] font-bold text-slate-900 mb-3 tracking-tight">Agensi Pemasaran Digital</h3>
                <p class="text-sm text-slate-500 leading-relaxed font-medium">
                    Tawarkan AR QR sebagai layanan bernilai tambah ke klien Anda. Siap label putih.
                </p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] hover:shadow-lg hover:border-teal-200 transition-all">
                <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                    </svg>
                </div>
                <h3 class="text-[17px] font-bold text-slate-900 mb-3 tracking-tight">Penyelenggara Acara</h3>
                <p class="text-sm text-slate-500 leading-relaxed font-medium">
                    Jadikan setiap acara lebih interaktif dengan konten AR di semua materi cetak.
                </p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] hover:shadow-lg hover:border-teal-200 transition-all">
                <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                    </svg>
                </div>
                <h3 class="text-[17px] font-bold text-slate-900 mb-3 tracking-tight">Sekolah & Universitas</h3>
                <p class="text-sm text-slate-500 leading-relaxed font-medium">
                    Integrasi AR di materi ajar untuk pengalaman belajar yang lebih menarik.
                </p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] hover:shadow-lg hover:border-teal-200 transition-all">
                <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                    </svg>
                </div>
                <h3 class="text-[17px] font-bold text-slate-900 mb-3 tracking-tight">Museum & Galeri</h3>
                <p class="text-sm text-slate-500 leading-relaxed font-medium">
                    Pemandu AR untuk setiap koleksi — tingkatkan pengalaman pengunjung.
                </p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] hover:shadow-lg hover:border-teal-200 transition-all">
                <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" />
                    </svg>
                </div>
                <h3 class="text-[17px] font-bold text-slate-900 mb-3 tracking-tight">Konsultan TI / Reseller</h3>
                <p class="text-sm text-slate-500 leading-relaxed font-medium">
                    Jual kembali platform ScanYuk dengan harga Anda sendiri. Program kemitraan tersedia.
                </p>
            </div>

        </div>
    </div>

    <div class="flex justify-center pt-16 pb-8">
        <a href="#" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl bg-gradient-to-r from-teal-500 to-indigo-500 text-white text-sm font-bold shadow-[0_10px_25px_-5px_rgba(79,70,229,0.3)] hover:opacity-90 transition-all hover:-translate-y-0.5">
            Jadi Mitra
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
        </a>
    </div>

</main>
@endsection