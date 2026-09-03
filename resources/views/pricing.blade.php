@extends('layouts.app')

@section('content')

@php
    $packages = \App\Models\PricingPackage::orderBy('id', 'asc')->get();
@endphp

{{-- Bungkus seluruh konten dengan x-data Alpine.js untuk modal --}}
<div x-data="{ showWarningModal: false, selectedPkg: null }">

    <section class="w-full pt-24 pb-12 md:pt-32 md:pb-16 px-6 bg-white text-center relative overflow-hidden">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full max-w-6xl pointer-events-none z-0 opacity-40">
            <div class="absolute top-20 right-1/4 w-96 h-96 bg-teal-50 rounded-full mix-blend-multiply filter blur-3xl animate-blob"></div>
            <div class="absolute bottom-20 left-1/4 w-96 h-96 bg-indigo-50 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000"></div>
        </div>

        <div class="relative z-10 max-w-4xl mx-auto">
            <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-6 tracking-tight">
                Paket <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-500 to-indigo-600">Harga</span>
            </h1>
            <p class="text-lg md:text-xl text-slate-500 leading-relaxed max-w-2xl mx-auto">
                Pilih paket yang sesuai kebutuhan Anda. Kuota tidak expired selama belum dipakai.
            </p>
        </div>
    </section>

    <section class="w-full px-6 bg-white relative z-10">
        <div class="max-w-[90rem] mx-auto">
            
            {{-- 
<div class="flex items-center justify-center gap-3 mb-12">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-indigo-900" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" />
                </svg>
                <h2 class="text-2xl md:text-3xl font-bold text-slate-900">Harga Personal</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 items-start">
                
                @foreach($packages as $package)
                    @php
                        $isPopular = $package->id == 3;
                        $roleName = 'free';
                        if($package->id == 2) $roleName = 'starter';
                        if($package->id == 3) $roleName = 'professional';
                        if($package->id == 4) $roleName = 'business';
                        
                        $buttonText = $package->price == 0 ? 'Mulai Gratis' : 'Pilih Paket';
                        if ($roleName === 'business') $buttonText = 'Hubungi Kami';

                        // Memisahkan class tombol agar mudah diterapkan ke <button> maupun <a>
                        $btnClasses = "mt-auto block w-full py-3 rounded-lg font-semibold text-center transition-all duration-200 " . 
                                      ($isPopular ? 'btn-gradient text-white hover:opacity-90 shadow-lg shadow-indigo-200' : 'border border-teal-500 text-teal-600 hover:bg-teal-50');
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
                                Rp{{ number_format($package->price, 0, ',', '.') }}
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
                            
                        {{-- LOGIKA TOMBOL BERDASARKAN STATUS LOGIN --}}
                        @if($roleName === 'business')
                            <a href="{{ route('contact') }}" class="{{ $btnClasses }}">
                                {{ $buttonText }}
                            </a>
                        @else
                            @auth
                                {{-- Jika sudah login, klik tombol akan membuka Modal Bayar (Sama seperti Dashboard) --}}
                                <button type="button" @click="selectedPkg = {{ $package->id }}; showWarningModal = true" class="{{ $btnClasses }}">
                                    {{ $buttonText }}
                                </button>
                            @else
                                {{-- Jika belum login, klik tombol akan menuju Register --}}
                                <a href="{{ route('register', ['plan' => $roleName]) }}" class="{{ $btnClasses }}">
                                    {{ $buttonText }}
                                </a>
                            @endauth
                        @endif

                    </div>
                @endforeach
            </div>

            
--}}
{{-- Sistem Antrian Pricing (WhatsApp) --}}
            <div class="mt-20 max-w-4xl mx-auto bg-gradient-to-br from-slate-900 to-indigo-900 rounded-3xl p-8 md:p-12 shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-teal-500 rounded-full mix-blend-screen filter blur-3xl opacity-20"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="text-left">
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-teal-500/20 border border-teal-500/30 rounded-full text-teal-300 text-xs font-bold tracking-wider mb-4">
                            <span class="w-2 h-2 rounded-full bg-teal-400 animate-pulse"></span>
                            PRODUK BARU
                        </div>
                        <h3 class="text-2xl md:text-3xl font-extrabold text-white mb-3">Sistem Antrian Digital</h3>
                        <p class="text-slate-300 text-sm md:text-base max-w-xl leading-relaxed">
                            Solusi antrian modern untuk bisnis Anda. Termasuk fitur integrasi Augmented Reality, notifikasi suara, analitik antrian, dan manajemen multi-loket (booth).
                        </p>
                    </div>
                    <div class="flex-shrink-0 w-full md:w-auto">
                        <a href="https://wa.me/628152022225?text=Halo%20tim%20ScanYuk,%20saya%20tertarik%20dengan%20Sistem%20Antrian%20Digital" target="_blank" class="block w-full text-center px-8 py-4 bg-teal-500 hover:bg-teal-400 text-slate-900 font-extrabold rounded-xl transition-all shadow-lg shadow-teal-500/30 hover:-translate-y-1">
                            Hubungi via WhatsApp
                        </a>
                        <p class="text-slate-400 text-xs text-center mt-3">Diskusikan kebutuhan spesifik Anda</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section class="w-full px-6 py-20 bg-slate-50">
        <div class="max-w-5xl mx-auto">
            
            <div class="flex items-center justify-center gap-3 mb-10">
                <span class="text-4xl">🏢</span>
                <h2 class="text-2xl md:text-3xl font-bold text-slate-900">Harga Bisnis</h2>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200 p-8 md:p-12 text-center shadow-lg">
                <h3 class="text-2xl font-bold text-slate-900 mb-2">Enterprise / Custom</h3>
                <p class="text-slate-500 mb-10 max-w-2xl mx-auto">
                    Solusi khusus untuk kebutuhan bisnis Anda dengan harga yang disesuaikan.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 text-left max-w-3xl mx-auto mb-10">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        <span class="text-slate-600">Harga kustom per kampanye</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        <span class="text-slate-600">Pilihan scan tak terbatas</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        <span class="text-slate-600">Dashboard multi-user</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        <span class="text-slate-600">Pembuatan QR massal</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        <span class="text-slate-600">Analitik lanjutan & ekspor</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        <span class="text-slate-600">SLA & manajer akun khusus</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        <span class="text-slate-600">Branding label putih</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        <span class="text-slate-600">Kontrak tahunan tersedia</span>
                    </div>
                </div>

                <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-lg btn-gradient text-white font-bold hover:scale-105 transition-transform shadow-lg shadow-indigo-200">
                    Hubungi Tim Penjualan
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>

            </div>
        </div>
    </section>

    {{-- MODAL PERINGATAN (SAMA SEPERTI DI DASHBOARD) --}}
    @auth
    <div x-show="showWarningModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center">
        <div x-show="showWarningModal" x-transition.opacity class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm" @click="showWarningModal = false"></div>
        <div x-show="showWarningModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md mx-4 p-8 text-center">
            
            <div class="w-20 h-20 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center mx-auto mb-5 border-4 border-amber-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            </div>
            
            <h3 class="font-extrabold text-slate-900 text-xl mb-2">Peringatan Penting!</h3>
            <p class="text-sm text-slate-600 mb-6 bg-amber-50 border border-amber-100 p-4 rounded-xl text-left">
                Membeli paket baru (meskipun paket yang sama) akan <strong>MENGHAPUS & MERESET</strong> seluruh data QR Code Anda saat ini ke 0. Harap <span class="font-bold">Download</span> QR Code Anda jika masih ingin menggunakannya!
            </p>
            
            <form action="{{ route('payment.checkout') }}" method="POST" class="w-full flex gap-3">
                @csrf
                <input type="hidden" name="package_id" x-bind:value="selectedPkg">
                <button type="button" @click="showWarningModal = false" class="w-1/2 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-colors">Batal</button>
                <button type="submit" class="w-1/2 py-3 rounded-xl btn-gradient text-white font-bold transition-colors shadow-lg">Lanjut Bayar</button>
            </form>
        </div>
    </div>
    @endauth

</div>

@endsection