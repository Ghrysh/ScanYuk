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
        body { background-color: #000; margin: 0; overflow: hidden; }
        
        #reader { width: 100vw; height: 100vh; border: none !important; }
        #reader video { object-fit: cover !important; width: 100vw !important; height: 100vh !important; }
        
        #reader__dashboard_section_csr, #reader__dashboard_section_swaplink, #reader__glass_ext { display: none !important; }
    </style>
</head>
<body x-data="arScanner()" x-init="initScanner()">

    <div x-show="!arActive" class="fixed top-6 left-6 z-40">
        <a href="{{ route('home') }}" @click="stopScanner()" class="bg-black/30 backdrop-blur-md text-white px-5 py-2.5 rounded-full flex items-center gap-2 hover:bg-black/50 transition shadow-lg border border-white/20">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
            Kembali
        </a>
    </div>

    <div x-show="isProcessing" style="display: none;" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50 bg-black/60 backdrop-blur-xl text-white px-6 py-4 rounded-2xl flex flex-col items-center gap-3 shadow-2xl border border-white/10">
        <svg class="animate-spin h-8 w-8 text-teal-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        <span class="font-medium text-sm tracking-wide">Menyiapkan AR...</span>
    </div>

    <div id="reader"></div>

    <div x-show="arActive" style="display: none;" class="fixed inset-0 z-50 flex flex-col items-center justify-between p-6">
        
        <div class="w-full h-10"></div>

        <div class="flex-grow flex items-center justify-center w-full max-w-sm relative"
             x-transition:enter="ease-out duration-500"
             x-transition:enter-start="opacity-0 scale-75 translate-y-10"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            <template x-if="arData.image_url">
                <img :src="arData.image_url" class="max-w-full max-h-[60vh] object-contain drop-shadow-[0_20px_25px_rgba(0,0,0,0.6)]">
            </template>
        </div>

        <div x-show="arActive" class="w-full max-w-sm flex items-center justify-between gap-4 pb-4"
             x-transition:enter="ease-out duration-300 delay-300"
             x-transition:enter-start="opacity-0 translate-y-12"
             x-transition:enter-end="opacity-100 translate-y-0">
            
            <button @click="closeAR()" class="flex-1 bg-white/20 hover:bg-white/30 backdrop-blur-xl border border-white/30 text-white py-4 px-4 rounded-2xl font-bold flex items-center justify-center gap-2 transition shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                Tutup AR
            </button>

            <button @click="playAI_Voice(arData.narration)" class="flex-1 bg-gradient-to-r from-teal-500 to-indigo-500 hover:opacity-90 backdrop-blur-md text-white py-4 px-4 rounded-2xl font-bold flex items-center justify-center gap-2 transition shadow-[0_10px_25px_rgba(20,184,166,0.4)]">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M13.5 4.06c0-1.336-1.616-2.005-2.56-1.06l-4.5 4.5H4.508c-1.141 0-2.318.664-2.66 1.905A9.76 9.76 0 001.5 12c0 .898.121 1.768.35 2.595.341 1.24 1.518 1.905 2.659 1.905h1.93l4.5 4.5c.945.945 2.561.276 2.561-1.06V4.06zM18.584 5.106a.75.75 0 011.06 0c3.808 3.807 3.808 9.98 0 13.788a.75.75 0 11-1.06-1.06 8.25 8.25 0 000-11.668.75.75 0 010-1.06z" /><path d="M15.932 7.757a.75.75 0 011.061 0 4.5 4.5 0 010 6.364.75.75 0 01-1.06-1.06 3 3 0 000-4.243.75.75 0 010-1.061z" /></svg>
                Putar Ulang
            </button>
        </div>
    </div>

    <div x-show="errorMessage" style="display: none;" class="fixed top-10 left-1/2 transform -translate-x-1/2 z-[60] bg-red-500/90 backdrop-blur-md text-white px-6 py-3 rounded-full shadow-2xl font-semibold flex items-center gap-3 w-fit whitespace-nowrap border border-white/20">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
        <span x-text="errorMessage" class="text-sm"></span>
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
                    const config = { fps: 10 };

                    html5QrCode.start({ facingMode: "environment" }, config, (decodedText) => {
                        this.onScanSuccess(decodedText, html5QrCode);
                    }).catch(err => {
                        this.errorMessage = "Akses kamera ditolak. Izinkan browser menggunakan kamera.";
                    });

                    this.html5QrcodeScanner = html5QrCode;
                },

                async onScanSuccess(decodedText, scannerInstance) {
                    if (this.isProcessing || this.arActive) return;
                    
                    if (!decodedText.includes('/api/scan/')) return; 

                    this.isProcessing = true;
                    scannerInstance.pause();

                    try {
                        const response = await fetch(decodedText);
                        const result = await response.json();

                        if (response.ok && result.status === 'success') {
                            this.isProcessing = false;
                            this.arData = result.data;
                            this.arActive = true;
                            
                            this.playAI_Voice(this.arData.narration);

                        } else {
                            this.throwError(result.message || "QR Code tidak valid.", scannerInstance);
                        }
                    } catch (error) {
                        this.throwError("Terjadi kesalahan jaringan/server.", scannerInstance);
                    }
                },

                throwError(msg, scanner) {
                    this.isProcessing = false;
                    this.errorMessage = msg;
                    setTimeout(() => { 
                        this.errorMessage = ''; 
                        scanner.resume(); 
                    }, 4000);
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
                    }, 500);
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