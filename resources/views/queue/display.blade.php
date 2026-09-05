<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%230d9488' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect width='5' height='5' x='3' y='3' rx='1'%3E%3C/rect%3E%3Crect width='5' height='5' x='16' y='3' rx='1'%3E%3C/rect%3E%3Crect width='5' height='5' x='3' y='16' rx='1'%3E%3C/rect%3E%3Cpath d='M21 16h-3a2 2 0 0 0-2 2v3'%3E%3C/path%3E%3Cpath d='M21 21v.01'%3E%3C/path%3E%3Cpath d='M12 7v3a2 2 0 0 1-2 2H7'%3E%3C/path%3E%3Cpath d='M3 12h.01'%3E%3C/path%3E%3Cpath d='M12 3h.01'%3E%3C/path%3E%3Cpath d='M12 16v.01'%3E%3C/path%3E%3Cpath d='M16 12h1'%3E%3C/path%3E%3Cpath d='M21 12v.01'%3E%3C/path%3E%3Cpath d='M12 21v-1'%3E%3C/path%3E%3C/svg%3E">
    <title>Display Antrian - {{ $location->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-slate-900 text-white h-screen flex flex-col overflow-hidden" 
    x-data="{
        calledTickets: @js($calledTickets),
        waitingTickets: @js($waitingTickets),
        currentTime: '',
        currentDate: '',
        lastUpdate: null,
        
        init() {
            this.updateClock();
            setInterval(() => this.updateClock(), 1000);
            this.pollData();
        },
        
        updateClock() {
            const now = new Date();
            this.currentTime = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            this.currentDate = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        },
        
        async pollData() {
            setInterval(async () => {
                try {
                    const res = await fetch('/api/queue/display/{{ $location->uuid }}');
                    const data = await res.json();
                    
                    // Check if there's a newly called ticket to play sound
                    const oldIds = this.calledTickets.map(t => t.id);
                    const newCalled = data.called.filter(t => !oldIds.includes(t.id));
                    if (newCalled.length > 0) {
                        this.playBell();
                    }
                    
                    this.calledTickets = data.called;
                    this.waitingTickets = data.waiting;
                } catch(e) {
                    console.error('Failed to fetch display data', e);
                }
            }, 3000);
        },
        
        playBell() {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                
                // Westminster chime pattern (simple approximation)
                const playNote = (freq, delay, duration) => {
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(freq, ctx.currentTime + delay);
                    
                    gain.gain.setValueAtTime(0, ctx.currentTime + delay);
                    gain.gain.linearRampToValueAtTime(0.5, ctx.currentTime + delay + 0.1);
                    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + delay + duration);
                    
                    osc.start(ctx.currentTime + delay);
                    osc.stop(ctx.currentTime + delay + duration);
                };
                
                // Notes: E4, C4, D4, G3
                playNote(329.63, 0, 1);
                playNote(261.63, 0.5, 1);
                playNote(293.66, 1.0, 1);
                playNote(196.00, 1.5, 2);
                
            } catch(e) {}
        }
    }">

    {{-- Header --}}
    <header class="bg-slate-800 border-b border-slate-700 p-4 sm:p-6 flex items-center justify-between shadow-lg z-10">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-teal-500 rounded-xl flex items-center justify-center text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">{{ $location->name }}</h1>
                <p class="text-slate-400 font-medium text-sm sm:text-base">{{ $location->address }}</p>
            </div>
        </div>
        
        <div class="text-right">
            <div class="text-3xl sm:text-4xl font-black tracking-tighter text-teal-400" x-text="currentTime"></div>
            <div class="text-slate-400 font-medium text-sm sm:text-base uppercase tracking-widest mt-1" x-text="currentDate"></div>
        </div>
    </header>

    {{-- Main Content Split --}}
    <main class="flex-grow flex flex-col md:flex-row overflow-hidden">
        
        {{-- Left: Currently Called (2/3 width) --}}
        <section class="flex-[2] flex flex-col border-r border-slate-800 bg-slate-900/50 p-6 overflow-hidden">
            <h2 class="text-xl font-bold text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-3">
                <span class="w-3 h-3 rounded-full bg-teal-500 animate-pulse"></span>
                Sedang Dipanggil
            </h2>
            
            <div class="flex-grow grid grid-cols-1 lg:grid-cols-2 gap-6 overflow-y-auto no-scrollbar pb-6">
                <template x-for="(ticket, index) in calledTickets" :key="ticket.id">
                    <div class="bg-slate-800 border-2 border-slate-700 rounded-3xl p-6 flex flex-col items-center justify-center relative overflow-hidden transition-all duration-500"
                        :class="index === 0 ? 'border-teal-500 shadow-[0_0_30px_rgba(20,184,166,0.3)] ring-1 ring-teal-400' : ''">
                        
                        <div x-show="index === 0" class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-teal-400 to-indigo-500"></div>
                        
                        <div class="text-slate-400 font-bold uppercase tracking-widest mb-2" x-text="ticket.service_name"></div>
                        
                        <div class="text-[5rem] xl:text-[7rem] font-black leading-none tracking-tighter mb-4 text-white" 
                            :class="index === 0 ? 'text-teal-400' : ''"
                            x-text="ticket.queue_number"></div>
                        
                        <div class="w-full bg-slate-900/50 rounded-2xl p-4 text-center border border-slate-700 mt-auto">
                            <div class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-1">Silakan Menuju</div>
                            <div class="text-2xl xl:text-3xl font-black text-slate-200" x-text="ticket.counter_name || 'Petugas'"></div>
                        </div>
                    </div>
                </template>
                
                <template x-if="calledTickets.length === 0">
                    <div class="col-span-full h-full flex flex-col items-center justify-center text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        <p class="text-2xl font-bold">Belum ada antrian yang dipanggil</p>
                    </div>
                </template>
            </div>
        </section>
        
        {{-- Right: Waiting List (1/3 width) --}}
        <section class="flex-1 flex flex-col bg-slate-800 p-6 overflow-hidden">
            <h2 class="text-xl font-bold text-slate-400 uppercase tracking-widest mb-6 flex items-center justify-between">
                <span>Antrian Menunggu</span>
                <span class="bg-slate-700 text-white px-3 py-1 rounded-full text-sm" x-text="waitingTickets.length"></span>
            </h2>
            
            <div class="flex-grow overflow-y-auto no-scrollbar pb-6 space-y-4 pr-2">
                <template x-for="ticket in waitingTickets" :key="ticket.id">
                    <div class="bg-slate-700/50 rounded-2xl p-4 flex items-center justify-between border border-slate-600 hover:border-slate-500 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-xl bg-slate-800 flex items-center justify-center font-black text-2xl text-white border border-slate-600" x-text="ticket.queue_number"></div>
                            <div>
                                <div class="font-bold text-slate-200 text-lg mb-0.5" x-text="ticket.customer_name"></div>
                                <div class="text-sm font-semibold text-teal-400" x-text="ticket.service_name"></div>
                            </div>
                        </div>
                    </div>
                </template>
                
                <template x-if="waitingTickets.length === 0">
                    <div class="h-full flex flex-col items-center justify-center text-slate-500 pt-10">
                        <p class="text-lg font-medium">Tidak ada antrian menunggu</p>
                    </div>
                </template>
            </div>
        </section>
    </main>
    
    {{-- Footer --}}
    <footer class="bg-slate-900 border-t border-slate-800 p-3 text-center z-10">
        <p class="text-slate-500 font-bold tracking-widest uppercase text-xs">Sistem Antrian Digital by ScanYuk</p>
    </footer>

</body>
</html>
