@extends('layouts.app')

@section('content')

@php
    $packages = \App\Models\PricingPackage::orderBy('id', 'asc')->get();
@endphp

<section class="w-full flex flex-col items-center justify-center px-6 text-center pt-24 pb-16 md:pt-32 md:pb-24 bg-gradient-to-b from-white to-slate-50/50">
    
    <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight mb-6 text-slate-900 leading-tight">
        AR QR Code untuk <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-500 to-indigo-600">Kreasi Personal</span>
    </h1>

    <p class="text-lg md:text-xl text-slate-500 max-w-3xl leading-relaxed mb-10">
        Buat momen spesial jadi lebih berkesan dengan augmented reality. Upload gambar, tulis narasi, dan bagikan lewat QR code.
    </p>

    <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
        <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 rounded-lg btn-gradient text-white font-semibold text-base shadow-lg shadow-indigo-200 hover:scale-105 transition-transform duration-200">
            Create Your AR QR Code
        </a>
        
        <a href="{{ route('demo') }}" class="w-full sm:w-auto px-8 py-4 rounded-lg bg-white text-teal-600 border border-teal-200 font-semibold text-base hover:bg-teal-50 transition-colors flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z" />
            </svg>
            Lihat Demo
        </a>
    </div>
</section>

<section class="w-full px-6 md:px-12 py-16 md:py-24 bg-slate-100">
    <div class="max-w-[90rem] mx-auto">
        
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900">
                Ide Penggunaan <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-500 to-indigo-600">Consumer</span>
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Undangan Pernikahan AR</h3>
                <p class="text-slate-500 leading-relaxed text-sm">Tambahkan foto & narasi suara di undangan cetak. Tamu scan QR, muncul AR berisi pesan spesial.</p>
            </div>

            <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H4.5a1.5 1.5 0 01-1.5-1.5v-8.25M3 11.25h18M3 11.25l7.714-3.666a1.5 1.5 0 011.572 0L21 11.25m-18 0l2.364-4.728A1.5 1.5 0 016.71 5.5h10.58a1.5 1.5 0 011.346 1.022l2.364 4.728" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Kado Ulang Tahun</h3>
                <p class="text-slate-500 leading-relaxed text-sm">Buat kado lebih berkesan dengan pesan AR personal yang muncul saat QR di-scan.</p>
            </div>

            <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Album Kenangan</h3>
                <p class="text-slate-500 leading-relaxed text-sm">Foto album cetak jadi hidup! Setiap halaman bisa punya narasi suara dan overlay AR.</p>
            </div>

            <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                         <path d="M12 14l9-5-9-5-9 5 9 5z" />
                         <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                         <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Edukasi Anak</h3>
                <p class="text-slate-500 leading-relaxed text-sm">Buku pelajaran interaktif dengan gambar AR dan penjelasan audio otomatis.</p>
            </div>

            <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Poster Digital</h3>
                <p class="text-slate-500 leading-relaxed text-sm">Poster dan flyer cetak dengan konten AR — cocok untuk promosi event personal.</p>
            </div>

        </div>
    </div>
</section>

<section class="w-full px-6 md:px-12 py-16 md:py-24 bg-white">
    <div class="max-w-[90rem] mx-auto">
        
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">
                Paket <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-500 to-indigo-600">Harga</span>
            </h2>
            <p class="text-slate-500 max-w-2xl mx-auto">
                Beli kuota untuk membuat AR. Tidak ada langganan, tidak ada expired — bayar sesuai kebutuhan.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-5xl mx-auto items-start">
            
            @foreach($packages as $package)
                @php
                    $isPopular = $package->id == 3;
                    $roleName = $package->id == 1 ? 'free' : ($package->id == 2 ? 'starter' : 'professional');
                    $buttonText = $package->price == 0 ? 'Mulai Gratis' : 'Pilih Paket';
                @endphp

                <div class="{{ $isPopular 
                    ? 'relative p-8 rounded-2xl border border-indigo-100 bg-white shadow-xl shadow-indigo-100 transform lg:-translate-y-4 z-10 flex flex-col h-full' 
                    : 'p-8 rounded-2xl border border-slate-200 bg-white flex flex-col h-full' 
                }}">
                    
                    @if($isPopular)
                        <div class="absolute -top-4 right-1/2 translate-x-1/2 px-4 py-1 bg-gradient-to-r from-teal-500 to-indigo-600 rounded-full text-white text-xs font-bold tracking-wide shadow-md">
                            POPULER
                        </div>
                    @endif

                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 mb-2">{{ $package->name }}</h3>
                        <div class="text-4xl font-bold text-slate-900 mb-6">
                            @if($package->price > 0)
                                Rp{{ number_format($package->price, 0, ',', '.') }}
                            @else
                                Gratis
                            @endif
                        </div>
                    </div>

                    <ul class="space-y-4 mb-8 text-sm text-slate-600 flex-grow">
                        @if(is_array($package->features))
                            @foreach($package->features as $feature)
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-teal-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg> 
                                    <span>{{ $feature }}</span>
                                </li>
                            @endforeach
                        @endif
                    </ul>

                    <a href="{{ route('register', ['plan' => $roleName]) }}" 
                        class="mt-auto block w-full py-3 rounded-lg font-semibold text-center transition-all duration-200 
                        {{ $isPopular 
                            ? 'btn-gradient text-white hover:opacity-90 shadow-lg shadow-indigo-200' 
                            : 'border border-teal-500 text-teal-600 hover:bg-teal-50' 
                        }}">
                        {{ $buttonText }}
                    </a>

                </div>
            @endforeach

        </div>
    </div>
</section>

@endsection