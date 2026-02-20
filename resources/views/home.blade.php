@extends('layouts.app')

@section('content')
<div class="w-full flex flex-col items-center justify-center px-4 text-center py-32 md:py-48 relative">
    
    <div class="mb-8 p-6 rounded-2xl bg-teal-100/50 border border-teal-200 text-teal-600 shadow-lg shadow-teal-100/50 animate-fade-in-up">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-qr-code w-12 h-12 text-primary"><rect width="5" height="5" x="3" y="3" rx="1"></rect><rect width="5" height="5" x="16" y="3" rx="1"></rect><rect width="5" height="5" x="3" y="16" rx="1"></rect><path d="M21 16h-3a2 2 0 0 0-2 2v3"></path><path d="M21 21v.01"></path><path d="M12 7v3a2 2 0 0 1-2 2H7"></path><path d="M3 12h.01"></path><path d="M12 3h.01"></path><path d="M12 16v.01"></path><path d="M16 12h1"></path><path d="M21 12v.01"></path><path d="M12 21v-1"></path></svg>
    </div>

    <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-6">
        <span class="text-slate-900">Scan</span><span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-500 to-indigo-600">Yuk</span>
    </h1>

    <p class="text-lg md:text-xl text-slate-500 max-w-2xl leading-relaxed mb-10">
        Arahkan kamera untuk scan QR Code atau objek dan lihat pengalaman Augmented Reality.
    </p>

    <a href="{{ route('scan-ar') }}" class="group relative px-8 py-4 rounded-lg btn-gradient text-white font-semibold text-lg shadow-xl shadow-indigo-200 hover:scale-105 transition-transform duration-200 flex items-center justify-center gap-3 w-fit">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
        </svg>
        Mulai Scan
    </a>

    <p class="mt-8 text-sm text-slate-400 font-medium">
        Scan QR Code atau objek langsung dari browser. Tanpa install aplikasi.
    </p>

</div>
@endsection