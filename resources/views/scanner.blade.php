<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>True WebAR Scanner - ScanYuk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
    <style>
        body { background-color: #000; margin: 0; overflow: hidden; touch-action: none; }
        
        #qr-video {
            position: absolute;
            top: 0; left: 0;
            width: 100vw; height: 100vh;
            object-fit: cover;
            z-index: 10;
        }

        #ar-overlay {
            position: absolute;
            z-index: 30;
            object-fit: contain;
            transform-origin: center center;
            display: none;
            filter: drop-shadow(0 25px 25px rgba(0,0,0,0.8));
            pointer-events: none;
        }
    </style>
</head>
<body x-data="arTracker()" x-init="startCamera()">

    <video id="qr-video" playsinline autoplay muted></video>
    
    <canvas id="qr-canvas" style="display: none;"></canvas>

    <img id="ar-overlay" src="" alt="AR Object">

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

    <div x-show="arActive" style="display: none;" class="fixed bottom-10 left-1/2 transform -translate-x-1/2 z-40 w-full max-w-xs"
         x-transition:enter="ease-out duration-300 delay-300"
         x-transition:enter-start="opacity-0 translate-y-12"
         x-transition:enter-end="opacity-100 translate-y-0">
        <button @click="replayVoice()" class="w-full bg-gradient-to-r from-teal-500 to-indigo-500 hover:opacity-90 backdrop-blur-md text-white py-4 px-4 rounded-2xl font-bold flex items-center justify-center gap-2 transition shadow-[0_10px_25px_rgba(20,184,166,0.4)]">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M13.5 4.06c0-1.336-1.616-2.005-2.56-1.06l-4.5 4.5H4.508c-1.141 0-2.318.664-2.66 1.905A9.76 9.76 0 001.5 12c0 .898.121 1.768.35 2.595.341 1.24 1.518 1.905 2.659 1.905h1.93l4.5 4.5c.945.945 2.561.276 2.561-1.06V4.06zM18.584 5.106a.75.75 0 011.06 0c3.808 3.807 3.808 9.98 0 13.788a.75.75 0 11-1.06-1.06 8.25 8.25 0 000-11.668.75.75 0 010-1.06z" /><path d="M15.932 7.757a.75.75 0 011.061 0 4.5 4.5 0 010 6.364.75.75 0 01-1.06-1.06 3 3 0 000-4.243.75.75 0 010-1.061z" /></svg>
            Putar Ulang Narasi
        </button>
    </div>

    <div x-show="errorMessage" style="display: none;" class="fixed top-10 left-1/2 transform -translate-x-1/2 z-[60] bg-red-500/90 backdrop-blur-md text-white px-6 py-3 rounded-full shadow-2xl font-semibold flex items-center gap-3 w-fit whitespace-nowrap border border-white/20">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
        <span x-text="errorMessage" class="text-sm"></span>
    </div>

    <script>
        function arTracker() {
            return {
                video: null,
                canvasElement: null,
                canvas: null,
                arOverlay: null,
                
                isFetching: false,
                arActive: false,
                errorMessage: '',
                
                arCache: {}, 
                currentQrUrl: null,
                lastFoundTime: 0,

                startCamera() {
                    this.video = document.getElementById("qr-video");
                    this.canvasElement = document.getElementById("qr-canvas");
                    this.canvas = this.canvasElement.getContext("2d", { willReadFrequently: true });
                    this.arOverlay = document.getElementById("ar-overlay");

                    navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } }).then((stream) => {
                        this.video.srcObject = stream;
                        this.video.setAttribute("playsinline", true);
                        this.video.play();
                        requestAnimationFrame(() => this.tick());
                    }).catch(err => {
                        this.showError("Akses kamera ditolak atau tidak ditemukan.");
                    });
                },

                tick() {
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
                                this.updateImagePosition(code.location);
                            }
                        } else {
                            if (Date.now() - this.lastFoundTime > 400) { 
                                this.arOverlay.style.display = 'none';
                                this.arActive = false;
                            }
                        }
                    }
                    requestAnimationFrame(() => this.tick());
                },

                updateImagePosition(loc) {
                    const tl = loc.topLeftCorner;
                    const tr = loc.topRightCorner;
                    const br = loc.bottomRightCorner;
                    const bl = loc.bottomLeftCorner;

                    const centerX = (tl.x + tr.x + br.x + bl.x) / 4;
                    const centerY = (tl.y + tr.y + br.y + bl.y) / 4;

                    const qrWidth = (Math.hypot(tr.x - tl.x, tr.y - tl.y) + Math.hypot(br.x - bl.x, br.y - bl.y)) / 2;

                    const angle = Math.atan2(tr.y - tl.y, tr.x - tl.x) * (180 / Math.PI);

                    const vw = window.innerWidth;
                    const vh = window.innerHeight;
                    const videoRatio = this.video.videoWidth / this.video.videoHeight;
                    const screenRatio = vw / vh;

                    let scale, offsetX = 0, offsetY = 0;
                    if (screenRatio > videoRatio) {
                        scale = vw / this.video.videoWidth;
                        offsetY = (vh - (this.video.videoHeight * scale)) / 2;
                    } else {
                        scale = vh / this.video.videoHeight;
                        offsetX = (vw - (this.video.videoWidth * scale)) / 2;
                    }

                    const screenX = (centerX * scale) + offsetX;
                    const screenY = (centerY * scale) + offsetY;
                    const screenQrWidth = qrWidth * scale;

                    const imageSize = screenQrWidth * 1.8; 

                    this.arOverlay.style.width = imageSize + 'px';
                    this.arOverlay.style.height = imageSize + 'px';
                    this.arOverlay.style.left = (screenX - imageSize/2) + 'px';
                    this.arOverlay.style.top = (screenY - imageSize/2) + 'px';
                    this.arOverlay.style.transform = `rotate(${angle}deg)`;
                    this.arOverlay.style.display = 'block';
                },

                async fetchArData(url) {
                    this.isFetching = true;
                    try {
                        const response = await fetch(url);
                        const result = await response.json();

                        if (response.ok && result.status === 'success') {
                            this.arCache[url] = {
                                image_url: result.data.image_url,
                                narration: result.data.narration,
                                ready: true
                            };

                            this.arOverlay.src = result.data.image_url;
                            
                            this.replayVoice(result.data.narration);
                        } else {
                            this.showError(result.message || "QR Code tidak valid.");
                            this.arCache[url] = { ready: false };
                        }
                    } catch (error) {
                        this.showError("Terjadi kesalahan jaringan.");
                    } finally {
                        this.isFetching = false;
                    }
                },

                replayVoice(customText = null) {
                    window.speechSynthesis.cancel();
                    let text = customText || (this.arCache[this.currentQrUrl] ? this.arCache[this.currentQrUrl].narration : '');
                    
                    if (text) {
                        let utterance = new SpeechSynthesisUtterance(text);
                        utterance.lang = 'id-ID';
                        utterance.rate = 0.9;
                        window.speechSynthesis.speak(utterance);
                    }
                },

                showError(msg) {
                    this.errorMessage = msg;
                    setTimeout(() => { this.errorMessage = ''; }, 4000);
                }
            }
        }
    </script>
</body>
</html>
