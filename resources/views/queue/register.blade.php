@extends('layouts.app')

@section('content')
<div class="w-full max-w-lg mx-auto bg-white min-h-[calc(100vh-64px)] sm:min-h-screen sm:border-x border-slate-200 relative pb-20" x-data="{ showForm: {{ $arQrCode ? 'false' : 'true' }}, selectedService: '' }">
    
    {{-- Header --}}
    <div class="bg-gradient-to-br from-teal-600 to-indigo-700 text-white p-6 rounded-b-3xl shadow-lg relative overflow-hidden">
        <!-- Decorative patterns -->
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 20px 20px;"></div>
        <div class="absolute top-0 right-0 w-32 h-32 bg-teal-500 rounded-full mix-blend-multiply filter blur-2xl opacity-50"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-indigo-500 rounded-full mix-blend-multiply filter blur-2xl opacity-50"></div>
        
        <div class="relative z-10">
            <h1 class="text-2xl font-black mb-1 leading-tight">{{ $location->name }}</h1>
            <p class="text-slate-300 text-sm mb-4">{{ $location->address }}</p>
            
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold bg-white/10 backdrop-blur-sm border border-white/20">
                <span class="w-2 h-2 rounded-full {{ $location->is_active ? 'bg-green-400' : 'bg-red-400' }} animate-pulse"></span>
                {{ $location->is_active ? 'Buka' : 'Tutup' }}
            </div>
        </div>
    </div>

    {{-- AR Section --}}
    @if($arQrCode)
    <div x-show="!showForm" x-transition.opacity class="p-6">
        <div class="bg-slate-50 rounded-2xl border border-slate-200 overflow-hidden shadow-inner mb-6 relative">
            @if($arQrCode->ar_type == '2d')
                <img src="{{ Storage::url($arQrCode->image_path) }}" alt="AR Content" class="w-full h-auto object-contain max-h-96">
            @elseif($arQrCode->ar_type == '3d')
                <div class="w-full h-80 relative">
                    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.4.0/model-viewer.min.js"></script>
                    <model-viewer src="{{ Storage::url($arQrCode->model_3d_path) }}" auto-rotate camera-controls shadow-intensity="1" class="w-full h-full bg-slate-100" ar ar-modes="webxr scene-viewer quick-look"></model-viewer>
                </div>
            @endif
        </div>

        <button @click="showForm = true; setTimeout(() => window.scrollTo({top: document.body.scrollHeight, behavior: 'smooth'}), 100)" class="w-full py-4 rounded-xl btn-gradient text-white font-bold text-lg shadow-lg shadow-teal-200/50 flex items-center justify-center gap-2 animate-bounce">
            Ambil Nomor Antrian
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" /></svg>
        </button>
    </div>
    @endif

    {{-- Registration Form --}}
    <div x-show="showForm" x-transition.opacity class="p-6">
        <h2 class="text-xl font-bold text-slate-900 mb-6">Pilih Layanan</h2>
        
        <form action="{{ route('queue.register.store', $location->uuid) }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="space-y-3">
                @foreach($location->services as $service)
                @php
                    $isFull = $service->daily_quota && $service->daily_quota <= ($service->today_registrations_count ?? 0);
                @endphp
                <label class="flex items-start gap-4 p-4 border rounded-xl cursor-pointer transition-all {{ $isFull ? 'bg-slate-50 border-slate-200 opacity-60' : 'bg-white border-slate-200 hover:border-teal-500 peer-checked:border-teal-500 peer-checked:ring-1 peer-checked:ring-teal-500' }}" :class="selectedService == '{{ $service->id }}' ? 'border-teal-500 ring-1 ring-teal-500 shadow-sm bg-teal-50/30' : ''">
                    <div class="mt-1">
                        <input type="radio" name="queue_service_id" value="{{ $service->id }}" x-model="selectedService" class="w-4 h-4 text-teal-600 focus:ring-teal-500" {{ $isFull ? 'disabled' : 'required' }}>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="font-bold text-slate-900">{{ $service->name }}</h3>
                            <span class="w-6 h-6 rounded bg-slate-100 text-slate-600 font-bold text-xs flex items-center justify-center">{{ $service->prefix }}</span>
                        </div>
                        <p class="text-xs text-slate-500">Estimasi: {{ $service->estimated_duration_minutes }} menit</p>
                        @if($service->daily_quota)
                        <p class="text-xs font-semibold mt-1 {{ $isFull ? 'text-red-500' : 'text-teal-600' }}">
                            {{ $isFull ? 'Kuota Penuh' : 'Tersedia ' . ($service->daily_quota - ($service->today_registrations_count ?? 0)) . ' slot lagi' }}
                        </p>
                        @endif
                    </div>
                </label>
                @endforeach
            </div>

            <div class="pt-6 border-t border-slate-100">
                <div class="mb-4">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="customer_name" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all" placeholder="Masukkan nama Anda">
                    @error('customer_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nomor HP / WhatsApp (Opsional)</label>
                    <input type="text" name="customer_phone" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all" placeholder="08xxxxxxxxxx">
                    @error('customer_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                @if($location->is_active)
                <button type="submit" class="w-full py-4 rounded-xl btn-gradient text-white font-bold text-lg shadow-lg shadow-teal-200/50 hover:-translate-y-0.5 transition-transform">
                    Daftar Antrian
                </button>
                @else
                <button type="button" disabled class="w-full py-4 rounded-xl bg-slate-300 text-slate-500 font-bold text-lg cursor-not-allowed">
                    Lokasi Sedang Tutup
                </button>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection
