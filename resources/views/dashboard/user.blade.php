@extends('layouts.app')

@section('content')

@php
    $features = $currentPackage ? $currentPackage->features : [];
    
    $imgLimit = (int) filter_var($features[0] ?? 0, FILTER_SANITIZE_NUMBER_INT);
    $voiceLimit = (int) filter_var($features[1] ?? 0, FILTER_SANITIZE_NUMBER_INT);
    $scanLimit = (int) filter_var($features[2] ?? 0, FILTER_SANITIZE_NUMBER_INT);

    $imgPercent = $imgLimit > 0 ? min(($user->image / $imgLimit) * 100, 100) : 0;
    $voicePercent = $voiceLimit > 0 ? min(($user->voice / $voiceLimit) * 100, 100) : 0;
    $scanPercent = $scanLimit > 0 ? min(($user->scan / $scanLimit) * 100, 100) : 0;
@endphp

<div class="max-w-[100rem] mx-auto w-full px-4 sm:px-6 lg:px-8 py-10" x-data="{ showPackages: false, showLimitModal: false, showWarningModal: false, selectedPkg: null, showDownloadModal: false, selectedQrId: null, isGeneratingFlyer: false, flyerProgress: 0 }">
    
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 mb-1">Dashboard</h1>
            <p class="text-slate-500">Selamat datang, {{ $user->name }}</p>
        </div>
        <div class="flex items-center gap-3">
            <button @click="showPackages = !showPackages" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-teal-200 text-teal-600 font-semibold text-sm hover:bg-teal-50 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                Upgrade Paket
            </button>
            @if($user->image >= $imgLimit)
                @if($user->role === 'business')
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-700 font-semibold text-sm hover:bg-amber-100 transition-colors shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        Hubungi Tim Penjualan
                    </a>
                @else
                    <button @click="showLimitModal = true" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl btn-gradient text-white font-semibold text-sm transition-colors shadow-sm cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                        Buat AR
                    </button>
                @endif
            @else
                <a href="{{ route('user.ar.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl btn-gradient text-white font-semibold text-sm transition-colors shadow-sm shadow-indigo-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                    Buat AR
                </a>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm mb-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-teal-50 flex items-center justify-center text-teal-600 flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-900">Paket {{ $currentPackage->name ?? ucfirst($user->role) }}</h3>
                <p class="text-sm text-slate-500">Kuota tidak expired selama belum dipakai</p>
            </div>
        </div>
        <button @click="showPackages = !showPackages" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg border border-slate-200 text-slate-700 font-semibold text-sm hover:bg-slate-50 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" /></svg>
            Ubah Paket
        </button>
    </div>

    <div x-show="showPackages" x-collapse class="mb-8" id="packages-section">
        <div class="bg-white rounded-xl border border-slate-200 p-6 md:p-8 shadow-sm">
            <h3 class="text-lg font-bold text-slate-900 mb-6">Pilih Paket</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($packages as $pkg)
                <div class="rounded-xl border p-6 flex flex-col h-full transition-all 
                    {{ ($currentPackage && $pkg->id === $currentPackage->id) ? 'border-teal-500 bg-teal-50/20 shadow-md shadow-teal-100/50' : 'border-slate-200 hover:border-indigo-300 bg-white' }}">
                    
                    <h4 class="text-base font-bold text-slate-900 mb-1">{{ $pkg->name }}</h4>
                    <div class="text-2xl font-extrabold text-slate-900 mb-4">
                        {{ $pkg->price == 0 ? 'Rp0' : 'Rp' . number_format($pkg->price, 0, ',', '.') }}
                    </div>
                    
                    <ul class="space-y-2 mb-8 flex-grow text-sm text-slate-500">
                        @foreach(array_slice($pkg->features, 0, 3) as $feature)
                        <li>{{ $feature }}</li>
                        @endforeach
                    </ul>

                    @if($currentPackage && $pkg->id === $currentPackage->id)
                        <div class="w-full py-2.5 rounded-lg flex items-center justify-center gap-2 text-teal-600 font-bold text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            Paket Aktif
                        </div>
                    @else
                        <button type="button" @click="selectedPkg = {{ $pkg->id }}; showWarningModal = true" class="w-full py-2.5 rounded-lg btn-gradient text-white font-bold text-sm transition-colors">
                            Bayar
                        </button>
                    @endif
                </div>
                @endforeach
            </div>
            <p class="text-xs text-slate-400 mt-6">Pembayaran akan diproses melalui iPaymu. Kuota ditambahkan setelah pembayaran berhasil.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center gap-2 text-slate-500 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                <span class="text-sm font-medium">Total AR</span>
            </div>
            <div>
                <div class="text-3xl font-extrabold text-slate-900 mb-3">
                    {{ $user->image }} <span class="text-lg font-medium text-slate-400">/ {{ $imgLimit }}</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2.5">
                    <div class="bg-teal-500 h-2.5 rounded-full" style="width: {{ $imgPercent }}%"></div>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center gap-2 text-slate-500 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" /></svg>
                <span class="text-sm font-medium">Voice Narration</span>
            </div>
            <div>
                <div class="text-3xl font-extrabold text-slate-900 mb-3">
                    {{ $user->voice }} <span class="text-lg font-medium text-slate-400">/ {{ $voiceLimit }}</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2.5">
                    <div class="bg-teal-500 h-2.5 rounded-full" style="width: {{ $voicePercent }}%"></div>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center gap-2 text-slate-500 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                <span class="text-sm font-medium">Total Scan</span>
            </div>
            <div>
                <div class="text-3xl font-extrabold text-slate-900 mb-3">
                    {{ $user->scan }} <span class="text-lg font-medium text-slate-400">/ {{ $scanLimit }}</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2.5">
                    <div class="bg-teal-500 h-2.5 rounded-full" style="width: {{ $scanPercent }}%"></div>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center gap-2 text-slate-500 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                <span class="text-sm font-medium">QR Code Aktif</span>
            </div>
            <div>
                <div class="text-3xl font-extrabold text-slate-900 mb-1">
                    {{ collect($qrCodes)->where('status', 'Aktif')->count() }}
                </div>
                <div class="text-sm text-slate-400">
                    Dari {{ count($qrCodes) }} total QR
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h2 class="text-base font-bold text-slate-900">QR Code Anda</h2>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($qrCodes as $qr)
            <div class="p-4 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50 transition-colors">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl {{ $qr->ar_type == '3d' ? 'bg-indigo-50 text-indigo-600' : 'bg-teal-50 text-teal-600' }} flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-7 h-7"><rect width="5" height="5" x="3" y="3" rx="1"></rect><rect width="5" height="5" x="16" y="3" rx="1"></rect><rect width="5" height="5" x="3" y="16" rx="1"></rect><path d="M21 16h-3a2 2 0 0 0-2 2v3"></path><path d="M21 21v.01"></path><path d="M12 7v3a2 2 0 0 1-2 2H7"></path><path d="M3 12h.01"></path><path d="M12 3h.01"></path><path d="M12 16v.01"></path><path d="M16 12h1"></path><path d="M21 12v.01"></path><path d="M12 21v-1"></path></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-0.5">
                            <h4 class="text-sm font-bold text-slate-900">{{ $qr->title }}</h4>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold tracking-wider uppercase {{ $qr->ar_type == '3d' ? 'bg-indigo-100 text-indigo-700' : 'bg-teal-100 text-teal-700' }}">
                                {{ $qr->ar_type }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500">{{ $qr->scan_count }} scan • Dibuat {{ $qr->created_at->format('d M Y') }}</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $qr->status == 'Aktif' ? 'bg-teal-50 text-teal-600' : 'bg-slate-100 text-slate-500' }}">
                        {{ $qr->status }}
                    </span>
                    
                    <button type="button" class="text-slate-400 hover:text-indigo-600 transition-colors" title="Lihat/Download QR" @click="selectedQrId = {{ $qr->id }}; showDownloadModal = true;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    </button>

                    <form action="{{ route('user.ar.toggle-status', $qr->id) }}" method="POST" class="inline m-0 p-0">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors mt-1" title="{{ $qr->status == 'Aktif' ? 'Sembunyikan' : 'Tampilkan' }}">
                            @if($qr->status == 'Aktif')
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                            @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            @endif
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-slate-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                Belum ada QR Code. Klik <span class="font-bold text-slate-700">Buat AR</span> untuk memulai.
            </div>
            @endforelse
        </div>
    </div>
    <div x-show="showLimitModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center">
        <div x-show="showLimitModal" x-transition.opacity class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm" @click="showLimitModal = false"></div>
        <div x-show="showLimitModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden flex flex-col items-center p-8 text-center border border-white/20">
            
            <div class="w-20 h-20 rounded-full bg-red-50 text-red-500 flex items-center justify-center mb-5 border-4 border-red-100 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            </div>
            
            <h3 class="font-extrabold text-slate-900 text-2xl mb-2">
                {{ $user->role === 'business' ? 'Limit Tertinggi Tercapai!' : 'Kuota AR Habis!' }}
            </h3>
            <p class="text-sm text-slate-500 mb-8 leading-relaxed">
                Anda telah mencapai batas maksimal {{ $imgLimit }} AR. 
                @if($user->role === 'business')
                    Untuk penawaran limit tanpa batas, silakan hubungi tim kami untuk membuat paket Custom.
                @else
                    Silakan upgrade paket Anda untuk mereset dan membuat lebih banyak AR.
                @endif
            </p>
            
            <div class="w-full space-y-3">
                @if($user->role === 'business')
                    <a href="{{ route('contact') }}" class="w-full block py-3.5 px-4 rounded-xl btn-gradient text-white font-bold shadow-lg shadow-indigo-200">Hubungi Tim Kami</a>
                @else
                    <button @click="showLimitModal = false; showPackages = true;..." class="w-full block py-3.5 px-4 rounded-xl btn-gradient text-white font-bold shadow-lg shadow-indigo-200">Lihat Pilihan Paket</button>
                @endif
                <button @click="showLimitModal = false" class="w-full py-3.5 px-4 rounded-xl border-2 border-slate-200 text-slate-500 font-bold hover:bg-slate-50 hover:text-slate-700 transition-colors">Nanti Saja</button>
            </div>
        </div>
    </div>
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

    <div x-show="showDownloadModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center">
        <div x-show="showDownloadModal" x-transition.opacity class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm" @click="showDownloadModal = false"></div>
        <div x-show="showDownloadModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden flex flex-col items-center p-8 text-center">
            
            <h3 class="font-extrabold text-slate-900 text-xl mb-6">Pilih Format Download</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                <button type="button" @click="window.open(`/dashboard/ar/${selectedQrId}/download?type=svg`, '_self'); showDownloadModal = false;" class="flex flex-col items-center p-6 border-2 border-slate-200 rounded-2xl hover:border-teal-500 hover:bg-teal-50 transition-all group">
                    <div class="w-16 h-16 bg-slate-100 rounded-xl mb-4 flex items-center justify-center text-slate-400 group-hover:text-teal-500 group-hover:bg-white shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="5" height="5" x="3" y="3" rx="1"></rect><rect width="5" height="5" x="16" y="3" rx="1"></rect><rect width="5" height="5" x="3" y="16" rx="1"></rect><path d="M21 16h-3a2 2 0 0 0-2 2v3"></path><path d="M21 21v.01"></path><path d="M12 7v3a2 2 0 0 1-2 2H7"></path><path d="M3 12h.01"></path><path d="M12 3h.01"></path><path d="M12 16v.01"></path><path d="M16 12h1"></path><path d="M21 12v.01"></path><path d="M12 21v-1"></path></svg>
                    </div>
                    <span class="font-bold text-slate-900 mb-1">QR Code Saja</span>
                    <span class="text-xs text-slate-500">File SVG Mentah</span>
                </button>

                <button type="button" @click="showDownloadModal = false; generateAutomatedFlyer(selectedQrId, $data);" class="flex flex-col items-center p-6 border-2 border-slate-200 rounded-2xl hover:border-indigo-500 hover:bg-indigo-50 transition-all group relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-16 h-16 bg-indigo-100 rounded-full blur-xl group-hover:bg-indigo-200 transition-all"></div>
                    <div class="w-16 h-16 bg-slate-100 rounded-xl mb-4 flex items-center justify-center text-slate-400 group-hover:text-indigo-600 group-hover:bg-white shadow-sm z-10">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline><path d="M12 18v-6"></path><path d="M9 15h6"></path></svg>
                    </div>
                    <span class="font-bold text-slate-900 mb-1 z-10">Poster Instruksi</span>
                    <span class="text-xs text-slate-500 z-10">Siap Cetak (PNG)</span>
                </button>
            </div>
            
            <button @click="showDownloadModal = false" class="mt-6 text-sm text-slate-400 hover:text-slate-600 font-bold underline">Batal</button>
        </div>
    </div>

    <div x-show="isGeneratingFlyer" style="display: none;" class="fixed inset-0 z-[200] flex items-center justify-center">
        <div x-show="isGeneratingFlyer" x-transition.opacity class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm"></div>
        <div x-show="isGeneratingFlyer" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden flex flex-col items-center p-8 text-center border border-white/20">
            
            <div class="w-16 h-16 border-4 border-slate-100 border-t-indigo-500 rounded-full animate-spin mb-6"></div>
            
            <h3 class="font-extrabold text-slate-900 text-xl mb-2">Menyiapkan Poster...</h3>
            <div class="w-full bg-slate-100 rounded-full h-3 mb-3 overflow-hidden">
                <div class="bg-gradient-to-r from-teal-400 to-indigo-500 h-3 rounded-full transition-all duration-300" :style="'width: ' + flyerProgress + '%'"></div>
            </div>
            <div class="flex justify-between w-full text-sm">
                <span class="font-bold text-indigo-600" x-text="flyerProgress + '%'"></span>
                <span class="text-slate-500 font-medium">Mohon tunggu</span>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <div style="position: absolute; left: -9999px; top: -9999px;">
        <div id="flyer-template" class="w-[800px] h-[1130px] flex flex-col items-center justify-between p-8 bg-slate-900 text-white relative overflow-hidden" style="font-family: 'Inter', sans-serif;">
            
            <div class="absolute inset-0 z-0 pointer-events-none" style="background: radial-gradient(circle at 10% 10%, rgba(20, 184, 166, 0.4) 0%, transparent 40%), radial-gradient(circle at 90% 90%, rgba(99, 102, 241, 0.4) 0%, transparent 40%);"></div>

            <div class="z-10 text-center mt-2 w-full">
                <div class="inline-flex items-center justify-center bg-teal-500/20 border border-teal-400 text-teal-400 font-bold px-8 pt-2.5 pb-6 rounded-full text-xl mb-4 tracking-widest uppercase leading-none">
                    AR Experience
                </div>
                <h1 class="text-5xl font-black mb-2 leading-tight tracking-tight">SCAN UNTUK<br>MEMULAI KEAJAIBAN</h1>
                <p class="text-xl text-slate-300 font-medium">Arahkan kamera HP Anda ke QR Code di bawah ini</p>
            </div>

            <div class="z-10 bg-white p-5 rounded-[2rem] shadow-2xl my-2">
                <div id="flyer-qr-container" class="w-[370px] h-[370px] bg-white flex items-center justify-center"></div>
            </div>

            <div class="z-10 w-full bg-slate-800/80 border border-slate-700 rounded-3xl p-6 mb-2 shadow-xl">
                <h3 class="text-2xl font-bold mb-4 text-teal-400 border-b border-slate-700 pb-3">Cara Penggunaan:</h3>
                
                <div class="space-y-4 text-xl font-medium text-slate-200">
                    <div class="flex items-start gap-5">
                        <div class="w-12 h-12 rounded-full bg-teal-500 text-white flex items-center justify-center font-bold flex-shrink-0 text-2xl pb-4" style="line-height: 1;">1</div>
                        <p class="mt-1 leading-relaxed">Buka aplikasi <span class="text-white font-bold">Kamera Bawaan</span> atau <span class="text-white font-bold">Google Lens</span>.</p>
                    </div>
                    <div class="flex items-start gap-5">
                        <div class="w-12 h-12 rounded-full bg-teal-500 text-white flex items-center justify-center font-bold flex-shrink-0 text-2xl pb-4" style="line-height: 1;">2</div>
                        <p class="mt-1 leading-relaxed">Arahkan lensa ke QR Code dan klik <span class="text-white font-bold">Link Tautan</span> yang muncul.</p>
                    </div>
                    <div class="flex items-start gap-5">
                        <div class="w-12 h-12 rounded-full bg-teal-500 text-white flex items-center justify-center font-bold flex-shrink-0 text-2xl pb-4" style="line-height: 1;">3</div>
                        <p class="mt-1 leading-relaxed">Izinkan akses kamera dan lihat dunia dalam AR 3D!</p>
                    </div>
                </div>
            </div>
            
            <p class="z-10 text-slate-500 font-bold tracking-widest uppercase text-sm mb-1">Powered by ScanYuk</p>
        </div>
    </div>

<script>
    async function generateAutomatedFlyer(qrId, alpineData) {
        alpineData.isGeneratingFlyer = true;
        alpineData.flyerProgress = 10;

        try {
            const response = await fetch(`/dashboard/ar/${qrId}/download?type=svg`);
            if (!response.ok) throw new Error("Gagal mengambil respon dari server");
            
            alpineData.flyerProgress = 30;

            const svgText = await response.text();
            const qrContainer = document.getElementById('flyer-qr-container');
            qrContainer.innerHTML = svgText;

            const svgElement = qrContainer.querySelector('svg');
            if (svgElement) {
                svgElement.setAttribute('width', '100%');
                svgElement.setAttribute('height', '100%');
                svgElement.style.display = 'block';
            }

            await document.fonts.ready;

            alpineData.flyerProgress = 50;
            const flyerNode = document.getElementById('flyer-template');

            let progressInterval = setInterval(() => {
                if(alpineData.flyerProgress < 90) alpineData.flyerProgress += 5;
            }, 250);

            html2canvas(flyerNode, { 
                scale: 3,
                useCORS: true,
                allowTaint: true,
                backgroundColor: "#0f172a",
                onclone: function(clonedDoc) {
                    const clonedNode = clonedDoc.getElementById('flyer-template');
                    clonedNode.style.position = 'relative';
                    clonedNode.style.left = '0';
                    clonedNode.style.top = '0';
                }
            }).then(canvas => {
                clearInterval(progressInterval);
                alpineData.flyerProgress = 100;

                const link = document.createElement('a');
                link.download = `ScanYuk-Poster-Instruksi-${qrId}.png`;
                link.href = canvas.toDataURL('image/png', 1.0);
                link.click();

                setTimeout(() => {
                    alpineData.isGeneratingFlyer = false;
                    alpineData.flyerProgress = 0;
                    qrContainer.innerHTML = ''; 
                }, 800);

            }).catch(err => {
                clearInterval(progressInterval);
                console.error("html2canvas error:", err);
                alert('Gagal merender poster. Silakan coba lagi.');
                alpineData.isGeneratingFlyer = false;
                qrContainer.innerHTML = '';
            });

        } catch (error) {
            console.error("Proses pembuatan poster gagal:", error);
            alert('Gagal mengambil data QR Code SVG. Pastikan QR code tersebut valid.');
            alpineData.isGeneratingFlyer = false;
            alpineData.flyerProgress = 0;
        }
    }
</script>
</div>
@endsection