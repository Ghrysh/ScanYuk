<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Staff Dashboard - ScanYuk</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
        .bg-grid-pattern {
            background-size: 40px 40px;
            background-image: linear-gradient(to right, rgba(0,0,0,0.03) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(0,0,0,0.03) 1px, transparent 1px);
        }
    </style>
</head>
<body class="bg-slate-50 bg-grid-pattern min-h-screen text-slate-800 flex flex-col" x-data="{
        confirmTitle: '',
        confirmDesc: '',
        confirmTarget: null,
        showConfirm: false,
        serviceFilter: '',
        confirmAction(title, desc, target) {
            this.confirmTitle = title;
            this.confirmDesc = desc;
            this.confirmTarget = target;
            this.showConfirm = true;
        },
        executeConfirm() {
            if (this.confirmTarget) {
                this.confirmTarget.classList.add('submitting');
                this.confirmTarget.submit();
            }
            this.showConfirm = false;
        },
        init() {
            // Auto refresh via Alpine polling
            setInterval(() => {
                if(!document.querySelector('form.submitting') && !this.showConfirm) {
                    window.location.reload();
                }
            }, 10000);
        }
    }">


    {{-- Header --}}
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-[100rem] mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                </div>
                <div>
                    <h1 class="text-base sm:text-lg font-bold text-slate-900">{{ $staff->name }}</h1>
                    <p class="text-xs sm:text-sm text-slate-500">{{ $staff->counter ? $staff->counter->name : 'Tanpa Loket' }} &bull; {{ $staff->location->name }}</p>
                </div>
            </div>
            
            <form action="{{ route('queue.staff.logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center gap-2 text-sm font-semibold text-red-500 hover:text-red-700 hover:bg-red-50 px-3 py-2 rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    <span class="hidden sm:inline">Logout</span>
                </button>
            </form>
        </div>
    </header>

    <main class="flex-grow max-w-[100rem] mx-auto w-full px-4 sm:px-6 lg:px-8 py-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Left: Current Ticket Actions --}}
        <section class="lg:col-span-2 space-y-6">
            
            @if(session('success'))
            <div class="p-4 rounded-xl bg-teal-50 border border-teal-200 text-teal-700 flex items-center gap-3 text-sm font-bold shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 flex items-center gap-3 text-sm font-bold shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                {{ session('error') }}
            </div>
            @endif

            {{-- CURRENT TICKET --}}
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 p-8 text-center relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-teal-50 rounded-full mix-blend-multiply filter blur-2xl opacity-50 -z-10"></div>
                
                @if($currentTicket)
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-sm font-bold mb-6 {{ $currentTicket->status == 'called' ? 'bg-amber-100 text-amber-700 animate-pulse' : 'bg-teal-100 text-teal-700' }}">
                        <span class="w-2 h-2 rounded-full {{ $currentTicket->status == 'called' ? 'bg-amber-500' : 'bg-teal-500' }}"></span>
                        {{ $currentTicket->status == 'called' ? 'Sedang Dipanggil' : 'Sedang Dilayani' }}
                    </div>
                    
                    <h2 class="text-xl font-bold text-slate-500 mb-2">{{ $currentTicket->service->name }}</h2>
                    <div class="text-[6rem] sm:text-[8rem] font-black leading-none text-slate-900 tracking-tighter mb-4">{{ $currentTicket->queue_number }}</div>
                    
                    <div class="text-2xl font-bold text-slate-800 mb-8">{{ $currentTicket->customer_name }}</div>
                    
                    <div class="flex flex-col sm:flex-row justify-center gap-4">
                        @if($currentTicket->status == 'called')
                            <form action="{{ route('queue.staff.serve', $currentTicket->id) }}" method="POST" class="flex-1 max-w-[200px]" onsubmit="this.classList.add('submitting')">
                                @csrf @method('PATCH')
                                <button type="submit" class="w-full py-4 rounded-xl bg-teal-500 hover:bg-teal-600 text-white font-bold text-lg shadow-lg shadow-teal-200 transition-colors">
                                    Mulai Layani
                                </button>
                            </form>
                        @elseif($currentTicket->status == 'serving')
                            <form action="{{ route('queue.staff.complete', $currentTicket->id) }}" method="POST" class="flex-1 max-w-[200px]" onsubmit="this.classList.add('submitting')">
                                @csrf @method('PATCH')
                                <button type="submit" class="w-full py-4 rounded-xl bg-green-500 hover:bg-green-600 text-white font-bold text-lg shadow-lg shadow-green-200 transition-colors">
                                    Selesai
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('queue.staff.skip', $currentTicket->id) }}" method="POST" class="flex-1 max-w-[200px]" @submit.prevent="confirmAction('Lewati antrian ini?', 'Antrian yang dilewati bisa dipanggil kembali nanti.', $event.target)">
                            @csrf @method('PATCH')
                            <button type="submit" class="w-full py-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-lg transition-colors border border-slate-200">
                                Lewati (Skip)
                            </button>
                        </form>
                    </div>
                @else
                    <div class="py-10 flex flex-col items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 text-slate-200 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                        <h2 class="text-2xl font-bold text-slate-400 mb-2">Belum ada antrian aktif</h2>
                        <p class="text-slate-500">Klik "Panggil Berikutnya" untuk memanggil nomor antrian.</p>
                    </div>
                @endif
            </div>

            {{-- CALL NEXT --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col sm:flex-row items-center gap-4">
                <form action="{{ route('queue.staff.call-next') }}" method="POST" class="w-full flex flex-col sm:flex-row gap-4" onsubmit="this.classList.add('submitting')">
                    @csrf
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Filter Layanan (Opsional)</label>
                        <select name="service_id" x-model="serviceFilter" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-semibold text-slate-700 outline-none focus:border-teal-500">
                            <option value="">-- Panggil dari Semua Layanan --</option>
                            @foreach($services as $svc)
                            <option value="{{ $svc->id }}">{{ $svc->name }} ({{ $svc->prefix }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="sm:mt-6 px-8 py-3 rounded-xl bg-gradient-to-r from-teal-500 to-indigo-500 hover:opacity-90 text-white font-bold shadow-lg flex items-center justify-center gap-2 transition-opacity" {{ $currentTicket ? 'disabled' : '' }} :class="'{{ $currentTicket ? true : false }}' ? 'opacity-50 cursor-not-allowed' : ''">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>
                        Panggil Berikutnya
                    </button>
                </form>
            </div>
            
        </section>

        {{-- Right: Waiting List & Stats --}}
        <section class="lg:col-span-1 space-y-6">
            
            <div class="bg-indigo-50 rounded-xl border border-indigo-100 p-6 flex items-center justify-between shadow-sm">
                <div>
                    <div class="text-sm font-bold text-indigo-600 uppercase tracking-widest mb-1">Selesai Hari Ini</div>
                    <div class="text-3xl font-black text-indigo-700">{{ $completedCount }} <span class="text-lg font-bold text-indigo-400">Orang</span></div>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden flex flex-col h-[500px]">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="font-bold text-slate-900">Antrian Menunggu</h3>
                    <span class="bg-slate-200 text-slate-600 px-2 py-0.5 rounded-full text-xs font-bold">{{ $waitingTickets->count() }}</span>
                </div>
                
                <div class="flex-grow overflow-y-auto no-scrollbar p-2 divide-y divide-slate-50">
                    @forelse($waitingTickets as $ticket)
                    <div class="p-3 hover:bg-slate-50 rounded-lg transition-colors flex items-center justify-between group">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-lg bg-slate-100 flex items-center justify-center font-black text-slate-700 border border-slate-200 group-hover:border-teal-300 group-hover:bg-teal-50 group-hover:text-teal-600 transition-colors">
                                {{ $ticket->queue_number }}
                            </div>
                            <div>
                                <div class="font-bold text-slate-900 text-sm">{{ $ticket->customer_name }}</div>
                                <div class="text-xs font-semibold text-teal-600">{{ $ticket->service->name }}</div>
                            </div>
                        </div>
                        <form action="{{ route('queue.staff.call-specific', $ticket->id) }}" method="POST" onsubmit="this.classList.add('submitting')">
                            @csrf
                            <button type="submit" class="p-2 text-slate-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-colors" title="Panggil Antrian Ini" {{ $currentTicket ? 'disabled' : '' }}>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>
                            </button>
                        </form>
                    </div>
                    @empty
                    <div class="h-full flex flex-col items-center justify-center text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <p class="text-sm font-medium">Tidak ada antrian</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </section>

    </main>


    <!-- Confirm Modal -->
    <div x-show="showConfirm" style="display: none;" class="fixed inset-0 z-[999] flex items-center justify-center p-4">
        <div x-show="showConfirm" x-transition.opacity.duration.300ms class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showConfirm = false"></div>
        <div x-show="showConfirm" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            class="relative bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 text-center">
            
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            </div>
            
            <h3 class="text-xl font-bold text-slate-900 mb-2" x-text="confirmTitle"></h3>
            <p class="text-slate-500 text-sm mb-6" x-text="confirmDesc"></p>
            
            <div class="flex gap-3 w-full">
                <button type="button" @click="showConfirm = false" class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl transition-colors">Batal</button>
                <button type="button" @click="executeConfirm()" class="flex-1 py-3 bg-red-500 hover:bg-red-600 text-white font-bold rounded-xl transition-colors">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>

</body>
</html>
