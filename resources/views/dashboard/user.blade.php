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

<div class="max-w-[100rem] mx-auto w-full px-4 sm:px-6 lg:px-8 py-10" x-data="{ showPackages: false }">
    
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
            <a href="{{ route('user.ar.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl btn-gradient text-white font-semibold text-sm transition-colors shadow-sm shadow-indigo-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                Buat AR
            </a>
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

    <div x-show="showPackages" x-collapse class="mb-8">
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
                        <form action="#" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="package_id" value="{{ $pkg->id }}">
                            <button type="submit" class="w-full py-2.5 rounded-lg btn-gradient text-white font-bold text-sm transition-colors">
                                Bayar
                            </button>
                        </form>
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
                    
                    <button type="button" class="text-slate-400 hover:text-indigo-600 transition-colors" title="Lihat/Download QR" onclick="window.open('{{ route('user.ar.download', $qr->id) }}', '_self')">
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

</div>
@endsection