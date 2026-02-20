<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>AR Scanner - ScanYuk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <style>
        body { background-color: #0f172a; margin: 0; overflow: hidden; }
        .scan-line {
            width: 100%; height: 3px; background: #14b8a6;
            box-shadow: 0 0 10px #14b8a6, 0 0 20px #14b8a6;
            position: absolute; top: 0; left: 0;
            animation: scan 2.5s infinite linear;
            z-index: 10;
        }
        @keyframes scan {
            0% { top: 0; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }

        #reader { width: 100%; height: 100vh; border: none !important; }
        #reader video { object-fit: cover !important; height: 100vh !important; }
        #reader__dashboard_section_csr { display: none !important; }
    </style>
</head>
<body x-data="arScanner()" x-init="initScanner()">

    <div class="fixed top-0 left-0 w-full p-4 z-40 flex justify-between items-center pointer-events-auto">
        <a href="{{ route('home') }}" @click="stopScanner()" class="bg-black/50 backdrop-blur-md text-white px-4 py-2 rounded-full flex items-center gap-2 hover:bg-black/70 transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
            Tutup Kamera
        </a>
    </div>

    <div class="relative w-full h-screen flex justify-center items-center">
        <div x-show="!arActive" class="absolute w-64 h-64 border-2 border-teal-500/50 rounded-2xl z-20 pointer-events-none flex items-center justify-center">
            <div class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-teal-400 rounded-tl-xl"></div>
            <div class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-teal-400 rounded-tr-xl"></div>
            <div class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-teal-400 rounded-bl-xl"></div>
            <div class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-teal-400 rounded-br-xl"></div>
            <div class="scan-line"></div>
            <p class="text-white/70 text-sm font-semibold mt-32 text-center bg-black/40 px-3 py-1 rounded-full backdrop-blur-sm">Arahkan ke QR Code ScanYuk</p>
        </div>

        <div id="reader"></div>
    </div>

    <div x-show="arActive" x-transition.opacity.duration.500ms style="display: none;" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/80 backdrop-blur-md p-4">
        
        <div class="relative w-full max-w-sm bg-white rounded-3xl overflow-hidden shadow-2xl transform transition-transform" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="scale-90 translate-y-8"
             x-transition:enter-end="scale-100 translate-y-0">
            
            <button @click="closeAR()" class="absolute top-4 right-4 bg-white/50 hover:bg-white/90 backdrop-blur-sm text-slate-800 p-2 rounded-full transition z-10 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>

            <div class="w-full h-80 bg-slate-100 flex items-center justify-center p-4 relative">
                <template x-if="arData.image_url">
                    <img :src="arData.image_url" class="max-w-full max-h-full object-contain drop-shadow-xl animate-pulse">
                </template>
            </div>

            <div class="p-6 text-center bg-white">
                <h3 class="text-xl font-bold text-slate-900 mb-2" x-text="arData.title"></h3>
                
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-700 rounded-full text-sm font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" /></svg>
                    Mendengarkan Narasi AI...
                </div>
            </div>
        </div>
    </div>

    <div x-show="errorMessage" style="display: none;" class="fixed top-20 left-1/2 transform -translate-x-1/2 z-[60] bg-red-600 text-white px-6 py-3 rounded-xl shadow-lg font-medium flex items-center gap-3 w-[90%] max-w-md">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
        <span x-text="errorMessage" class="text-sm"></span>
        <button @click="errorMessage = ''" class="ml-auto p-1 bg-red-700 rounded hover:bg-red-800"><svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></button>
    </div>

    <script>
        function arScanner() {
            return {
                html5QrcodeScanner: null,
                isProcessing: false,
                arActive: false,
                errorMessage: '',
                arData: { title: '', image_url: '', narration: '' },

                initScanner() {
                    const html5QrCode = new Html5Qrcode("reader");
                    const config = { fps: 10, qrbox: { width: 250, height: 250 } };

                    html5QrCode.start({ facingMode: "environment" }, config, (decodedText) => {
                        this.onScanSuccess(decodedText, html5QrCode);
                    }).catch(err => {
                        this.errorMessage = "Gagal mengakses kamera. Pastikan izin kamera diberikan.";
                    });

                    this.html5QrcodeScanner = html5QrCode;
                },

                async onScanSuccess(decodedText, scannerInstance) {
                    if (this.isProcessing || this.arActive) return;
                    
                    if (!decodedText.includes('/api/scan/')) {
                        return; 
                    }

                    this.isProcessing = true;
                    scannerInstance.pause();

                    try {
                        const response = await fetch(decodedText);
                        
                        const contentType = response.headers.get("content-type");
                        if (!contentType || !contentType.includes("application/json")) {
                            this.errorMessage = "Server membalas dengan HTML (Cek URL/Error Server).";
                            setTimeout(() => { this.errorMessage = ''; scannerInstance.resume(); }, 5000);
                            return;
                        }

                        const result = await response.json();

                        if (response.ok && result.status === 'success') {
                            this.arData = result.data;
                            this.arActive = true;
                            this.playAI_Voice(this.arData.narration);
                        } else {
                            this.errorMessage = result.message || "QR Code tidak valid.";
                            setTimeout(() => { this.errorMessage = ''; scannerInstance.resume(); }, 3000);
                        }
                    } catch (error) {
                        this.errorMessage = "Error: " + error.message;
                        setTimeout(() => { this.errorMessage = ''; scannerInstance.resume(); }, 5000);
                    } finally {
                        this.isProcessing = false;
                    }
                },

                playAI_Voice(text) {
                    window.speechSynthesis.cancel();
                    if (text) {
                        let utterance = new SpeechSynthesisUtterance(text);
                        utterance.lang = 'id-ID';
                        utterance.rate = 0.9;
                        window.speechSynthesis.speak(utterance);
                    }
                },

                closeAR() {
                    this.arActive = false;
                    window.speechSynthesis.cancel();
                    
                    setTimeout(() => {
                        this.arData = { title: '', image_url: '', narration: '' };
                        if (this.html5QrcodeScanner) {
                            this.html5QrcodeScanner.resume();
                        }
                    }, 1000);
                },

                stopScanner() {
                    if (this.html5QrcodeScanner) {
                        this.html5QrcodeScanner.stop().catch(err => console.error(err));
                    }
                }
            }
        }
    </script>
</body>
</html>