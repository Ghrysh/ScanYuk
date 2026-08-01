@extends('layouts.app') 
@section('content')
<main class="bg-slate-50 min-h-screen pb-24">
    
    <div class="pt-24 pb-12 px-6 text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4 tracking-tight">
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-teal-500 to-indigo-500">Kebijakan</span>
            <span class="text-slate-900">Privasi</span>
        </h1>
        <p class="text-slate-500 text-base md:text-lg max-w-2xl mx-auto font-medium">
            Komitmen kami dalam melindungi data, aset, dan privasi Anda di platform ScanYuk.
        </p>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 space-y-5">

        {{-- Poin 1: Pengumpulan Data --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 shadow-sm flex flex-col md:flex-row items-start gap-5 hover:shadow-md hover:border-teal-200 transition-all">
            <div class="w-14 h-14 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
            </div>
            <div>
                <h3 class="text-[17px] font-bold text-slate-900 mb-2">Pengumpulan Data & Aset</h3>
                <p class="text-[15px] text-slate-500 leading-relaxed font-medium">
                    Kami mengumpulkan informasi akun (seperti nama dan email) serta aset yang Anda unggah secara sadar, termasuk gambar Marker, model 3D (.glb), dan file Blender (.blend) untuk keperluan pembuatan layanan Augmented Reality.
                </p>
            </div>
        </div>

        {{-- Poin 2: Penggunaan Informasi --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 shadow-sm flex flex-col md:flex-row items-start gap-5 hover:shadow-md hover:border-teal-200 transition-all">
            <div class="w-14 h-14 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                </svg>
            </div>
            <div>
                <h3 class="text-[17px] font-bold text-slate-900 mb-2">Penggunaan Aset AR</h3>
                <p class="text-[15px] text-slate-500 leading-relaxed font-medium">
                    Aset 3D dan Marker yang Anda unggah hanya digunakan semata-mata untuk di-render dan ditampilkan pada pemindai (scanner) AR Anda. Kami menggunakan sistem untuk memproses dan mengonversi (seperti .blend ke .glb) tanpa menggunakan file Anda untuk tujuan komersial pihak lain.
                </p>
            </div>
        </div>

        {{-- Poin 3: Analitik & Pelacakan Scan --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 shadow-sm flex flex-col md:flex-row items-start gap-5 hover:shadow-md hover:border-teal-200 transition-all">
            <div class="w-14 h-14 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                </svg>
            </div>
            <div>
                <h3 class="text-[17px] font-bold text-slate-900 mb-2">Pelacakan & Analitik QR</h3>
                <p class="text-[15px] text-slate-500 leading-relaxed font-medium">
                    Untuk memberikan fitur Dashboard Analitik, sistem kami melacak interaksi audiens pada QR Code AR Anda secara anonim, termasuk menghitung jumlah *scan*, jenis browser/perangkat, dan lokasi kasar pengunjung tanpa mengumpulkan data pribadi mereka.
                </p>
            </div>
        </div>

        {{-- Poin 4: Keterlibatan Pihak Ketiga --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 shadow-sm flex flex-col md:flex-row items-start gap-5 hover:shadow-md hover:border-teal-200 transition-all">
            <div class="w-14 h-14 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                </svg>
            </div>
            <div>
                <h3 class="text-[17px] font-bold text-slate-900 mb-2">Keterlibatan Pihak Ketiga</h3>
                <p class="text-[15px] text-slate-500 leading-relaxed font-medium">
                    Kami tidak akan pernah memperjualbelikan data maupun aset 3D Anda. Data hanya diteruskan secara aman ke mitra infrastruktur (hosting/VPS) untuk keperluan penyimpanan dan pemrosesan layanan, termasuk verifikasi bukti pembayaran manual Anda secara internal.
                </p>
            </div>
        </div>

        {{-- Poin 5: Kendali Pengguna --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 shadow-sm flex flex-col md:flex-row items-start gap-5 hover:shadow-md hover:border-teal-200 transition-all">
            <div class="w-14 h-14 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.036 18.036 0 00-.39-3.235m3.492 3.304a22.996 22.996 0 001.442 4.28c.268.579.977.78 1.528.461l.656-.38c.523-.302.71-.962.463-1.512a20.846 20.846 0 00-.985-2.783m-3.102-.07a18.03 18.03 0 01.39-3.235m-3.492-3.304c.168-.61.38-1.205.632-1.78.247-.563.06-1.222-.463-1.523l-.657-.38c-.551-.318-1.26-.117-1.527.461A20.844 20.844 0 007.41 7.28m3.102.068a18.035 18.035 0 01.39 3.235m3.492-3.304c-.168-.61-.38-1.205-.632-1.78-.247-.563-.06-1.222.463-1.523l.657-.38c.551-.318 1.26-.117 1.527.461a20.844 20.844 0 011.44 4.283m-3.102.068a18.035 18.035 0 00-.39 3.235" />
                </svg>
            </div>
            <div>
                <h3 class="text-[17px] font-bold text-slate-900 mb-2">Hak & Kendali Anda</h3>
                <p class="text-[15px] text-slate-500 leading-relaxed font-medium">
                    Anda memegang kendali penuh atas akun Anda. Kapan pun Anda menghapus Marker, File 3D, atau Project AR dari Dashboard, file tersebut akan secara permanen dihapus dari penyimpanan utama kami.
                </p>
            </div>
        </div>

    </div>

</main>
@endsection