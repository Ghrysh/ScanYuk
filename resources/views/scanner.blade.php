<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>True WebAR Scanner - ScanYuk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
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

    <div id="ar-overlay-container">
        <img x-show="arData.type === '2d'" :src="arData.src" class="w-full h-full object-contain filter drop-shadow(0 25px 25px rgba(0,0,0,0.8))">
        <model-viewer 
            x-show="arData.type === '3d'" 
            id="main-ar-viewer"
            :src="arData.src" 
            camera-controls 
            interaction-prompt="none"
            shadow-intensity="1" 
            loading="eager"
            class="w-full h-full bg-transparent">
        </model-viewer>
    </div>

    <div class="fixed top-6 left-6 z-40">
        <a href="{{ route('home') }}" class="bg-black/40 backdrop-blur-md text-white px-5 py-2.5 rounded-full flex items-center gap-2 hover:bg-black/60 transition shadow-lg border border-white/20">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
            Kembali
        </a>
    </div>

    <div x-show="isFetching" style="display: none;" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50 bg-black/60 backdrop-blur-xl text-white px-6 py-4 rounded-2xl flex flex-col items-center gap-3 shadow-2xl border border-white/10">
        <svg class="animate-spin h-8 w-8 text-teal-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        <span class="font-medium text-sm tracking-wide">Membuka Portal AR...</span>
    </div>

    <div x-show="arActive" style="display: none;" class="fixed bottom-10 left-1/2 transform -translate-x-1/2 z-40 w-full max-w-xs">
        <button @click="replayVoice()" class="w-full bg-gradient-to-r from-teal-500 to-indigo-500 hover:opacity-90 backdrop-blur-md text-white py-4 px-4 rounded-2xl font-bold flex items-center justify-center gap-2 transition shadow-[0_10px_25px_rgba(20,184,166,0.4)]">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M13.5 4.06c0-1.336-1.616-2.005-2.56-1.06l-4.5 4.5H4.508c-1.141 0-2.318.664-2.66 1.905A9.76 9.76 0 001.5 12c0 .898.121 1.768.35 2.595.341 1.24 1.518 1.905 2.659 1.905h1.93l4.5 4.5c.945.945 2.561.276 2.561-1.06V4.06zM18.584 5.106a.75.75 0 011.06 0c3.808 3.807 3.808 9.98 0 13.788a.75.75 0 11-1.06-1.06 8.25 8.25 0 000-11.668.75.75 0 010-1.06z" /><path d="M15.932 7.757a.75.75 0 011.061 0 4.5 4.5 0 010 6.364.75.75 0 01-1.06-1.06 3 3 0 000-4.243.75.75 0 010-1.061z" /></svg>
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
                
                arData: { type: '2d', src: '' },
                bgmPlayer: null,
                narrationPlayer: null,
                
                curX: 0, curY: 0, curScale: 0, curAngle: 0, curYaw: 0, curPitch: 0,
                targetX: 0, targetY: 0, targetScale: 0, targetAngle: 0, targetYaw: 0, targetPitch: 0,
                hasSnaped: false,

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
                            
                            if (!this.arCache[this.currentQrUrl] && !this.isFetching) {
                                this.fetchArData(this.currentQrUrl);
                            } 
                            else if (this.arCache[this.currentQrUrl] && this.arCache[this.currentQrUrl].ready) {
                                if (!this.arActive) {
                                    this.arActive = true;
                                    this.restartAudioFromPause(this.currentQrUrl);
                                }
                                this.calculateTarget(code.location);
                            }
                        } else {
                            if (Date.now() - this.lastFoundTime > 500) { 
                                this.arOverlayContainer.style.opacity = '0';
                                this.arOverlayContainer.style.pointerEvents = 'none';
                                this.arOverlayContainer.style.transform = 'translate3d(-9999px, -9999px, 0)';
                                this.arActive = false;
                                this.hasSnaped = false;
                                this.stopAllAudio();
                            }
                        }
                    }
                    requestAnimationFrame(() => this.logicLoop());
                },

                calculateTarget(loc) {
                    const tl = loc.topLeftCorner, tr = loc.topRightCorner, br = loc.bottomRightCorner, bl = loc.bottomLeftCorner;
                    const centerX = (tl.x + tr.x + br.x + bl.x) / 4;
                    const centerY = (tl.y + tr.y + br.y + bl.y) / 4;
                    const qrWidth = (Math.hypot(tr.x - tl.x, tr.y - tl.y) + Math.hypot(br.x - bl.x, br.y - bl.y)) / 2;
                    let angle = Math.atan2(tr.y - tl.y, tr.x - tl.x) * (180 / Math.PI);

                    const sideL = Math.hypot(tl.x - bl.x, tl.y - bl.y);
                    const sideR = Math.hypot(tr.x - br.x, tr.y - br.y);
                    const sideT = Math.hypot(tl.x - tr.x, tl.y - tr.y);
                    const sideB = Math.hypot(bl.x - br.x, bl.y - br.y);

                    let pitch = ((sideB - sideT) / Math.max(sideB, sideT)) * 60; 
                    let yaw = ((sideR - sideL) / Math.max(sideR, sideL)) * 60; 

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
                    this.targetYaw = yaw;
                    this.targetPitch = pitch;
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
                            let ease = Math.min(1.0, 0.3 + (dist / 100)); 
                            
                            this.curX += (this.targetX - this.curX) * ease;
                            this.curY += (this.targetY - this.curY) * ease;
                            this.curScale += (this.targetScale - this.curScale) * 0.2;

                            let dAngle = this.targetAngle - this.curAngle;
                            if (dAngle > 180) dAngle -= 360;
                            if (dAngle < -180) dAngle += 360;
                            this.curAngle += dAngle * ease;

                            this.curYaw += (this.targetYaw - this.curYaw) * ease;
                            this.curPitch += (this.targetPitch - this.curPitch) * ease;
                        }

                        this.arOverlayContainer.style.transform = `translate3d(${this.curX}px, ${this.curY}px, 0) scale(${this.curScale}) perspective(800px) rotateZ(${this.curAngle}deg) rotateX(${this.curPitch}deg) rotateY(${this.curYaw}deg)`;
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
                            const src = type === '3d' ? result.data.file_3d_url : result.data.image_url;
                            
                            this.arData = { type: type, src: src };
                            
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
                                const viewer = document.querySelector('#ar-overlay-container model-viewer');
                                if(!viewer) return resolve();

                                const onProgress = (event) => {
                                    let currentProg = Math.round(event.detail.totalProgress * 100);
                                    if (currentProg > this.loadingProgress) {
                                        this.loadingProgress = currentProg;
                                    }
                                };

                                const onLoad = () => {
                                    this.loadingProgress = 100;
                                    cleanUp();
                                    resolve();
                                };

                                const onError = () => {
                                    console.error("Gagal merender 3D. Pastikan file .glb valid dan bukan 404 HTML.");
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

                                setTimeout(() => {
                                    cleanUp();
                                    resolve();
                                }, 10000); 
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
                        this.isLoading = false; 
                        this.loadingProgress = 100;
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

                showError(msg) { this.errorMessage = msg; setTimeout(() => { this.errorMessage = ''; }, 4000); }
            }
        }
    </script>
</body>
</html>