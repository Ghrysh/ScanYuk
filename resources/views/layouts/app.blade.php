<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $seoData->meta_title ?? 'Scan Yuk - Platform AR QR Code' }}</title>
    @if(isset($seoData->meta_description))
    <meta name="description" content="{{ $seoData->meta_description }}">
    @endif

    @if(isset($seoData->faq_schema) && is_array($seoData->faq_schema) && count($seoData->faq_schema) > 0)
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "FAQPage",
      "mainEntity": [
        @foreach($seoData->faq_schema as $index => $faq)
        {
          "@@type": "Question",
          "name": "{{ $faq['question'] ?? '' }}",
          "acceptedAnswer": {
            "@@type": "Answer",
            "text": "{{ $faq['answer'] ?? '' }}"
          }
        }@if(!$loop->last),@endif 
        @endforeach
      ]
    }
    </script>
    @endif



    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%230d9488' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect width='5' height='5' x='3' y='3' rx='1'%3E%3C/rect%3E%3Crect width='5' height='5' x='16' y='3' rx='1'%3E%3C/rect%3E%3Crect width='5' height='5' x='3' y='16' rx='1'%3E%3C/rect%3E%3Cpath d='M21 16h-3a2 2 0 0 0-2 2v3'%3E%3C/path%3E%3Cpath d='M21 21v.01'%3E%3C/path%3E%3Cpath d='M12 7v3a2 2 0 0 1-2 2H7'%3E%3C/path%3E%3Cpath d='M3 12h.01'%3E%3C/path%3E%3Cpath d='M12 3h.01'%3E%3C/path%3E%3Cpath d='M12 16v.01'%3E%3C/path%3E%3Cpath d='M16 12h1'%3E%3C/path%3E%3Cpath d='M21 12v.01'%3E%3C/path%3E%3Cpath d='M12 21v-1'%3E%3C/path%3E%3C/svg%3E">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            text: '#1e293b',
                            subtext: '#64748b',
                            primary: '#6366f1',
                            secondary: '#14b8a6',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        .bg-grid-pattern {
            background-size: 50px 50px;
            background-image: 
                linear-gradient(to right, rgba(0,0,0,0.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(0,0,0,0.03) 1px, transparent 1px);
            background-color: #fafafa;
        }
        
        .text-gradient {
            background: linear-gradient(to right, #0d9488, #7c3aed);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-gradient {
            background: linear-gradient(90deg, #14b8a6 0%, #8b5cf6 100%);
            transition: opacity 0.3s ease;
        }
        .btn-gradient:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body class="font-sans antialiased text-brand-text bg-grid-pattern min-h-screen flex flex-col">

    @if(request()->is('admin*'))
        <nav class="sticky top-0 z-50 w-full bg-white/80 backdrop-blur-md border-b border-slate-200 shadow-sm">
            <div class="max-w-[100rem] mx-auto px-6 md:px-12 py-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded bg-teal-50 text-teal-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><rect width="5" height="5" x="3" y="3" rx="1"></rect><rect width="5" height="5" x="16" y="3" rx="1"></rect><rect width="5" height="5" x="3" y="16" rx="1"></rect><path d="M21 16h-3a2 2 0 0 0-2 2v3"></path><path d="M21 21v.01"></path><path d="M12 7v3a2 2 0 0 1-2 2H7"></path><path d="M3 12h.01"></path><path d="M12 3h.01"></path><path d="M12 16v.01"></path><path d="M16 12h1"></path><path d="M21 12v.01"></path><path d="M12 21v-1"></path></svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-slate-900">ScanYuk Admin</span>
                </div>
                
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" x2="9" y1="12" y2="12"></line></svg>
                        Logout
                    </button>
                </form>
            </div>
        </nav>

    @elseif(request()->is('dashboard*'))
        <nav class="sticky top-0 z-50 w-full bg-white/80 backdrop-blur-md border-b border-slate-200 shadow-sm">
            <div class="max-w-[100rem] mx-auto px-6 md:px-12 py-4 flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                    <div class="w-8 h-8 rounded bg-teal-50 text-teal-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="5" height="5" x="3" y="3" rx="1"></rect><rect width="5" height="5" x="16" y="3" rx="1"></rect><rect width="5" height="5" x="3" y="16" rx="1"></rect><path d="M21 16h-3a2 2 0 0 0-2 2v3"></path><path d="M21 21v.01"></path><path d="M12 7v3a2 2 0 0 1-2 2H7"></path><path d="M3 12h.01"></path><path d="M12 3h.01"></path><path d="M12 16v.01"></path><path d="M16 12h1"></path><path d="M21 12v.01"></path><path d="M12 21v-1"></path></svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-slate-900">ScanYuk</span>
                </a>
                
                <div class="flex items-center gap-6">
                    <div class="hidden md:flex items-center gap-1">
                        <a href="{{ route('user.dashboard') }}" class="px-3 py-1.5 rounded-lg text-sm font-semibold transition-colors {{ request()->is('dashboard') && !request()->is('dashboard/queue*') ? 'text-teal-700 bg-teal-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">Augmented Reality</a>
                        <a href="{{ route('queue.index') }}" class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-semibold transition-colors {{ request()->is('dashboard/queue*') ? 'text-teal-700 bg-teal-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                            Sistem Antrian
                            @if(Auth::check() && Auth::user()->queue_status === 'active')
                            <span class="px-1.5 py-0.5 rounded-full bg-red-100 text-red-600 text-[10px] font-black uppercase tracking-wider">Baru</span>
                            @endif
                        </a>
                    </div>
                    <div class="hidden md:flex items-center text-sm font-medium text-slate-600">
                        {{ Auth::user()->name }} <span class="mx-2 text-slate-300">•</span> 
                        <span class="text-teal-600 font-bold capitalize">{{ Auth::user()->role }}</span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" x2="9" y1="12" y2="12"></line></svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </nav>

    @else
        @include('partials.navbar')
    @endif

    <main class="flex-grow flex flex-col">
        @yield('content')
    </main>

    @if(!request()->is('admin*') && !request()->is('dashboard*'))
        @include('partials.footer')
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = encodeURIComponent(window.location.pathname);
            
            fetch('/sys-ping/v1?path=' + currentPath, {
                method: 'GET',
                keepalive: true,
                credentials: 'same-origin',
                headers: { 'Accept': 'application/json' }
            }).catch(e => {});
        });
    </script>

    @auth
    @php
        $paymentNotification = auth()->user()->unreadNotifications->where('data.type', 'payment_verification')->first();
    @endphp
    @if($paymentNotification)
        <div x-data="{ 
                show: true,
                markAsRead() {
                    fetch('/notifications/{{ $paymentNotification->id }}/read', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Accept': 'application/json'
                        }
                    });
                    this.show = false;
                }
            }" 
            x-show="show" 
            class="fixed inset-0 z-[200] flex items-center justify-center p-4" style="display: none;">
            <div x-show="show" x-transition.opacity class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            <div x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden flex flex-col p-6 text-center border-t-4 {{ $paymentNotification->data['status'] == 'diterima' ? 'border-teal-500' : 'border-red-500' }}">
                <div class="mx-auto w-16 h-16 rounded-full flex items-center justify-center mb-4 {{ $paymentNotification->data['status'] == 'diterima' ? 'bg-teal-50 text-teal-500' : 'bg-red-50 text-red-500' }}">
                    @if($paymentNotification->data['status'] == 'diterima')
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    @else
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    @endif
                </div>
                <h3 class="font-bold text-slate-900 text-xl mb-2">
                    {{ $paymentNotification->data['status'] == 'diterima' ? 'Pembayaran Berhasil' : 'Pembayaran Ditolak' }}
                </h3>
                <p class="text-sm text-slate-600 mb-6">
                    {{ $paymentNotification->data['message'] }}
                </p>
                <button @click="markAsRead()" class="w-full py-3 rounded-xl text-white font-bold transition-all shadow-md {{ $paymentNotification->data['status'] == 'diterima' ? 'bg-teal-500 hover:bg-teal-600' : 'bg-red-500 hover:bg-red-600' }}">
                    Tutup
                </button>
            </div>
        </div>
    @endif
    @endauth

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('ai3d', {
                isProcessing: false,
                isMinimized: false,
                showModal: false,
                progress: 0,
                jobId: null,
                resultUrl: null,
                timeRemaining: 'Menghitung...',
                
                init() {
                    let saved = localStorage.getItem('ai_job_state');
                    if (saved) {
                        let data = JSON.parse(saved);
                        if (data.jobId && data.status !== 'completed') {
                            this.jobId = data.jobId;
                            this.isProcessing = true;
                            this.isMinimized = true;
                            this.pollStatus();
                        } else if (data.status === 'completed' && data.resultUrl) {
                            this.jobId = data.jobId;
                            this.resultUrl = data.resultUrl;
                            this.isProcessing = false;
                            this.progress = 100;
                            this.timeRemaining = 'Selesai!';
                            this.isMinimized = true; 
                        }
                    }
                },
                
                async startProcess(file) {
                    this.isProcessing = true;
                    this.showModal = true;
                    this.isMinimized = false;
                    this.progress = 0;
                    this.resultUrl = null;
                    this.timeRemaining = 'Mulai memproses...';
                    
                    let formData = new FormData();
                    formData.append('image', file);
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                    
                    try {
                        let res = await fetch('/api/convert-3d/start', { method: 'POST', body: formData });
                        let data = await res.json();
                        
                        if (data.success) {
                            this.jobId = data.job_id;
                            this.saveState();
                            this.pollStatus();
                        }
                    } catch (e) {
                        alert("Gagal memulai proses AI.");
                        this.isProcessing = false;
                        this.showModal = false;
                    }
                },
                
                pollStatus() {
                    if (!this.jobId) return;
                    let interval = setInterval(async () => {
                        if (!this.isProcessing) {
                            clearInterval(interval);
                            return;
                        }
                        
                        let res = await fetch('/api/convert-3d/status/' + this.jobId);
                        let data = await res.json();
                        
                        this.progress = data.progress;
                        this.timeRemaining = data.time_remaining;
                        
                        if (data.status === 'completed') {
                            this.isProcessing = false;
                            this.resultUrl = data.result_url;
                            clearInterval(interval);
                        }
                        this.saveState();
                    }, 2000);
                },
                
                minimize() {
                    this.showModal = false;
                    this.isMinimized = true;
                },
                
                openModal() {
                    this.isMinimized = false;
                    this.showModal = true;
                },
                
                closeAll() {
                    this.isProcessing = false;
                    this.showModal = false;
                    this.isMinimized = false;
                    this.jobId = null;
                    this.resultUrl = null;
                    localStorage.removeItem('ai_job_state');
                },

                saveState() {
                    localStorage.setItem('ai_job_state', JSON.stringify({
                        jobId: this.jobId,
                        status: this.isProcessing ? 'processing' : 'completed',
                        resultUrl: this.resultUrl
                    }));
                }
            });
        });
    </script>

    <div x-data>
        <div x-show="$store.ai3d.isMinimized && ($store.ai3d.isProcessing || $store.ai3d.resultUrl)" 
             x-transition.opacity
             @click="$store.ai3d.openModal()"
             style="display: none;"
             class="fixed bottom-6 right-6 z-[100] bg-white rounded-full shadow-2xl p-2.5 flex items-center gap-3 cursor-pointer hover:scale-105 transition-transform border border-slate-200">
             
             <div class="relative w-12 h-12 flex items-center justify-center bg-indigo-50 rounded-full flex-shrink-0">
                 <template x-if="$store.ai3d.isProcessing">
                     <svg class="w-10 h-10 text-indigo-500 animate-spin absolute" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10" stroke-dasharray="60" stroke-dashoffset="20"></circle></svg>
                 </template>
                 <template x-if="!$store.ai3d.isProcessing">
                     <span class="text-xl">✅</span>
                 </template>
                 <div x-show="$store.ai3d.isProcessing" class="absolute inset-0 flex items-center justify-center text-[10px] font-bold text-indigo-700" x-text="$store.ai3d.progress + '%'"></div>
             </div>
             
             <div class="pr-4 hidden sm:block">
                 <p class="text-sm font-bold text-slate-800" x-text="$store.ai3d.isProcessing ? 'Memproses 3D...' : '3D Selesai!'"></p>
                 <p class="text-xs text-slate-500 font-medium" x-text="$store.ai3d.timeRemaining"></p>
             </div>
        </div>

        <div x-show="$store.ai3d.showModal" style="display: none;" class="fixed inset-0 z-[150] flex items-center justify-center p-4">
            <div x-show="$store.ai3d.showModal" x-transition.opacity class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm"></div>
            <div x-show="$store.ai3d.showModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden flex flex-col">
                
                <div class="w-full p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h3 class="font-bold text-slate-900 text-lg flex items-center gap-2">
                        <span x-text="$store.ai3d.isProcessing ? '⚙️ AI Bekerja...' : '✨ Hasil 3D'"></span>
                    </h3>
                    <div class="flex items-center gap-2">
                        <button @click="$store.ai3d.minimize()" title="Sembunyikan ke Bubble" class="text-slate-400 hover:text-indigo-600 p-1.5 bg-white rounded-lg shadow-sm border border-slate-200 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" /></svg>
                        </button>
                        <button @click="$store.ai3d.closeAll()" title="Tutup & Hapus" class="text-slate-400 hover:text-red-600 p-1.5 bg-white rounded-lg shadow-sm border border-slate-200 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>
                
                <div class="p-6">
                    <template x-if="$store.ai3d.isProcessing">
                        <div class="flex flex-col items-center text-center py-4">
                            <div class="mb-4">
                                <svg class="w-16 h-16 text-indigo-500 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10" stroke-dasharray="60" stroke-dashoffset="20"></circle></svg>
                            </div>
                            <h4 class="text-lg font-bold text-slate-800 mb-1">Server AI sedang merender</h4>
                            <p class="text-sm text-slate-500 mb-6 px-4">Anda bisa me-minimize jendela ini (klik tanda minus) dan lanjut bekerja. Kami akan memberitahu jika sudah selesai.</p>
                            
                            <div class="w-full bg-slate-100 rounded-full h-4 mb-2 overflow-hidden shadow-inner">
                                <div class="bg-gradient-to-r from-indigo-500 to-purple-500 h-4 rounded-full transition-all duration-1000 ease-out" :style="'width: ' + $store.ai3d.progress + '%'"></div>
                            </div>
                            <div class="w-full flex justify-between text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <span x-text="$store.ai3d.progress + '%'"></span>
                                <span x-text="'Sisa: ' + $store.ai3d.timeRemaining"></span>
                            </div>
                        </div>
                    </template>

                    <template x-if="!$store.ai3d.isProcessing && $store.ai3d.resultUrl">
                        <div class="flex flex-col gap-4">
                            <div class="w-full bg-slate-100 relative h-[250px] rounded-2xl overflow-hidden border border-slate-200">
                                <model-viewer :src="$store.ai3d.resultUrl" auto-rotate camera-controls shadow-intensity="1" class="w-full h-full bg-slate-100"></model-viewer>
                            </div>
                            <a :href="$store.ai3d.resultUrl" download="scanyuk_3d_model.glb" class="w-full py-3.5 px-6 rounded-xl bg-teal-500 hover:bg-teal-600 text-white font-bold shadow-lg shadow-teal-200 text-center flex items-center justify-center gap-2 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                Download File 3D (.glb)
                            </a>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    @if(!request()->is('admin*'))
        @include('components.chatbot')
    @endif

</body>
</html>