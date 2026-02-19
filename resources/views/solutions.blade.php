@extends('layouts.app')

@section('content')

<section class="w-full pt-24 pb-12 md:pt-32 md:pb-16 px-6 bg-white text-center">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-6 tracking-tight">
            Solusi <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-500 to-indigo-600">By Industry</span>
        </h1>
        <p class="text-lg md:text-xl text-slate-500 leading-relaxed max-w-2xl mx-auto">
            ScanYuk dirancang untuk berbagai industri. Temukan solusi yang tepat untuk kebutuhan Anda.
        </p>
    </div>
</section>

<section class="w-full px-6 pb-24 bg-white">
    <div class="max-w-6xl mx-auto flex flex-col gap-8">

        <div class="bg-white border border-slate-200 rounded-2xl p-8 md:p-10 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900">Education</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h4 class="text-xs font-bold text-red-500 uppercase tracking-wide mb-2">MASALAH</h4>
                    <p class="text-slate-600 leading-relaxed">
                        Buku teks dan materi ajar terasa monoton, siswa kurang engaged.
                    </p>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-teal-500 uppercase tracking-wide mb-2">SOLUSI AR QR</h4>
                    <p class="text-slate-600 leading-relaxed">
                        Tambahkan konten AR interaktif di setiap halaman buku — gambar 3D dan narasi suara otomatis.
                    </p>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-indigo-500 uppercase tracking-wide mb-2">CONTOH</h4>
                    <p class="text-slate-600 leading-relaxed">
                        Buku biologi SD dengan AR organ tubuh dan penjelasan audio.
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-8 md:p-10 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900">Retail</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h4 class="text-xs font-bold text-red-500 uppercase tracking-wide mb-2">MASALAH</h4>
                    <p class="text-slate-600 leading-relaxed">
                        Informasi produk terbatas pada label dan kemasan.
                    </p>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-teal-500 uppercase tracking-wide mb-2">SOLUSI AR QR</h4>
                    <p class="text-slate-600 leading-relaxed">
                        QR code di kemasan produk memunculkan AR info, demo, dan review.
                    </p>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-indigo-500 uppercase tracking-wide mb-2">CONTOH</h4>
                    <p class="text-slate-600 leading-relaxed">
                        Brand kosmetik menampilkan tutorial makeup AR saat scan kemasan.
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-8 md:p-10 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                         <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900">Hospitality & Tourism</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h4 class="text-xs font-bold text-red-500 uppercase tracking-wide mb-2">MASALAH</h4>
                    <p class="text-slate-600 leading-relaxed">
                        Wisatawan kesulitan mendapatkan informasi mendalam tentang destinasi.
                    </p>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-teal-500 uppercase tracking-wide mb-2">SOLUSI AR QR</h4>
                    <p class="text-slate-600 leading-relaxed">
                        QR code di lokasi wisata memunculkan AR guide dengan narasi multilingual.
                    </p>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-indigo-500 uppercase tracking-wide mb-2">CONTOH</h4>
                    <p class="text-slate-600 leading-relaxed">
                        Museum sejarah dengan AR guide di setiap artefak.
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-8 md:p-10 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900">Government</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h4 class="text-xs font-bold text-red-500 uppercase tracking-wide mb-2">MASALAH</h4>
                    <p class="text-slate-600 leading-relaxed">
                        Dokumen dan poster publik kurang informatif dan tidak interaktif.
                    </p>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-teal-500 uppercase tracking-wide mb-2">SOLUSI AR QR</h4>
                    <p class="text-slate-600 leading-relaxed">
                        AR QR di dokumen resmi, poster kampanye, dan fasilitas publik.
                    </p>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-indigo-500 uppercase tracking-wide mb-2">CONTOH</h4>
                    <p class="text-slate-600 leading-relaxed">
                        Poster informasi publik dengan AR video penjelasan dari kepala dinas.
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-8 md:p-10 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900">Event Organizer</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h4 class="text-xs font-bold text-red-500 uppercase tracking-wide mb-2">MASALAH</h4>
                    <p class="text-slate-600 leading-relaxed">
                        Brosur dan banner event hanya bisa menyampaikan info terbatas.
                    </p>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-teal-500 uppercase tracking-wide mb-2">SOLUSI AR QR</h4>
                    <p class="text-slate-600 leading-relaxed">
                        Setiap materi cetak event bisa memiliki AR content interaktif.
                    </p>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-indigo-500 uppercase tracking-wide mb-2">CONTOH</h4>
                    <p class="text-slate-600 leading-relaxed">
                        Conference dengan AR speaker profile di setiap name tag.
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-8 md:p-10 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900">Corporate Marketing</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h4 class="text-xs font-bold text-red-500 uppercase tracking-wide mb-2">MASALAH</h4>
                    <p class="text-slate-600 leading-relaxed">
                        Materi marketing cetak sulit diukur efektivitasnya.
                    </p>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-teal-500 uppercase tracking-wide mb-2">SOLUSI AR QR</h4>
                    <p class="text-slate-600 leading-relaxed">
                        QR code dengan scan tracking, AR content, dan analytics real-time.
                    </p>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-indigo-500 uppercase tracking-wide mb-2">CONTOH</h4>
                    <p class="text-slate-600 leading-relaxed">
                        Brosur perusahaan dengan AR company profile dan video CEO.
                    </p>
                </div>
            </div>
        </div>

    </div>

    <div class="text-center mt-16">
        <a href="{{ route('contact') }}" class="inline-flex items-center justify-center px-8 py-4 rounded-lg btn-gradient text-white font-bold text-base shadow-lg shadow-indigo-200 hover:scale-105 transition-transform duration-200">
            Diskusikan Kebutuhan Anda
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
        </a>
    </div>
</section>

@endsection