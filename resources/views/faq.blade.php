@extends('layouts.app') @section('content')
<main class="bg-slate-50 min-h-screen pb-24">
    
    <div class="pt-24 pb-12 px-6 text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4 tracking-tight">
            <span class="text-slate-900">Pertanyaan yang </span>
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-teal-500 via-indigo-500 to-purple-600">Sering Diajukan</span>
        </h1>
        <p class="text-slate-500 text-base md:text-lg max-w-2xl mx-auto font-medium">
            Temukan jawaban atas pertanyaan umum tentang ScanYuk.
        </p>
    </div>

    <div class="max-w-[850px] mx-auto px-4 sm:px-6" x-data="{ active: null }">
        <div class="space-y-4">

            <div class="bg-white rounded-2xl border transition-colors duration-300 cursor-pointer" 
                 :class="active === 1 ? 'border-teal-300 shadow-md' : 'border-slate-200 shadow-sm hover:border-teal-200'" 
                 @click="active = active === 1 ? null : 1">
                <div class="px-6 py-5 flex items-center justify-between gap-4">
                    <h3 class="font-semibold text-slate-900 text-[15px] md:text-base leading-snug" :class="active === 1 ? 'text-teal-700' : ''">
                        Apa itu ScanYuk?
                    </h3>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 flex-shrink-0 transition-transform duration-300" :class="active === 1 ? 'rotate-180 text-teal-500' : 'text-slate-400'">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </div>
                <div class="grid transition-all duration-300 ease-in-out px-6" :class="active === 1 ? 'grid-rows-[1fr] opacity-100 pb-5' : 'grid-rows-[0fr] opacity-0 pb-0'">
                    <div class="overflow-hidden">
                        <p class="text-sm md:text-[15px] text-slate-500 leading-relaxed border-t border-slate-100 pt-4">
                            ScanYuk adalah platform yang memungkinkan Anda membuat pengalaman Augmented Reality (AR) melalui QR Code dan pemindaian objek. Anda bisa mengunggah gambar, menulis narasi suara, dan membagikannya lewat QR Code.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border transition-colors duration-300 cursor-pointer" 
                 :class="active === 2 ? 'border-teal-300 shadow-md' : 'border-slate-200 shadow-sm hover:border-teal-200'" 
                 @click="active = active === 2 ? null : 2">
                <div class="px-6 py-5 flex items-center justify-between gap-4">
                    <h3 class="font-semibold text-slate-900 text-[15px] md:text-base leading-snug" :class="active === 2 ? 'text-teal-700' : ''">
                        Bagaimana cara membuat AR QR Code?
                    </h3>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 flex-shrink-0 transition-transform duration-300" :class="active === 2 ? 'rotate-180 text-teal-500' : 'text-slate-400'">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </div>
                <div class="grid transition-all duration-300 ease-in-out px-6" :class="active === 2 ? 'grid-rows-[1fr] opacity-100 pb-5' : 'grid-rows-[0fr] opacity-0 pb-0'">
                    <div class="overflow-hidden">
                        <p class="text-sm md:text-[15px] text-slate-500 leading-relaxed border-t border-slate-100 pt-4">
                            Cukup daftar akun, pilih paket, lalu unggah gambar dan tulis narasi. Sistem akan otomatis membuat QR Code unik yang terhubung ke konten AR Anda.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border transition-colors duration-300 cursor-pointer" 
                 :class="active === 3 ? 'border-teal-300 shadow-md' : 'border-slate-200 shadow-sm hover:border-teal-200'" 
                 @click="active = active === 3 ? null : 3">
                <div class="px-6 py-5 flex items-center justify-between gap-4">
                    <h3 class="font-semibold text-slate-900 text-[15px] md:text-base leading-snug" :class="active === 3 ? 'text-teal-700' : ''">
                        Apakah kuota saya akan kedaluwarsa?
                    </h3>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 flex-shrink-0 transition-transform duration-300" :class="active === 3 ? 'rotate-180 text-teal-500' : 'text-slate-400'">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </div>
                <div class="grid transition-all duration-300 ease-in-out px-6" :class="active === 3 ? 'grid-rows-[1fr] opacity-100 pb-5' : 'grid-rows-[0fr] opacity-0 pb-0'">
                    <div class="overflow-hidden">
                        <p class="text-sm md:text-[15px] text-slate-500 leading-relaxed border-t border-slate-100 pt-4">
                            Tidak. Kuota Anda tidak memiliki masa berlaku. Selama kuota belum digunakan, kuota akan tetap tersedia di akun Anda.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border transition-colors duration-300 cursor-pointer" 
                 :class="active === 4 ? 'border-teal-300 shadow-md' : 'border-slate-200 shadow-sm hover:border-teal-200'" 
                 @click="active = active === 4 ? null : 4">
                <div class="px-6 py-5 flex items-center justify-between gap-4">
                    <h3 class="font-semibold text-slate-900 text-[15px] md:text-base leading-snug" :class="active === 4 ? 'text-teal-700' : ''">
                        Apa perbedaan paket Consumer dan Business?
                    </h3>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 flex-shrink-0 transition-transform duration-300" :class="active === 4 ? 'rotate-180 text-teal-500' : 'text-slate-400'">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </div>
                <div class="grid transition-all duration-300 ease-in-out px-6" :class="active === 4 ? 'grid-rows-[1fr] opacity-100 pb-5' : 'grid-rows-[0fr] opacity-0 pb-0'">
                    <div class="overflow-hidden">
                        <p class="text-sm md:text-[15px] text-slate-500 leading-relaxed border-t border-slate-100 pt-4">
                            Paket Consumer ditujukan untuk kebutuhan personal seperti undangan, kado, dan album kenangan. Paket Business dirancang untuk kebutuhan korporasi dengan fitur seperti dashboard multi-pengguna, pembuatan QR massal, dan dukungan khusus.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border transition-colors duration-300 cursor-pointer" 
                 :class="active === 5 ? 'border-teal-300 shadow-md' : 'border-slate-200 shadow-sm hover:border-teal-200'" 
                 @click="active = active === 5 ? null : 5">
                <div class="px-6 py-5 flex items-center justify-between gap-4">
                    <h3 class="font-semibold text-slate-900 text-[15px] md:text-base leading-snug" :class="active === 5 ? 'text-teal-700' : ''">
                        Bagaimana cara melakukan pembayaran?
                    </h3>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 flex-shrink-0 transition-transform duration-300" :class="active === 5 ? 'rotate-180 text-teal-500' : 'text-slate-400'">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </div>
                <div class="grid transition-all duration-300 ease-in-out px-6" :class="active === 5 ? 'grid-rows-[1fr] opacity-100 pb-5' : 'grid-rows-[0fr] opacity-0 pb-0'">
                    <div class="overflow-hidden">
                        <p class="text-sm md:text-[15px] text-slate-500 leading-relaxed border-t border-slate-100 pt-4">
                            Pembayaran dilakukan secara manual melalui transfer bank (BNI). Anda cukup mentransfer sesuai total tagihan ke rekening yang tertera saat checkout, lalu mengunggah bukti pembayaran Anda untuk diverifikasi secara internal oleh tim kami.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border transition-colors duration-300 cursor-pointer" 
                 :class="active === 6 ? 'border-teal-300 shadow-md' : 'border-slate-200 shadow-sm hover:border-teal-200'" 
                 @click="active = active === 6 ? null : 6">
                <div class="px-6 py-5 flex items-center justify-between gap-4">
                    <h3 class="font-semibold text-slate-900 text-[15px] md:text-base leading-snug" :class="active === 6 ? 'text-teal-700' : ''">
                        Apakah saya bisa mengubah paket setelah membeli?
                    </h3>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 flex-shrink-0 transition-transform duration-300" :class="active === 6 ? 'rotate-180 text-teal-500' : 'text-slate-400'">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </div>
                <div class="grid transition-all duration-300 ease-in-out px-6" :class="active === 6 ? 'grid-rows-[1fr] opacity-100 pb-5' : 'grid-rows-[0fr] opacity-0 pb-0'">
                    <div class="overflow-hidden">
                        <p class="text-sm md:text-[15px] text-slate-500 leading-relaxed border-t border-slate-100 pt-4">
                            Ya, Anda bisa melakukan upgrade paket kapan saja melalui halaman Dashboard. Kuota dari paket baru akan ditambahkan ke akun Anda.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border transition-colors duration-300 cursor-pointer" 
                 :class="active === 7 ? 'border-teal-300 shadow-md' : 'border-slate-200 shadow-sm hover:border-teal-200'" 
                 @click="active = active === 7 ? null : 7">
                <div class="px-6 py-5 flex items-center justify-between gap-4">
                    <h3 class="font-semibold text-slate-900 text-[15px] md:text-base leading-snug" :class="active === 7 ? 'text-teal-700' : ''">
                        Berapa jumlah total scan yang diperbolehkan?
                    </h3>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 flex-shrink-0 transition-transform duration-300" :class="active === 7 ? 'rotate-180 text-teal-500' : 'text-slate-400'">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </div>
                <div class="grid transition-all duration-300 ease-in-out px-6" :class="active === 7 ? 'grid-rows-[1fr] opacity-100 pb-5' : 'grid-rows-[0fr] opacity-0 pb-0'">
                    <div class="overflow-hidden">
                        <p class="text-sm md:text-[15px] text-slate-500 leading-relaxed border-t border-slate-100 pt-4">
                            Setiap paket memiliki batas total scan yang berlaku untuk semua QR Code Anda secara keseluruhan, bukan per QR Code. Misalnya, paket Starter memiliki 10 total scan untuk seluruh QR Code yang Anda buat.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border transition-colors duration-300 cursor-pointer" 
                 :class="active === 8 ? 'border-teal-300 shadow-md' : 'border-slate-200 shadow-sm hover:border-teal-200'" 
                 @click="active = active === 8 ? null : 8">
                <div class="px-6 py-5 flex items-center justify-between gap-4">
                    <h3 class="font-semibold text-slate-900 text-[15px] md:text-base leading-snug" :class="active === 8 ? 'text-teal-700' : ''">
                        Apakah saya perlu menginstal aplikasi untuk memindai?
                    </h3>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 flex-shrink-0 transition-transform duration-300" :class="active === 8 ? 'rotate-180 text-teal-500' : 'text-slate-400'">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </div>
                <div class="grid transition-all duration-300 ease-in-out px-6" :class="active === 8 ? 'grid-rows-[1fr] opacity-100 pb-5' : 'grid-rows-[0fr] opacity-0 pb-0'">
                    <div class="overflow-hidden">
                        <p class="text-sm md:text-[15px] text-slate-500 leading-relaxed border-t border-slate-100 pt-4">
                            Tidak. Pengguna cukup membuka browser di ponsel mereka dan memindai QR Code atau objek langsung tanpa perlu mengunduh aplikasi tambahan.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border transition-colors duration-300 cursor-pointer" 
                 :class="active === 9 ? 'border-teal-300 shadow-md' : 'border-slate-200 shadow-sm hover:border-teal-200'" 
                 @click="active = active === 9 ? null : 9">
                <div class="px-6 py-5 flex items-center justify-between gap-4">
                    <h3 class="font-semibold text-slate-900 text-[15px] md:text-base leading-snug" :class="active === 9 ? 'text-teal-700' : ''">
                        Bagaimana cara menghubungi tim dukungan?
                    </h3>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 flex-shrink-0 transition-transform duration-300" :class="active === 9 ? 'rotate-180 text-teal-500' : 'text-slate-400'">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </div>
                <div class="grid transition-all duration-300 ease-in-out px-6" :class="active === 9 ? 'grid-rows-[1fr] opacity-100 pb-5' : 'grid-rows-[0fr] opacity-0 pb-0'">
                    <div class="overflow-hidden">
                        <p class="text-sm md:text-[15px] text-slate-500 leading-relaxed border-t border-slate-100 pt-4">
                            Anda bisa menghubungi kami melalui halaman Hubungi Kami, mengirim email ke info@scanyuk.com, atau menghubungi via WhatsApp di (+62) 815-2022-225.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border transition-colors duration-300 cursor-pointer" 
                 :class="active === 10 ? 'border-teal-300 shadow-md' : 'border-slate-200 shadow-sm hover:border-teal-200'" 
                 @click="active = active === 10 ? null : 10">
                <div class="px-6 py-5 flex items-center justify-between gap-4">
                    <h3 class="font-semibold text-slate-900 text-[15px] md:text-base leading-snug" :class="active === 10 ? 'text-teal-700' : ''">
                        Apakah data saya aman?
                    </h3>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 flex-shrink-0 transition-transform duration-300" :class="active === 10 ? 'rotate-180 text-teal-500' : 'text-slate-400'">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </div>
                <div class="grid transition-all duration-300 ease-in-out px-6" :class="active === 10 ? 'grid-rows-[1fr] opacity-100 pb-5' : 'grid-rows-[0fr] opacity-0 pb-0'">
                    <div class="overflow-hidden">
                        <p class="text-sm md:text-[15px] text-slate-500 leading-relaxed border-t border-slate-100 pt-4">
                            Ya. Semua data pengguna dienkripsi dan disimpan sesuai standar keamanan industri. Kami tidak membagikan data Anda ke pihak ketiga.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

</main>
@endsection