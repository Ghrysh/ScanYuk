@extends('layouts.app')

@section('content')
<div class="w-full max-w-[400px] mx-auto bg-white rounded-2xl shadow-xl border border-slate-100 p-8 my-10 relative z-10" x-data="{
    selectedLocation: '',
    selectedStaff: '',
    allStaff: @js(\App\Models\QueueStaff::where('is_active', true)->get(['id', 'name', 'queue_location_id'])),
    get filteredStaff() {
        if (!this.selectedLocation) return [];
        return this.allStaff.filter(s => s.queue_location_id == this.selectedLocation);
    }
}">
    
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center gap-2 mb-6">
            <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            </div>
        </div>
        <h2 class="text-2xl font-bold text-slate-900">Login Petugas</h2>
        <p class="mt-2 text-sm text-slate-500">Pilih lokasi dan masukkan PIN Anda</p>
    </div>

    @if(session('error'))
    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 flex items-center gap-3 text-sm font-bold">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
        {{ session('error') }}
    </div>
    @endif

    <form class="space-y-5" action="{{ route('queue.staff.login.post') }}" method="POST">
        @csrf

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Lokasi Antrian</label>
            <select name="location_id" x-model="selectedLocation" required class="appearance-none block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all sm:text-sm">
                <option value="">-- Pilih Lokasi --</option>
                @foreach($locations as $loc)
                <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                @endforeach
            </select>
        </div>

        <div x-show="selectedLocation">
            <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Petugas</label>
            <select name="staff_id" x-model="selectedStaff" required class="appearance-none block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all sm:text-sm">
                <option value="">-- Pilih Petugas --</option>
                <template x-for="staff in filteredStaff" :key="staff.id">
                    <option :value="staff.id" x-text="staff.name"></option>
                </template>
            </select>
        </div>

        <div x-show="selectedStaff">
            <label class="block text-sm font-semibold text-slate-700 mb-1">PIN (4-6 Digit Angka)</label>
            <input type="password" name="pin" required pattern="[0-9]{4,6}"
                class="appearance-none block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all sm:text-sm text-center tracking-widest text-lg font-mono" 
                placeholder="••••">
        </div>

        <button type="submit" class="w-full py-3 px-4 rounded-lg btn-gradient text-white font-bold shadow-lg shadow-teal-200 hover:opacity-90 transition-all hover:-translate-y-0.5 mt-2" :disabled="!selectedStaff" :class="!selectedStaff ? 'opacity-50 cursor-not-allowed' : ''">
            Masuk
        </button>
    </form>
</div>
@endsection
