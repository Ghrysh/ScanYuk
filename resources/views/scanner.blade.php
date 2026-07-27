<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>True WebAR Scanner - ScanYuk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.4.0/model-viewer.min.js"></script>
    <style>
        body { background-color: #000; margin: 0; overflow: hidden; touch-action: none; }
        
        #qr-video {
            position: absolute; top: 0; left: 0;
            width: 100vw; height: 100vh;
            object-fit: cover; z-index: 10;
        }

        #ar-overlay-container {
            position: absolute;
            z-index: 30;
            width: 250px; 
            height: 250px;
            margin-left: -125px; 
            margin-top: -125px;
            left: 0; top: 0;
            opacity: 0;
            pointer-events: none; 
            will-change: transform; 
            transform: translate3d(-9999px, -9999px, 0);
        }

        model-viewer::part(default-progress-bar) { display: none; }
    </style>
</head>
<body x-data="arTracker()" x-init="startCamera()">
    <video id="qr-video" playsinline webkit-playsinline muted autoplay></video>
    <canvas id="qr-canvas" style="display: none;"></canvas>
    
    <div x-show="isLoading" style="display: none;" class="fixed inset-0 z-50 flex flex-col items-center justify-center bg-slate-900/90 backdrop-blur-sm">
        <div class="w-16 h-16 border-4 border-teal-500 border-t-transparent rounded-full animate-spin mb-4"></div>
        <p class="text-white font-bold mb-2">Mempersiapkan AR Experience...</p>
        <div class="w-64 bg-slate-700 rounded-full h-2.5 overflow-hidden">
            <div class="bg-teal-500 h-2.5 rounded-full transition-all duration-200" :style="`width: ${loadingProgress}%`"></div>
        </div>
        <p class="text-teal-400 text-sm font-bold mt-2" x-text="`${loadingProgress}%`"></p>
        <p class="text-slate-400 text-xs mt-1" x-text="loadingStatusText"></p>
    </div>

    <!-- PERBAIKAN: Binding Posisi, Skala, Rotasi, & Animasi ke UI -->
    <div id="ar-overlay-container" class="transition-opacity duration-300" :class="arActive ? 'opacity-100' : 'opacity-0 pointer-events-none'">
        
        <!-- Overlay untuk AR 2D -->
        <img x-show="arData.type === '2d'" id="main-ar-2d" :src="arData.src" 
             class="w-full h-full object-contain filter drop-shadow(0 25px 25px rgba(0,0,0,0.8))"
             :style="`margin-left: ${arData.posX * 50}px; margin-top: ${-arData.posY * 50}px;`">
             
        <!-- Overlay untuk AR 3D -->
        <div class="w-full h-full absolute inset-0" :class="arData.type === '3d' ? 'opacity-100' : 'opacity-0 pointer-events-none'">
            <model-viewer 
                id="main-ar-viewer"
                @load="adjustHotspots($event.target)"
                :src="arData.src" 
                :scale="arData.scale"
                :orientation="`${arData.baseRotZ}deg ${arData.baseRotX}deg ${arData.baseRotY}deg`"
                :auto-rotate="arData.orbitActive"
                :animation-name="arData.animClip !== '*' ? arData.animClip : null"
                autoplay
                :style="`margin-left: ${arData.posX * 50}px; margin-top: ${-arData.posY * 50}px;`"
                camera-controls
                disable-zoom 
                disable-pan
                interaction-prompt="none"
                shadow-intensity="1" 
                loading="eager"
                power-preference="high-performance"
                class="w-full h-full bg-transparent">
            </model-viewer>
        </div>

        <!-- TAMAGOTCHI UI — Positioned via CSS, OUTSIDE model-viewer -->
        <div id="tamagotchi-layer" style="position: absolute; inset: 0; pointer-events: none; opacity: 0; transition: opacity 0.3s;">
            <!-- Ekspresi Wajah (kiri atas model) -->
            <div id="tama-expression" style="position: absolute; top: 5%; left: 5%;">
                <img :src="'/ekspresi/' + expState + '.png'" class="w-14 h-14 drop-shadow-[0_10px_15px_rgba(0,0,0,0.5)] object-contain animate-bounce" style="animation-duration: 2s;" onerror="this.src='/ekspresi/senang.png'">
            </div>

            <!-- Bar + Pesan (kanan atas model) -->
            <div id="tama-bar" style="position: absolute; top: 5%; right: 5%;">
                <div class="flex flex-col items-center gap-2">
                    <span class="text-white text-[10px] font-bold drop-shadow-md bg-black/40 px-2 py-1 rounded-full backdrop-blur-sm" x-text="Math.floor(expPoints) + '/100'"></span>
                    <div class="w-4 h-32 bg-slate-800/80 rounded-full border-2 border-white/20 shadow-xl overflow-hidden relative flex flex-col-reverse">
                        <div class="w-full transition-all duration-1000 ease-linear rounded-full" 
                             :style="`height: ${expPoints}%; background-color: ${getExpColor()}`"></div>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-white/10 backdrop-blur-sm flex items-center justify-center border border-white/20 mt-1 shadow-lg overflow-hidden shrink-0">
                        <div class="w-full h-full" :style="`background-color: ${getExpColor()}; opacity: 0.8`"></div>
                    </div>
                </div>
                
                <!-- Pesan Tamagotchi -->
                <div x-show="showExpMessage" x-transition.opacity.duration.300ms class="bg-white/90 backdrop-blur-sm px-3 py-2 rounded-2xl rounded-tl-none shadow-xl border border-white/50 text-sm font-bold text-slate-800 absolute top-6 left-8 z-10 max-w-[150px]">
                    <span x-text="expMessage"></span>
                    <div class="absolute top-0 -left-2 w-0 h-0 border-t-[10px] border-t-white/90 border-l-[10px] border-l-transparent"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="fixed top-6 left-6 z-40">
        <a href="{{ route('home') }}" class="bg-black/40 backdrop-blur-md text-white px-5 py-2.5 rounded-full flex items-center gap-2 hover:bg-black/60 transition shadow-lg border border-white/20">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
            Kembali
        </a>
    </div>

    <!-- TAMAGOTCHI: Phone Registration Modal -->
    <!-- TAMAGOTCHI: Registration Modal -->
    <div x-show="showPhoneModal" style="display: none;" class="fixed inset-0 z-[200] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/90 backdrop-blur-md"></div>
        <div class="relative bg-gradient-to-br from-slate-800 to-slate-900 rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden border border-white/10">
            <!-- Header -->
            <div class="p-6 pb-2 border-b border-white/10">
                <h3 class="text-white font-bold text-xl text-center">Masuk / Daftar</h3>
                <p class="text-slate-400 text-sm mt-2 text-center">Silakan masukkan detail akun Anda</p>
            </div>
            
            <!-- Form -->
            <div class="p-6 pt-3 space-y-3">
                <div class="space-y-3">
                    <div>
                        <label class="text-slate-400 text-xs font-medium mb-1 block">Username (Tanpa spasi)</label>
                        <input x-model="tamaUsername" type="text" placeholder="Masukkan username Anda" 
                            class="w-full bg-slate-700/60 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition">
                    </div>
                    <div>
                        <label class="text-slate-400 text-xs font-medium mb-1 block">Password</label>
                        <input x-model="tamaPassword" type="password" placeholder="Minimal 4 karakter" 
                            class="w-full bg-slate-700/60 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition">
                    </div>
                    <button @click="registerTamaUser()" :disabled="tamaLoading" 
                        class="w-full py-3 rounded-xl bg-gradient-to-r from-teal-500 to-indigo-500 text-white font-bold shadow-lg shadow-teal-500/30 hover:opacity-90 transition disabled:opacity-50 flex items-center justify-center gap-2">
                        <svg x-show="tamaLoading" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span x-text="tamaLoading ? 'Memproses...' : '🚀 Mulai Petualangan!'"></span>
                    </button>
                </div>

                <p x-show="tamaError" class="text-red-400 text-sm text-center" x-text="tamaError"></p>
            </div>
        </div>
    </div>

    <!-- Sharing Overlay -->
    <div x-show="shareState !== 'idle'" style="display: none;" class="fixed inset-0 z-[300] flex flex-col items-center justify-center p-4 bg-slate-900/95 backdrop-blur-md">
        
        <template x-if="shareState === 'loading'">
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 border-4 border-teal-500/30 border-t-teal-500 rounded-full animate-spin mb-4"></div>
                <p class="text-white font-bold text-xl animate-pulse">Mempersiapkan Kartu...</p>
                <p class="text-slate-400 text-sm mt-2 text-center max-w-xs">Tunggu sebentar, kami sedang mengambil gambar AR terbaikmu!</p>
            </div>
        </template>
        
        <template x-if="shareState === 'ready'">
            <div class="flex flex-col items-center text-center">
                <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mb-4 shadow-lg shadow-green-500/30">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <p class="text-white font-bold text-xl mb-2">Kartu Siap Dibagikan!</p>
                <p class="text-slate-300 text-sm mb-6 max-w-xs">Klik tombol di bawah untuk memilih aplikasi tujuan (WhatsApp, Instagram, dll).</p>
                
                <button @click="executeShare()" class="w-full max-w-xs py-3 rounded-xl bg-gradient-to-r from-teal-500 to-indigo-500 text-white font-bold shadow-lg shadow-teal-500/30 hover:opacity-90 transition mb-3 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                    Buka Aplikasi
                </button>
                <button @click="shareState = 'idle'" class="text-slate-400 text-sm underline">Batal</button>
            </div>
        </template>
    </div>

    <!-- TAMAGOTCHI: Journey Modal -->
    <div x-show="showJourneyModal" style="display: none;" class="fixed inset-0 z-[200] flex items-end justify-center">
        <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm" @click="showJourneyModal = false"></div>
        <div class="relative bg-gradient-to-br from-slate-800 to-slate-900 rounded-t-3xl shadow-2xl w-full max-w-lg border-t border-white/10 max-h-[85vh] flex flex-col" 
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0">
            <!-- Handle -->
            <div class="flex justify-center pt-3 pb-1"><div class="w-10 h-1 bg-white/20 rounded-full"></div></div>
            
            <!-- Header -->
            <div class="p-4 pb-2 flex items-center justify-between">
                <div>
                    <h3 class="text-white font-bold text-lg">📖 Journey Kamu</h3>
                    <p class="text-slate-400 text-xs" x-text="tamaDisplayName"></p>
                </div>
                <div class="flex gap-2">
                    <a :href="'/tamagotchi/' + tamaUsername" target="_blank" class="bg-teal-500/20 text-teal-400 px-4 py-2 rounded-xl hover:bg-teal-500/30 transition flex items-center gap-2 text-sm font-bold" title="Detail Full">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        Detail
                    </a>
                    <button @click="shareJourney('general')" class="bg-indigo-500/20 text-indigo-400 px-4 py-2 rounded-xl hover:bg-indigo-500/30 transition flex items-center gap-2 text-sm font-bold" title="Bagikan">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                        Bagikan
                    </button>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="mx-4 p-3 bg-slate-700/40 rounded-xl flex items-center gap-3">
                <img :src="'/ekspresi/' + expState + '.png'" class="w-10 h-10 object-contain" onerror="this.src='/ekspresi/senang.png'">
                <div class="flex-1">
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-slate-300" x-text="expState"></span>
                        <span class="text-teal-400 font-bold" x-text="Math.floor(expPoints) + '/100'"></span>
                    </div>
                    <div class="w-full h-2 bg-slate-600 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-500" :style="`width: ${expPoints}%; background-color: ${getExpColor()}`"></div>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-white text-sm font-bold" x-text="tamaTotalScans + 'x'"></p>
                    <p class="text-slate-400 text-[10px]">scans</p>
                </div>
            </div>

            <!-- Journey List -->
            <div class="flex-1 overflow-y-auto p-4 space-y-3">
                <template x-if="journeyEntries.length === 0">
                    <div class="text-center py-8">
                        <p class="text-slate-500 text-4xl mb-2">📝</p>
                        <p class="text-slate-400 text-sm">Belum ada journey. Tulis ceritamu!</p>
                    </div>
                </template>
                <template x-for="(entry, i) in journeyEntries" :key="entry.id">
                    <div class="flex gap-3">
                        <!-- Timeline dot -->
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-lg shrink-0" 
                                :class="i === 0 ? 'bg-teal-500/20 ring-2 ring-teal-400' : 'bg-slate-700'">
                                <span x-text="getMoodEmoji(entry.mood)"></span>
                            </div>
                            <div x-show="i < journeyEntries.length - 1" class="w-0.5 flex-1 bg-slate-700 mt-1"></div>
                        </div>
                        <!-- Content -->
                        <div class="flex-1 pb-4">
                            <p class="text-white text-sm font-medium" x-text="entry.status_text"></p>
                            <div class="flex flex-wrap items-center gap-2 mt-1">
                                <span class="text-slate-500 text-[11px]" x-text="entry.date + ' • ' + entry.time"></span>
                                <template x-if="entry.location_name">
                                    <span class="text-slate-400 text-[11px] flex items-center gap-0.5 max-w-[120px] truncate">
                                        📍 <span class="truncate" x-text="entry.location_name"></span>
                                    </span>
                                </template>
                                <span class="text-[10px] px-1.5 py-0.5 rounded-full shrink-0" 
                                    :class="getExpBadgeClass(entry.exp_points)" 
                                    x-text="Math.floor(entry.exp_points) + ' pts'"></span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- TAMAGOTCHI: Status Input (bottom bar) -->
    <div x-show="expressionEnabled && tamaSessionId && arActive" style="display: none;" 
         class="fixed bottom-24 left-1/2 transform -translate-x-1/2 z-40 w-full max-w-xs">
        <div class="bg-black/50 backdrop-blur-xl rounded-2xl border border-white/10 p-2 flex gap-2 items-center">
            <input x-model="statusInput" type="text" placeholder="Tuliskan aktivitas atau status..." maxlength="255"
                @keydown.enter="submitStatus()"
                class="flex-1 bg-transparent text-white text-sm px-3 py-2 outline-none placeholder-slate-500">
            <button @click="submitStatus()" :disabled="!statusInput.trim() || statusSubmitting" 
                class="bg-gradient-to-r from-teal-500 to-indigo-500 text-white p-2 rounded-xl hover:opacity-90 transition disabled:opacity-30 shrink-0">
                <svg x-show="!statusSubmitting" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                <svg x-show="statusSubmitting" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
            </button>
        </div>
        <p x-show="statusSaved" x-transition.opacity class="text-teal-400 text-xs text-center mt-1 font-medium">✓ Tersimpan di journey!</p>
    </div>

    <!-- TAMAGOTCHI: Journey Button (floating) -->
    <button x-show="expressionEnabled && tamaSessionId && arActive" style="display: none;" 
        @click="openJourney()" 
        class="fixed top-6 right-6 z-40 bg-gradient-to-br from-amber-400 to-orange-500 text-white w-12 h-12 rounded-full flex items-center justify-center shadow-lg shadow-orange-500/40 hover:scale-110 transition-transform border-2 border-white/20">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
    </button>

    <div x-show="isFetching" style="display: none;" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50 bg-black/60 backdrop-blur-xl text-white px-6 py-4 rounded-2xl flex flex-col items-center gap-3 shadow-2xl border border-white/10">
        <svg class="animate-spin h-8 w-8 text-teal-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        <span class="font-medium text-sm tracking-wide">Membuka Portal AR...</span>
    </div>

    <div x-show="arActive && !expressionEnabled" style="display: none;" class="fixed bottom-10 left-1/2 transform -translate-x-1/2 z-40 w-full max-w-xs">
        <button @click="replayVoice()" class="w-full bg-gradient-to-r from-teal-500 to-indigo-500 hover:opacity-90 backdrop-blur-md text-white py-4 px-4 rounded-2xl font-bold flex items-center justify-center gap-2 transition shadow-[0_10px_25px_rgba(20,184,166,0.4)]">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M13.5 4.06c0-1.336-1.616-2.005-2.56-1.06l-4.5 4.5H4.508c-1.141 0-2.318.664-2.66 1.905A9.76 9.76 0 001.5 12c0 .898.121 1.768.35 2.595.341 1.24 1.518 1.905 2.659 1.905h1.93l4.5 4.5c.945.945 2.561.276 2.561-1.06V4.06zM18.584 5.106a.75.75 0 011.06 0c3.808 3.807 3.808 9.98 0 13.788a.75.75 0 11-1.06-1.06 8.25 8.25 0 000-11.668.75.75 0 010-1.06z" /><path d="M15.932 7.757a.75.75 0 011.061 0 4.5 4.5 0 010 6.364.75.75 0 01-1.06-1.06 3 3 0 000-4.243.75.75 0 010-1.061z" /></svg>
            Putar Ulang Narasi
        </button>
    </div>

    <div x-show="arActive && expressionEnabled && tamaSessionId" style="display: none;" class="fixed bottom-10 left-1/2 transform -translate-x-1/2 z-30 w-full max-w-xs">
        <button @click="replayVoice()" class="w-full bg-black/40 backdrop-blur-md text-white py-3 px-4 rounded-2xl font-medium flex items-center justify-center gap-2 transition border border-white/10 text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path d="M13.5 4.06c0-1.336-1.616-2.005-2.56-1.06l-4.5 4.5H4.508c-1.141 0-2.318.664-2.66 1.905A9.76 9.76 0 001.5 12c0 .898.121 1.768.35 2.595.341 1.24 1.518 1.905 2.659 1.905h1.93l4.5 4.5c.945.945 2.561.276 2.561-1.06V4.06z" /></svg>
            Putar Ulang Narasi
        </button>
    </div>

    <div x-show="audioBlocked" style="display: none;" @click="resumeAudio()" class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-slate-900/80 backdrop-blur-sm cursor-pointer pointer-events-auto">
        <div class="w-16 h-16 bg-teal-500 rounded-full flex items-center justify-center text-white mb-4 shadow-[0_0_30px_rgba(20,184,166,0.6)] animate-pulse">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 ml-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" /></svg>
        </div>
        <span class="text-white font-bold text-lg tracking-wide">Ketuk untuk Memutar Suara</span>
        <span class="text-slate-300 text-sm mt-2">Browser memblokir pemutaran otomatis</span>
    </div>

<script>
        function arTracker() {
            return {
                video: null, canvasElement: null, canvas: null, arOverlayContainer: null,
                isFetching: false, arActive: false, errorMessage: '', arCache: {}, currentQrUrl: null, lastFoundTime: 0, audioBlocked: false,
                
                arData: { 
                    type: '2d', src: '',
                    scale: '1 1 1', scaleRaw: 1,
                    baseRotX: 0, baseRotY: 0, baseRotZ: 0,
                    posX: 0, posY: 0, posZ: 0,
                    orbitActive: false, animClip: '*'
                },

                bgmPlayer: null,
                narrationPlayer: null,
                
                curX: 0, curY: 0, curScale: 0, curAngle: 0, curYaw: 0, curPitch: 0,
                targetX: 0, targetY: 0, targetScale: 0, targetAngle: 0, targetYaw: 0, targetPitch: 0,
                hasSnaped: false,

                // TAMAGOTCHI SESSION & JOURNEY
                showPhoneModal: false,
                tamaUsername: '',
                tamaPassword: '',
                tamaLoading: false,
                tamaError: '',
                tamaSessionId: null,
                tamaDisplayName: '',
                tamaTotalScans: 0,
                showJourneyModal: false,
                shareState: 'idle',
                sharePlatform: '',
                shareFile: null,
                messageInterval: null,
                journeyEntries: [],
                statusInput: '',
                statusSubmitting: false,
                statusSaved: false,
                _syncInterval: null,
                _sessionBrowserKey: null,
                
                expressionEnabled: false,
                expPoints: 100,
                expState: 'senang',
                expMessage: '',
                showExpMessage: false,
                msgTimeout: null,
                expInterval: null,
                gpsWatchId: null,
                lastLat: null,
                lastLon: null,
                arUuid: null,
                
                messages: {
                    senang: ["Aku senang saat ini", "Halo dunia!", "Terima kasih sudah scan aku!", "Cuaca yang cerah ya!"],
                    suntuk: ["Aku ingin main", "Bosan nih", "Lama tidak bertemu", "Ayo lakukan sesuatu", "Main yuk!"],
                    marah: ["Kenapa lama sekali?", "Aku sedang marah", "Jangan tinggalkan aku", "Grrr...", "Kamu jahat!"],
                    menangis: ["Aku kangen", "Hik hik hik", "Jangan lupakan aku", "Aku kesepian", "Tolong temani aku"],
                    cape: ["Aku cape", "Hari yang melelahkan", "Butuh istirahat", "Hooaamm..."],
                    tidur: ["Zzzzz...", "Sedang tidur", "Jangan berisik...", "Sssttt..."]
                },

                isLoading: false,
                loadingProgress: 0,
                loadingStatusText: 'Mengunduh model 3D...',

                startCamera() {
                    if (window.history.replaceState) {
                        window.history.replaceState({}, document.title, "/scan-ar");
                    }

                    this.video = document.getElementById("qr-video");
                    this.canvasElement = document.getElementById("qr-canvas");
                    this.canvas = this.canvasElement.getContext("2d", { willReadFrequently: true });
                    this.arOverlayContainer = document.getElementById("ar-overlay-container");

                    navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } }).then((stream) => {
                        this.video.srcObject = stream;
                        this.video.setAttribute("playsinline", true);
                        this.video.play();
                        
                        requestAnimationFrame(() => this.logicLoop());
                        requestAnimationFrame(() => this.renderLoop());
                        
                        window.speechSynthesis.getVoices();
                    }).catch(err => {
                        this.showError("Akses kamera ditolak.");
                    });
                },

                logicLoop() {
                    if (this.video.readyState === this.video.HAVE_ENOUGH_DATA) {
                        this.canvasElement.height = this.video.videoHeight;
                        this.canvasElement.width = this.video.videoWidth;
                        this.canvas.drawImage(this.video, 0, 0, this.canvasElement.width, this.canvasElement.height);
                        
                        let imageData = this.canvas.getImageData(0, 0, this.canvasElement.width, this.canvasElement.height);
                        let code = jsQR(imageData.data, imageData.width, imageData.height, { inversionAttempts: "dontInvert" });

                        let qrData = code ? code.data : '';
                        let uuid = null;

                        if (qrData.includes('?id=')) {
                            uuid = qrData.split('?id=')[1];
                        } else if (qrData.includes('/api/scan/')) {
                            uuid = qrData.split('/api/scan/')[1];
                        } else if (qrData.includes('/scanner/')) {
                            uuid = qrData.split('/scanner/')[1];
                        }

                        if (uuid) {
                            this.lastFoundTime = Date.now();
                            this.currentQrUrl = '/api/scan/' + uuid;
                            
                            if (this.arUuid !== uuid) {
                                this.arUuid = uuid;
                                this._sessionBrowserKey = 'tama_browser_' + uuid;
                            }

                            if (!this.arCache[this.currentQrUrl] && !this.isFetching) {
                                this.fetchArData(this.currentQrUrl);
                            } 
                            else if (this.arCache[this.currentQrUrl] && this.arCache[this.currentQrUrl].ready) {
                                if (!this.arActive) {
                                    this.arActive = true;
                                    this.restartAudioFromPause(this.currentQrUrl);
                                    
                                    // Start Tamagotchi: check if phone registration is needed
                                    if (this.expressionEnabled && !this.tamaSessionId) {
                                        this.checkOrShowPhoneModal(uuid);
                                    } else if (this.expressionEnabled && this.tamaSessionId) {
                                        this.initExpression(uuid);
                                    }
                                }
                                this.calculateTarget(code.location);
                            }
                        } else {
                            // QR lost — hide AR visually but DON'T destroy session
                            if (Date.now() - this.lastFoundTime > 500) { 
                                this.arOverlayContainer.style.opacity = '0';
                                this.arOverlayContainer.style.pointerEvents = 'none';
                                this.arOverlayContainer.style.transform = 'translate3d(-9999px, -9999px, 0)';
                                this.arActive = false;
                                this.hasSnaped = false;
                                // DON'T stop audio or destroy session — user just moved camera
                                // Session persists via sessionStorage
                            }
                        }
                    }
                    requestAnimationFrame(() => this.logicLoop());
                },

                calculateTarget(loc) {
                    const tl = loc.topLeftCorner, tr = loc.topRightCorner, br = loc.bottomRightCorner, bl = loc.bottomLeftCorner;
                    const centerX = (tl.x + tr.x + br.x + bl.x) / 4;
                    const centerY = (tl.y + tr.y + br.y + bl.y) / 4;
                    
                    const sideL = Math.hypot(tl.x - bl.x, tl.y - bl.y);
                    const sideR = Math.hypot(tr.x - br.x, tr.y - br.y);
                    const sideT = Math.hypot(tl.x - tr.x, tl.y - tr.y);
                    const sideB = Math.hypot(bl.x - br.x, bl.y - br.y);

                    let yawRatio = (sideL - sideR) / Math.max(sideL, sideR);
                    let pitchRatio = (sideT - sideB) / Math.max(sideT, sideB);

                    const sensitivity = 200; 
                    
                    let rawYaw = yawRatio * sensitivity;
                    let rawPitch = pitchRatio * sensitivity;

                    this.targetYaw = Math.max(-85, Math.min(85, rawYaw));
                    this.targetPitch = Math.max(-85, Math.min(85, rawPitch));

                    const qrWidth = (sideT + sideB + sideL + sideR) / 4;
                    let angle = Math.atan2(tr.y - tl.y, tr.x - tl.x) * (180 / Math.PI);

                    const vw = window.innerWidth, vh = window.innerHeight;
                    const videoRatio = this.video.videoWidth / this.video.videoHeight;
                    const screenRatio = vw / vh;

                    let scale = 1, offsetX = 0, offsetY = 0;
                    if (screenRatio > videoRatio) {
                        scale = vw / this.video.videoWidth;
                        offsetY = (vh - (this.video.videoHeight * scale)) / 2;
                    } else {
                        scale = vh / this.video.videoHeight;
                        offsetX = (vw - (this.video.videoWidth * scale)) / 2;
                    }

                    this.targetX = (centerX * scale) + offsetX;
                    this.targetY = (centerY * scale) + offsetY;
                    this.targetScale = ((qrWidth * scale) * 2) / 250;
                    this.targetAngle = angle;
                },

                renderLoop() {
                    if (this.arActive) {
                        if (!this.hasSnaped) {
                            this.curX = this.targetX; 
                            this.curY = this.targetY;
                            this.curAngle = this.targetAngle;
                            this.curScale = this.targetScale;
                            this.curYaw = this.targetYaw;
                            this.curPitch = this.targetPitch;
                            this.arOverlayContainer.style.opacity = '1';
                            this.arOverlayContainer.style.pointerEvents = 'auto';
                            this.hasSnaped = true;
                        } else {
                            let dist = Math.hypot(this.targetX - this.curX, this.targetY - this.curY);
                            let ease = Math.min(1.0, 0.25 + (dist / 100)); 
                            
                            this.curX += (this.targetX - this.curX) * ease;
                            this.curY += (this.targetY - this.curY) * ease;
                            this.curScale += (this.targetScale - this.curScale) * 0.15;

                            let dAngle = this.targetAngle - this.curAngle;
                            if (dAngle > 180) dAngle -= 360;
                            if (dAngle < -180) dAngle += 360;
                            this.curAngle += dAngle * ease;

                            this.curYaw += (this.targetYaw - this.curYaw) * ease;
                            this.curPitch += (this.targetPitch - this.curPitch) * ease;
                        }

                        this.arOverlayContainer.style.transform = `translate3d(${this.curX}px, ${this.curY}px, 0) rotate(${this.curAngle}deg) scale(${this.curScale})`;

                        // Tamagotchi layer visibility — pure JS, no Alpine dependency
                        const tamaLayer = document.getElementById('tamagotchi-layer');
                        if (tamaLayer) {
                            tamaLayer.style.opacity = this.expressionEnabled ? '1' : '0';
                        }

                        // PERBAIKAN MATEMATIKA: 
                        // Rotasi Base dikunci di atribut 'orientation' pada HTML.
                        // Tracking Kamera memutar sekeliling objek dengan 'camera-orbit'.
                        if (this.arData.type === '3d') {
                            const viewer = document.getElementById('main-ar-viewer');
                            if (viewer) {
                                // 90 adalah sudut lurus menghadap objek. 
                                // Jika HP miring ke atas (pitch naik), kamera memutar ke bawah memandang ke atas.
                                let orbitPitch = Math.round((90 - this.curPitch) * 10) / 10;
                                let orbitYaw = Math.round((-this.curYaw) * 10) / 10; 
                                let newOrbit = `${orbitYaw}deg ${orbitPitch}deg auto`;
                                
                                if (viewer.getAttribute('camera-orbit') !== newOrbit) {
                                    viewer.setAttribute('camera-orbit', newOrbit);
                                }
                            }
                        } else if (this.arData.type === '2d') {
                            const img2d = document.getElementById('main-ar-2d');
                            if (img2d) {
                                let fPitch = this.arData.baseRotX - this.curPitch;
                                let fYaw = this.arData.baseRotY + this.curYaw;
                                let fRoll = this.arData.baseRotZ;
                                img2d.style.transform = `perspective(600px) rotateX(${fPitch}deg) rotateY(${fYaw}deg) rotateZ(${fRoll}deg) scale(${this.arData.scaleRaw})`;
                            }
                        }
                    }
                    requestAnimationFrame(() => this.renderLoop());
                },

                async fetchArData(url) {
                    this.isFetching = true;
                    try {
                        const response = await fetch(url);
                        const result = await response.json();
                        if (response.ok && result.status === 'success') {
                            
                            const type = result.data.ar_type;
                            let src = type === '3d' ? result.data.file_3d_url : result.data.image_url;

                            if (src.toLowerCase().includes('logo.glb')) {
                                src = src + '?v=' + new Date().getTime(); 
                            }
                            
                            let dbScale = result.data.scale ? parseFloat(result.data.scale) : 1;
                            let dbRot = [0,0,0];
                            let dbPos = [0,0,0];

                            try { if(result.data.rotation) dbRot = JSON.parse(result.data.rotation); } catch(e){}
                            try { if(result.data.position) dbPos = JSON.parse(result.data.position); } catch(e){}
                            
                            this.arData = { 
                                type: type, 
                                src: src,
                                scale: `${dbScale} ${dbScale} ${dbScale}`,
                                scaleRaw: dbScale,
                                baseRotX: parseFloat(dbRot[0]) || 0,
                                baseRotY: parseFloat(dbRot[1]) || 0,
                                baseRotZ: parseFloat(dbRot[2]) || 0,
                                posX: parseFloat(dbPos[0]) || 0,
                                posY: parseFloat(dbPos[1]) || 0,
                                posZ: parseFloat(dbPos[2]) || 0,
                                orbitActive: result.data.orbit_active == 1 || result.data.orbit_active == true,
                                animClip: result.data.anim_clip || '*'
                            };
                            this.expressionEnabled = result.data.enable_expression == 1 || result.data.enable_expression == true;

                            this.arCache[url] = { 
                                narration: result.data.narration, 
                                ai_voice: result.data.ai_voice,
                                custom_audio_url: result.data.custom_audio_url,
                                bgm_url: result.data.bgm_url,
                                ready: true 
                            };
                            
                            this.isFetching = false;

                            this.isLoading = true;
                            this.loadingProgress = 0;
                            this.loadingStatusText = 'Menyiapkan AR, Musik, dan Narasi...';

                            this.playAllMediaSynchronized(url, type);

                        } else {
                            this.showError("QR Code tidak valid / Limit.");
                            this.arCache[url] = { ready: false };
                            this.isFetching = false;
                        }
                    } catch (error) { 
                        this.showError("Terjadi kesalahan jaringan."); 
                        this.isFetching = false;
                    } 
                },

                playAllMediaSynchronized(url, type) {
                    this.stopAllAudio();
                    const cache = this.arCache[url];
                    if(!cache) return;

                    let promises = [];

                    if (type === '3d') {
                        promises.push(new Promise((resolve) => {
                            this.$nextTick(() => {
                                const viewer = document.querySelector('#main-ar-viewer');
                                if(!viewer) return resolve();

                                const onProgress = (event) => {
                                    let currentProg = Math.round(event.detail.totalProgress * 100);
                                    if (currentProg > this.loadingProgress) {
                                        this.loadingProgress = currentProg;
                                    }
                                };

                                const onLoad = () => {
                                    this.loadingProgress = 100;
                                    this.adjustHotspots(viewer);
                                    cleanUp();
                                    resolve();
                                };

                                const onError = () => {
                                    console.error("Gagal merender 3D.");
                                    cleanUp();
                                    resolve();
                                };

                                const cleanUp = () => {
                                    viewer.removeEventListener('progress', onProgress);
                                    viewer.removeEventListener('load', onLoad);
                                    viewer.removeEventListener('error', onError);
                                };

                                viewer.addEventListener('progress', onProgress);
                                viewer.addEventListener('load', onLoad);
                                viewer.addEventListener('error', onError);

                                // PERBAIKAN 1: Perpanjang batas waktu tunggu dari 10 detik menjadi 45 detik
                                // Agar untuk file besar (>5MB), suara tidak bocor duluan sebelum loading selesai.
                                setTimeout(() => {
                                    cleanUp();
                                    resolve();
                                }, 45000); 
                            });
                        }));
                    } else {
                        this.loadingProgress = 100;
                    }

                    if (cache.bgm_url) {
                        let urlParts = cache.bgm_url.split('#t=');
                        let audioSrc = urlParts[0];
                        if (!audioSrc.startsWith('http') && !audioSrc.startsWith('/')) { audioSrc = '/' + audioSrc; }

                        this.bgmPlayer = new Audio();
                        this.bgmPlayer.preload = 'auto';
                        this.bgmPlayer.volume = 0.3;
                        this.bgmPlayer.src = audioSrc;

                        let startTime = 0;
                        let endTime = null;
                        
                        if (urlParts.length > 1 && urlParts[1]) {
                            let times = urlParts[1].split(',');
                            startTime = parseFloat(times[0]);
                            endTime = parseFloat(times[1]);
                            this.bgmPlayer.ontimeupdate = () => {
                                if(endTime && this.bgmPlayer.currentTime >= endTime) this.bgmPlayer.currentTime = startTime;
                            };
                        } else {
                            this.bgmPlayer.loop = true;
                        }

                        promises.push(new Promise((resolve) => {
                            let timeout = setTimeout(resolve, 5000);
                            this.bgmPlayer.addEventListener('loadeddata', () => {
                                clearTimeout(timeout);
                                this.bgmPlayer.currentTime = startTime; 
                                resolve();
                            }, { once: true });
                            this.bgmPlayer.addEventListener('error', () => {
                                clearTimeout(timeout);
                                resolve();
                            }, { once: true });
                            this.bgmPlayer.load(); 
                        }));
                    }

                    let usingRecordedAudio = false;
                    if (cache.custom_audio_url) {
                        usingRecordedAudio = true;
                        this.narrationPlayer = new Audio();
                        this.narrationPlayer.preload = 'auto';
                        this.narrationPlayer.volume = 1.0;
                        this.narrationPlayer.src = cache.custom_audio_url;
                        
                        promises.push(new Promise((resolve) => {
                            let timeout = setTimeout(resolve, 5000);
                            this.narrationPlayer.addEventListener('loadeddata', () => {
                                clearTimeout(timeout);
                                resolve();
                            }, { once: true });
                            this.narrationPlayer.addEventListener('error', () => {
                                clearTimeout(timeout);
                                resolve();
                            }, { once: true });
                            this.narrationPlayer.load();
                        }));
                    }

                    Promise.all(promises).then(() => {
                        this.loadingProgress = 100;
                        this.loadingStatusText = 'Mengekstrak AR ke layar...';

                        // PERBAIKAN 2: Jeda emas (Golden Delay) 600 milidetik
                        // Kita menahan layar loading sedikit lebih lama setelah 100% agar GPU 
                        // punya waktu menggambar 3D-nya ke layar sebelum kita memutar musiknya.
                        setTimeout(() => {
                            this.isLoading = false; 
                            this.arActive = true; 

                            let playPromises = [];
                            if (this.bgmPlayer) playPromises.push(this.bgmPlayer.play());
                            if (this.narrationPlayer) playPromises.push(this.narrationPlayer.play());

                            const playTTS = () => {
                                if(!usingRecordedAudio && cache.narration) {
                                    let utterance = new SpeechSynthesisUtterance(cache.narration);
                                    utterance.lang = 'id-ID';
                                    if(cache.ai_voice) {
                                        let voices = window.speechSynthesis.getVoices();
                                        let selectedVoice = voices.find(v => v.voiceURI === cache.ai_voice);
                                        if(selectedVoice) utterance.voice = selectedVoice;
                                    }
                                    window.speechSynthesis.speak(utterance);
                                }
                            };

                            if (playPromises.length > 0) {
                                Promise.all(playPromises).then(() => {
                                    playTTS();
                                }).catch(e => {
                                    console.log('Autoplay diblokir browser:', e);
                                    this.audioBlocked = true; 
                                });
                            } else {
                                playTTS();
                            }
                        }, 600); 
                    });
                },

                replayVoice() {

                    if (!this.currentQrUrl || !this.arActive) return;

                    const cache = this.arCache[this.currentQrUrl];
                    if(!cache) return;

                    window.speechSynthesis.cancel();
                    if (this.narrationPlayer) {
                        this.narrationPlayer.pause();
                        this.narrationPlayer.currentTime = 0;
                    }

                    if (cache.custom_audio_url && this.narrationPlayer) {
                        this.narrationPlayer.play().catch(e => console.log('Replay Error:', e));
                    } 
                    else if (cache.narration) {
                        let utterance = new SpeechSynthesisUtterance(cache.narration);
                        utterance.lang = 'id-ID';
                        if(cache.ai_voice) {
                            let voices = window.speechSynthesis.getVoices();
                            let selectedVoice = voices.find(v => v.voiceURI === cache.ai_voice);
                            if(selectedVoice) utterance.voice = selectedVoice;
                        }
                        window.speechSynthesis.speak(utterance);
                    }
                },

                resumeAudio() {
                    this.audioBlocked = false;
                    if (this.bgmPlayer) this.bgmPlayer.play().catch(e=>{});
                    if (this.narrationPlayer) this.narrationPlayer.play().catch(e=>{});
                    
                    const cache = this.arCache[this.currentQrUrl];
                    if (cache && !cache.custom_audio_url && cache.narration) {
                        let utterance = new SpeechSynthesisUtterance(cache.narration);
                        utterance.lang = 'id-ID';
                        if(cache.ai_voice) {
                            let voices = window.speechSynthesis.getVoices();
                            let selectedVoice = voices.find(v => v.voiceURI === cache.ai_voice);
                            if(selectedVoice) utterance.voice = selectedVoice;
                        }
                        window.speechSynthesis.speak(utterance);
                    }
                },

                stopAllAudio() {
                    window.speechSynthesis.cancel();
                    this.audioBlocked = false;
                    
                    if(this.bgmPlayer) {
                        this.bgmPlayer.pause();
                    }
                    
                    if(this.narrationPlayer) {
                        this.narrationPlayer.pause();
                    }
                },

                restartAudioFromPause(url) {
                    const cache = this.arCache[url];
                    if(!cache) return;

                    let playPromises = [];

                    if (this.bgmPlayer) {
                        let startTime = 0;
                        if (cache.bgm_url && cache.bgm_url.includes('#t=')) {
                            let times = cache.bgm_url.split('#t=')[1].split(',');
                            startTime = parseFloat(times[0]);
                        }
                        this.bgmPlayer.currentTime = startTime;
                        playPromises.push(this.bgmPlayer.play());
                    }

                    let usingRecordedAudio = false;
                    if (this.narrationPlayer && cache.custom_audio_url) {
                        usingRecordedAudio = true;
                        this.narrationPlayer.currentTime = 0;
                        playPromises.push(this.narrationPlayer.play());
                    }

                    const playTTS = () => {
                        if(!usingRecordedAudio && cache.narration) {
                            let utterance = new SpeechSynthesisUtterance(cache.narration);
                            utterance.lang = 'id-ID';
                            if(cache.ai_voice) {
                                let voices = window.speechSynthesis.getVoices();
                                let selectedVoice = voices.find(v => v.voiceURI === cache.ai_voice);
                                if(selectedVoice) utterance.voice = selectedVoice;
                            }
                            window.speechSynthesis.speak(utterance);
                        }
                    };

                    if (playPromises.length > 0) {
                        Promise.all(playPromises).then(() => {
                            playTTS();
                        }).catch(e => {
                            console.log('Autoplay diblokir browser:', e);
                            this.audioBlocked = true; 
                        });
                    } else {
                        playTTS();
                    }
                },

                // TAMAGOTCHI LOGIC
                getExpColor() {
                    if (this.expPoints >= 75) return '#4ade80'; 
                    if (this.expPoints >= 35) return '#facc15'; 
                    if (this.expPoints >= 10) return '#f87171'; 
                    return '#991b1b'; 
                },
                
                adjustHotspots(viewer) {
                    if (!viewer) return;
                    try {
                        const center = viewer.getBoundingBoxCenter();
                        const size = viewer.getDimensions();
                        const maxDim = Math.max(size.x, size.y, size.z);
                        if (maxDim === 0 || isNaN(maxDim)) {
                            console.log('adjustHotspots skip: maxDim 0');
                            return;
                        }
                        const norm = 2.0 / maxDim;
                        
                        const pExpX = (-0.4 / norm) + center.x;
                        const pExpY = (0.7 / norm) + center.y;
                        
                        const pBarX = (0.7 / norm) + center.x;
                        const pBarY = (0.7 / norm) + center.y;
                        
                        const frontZ = center.z + (size.z / 2) + 0.1;

                        const expEl = viewer.querySelector('[slot="hotspot-expression"]');
                        if(expEl) {
                            expEl.setAttribute('data-position', `${pExpX} ${pExpY} ${frontZ}`);
                            
                            // FORCE update API (name must omit 'hotspot-' prefix)
                            if (typeof viewer.updateHotspot === 'function') {
                                viewer.updateHotspot({name: 'expression', position: `${pExpX} ${pExpY} ${frontZ}`});
                            }
                            
                            console.log(`Exp pos: ${pExpX.toFixed(2)}, ${pExpY.toFixed(2)}, ${frontZ.toFixed(2)}`);
                            
                            // Debugging internal transform
                            setTimeout(() => {
                                console.log(`Exp transform: ${expEl.style.transform || 'NONE'}`);
                            }, 500);
                        }
                        
                        const barEl = viewer.querySelector('[slot="hotspot-bar"]');
                        if(barEl) {
                            barEl.setAttribute('data-position', `${pBarX} ${pBarY} ${frontZ}`);
                            
                            if (typeof viewer.updateHotspot === 'function') {
                                viewer.updateHotspot({name: 'bar', position: `${pBarX} ${pBarY} ${frontZ}`});
                            }
                        }
                        
                        // Perbarui QR Code hotspot jika ada
                        const qrEl = viewer.querySelector('[slot="hotspot-qrcode"]');
                        if(qrEl) {
                            const offsetZ = parseFloat('{{ $marker->base_z_offset ?? 0 }}');
                            // Kalau model raw offsetZ tidak sesuai, kita bisa paskan z belakang model:
                            const backZ = center.z - (size.z / 2);
                            qrEl.setAttribute('data-position', `0 ${center.y} ${backZ}`);
                        }
                    } catch(e) { console.error('Error adjusting hotspots:', e); }
                },

                // === TAMAGOTCHI SESSION MANAGEMENT ===
                checkOrShowPhoneModal(uuid) {
                    // Check sessionStorage first (browser session = not closed/lockscreen)
                    const browserKey = 'tama_browser_' + uuid;
                    const browserSession = sessionStorage.getItem(browserKey);
                    
                    if (browserSession) {
                        // Browser session exists — NOT a new scan, resume silently
                        try {
                            let data = JSON.parse(browserSession);
                            this.tamaSessionId = data.session_id;
                            this.tamaUsername = data.username;
                            this.tamaTotalScans = data.total_scans;
                            
                            // Restore exp from server
                            this.restoreSessionFromServer(data.session_id, data.username);
                            return;
                        } catch(e) {}
                    }

                    // Check localStorage (persistent across browser closes)
                    const localKey = 'tama_local_' + uuid;
                    const localData = localStorage.getItem(localKey);
                    
                    if (localData) {
                        try {
                            let data = JSON.parse(localData);
                            // Has previous session — verify with server and count as new scan
                            this.tamaUsername = data.username;
                            this.autoLoginSession(uuid, data);
                            return;
                        } catch(e) {}
                    }

                    // No session at all — show phone registration modal
                    this.showPhoneModal = true;
                },

                async autoLoginSession(uuid, data) {
                    try {
                        const res = await fetch('/api/tamagotchi/check-session', {
                            method: 'POST',
                            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                            body: JSON.stringify({ session_id: data.session_id, username: data.username })
                        });
                        const result = await res.json();
                        if (result.status === 'success') {
                            this.tamaSessionId = result.data.session_id;
                            this.tamaTotalScans = result.data.total_scans;
                            this.expPoints = Math.min(100, result.data.exp_points);
                            
                            // Save to sessionStorage (marks this browser tab as active)
                            sessionStorage.setItem('tama_browser_' + uuid, JSON.stringify({
                                session_id: this.tamaSessionId,
                                username: this.tamaUsername,
                                total_scans: this.tamaTotalScans
                            }));
                            
                            this.initExpression(uuid);
                            this.startSyncInterval();
                        } else {
                            // Session invalid — show phone modal
                            this.showPhoneModal = true;
                        }
                    } catch(e) {
                        this.showPhoneModal = true;
                    }
                },

                async restoreSessionFromServer(sessionId, username) {
                    try {
                        const res = await fetch('/api/tamagotchi/check-session', {
                            method: 'POST',
                            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                            body: JSON.stringify({ session_id: sessionId, username: username })
                        });
                        const result = await res.json();
                        if (result.status === 'success') {
                            this.expPoints = Math.min(100, result.data.exp_points);
                            this.tamaTotalScans = result.data.total_scans;
                        }
                    } catch(e) {}
                    this.initExpression(this.arUuid);
                    this.startSyncInterval();
                },

                async registerTamaUser() {
                    if (!this.tamaUsername.trim() || !this.tamaPassword.trim()) {
                        this.tamaError = 'Username dan Password wajib diisi.';
                        return;
                    }
                    if (this.tamaPassword.length < 4) {
                        this.tamaError = 'Password minimal 4 karakter.';
                        return;
                    }
                    this.tamaLoading = true;
                    this.tamaError = '';
                    try {
                        const res = await fetch('/api/tamagotchi/register', {
                            method: 'POST',
                            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                            body: JSON.stringify({
                                username: this.tamaUsername,
                                password: this.tamaPassword,
                                qr_uuid: this.arUuid,
                                lat: this.lastLat,
                                lon: this.lastLon,
                            })
                        });
                        const result = await res.json();
                        if (result.status === 'success') {
                            this.tamaSessionId = result.data.session_id;
                            this.tamaTotalScans = result.data.total_scans;
                            this.expPoints = Math.min(100, result.data.exp_points);
                            this.showPhoneModal = false;

                            // Save to localStorage (persistent) and sessionStorage (browser session)
                            const saveData = {
                                session_id: this.tamaSessionId,
                                username: this.tamaUsername,
                                total_scans: this.tamaTotalScans
                            };
                            localStorage.setItem('tama_local_' + this.arUuid, JSON.stringify(saveData));
                            sessionStorage.setItem('tama_browser_' + this.arUuid, JSON.stringify(saveData));

                            this.initExpression(this.arUuid);
                            this.startSyncInterval();
                        } else {
                            this.tamaError = result.message || 'Verifikasi gagal.';
                        }
                    } catch(e) {
                        this.tamaError = 'Kesalahan jaringan.';
                    }
                    this.tamaLoading = false;
                },

                startSyncInterval() {
                    if (this._syncInterval) clearInterval(this._syncInterval);
                    this._syncInterval = setInterval(() => {
                        if (this.tamaSessionId) {
                            fetch('/api/tamagotchi/sync', {
                                method: 'POST',
                                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                                body: JSON.stringify({
                                    session_id: this.tamaSessionId,
                                    exp_points: this.expPoints,
                                    mood: this.expState,
                                    lat: this.lastLat,
                                    lon: this.lastLon,
                                })
                            }).catch(() => {});
                        }
                    }, 30000); // Sync every 30 seconds
                },

                async submitStatus() {
                    if (!this.statusInput.trim() || this.statusSubmitting) return;
                    this.statusSubmitting = true;
                    try {
                        await fetch('/api/tamagotchi/journal', {
                            method: 'POST',
                            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                            body: JSON.stringify({
                                session_id: this.tamaSessionId,
                                status_text: this.statusInput.trim(),
                                mood: this.expState,
                                exp_points: this.expPoints,
                                lat: this.lastLat,
                                lon: this.lastLon,
                            })
                        });
                        this.statusInput = '';
                        this.statusSaved = true;
                        setTimeout(() => { this.statusSaved = false; }, 2000);
                    } catch(e) {}
                    this.statusSubmitting = false;
                },

                async openJourney() {
                    this.showJourneyModal = true;
                    try {
                        const res = await fetch('/api/tamagotchi/journey/' + this.tamaSessionId);
                        const result = await res.json();
                        if (result.status === 'success') {
                            this.journeyEntries = result.data.journeys;
                        }
                    } catch(e) {}
                },

                getMoodEmoji(mood) {
                    const map = { senang: '😊', suntuk: '😐', marah: '😠', menangis: '😢', cape: '😴', tidur: '💤' };
                    return map[mood] || '😊';
                },

                getExpBadgeClass(pts) {
                    if (pts >= 75) return 'bg-green-500/20 text-green-400';
                    if (pts >= 35) return 'bg-yellow-500/20 text-yellow-400';
                    if (pts >= 10) return 'bg-red-500/20 text-red-400';
                    return 'bg-red-900/30 text-red-500';
                },

                async shareJourney(platform) {
                    this.sharePlatform = platform;
                    this.shareState = 'loading';
                    
                    try {
                        const viewer = document.querySelector('model-viewer');
                        let modelDataUrl = '';
                        if (viewer && viewer.toDataURL) {
                            modelDataUrl = viewer.toDataURL('image/png', 0.9);
                        }
                        
                        if (!modelDataUrl) {
                            modelDataUrl = '/ekspresi/senang.png';
                        }

                        // Helper untuk crop gambar transparan
                        const cropAndCenter = (src) => {
                            return new Promise((resolve) => {
                                const img = new Image();
                                img.onload = () => {
                                    const cvs = document.createElement('canvas');
                                    cvs.width = img.width;
                                    cvs.height = img.height;
                                    const ctx = cvs.getContext('2d');
                                    ctx.drawImage(img, 0, 0);
                                    
                                    const pixels = ctx.getImageData(0, 0, cvs.width, cvs.height);
                                    let l = cvs.width, r = 0, t = cvs.height, b = 0;
                                    let hasPixels = false;
                                    for(let y = 0; y < cvs.height; y++) {
                                        for(let x = 0; x < cvs.width; x++) {
                                            if (pixels.data[(y * cvs.width + x) * 4 + 3] > 10) {
                                                hasPixels = true;
                                                if (x < l) l = x;
                                                if (x > r) r = x;
                                                if (y < t) t = y;
                                                if (y > b) b = y;
                                            }
                                        }
                                    }
                                    
                                    if (!hasPixels) {
                                        resolve(src); return;
                                    }
                                    
                                    const w = r - l + 1;
                                    const h = b - t + 1;
                                    
                                    // Bikin canvas kotak berukuran 400x400
                                    const finalCvs = document.createElement('canvas');
                                    finalCvs.width = 400;
                                    finalCvs.height = 400;
                                    const finalCtx = finalCvs.getContext('2d');
                                    
                                    // Hitung skala agar pas di tengah
                                    const scale = Math.min(380 / w, 380 / h);
                                    const finalW = w * scale;
                                    const finalH = h * scale;
                                    const dx = (400 - finalW) / 2;
                                    const dy = (400 - finalH) / 2;
                                    
                                    finalCtx.drawImage(cvs, l, t, w, h, dx, dy, finalW, finalH);
                                    resolve(finalCvs.toDataURL('image/png'));
                                };
                                img.src = src;
                            });
                        };

                        const finalImageUrl = await cropAndCenter(modelDataUrl);

                        // Populate the share card
                        document.getElementById('share-model-img').src = finalImageUrl;
                        document.getElementById('share-username').innerText = '@' + this.tamaUsername;
                        document.getElementById('share-scan-count').innerText = this.tamaTotalScans;
                        document.getElementById('share-mood-emoji').innerText = this.getMoodEmoji(this.expState);
                        document.getElementById('share-exp').innerText = Math.floor(this.expPoints);
                        
                        // Set experience bar width
                        const expBar = document.getElementById('share-exp-bar');
                        if (expBar) {
                            expBar.style.width = Math.min(100, Math.max(0, this.expPoints)) + '%';
                            if (this.expPoints >= 75) {
                                expBar.style.background = 'linear-gradient(90deg, #34d399, #10b981)';
                            } else if (this.expPoints >= 35) {
                                expBar.style.background = 'linear-gradient(90deg, #fcd34d, #f59e0b)';
                            } else {
                                expBar.style.background = 'linear-gradient(90deg, #f87171, #ef4444)';
                            }
                        }

                        const shareCard = document.getElementById('share-card-container');
                        
                        // Capture
                        const canvas = await html2canvas(shareCard, {
                            backgroundColor: '#0f172a',
                            scale: 2 // High res
                        });
                        
                        canvas.toBlob((blob) => {
                            this.shareFile = new File([blob], 'ar-journey.png', { type: 'image/png' });
                            
                            if (navigator.canShare && navigator.canShare({ files: [this.shareFile] })) {
                                // Tunggu klik manual user agar tidak terkena User Gesture Expired blokir browser
                                this.shareState = 'ready';
                            } else {
                                // Fallback (Browser lama / tidak support file share)
                                this.shareState = 'idle';
                                const link = document.createElement('a');
                                link.download = `ar-journey-${this.tamaUsername}.png`;
                                link.href = URL.createObjectURL(blob);
                                link.click();
                                
                                setTimeout(() => {
                                    alert('Perangkat Anda belum mendukung direct image share. Gambar telah didownload ke galeri, silakan lampirkan secara manual ke WA/IG Story Anda! ✨');
                                }, 500);
                            }
                        });
                    } catch (err) {
                        this.shareState = 'idle';
                        console.error('Error generating share image', err);
                        alert('Terjadi kesalahan saat mempersiapkan kartu.');
                    }
                },

                async executeShare() {
                    if (!this.shareFile) return;
                    const originalText = `🐾 Journey Tamagotchi AR ku!\nExplorer: @${this.tamaUsername}\nCoba scan QR Code nya di ScanYuk!`;
                    
                    try {
                        await navigator.share({
                            title: 'AR Journey',
                            text: originalText,
                            files: [this.shareFile]
                        });
                    } catch (e) {
                        console.log('Share canceled or failed', e);
                    } finally {
                        this.shareState = 'idle';
                    }
                },

                // === TAMAGOTCHI CORE LOGIC ===
                initExpression(uuid) {
                    // exp_points already loaded from server via session
                    this.updateExpState();
                    
                    // Periodically show message every 10 seconds
                    if (this.messageInterval) clearInterval(this.messageInterval);
                    this.messageInterval = setInterval(() => {
                        if (this.arActive && !this.showExpMessage) {
                            this.setRandomMessage(this.expState);
                        }
                    }, 10000);

                    if (this.expInterval) clearInterval(this.expInterval);
                    this.expInterval = setInterval(() => {
                        this.expLoop();
                    }, 1000);

                    // Setup GPS 
                    if (!this.gpsWatchId && navigator.geolocation) {
                        this.gpsWatchId = navigator.geolocation.watchPosition((pos) => {
                            let lat = pos.coords.latitude;
                            let lon = pos.coords.longitude;
                            
                            if (this.lastLat !== null && this.lastLon !== null) {
                                let dist = this.getDistanceFromLatLonInKm(this.lastLat, this.lastLon, lat, lon) * 1000;
                                if (dist > 100 && this.expPoints < 75) {
                                    this.expPoints = 100;
                                    this.updateExpState();
                                }
                            }
                            
                            this.lastLat = lat;
                            this.lastLon = lon;
                        }, (err) => {}, { enableHighAccuracy: false, maximumAge: 60000, timeout: 10000 });
                    }

                    // Listen for visibility change (lockscreen detection)
                    document.addEventListener('visibilitychange', () => {
                        if (document.hidden) {
                            this._hiddenAt = Date.now();
                        } else {
                            // Page became visible again
                            if (this._hiddenAt && (Date.now() - this._hiddenAt > 60000)) {
                                // Hidden for > 60 seconds = likely lockscreen
                                // Clear browser session so next QR scan = new scan
                                if (this.arUuid) {
                                    sessionStorage.removeItem('tama_browser_' + this.arUuid);
                                }
                            }
                        }
                    });
                },
                
                getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
                    var R = 6371; 
                    var dLat = (lat2-lat1) * (Math.PI/180);
                    var dLon = (lon2-lon1) * (Math.PI/180); 
                    var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                            Math.cos(lat1 * (Math.PI/180)) * Math.cos(lat2 * (Math.PI/180)) * 
                            Math.sin(dLon/2) * Math.sin(dLon/2); 
                    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
                    return R * c; 
                },

                expLoop() {
                    let hour = new Date().getHours();
                    let isTimeOverride = false;
                    
                    if (hour >= 21 || hour < 6) {
                        this.setExpState('tidur');
                        isTimeOverride = true;
                        // Saat tidur, energi pulih secara alami (1 poin per jam = 1/3600 per detik)
                        this.expPoints = Math.min(100, this.expPoints + 0.0002777);
                    } else if (hour === 20) {
                        this.setExpState('cape');
                        isTimeOverride = true;
                        // Saat cape (jam 20), energi tetap turun perlahan
                        this.expPoints = Math.max(0, this.expPoints - 0.0005787);
                    }
                    
                    if (!isTimeOverride) {
                        // 1. Turun otomatis secara konstan (sekitar 0.0005787 poin per detik = 48 jam)
                        this.expPoints = Math.max(0, this.expPoints - 0.0005787); 
                        
                        // 2. Naik otomatis secara halus JIKA sedang scan DAN poin masih di bawah 35 (Suntuk)
                        // (+1 poin per detik)
                        if (this.arActive && this.expPoints < 35) {
                            this.expPoints = Math.min(35, this.expPoints + 1);
                        }
                        
                        this.updateExpState();
                    }
                },
                
                updateExpState() {
                    let oldState = this.expState;
                    if (this.expPoints >= 75) this.expState = 'senang';
                    else if (this.expPoints >= 35) this.expState = 'suntuk';
                    else if (this.expPoints >= 10) this.expState = 'marah';
                    else this.expState = 'menangis';
                    
                    if (oldState !== this.expState || !this.expMessage) {
                        this.setRandomMessage(this.expState);
                    }
                },
                
                setExpState(state) {
                    if (this.expState !== state) {
                        this.expState = state;
                        this.setRandomMessage(state);
                    }
                },
                
                setRandomMessage(state) {
                    let msgs = this.messages[state] || ["..."];
                    this.expMessage = msgs[Math.floor(Math.random() * msgs.length)];
                    this.showExpMessage = true;
                    if (this.msgTimeout) clearTimeout(this.msgTimeout);
                    this.msgTimeout = setTimeout(() => {
                        this.showExpMessage = false;
                    }, 3000);
                },

                showError(msg) { this.errorMessage = msg; setTimeout(() => { this.errorMessage = ''; }, 4000); }
            }
        }
    </script>
    
    <!-- Hidden Share Card Template -->
    <div id="share-card-container" style="position: absolute; top: -9999px; left: -9999px; width: 480px; height: 853px; background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%); border-radius: 30px; overflow: hidden; font-family: 'Nunito', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; display: flex; flex-direction: column; box-shadow: 0 0 0 10px rgba(255,255,255,0.3) inset;">
        
        <!-- Header -->
        <div style="padding: 40px 20px 0; text-align: center; z-index: 10;">
            <h2 style="color: white; margin: 0; font-size: 38px; font-weight: 900; text-transform: uppercase; letter-spacing: 3px; text-shadow: 0 4px 6px rgba(0,0,0,0.2);">AR JOURNEY</h2>
        </div>
        
        <!-- Character Showcase -->
        <div style="flex: 1; position: relative; margin-top: -10px;">
            <!-- Glow effect behind character -->
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 350px; height: 350px; background: radial-gradient(circle, rgba(255,255,255,0.7) 0%, rgba(255,255,255,0) 70%); border-radius: 50%; z-index: 1;"></div>
            
            <!-- Podium -->
            <div style="position: absolute; bottom: 20%; left: 50%; transform: translateX(-50%); width: 280px; height: 50px; background: rgba(0,0,0,0.2); border-radius: 50%; filter: blur(5px); z-index: 1;"></div>
            
            <!-- Character Image wrapper with explicit positioning -->
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 5;">
                <img id="share-model-img" src="" style="position: absolute; top: 50%; left: 50%; width: 350px; height: 350px; margin-left: -175px; margin-top: -175px; filter: drop-shadow(0 20px 25px rgba(0,0,0,0.3)); transform: scale(1.15);">
            </div>
            
            <!-- Floating Badge using explicit absolute positioning to avoid html2canvas text bugs -->
            <div style="position: absolute; top: 20px; right: 20px; background: #fbbf24; border: 4px solid #fff; border-radius: 50%; width: 90px; height: 90px; box-shadow: 0 10px 15px rgba(0,0,0,0.2); z-index: 10; transform: rotate(15deg);">
                <div style="position: absolute; top: 18px; left: 0; width: 100%; text-align: center; font-size: 14px; font-weight: 900; color: #78350f; text-transform: uppercase; line-height: 1; margin: 0; padding: 0;">Scan</div>
                <div id="share-scan-count" style="position: absolute; top: 35px; left: 0; width: 100%; text-align: center; font-size: 40px; font-weight: 900; color: #fff; text-shadow: 0 2px 0 #d97706; line-height: 1; margin: 0; padding: 0; transform: translateY(-16px);">0</div>
            </div>
        </div>
        
        <!-- Stats Card -->
        <div style="background: rgba(255, 255, 255, 0.95); margin: 0 20px 20px; padding: 25px; border-radius: 25px; box-shadow: 0 15px 30px rgba(0,0,0,0.15); z-index: 10; position: relative;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <div style="flex: 1;">
                    <p style="color: #64748b; margin: 0 0 4px 0; font-size: 14px; font-weight: 700; text-transform: uppercase;">Explorer</p>
                    <p style="color: #1e293b; margin: 0; font-size: 26px; font-weight: 900;" id="share-username">@Player</p>
                </div>
                <div style="width: 60px; height: 60px; background: #f1f5f9; border-radius: 15px; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.05)); position: relative;">
                    <div id="share-mood-emoji" style="position: absolute; top: 10px; left: 0; width: 100%; text-align: center; font-size: 38px; line-height: 1; margin: 0; padding: 0; transform: translateY(-16px);">😊</div>
                </div>
            </div>
            
            <div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span style="color: #64748b; font-size: 14px; font-weight: 700;">Experience</span>
                    <span style="color: #8b5cf6; font-size: 16px; font-weight: 900;"><span id="share-exp">100</span> / 100</span>
                </div>
                <div style="width: 100%; background: #e2e8f0; border-radius: 10px; height: 14px; overflow: hidden; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);">
                    <div id="share-exp-bar" style="width: 100%; height: 100%; background: linear-gradient(90deg, #34d399, #10b981); border-radius: 10px; transition: width 0.3s ease;"></div>
                </div>
            </div>
            
            <div style="margin-top: 25px; padding-top: 15px; border-top: 2px dashed #e2e8f0; text-align: center;">
                <p style="color: #94a3b8; margin: 0; font-size: 14px; font-weight: 700;">Dimainkan di <span style="color: #3b82f6;">ScanYuk WebAR</span></p>
            </div>
        </div>
    </div>
</body>
</html>