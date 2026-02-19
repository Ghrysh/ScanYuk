@extends('layouts.app')

@section('content')

<section class="w-full pt-24 pb-12 md:pt-32 md:pb-16 px-6 bg-white text-center relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full max-w-6xl pointer-events-none z-0 opacity-40">
        <div class="absolute top-20 right-1/4 w-96 h-96 bg-teal-50 rounded-full mix-blend-multiply filter blur-3xl animate-blob"></div>
        <div class="absolute bottom-20 left-1/4 w-96 h-96 bg-indigo-50 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000"></div>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto">
        <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-6 tracking-tight">
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-500 to-indigo-600">Pricing</span> Plans
        </h1>
        <p class="text-lg md:text-xl text-slate-500 leading-relaxed max-w-2xl mx-auto">
            Pilih paket yang sesuai kebutuhan Anda. Kuota tidak expired selama belum dipakai.
        </p>
    </div>
</section>

<section class="w-full px-6 bg-white relative z-10">
    <div class="max-w-[90rem] mx-auto">
        
        <div class="flex items-center justify-center gap-3 mb-12">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-indigo-900" viewBox="0 0 24 24" fill="currentColor">
                <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" />
            </svg>
            <h2 class="text-2xl md:text-3xl font-bold text-slate-900">Consumer Pricing</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 items-start">
            
            @foreach($packages as $package)
                <div class="{{ $package->is_popular 
                    ? 'relative p-8 rounded-2xl border border-indigo-100 bg-white shadow-xl shadow-indigo-100 transform lg:-translate-y-4 z-10 flex flex-col h-full' 
                    : 'p-8 rounded-2xl border border-slate-200 bg-white flex flex-col h-full' 
                }}">
                    
                    @if($package->is_popular)
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
                        @foreach($package->features as $feature)
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-teal-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg> 
                                <span>{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <a href="{{ route('register') }}" class="mt-auto block w-full py-3 rounded-lg font-semibold text-center transition-all duration-200 
                        {{ $package->is_popular 
                            ? 'btn-gradient text-white hover:opacity-90 shadow-lg shadow-indigo-200' 
                            : 'border border-teal-500 text-teal-600 hover:bg-teal-50' 
                        }}">
                        {{ $package->button_text }}
                    </a>

                </div>
            @endforeach

        </div>
    </div>
</section>

<section class="w-full px-6 py-20 bg-slate-50">
    <div class="max-w-5xl mx-auto">
        
        <div class="flex items-center justify-center gap-3 mb-10">
            <span class="text-4xl">🏢</span>
            <h2 class="text-2xl md:text-3xl font-bold text-slate-900">Business Pricing</h2>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 p-8 md:p-12 text-center shadow-lg">
            <h3 class="text-2xl font-bold text-slate-900 mb-2">Enterprise / Custom</h3>
            <p class="text-slate-500 mb-10 max-w-2xl mx-auto">
                Solusi khusus untuk kebutuhan bisnis Anda dengan harga yang disesuaikan.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 text-left max-w-3xl mx-auto mb-10">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    <span class="text-slate-600">Custom pricing per campaign</span>
                </div>
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    <span class="text-slate-600">Unlimited scan options</span>
                </div>
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    <span class="text-slate-600">Multi-user dashboard</span>
                </div>
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    <span class="text-slate-600">Bulk QR creation</span>
                </div>
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    <span class="text-slate-600">Advanced analytics & export</span>
                </div>
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    <span class="text-slate-600">SLA & dedicated account manager</span>
                </div>
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    <span class="text-slate-600">White-label branding</span>
                </div>
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    <span class="text-slate-600">Annual contract available</span>
                </div>
            </div>

            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-lg btn-gradient text-white font-bold hover:scale-105 transition-transform shadow-lg shadow-indigo-200">
                Contact Sales
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>

        </div>
    </div>
</section>

@endsection