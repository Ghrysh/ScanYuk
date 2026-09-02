@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-200">
        <div class="bg-gradient-to-r from-teal-500 to-indigo-600 px-8 py-12 text-center relative overflow-hidden">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            <h2 class="text-3xl font-extrabold text-white relative z-10">Sistem Antrian Digital</h2>
            <p class="mt-4 text-teal-100 text-lg relative z-10 max-w-2xl mx-auto">Solusi antrian cerdas yang terintegrasi dengan Augmented Reality dan Notifikasi Suara.</p>
        </div>
        
        <div class="p-8 text-center bg-slate-50">
            <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto shadow-md mb-6 border border-slate-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            </div>
            
            <h3 class="text-xl font-bold text-slate-900 mb-2">Akses Terbatas</h3>
            <p class="text-slate-500 mb-8 max-w-md mx-auto">Fitur Sistem Antrian saat ini hanya tersedia berdasarkan permintaan. Klik tombol di bawah untuk mengajukan akses penggunaan fitur ini ke admin kami.</p>
            
            <form action="{{ route('queue.request.submit') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-8 py-4 btn-gradient text-white font-bold rounded-xl shadow-lg shadow-teal-200/50 hover:-translate-y-0.5 transition-all text-lg">
                    Request Akses Sistem Antrian
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
