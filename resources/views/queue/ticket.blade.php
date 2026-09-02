@extends('layouts.app')

@section('content')
<div class="w-full max-w-md mx-auto min-h-[calc(100vh-64px)] sm:min-h-screen p-4 flex flex-col justify-center items-center relative overflow-hidden"
    x-data="{
        status: '{{ $ticket->status }}',
        position: {{ $position }},
        estimatedWait: {{ $estimatedWait }},
        counterName: '{{ $ticket->counter?->name ?? '' }}',
        calledAt: '{{ $ticket->called_at }}',
        audioPlayed: false,
        init() {
            this.pollStatus();
        },
        pollStatus() {
            setInterval(async () => {
                try {
                    const res = await fetch('/api/queue/ticket/{{ $ticket->id }}/status');
                    const data = await res.json();
                    if (data.status !== this.status && data.status === 'called' && !this.audioPlayed) {
                        this.playNotification();
                        this.audioPlayed = true;
                    }
                    if (data.status === 'waiting') { this.audioPlayed = false; }
                    this.status = data.status;
                    this.position = data.position;
                    this.estimatedWait = data.estimated_wait;
                    this.counterName = data.counter_name || '';
                    this.calledAt = data.called_at || '';
                } catch(e) {}
            }, 5000);
        },
        playNotification() {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                [0, 0.2, 0.4].forEach((delay, i) => {
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.frequency.value = 600 + (i * 200);
                    gain.gain.value = 0.3;
                    osc.start(ctx.currentTime + delay);
                    osc.stop(ctx.currentTime + delay + 0.15);
                });
            } catch(e) {}
        },
        statusLabel() {
            const labels = { waiting: 'Menunggu', called: 'Dipanggil!', serving: 'Sedang Dilayani', completed: 'Selesai', skipped: 'Dilewati', no_show: 'Tidak Hadir' };
            return labels[this.status] || this.status;
        },
        statusColor() {
            const colors = { 
                waiting: 'text-amber-600 bg-amber-50 border-amber-200', 
                called: 'text-teal-600 bg-teal-50 border-teal-200 animate-pulse', 
                serving: 'text-indigo-600 bg-indigo-50 border-indigo-200', 
                completed: 'text-green-600 bg-green-50 border-green-200', 
                skipped: 'text-red-600 bg-red-50 border-red-200', 
                no_show: 'text-slate-600 bg-slate-100 border-slate-200' 
            };
            return colors[this.status] || '';
        }
    }">

    {{-- Background decoration --}}
    <div class="absolute top-0 right-0 w-64 h-64 bg-teal-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 -z-10 animate-blob"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 -z-10 animate-blob animation-delay-2000"></div>

    <div class="w-full bg-white rounded-[2rem] shadow-2xl border border-slate-100 overflow-hidden relative">
        {{-- Status banner --}}
        <div class="w-full text-center py-3 font-bold text-sm tracking-wider uppercase border-b transition-colors duration-500" :class="statusColor()">
            <span x-text="statusLabel()"></span>
        </div>

        <div class="p-8 text-center flex flex-col items-center">
            <p class="text-sm font-bold text-slate-500 mb-2">{{ $ticket->location->name }}</p>
            <h2 class="text-xl font-bold text-slate-900 mb-6">{{ $ticket->service->name }}</h2>

            <div class="w-48 h-48 rounded-full flex items-center justify-center shadow-inner mb-8 transition-colors duration-500" :class="status === 'called' ? 'bg-teal-50 shadow-teal-100 ring-8 ring-teal-50' : 'bg-slate-50 shadow-slate-100'">
                <span class="text-5xl font-black text-slate-900 tracking-tighter" :class="status === 'called' ? 'text-teal-600' : ''">{{ $ticket->queue_number }}</span>
            </div>

            <p class="text-lg font-bold text-slate-800 mb-1">{{ $ticket->customer_name }}</p>
            <p class="text-sm text-slate-500 mb-8">No. Tiket: {{ $ticket->ticket_code }}</p>

            {{-- Dynamic info based on status --}}
            <template x-if="status === 'waiting'">
                <div class="w-full grid grid-cols-2 gap-4">
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Posisi ke-</div>
                        <div class="text-2xl font-black text-slate-900" x-text="position"></div>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Estimasi Waktu</div>
                        <div class="text-2xl font-black text-slate-900"><span x-text="estimatedWait"></span><span class="text-sm font-bold text-slate-500 ml-1">mnt</span></div>
                    </div>
                </div>
            </template>

            <template x-if="status === 'called' || status === 'serving'">
                <div class="w-full bg-teal-50 border border-teal-100 p-6 rounded-2xl animate-bounce" style="animation-iteration-count: 3; animation-duration: 1s;">
                    <div class="text-xs font-bold text-teal-600 uppercase tracking-widest mb-1">Silakan menuju ke</div>
                    <div class="text-3xl font-black text-teal-700" x-text="counterName"></div>
                </div>
            </template>

            <template x-if="status === 'completed'">
                <div class="w-full bg-green-50 border border-green-100 p-6 rounded-2xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-green-500 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <div class="font-bold text-green-700">Pelayanan Selesai</div>
                    <div class="text-sm text-green-600 mt-1">Terima kasih atas kunjungan Anda</div>
                </div>
            </template>

            <template x-if="status === 'skipped' || status === 'no_show'">
                <div class="w-full bg-red-50 border border-red-100 p-6 rounded-2xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-red-500 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <div class="font-bold text-red-700">Antrian Dilewati</div>
                    <div class="text-sm text-red-600 mt-1">Harap mendaftar ulang jika masih ingin dilayani</div>
                </div>
            </template>
        </div>
        
        <div class="bg-slate-50 p-4 text-center border-t border-slate-100">
            <p class="text-xs font-medium text-slate-400">Harap jangan menutup halaman ini atau simpan link untuk mengecek status.</p>
        </div>
    </div>
</div>
@endsection
