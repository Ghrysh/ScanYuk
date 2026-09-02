@extends('layouts.app')

@section('content')
<div class="max-w-[100rem] mx-auto w-full px-4 sm:px-6 lg:px-8 py-10" x-data="{
    copyLink(url) {
        navigator.clipboard.writeText(url);
        this.$store.toast?.show('Link berhasil disalin!', 'success') || alert('Link berhasil disalin!');
    }
}">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 mb-1">Antrian Digital</h1>
            <p class="text-slate-500">Kelola lokasi antrian digital Anda</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('queue.analytics') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-teal-200 text-teal-600 font-semibold text-sm hover:bg-teal-50 transition-colors shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Analytics
            </a>
            @if($canCreate)
            <a href="{{ route('queue.locations.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl btn-gradient text-white font-semibold text-sm transition-colors shadow-sm cursor-pointer shadow-indigo-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Buat Lokasi
            </a>
            @endif
        </div>
    </div>

    @if($locations->isEmpty())
    <div class="bg-white rounded-xl border border-slate-200 p-10 text-center shadow-sm">
        <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4 text-slate-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
        </div>
        <h3 class="text-lg font-bold text-slate-900 mb-2">Belum ada lokasi antrian</h3>
        <p class="text-slate-500 mb-6">Buat lokasi pertama Anda untuk mulai menggunakan sistem antrian digital.</p>
        @if($canCreate)
        <a href="{{ route('queue.locations.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl btn-gradient text-white font-semibold text-sm transition-colors shadow-sm">
            Buat Lokasi Sekarang
        </a>
        @endif
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($locations as $location)
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm flex flex-col hover:border-teal-300 transition-colors">
            <div class="p-6 border-b border-slate-100 flex-grow">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 mb-1">{{ $location->name }}</h3>
                        <p class="text-sm text-slate-500 line-clamp-2">{{ $location->address ?: 'Tidak ada alamat' }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $location->is_active ? 'bg-teal-50 text-teal-600' : 'bg-slate-100 text-slate-500' }}">
                        {{ $location->is_active ? 'Buka' : 'Tutup' }}
                    </span>
                </div>

                <div class="grid grid-cols-3 gap-3 mt-6">
                    <div class="bg-amber-50 p-3 rounded-lg border border-amber-100 text-center">
                        <div class="text-2xl font-black text-amber-600 mb-1">{{ $location->today_waiting ?? 0 }}</div>
                        <div class="text-[10px] font-bold text-amber-700 uppercase tracking-wider">Menunggu</div>
                    </div>
                    <div class="bg-indigo-50 p-3 rounded-lg border border-indigo-100 text-center">
                        <div class="text-2xl font-black text-indigo-600 mb-1">{{ $location->today_serving ?? 0 }}</div>
                        <div class="text-[10px] font-bold text-indigo-700 uppercase tracking-wider">Dilayani</div>
                    </div>
                    <div class="bg-green-50 p-3 rounded-lg border border-green-100 text-center">
                        <div class="text-2xl font-black text-green-600 mb-1">{{ $location->today_completed ?? 0 }}</div>
                        <div class="text-[10px] font-bold text-green-700 uppercase tracking-wider">Selesai</div>
                    </div>
                </div>
            </div>
            
            <div class="p-4 bg-slate-50 flex items-center justify-between rounded-b-xl gap-2">
                <a href="{{ route('queue.locations.manage', $location->id) }}" class="flex-1 py-2 text-center bg-white border border-slate-200 rounded-lg text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors">
                    Kelola
                </a>
                <button @click="copyLink('{{ route('queue.register', $location->uuid) }}')" class="px-4 py-2 bg-white border border-slate-200 rounded-lg text-slate-500 hover:text-teal-600 hover:bg-teal-50 transition-colors" title="Copy Link Pendaftaran">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" /></svg>
                </button>
                <a href="{{ route('queue.display', $location->uuid) }}" target="_blank" class="px-4 py-2 bg-white border border-slate-200 rounded-lg text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="Buka TV Display">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
