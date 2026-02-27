@extends('layouts.app') @section('content')
<main class="bg-slate-50 min-h-screen pb-24">
    
    <div class="pt-24 pb-12 px-6 text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4 tracking-tight">
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-teal-500 to-indigo-500">Keamanan</span>
            <span class="text-slate-900">& Kepatuhan</span>
        </h1>
        <p class="text-slate-500 text-base md:text-lg max-w-2xl mx-auto font-medium">
            Keamanan data dan konten Anda adalah prioritas utama kami.
        </p>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 space-y-5">

        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 shadow-sm flex flex-col md:flex-row items-start gap-5 hover:shadow-md hover:border-teal-200 transition-all">
            <div class="w-14 h-14 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
            </div>
            <div>
                <h3 class="text-[17px] font-bold text-slate-900 mb-2">Privasi Data</h3>
                <p class="text-[15px] text-slate-500 leading-relaxed font-medium">
                    Semua data pengguna dienkripsi dan disimpan sesuai standar keamanan industri. Kami tidak membagikan data ke pihak ketiga.
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 shadow-sm flex flex-col md:flex-row items-start gap-5 hover:shadow-md hover:border-teal-200 transition-all">
            <div class="w-14 h-14 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                </svg>
            </div>
            <div>
                <h3 class="text-[17px] font-bold text-slate-900 mb-2">Pembayaran Aman (iPaymu)</h3>
                <p class="text-[15px] text-slate-500 leading-relaxed font-medium">
                    Pembayaran diproses melalui iPaymu, payment gateway terpercaya di Indonesia dengan sertifikasi PCI DSS.
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 shadow-sm flex flex-col md:flex-row items-start gap-5 hover:shadow-md hover:border-teal-200 transition-all">
            <div class="w-14 h-14 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <h3 class="text-[17px] font-bold text-slate-900 mb-2">Kepemilikan Konten AR</h3>
                <p class="text-[15px] text-slate-500 leading-relaxed font-medium">
                    Anda memiliki 100% kepemilikan atas konten AR yang Anda buat. Kami tidak menggunakan konten Anda untuk tujuan lain.
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 shadow-sm flex flex-col md:flex-row items-start gap-5 hover:shadow-md hover:border-teal-200 transition-all">
            <div class="w-14 h-14 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                </svg>
            </div>
            <div>
                <h3 class="text-[17px] font-bold text-slate-900 mb-2">Perlindungan Scan QR</h3>
                <p class="text-[15px] text-slate-500 leading-relaxed font-medium">
                    Setiap QR Code memiliki batas scan dan pelacakan. Konten AR hanya bisa diakses melalui QR Code yang valid.
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 shadow-sm flex flex-col md:flex-row items-start gap-5 hover:shadow-md hover:border-teal-200 transition-all">
            <div class="w-14 h-14 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.737 5.1a3.375 3.375 0 012.7-1.35h7.126c1.062 0 2.062.5 2.7 1.35l2.587 3.45a4.5 4.5 0 01.9 2.7m0 0a3 3 0 01-3 3m0 3h.008v.008h-.008v-.008zm0-6h.008v.008h-.008v-.008zm-3 6h.008v.008h-.008v-.008zm0-6h.008v.008h-.008v-.008z" />
                </svg>
            </div>
            <div>
                <h3 class="text-[17px] font-bold text-slate-900 mb-2">Keamanan Infrastruktur</h3>
                <p class="text-[15px] text-slate-500 leading-relaxed font-medium">
                    Dihosting di infrastruktur cloud yang aman dengan uptime 99,9%. Pencadangan data otomatis setiap hari.
                </p>
            </div>
        </div>

    </div>

</main>
@endsection