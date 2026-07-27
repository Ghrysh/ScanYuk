<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>Journey - {{ $username }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.4.0/model-viewer.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-grid-pattern {
            background-size: 50px 50px;
            background-image: 
                linear-gradient(to right, rgba(0,0,0,0.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(0,0,0,0.03) 1px, transparent 1px);
            background-color: #fafafa;
        }
    </style>
</head>
<body class="bg-grid-pattern text-slate-900 min-h-screen pb-12 relative" x-data="{ showSettings: false, tab: 'username' }">
    <!-- Header -->
    <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-xl border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}" class="text-slate-500 hover:text-slate-700 transition bg-slate-100 hover:bg-slate-200 p-2 rounded-full">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                </a>
                <h1 class="font-bold text-lg text-slate-800">Tamagotchi Journey</h1>
            </div>
            <button @click="showSettings = true" class="text-slate-500 hover:text-teal-600 transition">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            </button>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        @if(session('success'))
            <div class="bg-teal-500/10 border border-teal-500/50 text-teal-400 text-sm rounded-xl p-3 mb-6 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-500/10 border border-red-500/50 text-red-400 text-sm rounded-xl p-3 mb-6 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-500/10 border border-red-500/50 text-red-400 text-sm rounded-xl p-3 mb-6">
                <ul class="list-disc pl-4">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="md:grid md:grid-cols-12 md:gap-8 items-start">
            
            <!-- Sidebar / Profile -->
            <div class="md:col-span-5 lg:col-span-4 md:sticky md:top-24 mb-8 md:mb-0">
                <!-- Profile Card -->
        <div class="bg-white rounded-3xl p-6 mb-8 flex items-center gap-5 relative overflow-hidden shadow-sm border border-slate-200">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-teal-500/10 rounded-full blur-2xl"></div>
            
            <div class="relative shrink-0 z-10">
                <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center border-4 border-white shadow-md overflow-hidden relative">
                    @php
                        $latestJourney = $journeys->first();
                        $mood = $latestJourney ? $latestJourney->mood : 'senang';
                    @endphp
                    @if($arType === '3d' && $file3dUrl)
                        <model-viewer src="{{ $file3dUrl }}" auto-rotate camera-controls disable-zoom disable-pan interaction-prompt="none" shadow-intensity="1" class="w-full h-full object-contain"></model-viewer>
                    @elseif($arType === '2d' && $imageUrl)
                        <img src="{{ $imageUrl }}" class="w-14 h-14 object-contain animate-bounce" style="animation-duration: 3s;">
                    @else
                        <img src="/ekspresi/{{ $mood }}.png" class="w-14 h-14 object-contain animate-bounce" style="animation-duration: 3s;" onerror="this.src='/ekspresi/senang.png'">
                    @endif
                </div>
                <div class="absolute -bottom-1 -right-1 w-7 h-7 bg-teal-500 rounded-full border-2 border-white flex items-center justify-center z-10 shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                </div>
            </div>
            
            <div class="z-10 flex-1">
                <h2 class="text-2xl font-bold text-slate-800 mb-1">{{ $username }}</h2>
                <div class="flex items-center gap-4 text-sm font-medium text-slate-500 mb-3">
                    <span class="flex items-center gap-1"><svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg> EXP {{ round($session->exp_points) }}</span>
                    <span class="flex items-center gap-1"><svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg> {{ $session->total_scans }} Scans</span>
                </div>
                
                <!-- Mini Exp Bar -->
                    <div class="w-full bg-slate-800 rounded-full h-2 overflow-hidden shadow-inner">
                        <div class="h-full rounded-full transition-all duration-1000 ease-out" 
                             style="width: {{ $session->exp_points }}%; background: linear-gradient(90deg, #14b8a6, #3b82f6);"></div>
                    </div>
                </div>
            </div>
            </div>

            <!-- Main Content / Journey List -->
            <div class="md:col-span-7 lg:col-span-8">
                <h3 class="text-lg font-bold text-slate-800 mb-4 px-2">Jejak Perjalanan</h3>

        <div class="space-y-4">
            @forelse($journeys as $j)
                <a href="{{ route('tamagotchi.journey', ['username' => $username, 'id' => $j->id]) }}" class="block">
                    <div class="bg-white rounded-2xl p-4 flex gap-4 items-center hover:bg-slate-50 transition group border border-slate-200 hover:border-teal-500/30 shadow-sm hover:shadow-teal-500/10">
                        <div class="w-14 h-14 rounded-xl bg-slate-50 flex items-center justify-center shrink-0">
                            <img src="/ekspresi/{{ $j->mood }}.png" class="w-8 h-8 object-contain group-hover:scale-110 transition-transform" onerror="this.src='/ekspresi/senang.png'">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-slate-800 font-semibold text-sm truncate mb-1">{{ $j->status_text }}</p>
                            <div class="flex items-center gap-3 text-xs text-slate-500 mt-1">
                                <span>{{ $j->created_at->format('d M Y') }}</span>
                                <span class="w-1 h-1 rounded-full bg-slate-600"></span>
                                <span>{{ $j->created_at->format('H:i') }}</span>
                                @if($j->location_name)
                                <span class="w-1 h-1 rounded-full bg-slate-600"></span>
                                <span class="flex items-center gap-1 truncate max-w-[100px] sm:max-w-[150px]">
                                    <svg class="w-3 h-3 text-red-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                                    <span class="truncate">{{ $j->location_name }}</span>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="text-teal-400">
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </div>
                    </div>
                </a>
            @empty
                    <div class="text-center py-12 bg-white rounded-3xl border border-slate-200 shadow-sm">
                        <img src="/ekspresi/suntuk.png" class="w-16 h-16 mx-auto mb-4 opacity-50 grayscale" alt="Empty">
                        <p class="text-slate-500 font-medium">Belum ada perjalanan terekam.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </main>

    <!-- Settings Modal -->
    <div x-show="showSettings" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div x-show="showSettings" x-transition.opacity class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showSettings = false"></div>
        
        <!-- Modal Content -->
        <div x-show="showSettings" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-8 scale-95"
             class="relative bg-white rounded-3xl border border-slate-200 w-full max-w-sm shadow-2xl overflow-hidden">
             
             <div class="p-6 border-b border-slate-200 flex justify-between items-center bg-slate-50/50">
                 <h3 class="font-bold text-slate-800 text-lg">Pengaturan Akun</h3>
                 <button @click="showSettings = false" class="text-slate-400 hover:text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-full p-1"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
             </div>

             <div class="flex border-b border-slate-200">
                 <button @click="tab = 'username'" :class="tab === 'username' ? 'text-teal-600 border-b-2 border-teal-600' : 'text-slate-500 hover:text-slate-700'" class="flex-1 py-3 text-sm font-semibold transition-colors">Ubah Username</button>
                 <button @click="tab = 'password'" :class="tab === 'password' ? 'text-teal-600 border-b-2 border-teal-600' : 'text-slate-500 hover:text-slate-700'" class="flex-1 py-3 text-sm font-semibold transition-colors">Ubah Password</button>
             </div>

             <div class="p-6">
                 <!-- Form Username -->
                 <div x-show="tab === 'username'">
                     <form action="{{ route('tamagotchi.change-username', $username) }}" method="POST">
                         @csrf
                         <div class="mb-4">
                             <label class="block text-xs font-semibold text-slate-500 mb-1">Username Saat Ini</label>
                             <div class="text-slate-800 px-3 py-2 bg-slate-50 rounded-lg text-sm border border-slate-200">{{ $username }}</div>
                         </div>
                         <div class="mb-5">
                             <label class="block text-xs font-semibold text-slate-500 mb-1">Username Baru</label>
                             <input type="text" name="new_username" required pattern="[A-Za-z0-9_-]+" title="Hanya huruf, angka, min, dan underscore"
                                class="w-full bg-white border border-slate-200 text-slate-900 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500" placeholder="Username_Baru">
                         </div>
                         <button type="submit" class="w-full bg-teal-500 hover:bg-teal-600 text-white font-bold py-2.5 rounded-xl shadow-lg transition-colors text-sm">Simpan Username</button>
                     </form>
                 </div>

                 <!-- Form Password -->
                 <div x-show="tab === 'password'" style="display: none;">
                     <form action="{{ route('tamagotchi.reset-password', $username) }}" method="POST">
                         @csrf
                         <div class="mb-4">
                             <label class="block text-xs font-semibold text-slate-500 mb-1">Password Lama</label>
                             <input type="password" name="old_password" required
                                class="w-full bg-white border border-slate-200 text-slate-900 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500" placeholder="••••••••">
                         </div>
                         <div class="mb-5">
                             <label class="block text-xs font-semibold text-slate-500 mb-1">Password Baru</label>
                             <input type="password" name="new_password" required minlength="4"
                                class="w-full bg-white border border-slate-200 text-slate-900 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500" placeholder="Minimal 4 karakter">
                         </div>
                         <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2.5 rounded-xl shadow-lg transition-colors text-sm">Perbarui Password</button>
                     </form>
                 </div>
             </div>
        </div>
    </div>
</body>
</html>
