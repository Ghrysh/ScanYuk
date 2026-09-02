@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-3xl shadow-md overflow-hidden border border-slate-200 p-12 text-center">
        <div class="w-24 h-24 bg-yellow-50 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-yellow-500 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        
        <h2 class="text-2xl font-extrabold text-slate-900 mb-4">Permintaan Sedang Diproses</h2>
        <p class="text-slate-500 mb-8 max-w-lg mx-auto leading-relaxed">
            Permintaan akses Anda untuk fitur Sistem Antrian Digital telah kami terima dan sedang menunggu persetujuan dari tim admin kami. Kami akan meninjau akun Anda secepatnya.
        </p>
        
        <a href="{{ route('user.dashboard') }}" class="inline-block px-6 py-3 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200 transition-colors">
            Kembali ke Dashboard Utama
        </a>
    </div>
</div>
@endsection
