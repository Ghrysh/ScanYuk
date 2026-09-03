<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%230d9488' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect width='5' height='5' x='3' y='3' rx='1'%3E%3C/rect%3E%3Crect width='5' height='5' x='16' y='3' rx='1'%3E%3C/rect%3E%3Crect width='5' height='5' x='3' y='16' rx='1'%3E%3C/rect%3E%3Cpath d='M21 16h-3a2 2 0 0 0-2 2v3'%3E%3C/path%3E%3Cpath d='M21 21v.01'%3E%3C/path%3E%3Cpath d='M12 7v3a2 2 0 0 1-2 2H7'%3E%3C/path%3E%3Cpath d='M3 12h.01'%3E%3C/path%3E%3Cpath d='M12 3h.01'%3E%3C/path%3E%3Cpath d='M12 16v.01'%3E%3C/path%3E%3Cpath d='M16 12h1'%3E%3C/path%3E%3Cpath d='M21 12v.01'%3E%3C/path%3E%3Cpath d='M12 21v-1'%3E%3C/path%3E%3C/svg%3E">
    <title>Customer Leaderboard</title>
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
        .bg-grid-pattern {
            background-size: 40px 40px;
            background-image: linear-gradient(to right, rgba(255,255,255,0.05) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255,255,255,0.05) 1px, transparent 1px);
        }
        .text-shadow {
            text-shadow: 2px 4px 10px rgba(0,0,0,0.2);
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 bg-grid-pattern min-h-screen text-white overflow-hidden" 
    x-data="{
        customers: {{ Illuminate\Support\Js::from($customers) }},
        userId: '{{ $userId }}',
        currentTime: '',
        currentDate: '',
        updateClock() {
            const now = new Date();
            this.currentTime = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            this.currentDate = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        },
        async fetchData() {
            try {
                const res = await fetch(`/api/queue/display/leaderboard/${this.userId}`);
                if (res.ok) {
                    const data = await res.json();
                    this.customers = data.customers;
                }
            } catch (err) {
                console.error('Error fetching leaderboard:', err);
            }
        },
        init() {
            this.updateClock();
            setInterval(() => this.updateClock(), 1000);
            // Polling every 5 seconds
            setInterval(() => this.fetchData(), 5000);
        }
    }">

    <!-- Header -->
    <header class="p-6 md:p-8 flex justify-between items-center glass-panel border-x-0 border-t-0 shadow-lg relative z-10">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-lg shadow-amber-500/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
            </div>
            <div>
                <h1 class="text-3xl font-black text-white text-shadow tracking-tight">Top Customers</h1>
                <p class="text-indigo-200 font-medium">Klasemen Pelanggan Setia</p>
            </div>
        </div>
        
        <div class="text-right">
            <div class="text-3xl font-black text-white text-shadow tracking-widest font-mono" x-text="currentTime"></div>
            <div class="text-indigo-200 font-medium" x-text="currentDate"></div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="p-6 md:p-8 h-[calc(100vh-100px)] overflow-hidden relative">
        <div class="max-w-6xl mx-auto grid gap-4 relative z-10">
            
            <template x-for="(customer, index) in customers" :key="customer.id">
                <div class="glass-panel rounded-2xl p-4 md:p-6 flex items-center justify-between shadow-xl transition-all duration-500 transform hover:scale-[1.01]"
                    :class="[
                        index === 0 ? 'bg-gradient-to-r from-amber-500/20 to-orange-500/20 border-amber-500/50' : '',
                        index === 1 ? 'bg-gradient-to-r from-slate-300/10 to-slate-400/10 border-slate-300/40' : '',
                        index === 2 ? 'bg-gradient-to-r from-orange-800/20 to-amber-900/20 border-orange-700/40' : ''
                    ]">
                    
                    <div class="flex items-center gap-6">
                        <!-- Rank Badge -->
                        <div class="flex items-center justify-center w-12 h-12 md:w-16 md:h-16 rounded-full font-black text-2xl shadow-lg"
                            :class="[
                                index === 0 ? 'bg-gradient-to-br from-amber-300 to-orange-500 text-white' : 
                                (index === 1 ? 'bg-gradient-to-br from-slate-200 to-slate-400 text-white' : 
                                (index === 2 ? 'bg-gradient-to-br from-orange-300 to-orange-600 text-white' : 
                                'bg-white/10 text-white/50'))
                            ]">
                            <span x-text="index + 1"></span>
                        </div>
                        
                        <!-- Customer Info -->
                        <div>
                            <h2 class="text-2xl md:text-3xl font-black text-white tracking-tight" x-text="customer.name"></h2>
                            <p class="text-indigo-200 font-medium text-lg mt-1"><span x-text="customer.visits"></span>x Kunjungan</p>
                        </div>
                    </div>

                    <!-- Points -->
                    <div class="flex items-center gap-3">
                        <div class="text-right">
                            <div class="text-4xl md:text-5xl font-black text-white text-shadow tabular-nums tracking-tighter" x-text="customer.points"></div>
                            <div class="text-indigo-300 font-bold uppercase tracking-widest text-xs mt-1">Poin Reward</div>
                        </div>
                        <template x-if="index === 0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 md:w-16 md:h-16 text-amber-400 drop-shadow-[0_0_15px_rgba(251,191,36,0.8)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                        </template>
                    </div>
                </div>
            </template>

            <template x-if="customers.length === 0">
                <div class="flex flex-col items-center justify-center py-20 opacity-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    <h2 class="text-3xl font-black">Belum Ada Data</h2>
                    <p class="text-xl mt-2">Daftar pelanggan akan muncul di sini</p>
                </div>
            </template>
        </div>
        
        <!-- Animated Background Orbs -->
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-indigo-500 rounded-full mix-blend-screen filter blur-[128px] opacity-20 animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-amber-500 rounded-full mix-blend-screen filter blur-[128px] opacity-20 animate-pulse" style="animation-delay: 2s;"></div>
    </main>

</body>
</html>
