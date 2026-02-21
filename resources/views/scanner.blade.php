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
            display: none;
            pointer-events: auto; 
            will-change: transform; 
        }
    </style>
</head>
<body x-data="arTracker()" x-init="startCamera()">

    <video id="qr-video" playsinline autoplay muted></video>
    <canvas id="qr-canvas" style="display: none;"></canvas>
    
    <div id="ar-overlay-container">
        <img x-show="arData.type === '2d'" :src="arData.src" class="w-full h-full object-contain filter drop-shadow(0 25px 25px rgba(0,0,0,0.8))">
        <model-viewer 
            x-show="arData.type === '3d'" 
            :src="arData.src" 
            class="w-full h-full" 
            camera-controls 
            shadow-intensity="1" 
            exposure="1.2"
            loading="eager">
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

    <div x-show="errorMessage" style="display: none;" class="fixed top-10 left-1/2 transform -translate-x-1/2 z-[60] bg-red-500/90 backdrop-blur-md text-white px-6 py-3 rounded-full shadow-2xl font-semibold flex items-center gap-3 w-fit whitespace-nowrap border border-white/20">
        <span x-text="errorMessage" class="text-sm"></span>
    </div>

    <script>
        function arTracker() {
            return {
                video: null, canvasElement: null, canvas: null, arOverlayContainer: null,
                isFetching: false, arActive: false, errorMessage: '', arCache: {}, currentQrUrl: null, lastFoundTime: 0,
                
                arData: { type: '2d', src: '' },
                bgmPlayer: null,
                
                curX: 0, curY: 0, curScale: 0, curAngle: 0,
                targetX: 0, targetY: 0, targetScale: 0, targetAngle: 0,
                hasSnaped: false,

                startCamera() {
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

                        if (code && code.data.includes('/api/scan/')) {
                            this.lastFoundTime = Date.now();
                            this.currentQrUrl = code.data;
                            
                            if (!this.arCache[this.currentQrUrl] && !this.isFetching) {
                                this.fetchArData(this.currentQrUrl);
                            } 
                            else if (this.arCache[this.currentQrUrl] && this.arCache[this.currentQrUrl].ready) {
                                this.arActive = true;
                                this.calculateTarget(code.location);
                            }
                        } else {
                            if (Date.now() - this.lastFoundTime > 500) { 
                                this.arOverlayContainer.style.display = 'none';
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
                    
                    const imageSize = (qrWidth * scale) * 2;
                    this.targetScale = imageSize / 250; 
                    this.targetAngle = angle;
                },

                renderLoop() {
                    if (this.arActive) {
                        if (!this.hasSnaped) {
                            this.curX = this.targetX; 
                            this.curY = this.targetY;
                            this.curAngle = this.targetAngle;
                            this.curScale = 0; 
                            this.arOverlayContainer.style.display = 'block';
                            this.hasSnaped = true;
                        } else {
                            let dist = Math.hypot(this.targetX - this.curX, this.targetY - this.curY);
                            let ease = Math.min(1.0, 0.2 + (dist / 80)); 
                            
                            this.curX += (this.targetX - this.curX) * ease;
                            this.curY += (this.targetY - this.curY) * ease;
                            this.curScale += (this.targetScale - this.curScale) * 0.15;

                            let dAngle = this.targetAngle - this.curAngle;
                            if (dAngle > 180) dAngle -= 360;
                            if (dAngle < -180) dAngle += 360;
                            this.curAngle += dAngle * ease;
                        }

                        this.arOverlayContainer.style.transform = `translate3d(${this.curX}px, ${this.curY}px, 0) rotate(${this.curAngle}deg) scale(${this.curScale})`;
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
                                bgm_url: result.data.bgm_url,
                                ready: true 
                            };
                            
                            this.arActive = true;
                            this.playAllAudio(url);

                        } else {
                            this.showError("QR Code tidak valid / Limit.");
                            this.arCache[url] = { ready: false };
                        }
                    } catch (error) { this.showError("Terjadi kesalahan jaringan."); } 
                    finally { this.isFetching = false; }
                },

                playAllAudio(url) {
                    this.stopAllAudio();
                    const cache = this.arCache[url];
                    
                    if(cache) {
                        if(cache.bgm_url) {
                            this.bgmPlayer = new Audio(cache.bgm_url);
                            this.bgmPlayer.volume = 0.3;
                            this.bgmPlayer.loop = true;
                            this.bgmPlayer.play().catch(e => console.log('BGM Play Error:', e));
                        }
                        
                        if(cache.narration) {
                            let utterance = new SpeechSynthesisUtterance(cache.narration);
                            utterance.lang = 'id-ID';
                            utterance.volume = 1.0;
                            window.speechSynthesis.speak(utterance);
                        }
                    }
                },

                replayVoice() {
                    this.playAllAudio(this.currentQrUrl);
                },

                stopAllAudio() {
                    window.speechSynthesis.cancel();
                    if(this.bgmPlayer) {
                        this.bgmPlayer.pause();
                        this.bgmPlayer.currentTime = 0;
                        this.bgmPlayer = null;
                    }
                },

                showError(msg) { this.errorMessage = msg; setTimeout(() => { this.errorMessage = ''; }, 4000); }
            }
        }
    </script>
</body>
</html>