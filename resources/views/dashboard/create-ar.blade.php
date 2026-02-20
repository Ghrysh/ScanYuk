<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create AR Experience - ScanYuk</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%230d9488' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect width='5' height='5' x='3' y='3' rx='1'%3E%3C/rect%3E%3Crect width='5' height='5' x='16' y='3' rx='1'%3E%3C/rect%3E%3Crect width='5' height='5' x='3' y='16' rx='1'%3E%3C/rect%3E%3Cpath d='M21 16h-3a2 2 0 0 0-2 2v3'%3E%3C/path%3E%3Cpath d='M21 21v.01'%3E%3C/path%3E%3Cpath d='M12 7v3a2 2 0 0 1-2 2H7'%3E%3C/path%3E%3Cpath d='M3 12h.01'%3E%3C/path%3E%3Cpath d='M12 3h.01'%3E%3C/path%3E%3Cpath d='M12 16v.01'%3E%3C/path%3E%3Cpath d='M16 12h1'%3E%3C/path%3E%3Cpath d='M21 12v.01'%3E%3C/path%3E%3Cpath d='M12 21v-1'%3E%3C/path%3E%3C/svg%3E">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .btn-gradient {
            background: linear-gradient(90deg, #14b8a6 0%, #8b5cf6 100%);
            transition: opacity 0.3s ease;
        }
        .btn-gradient:hover { opacity: 0.9; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">

    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-[100rem] mx-auto px-4 h-16 flex items-center gap-4">
            <a href="{{ route('user.dashboard') }}" class="p-2 -ml-2 rounded-lg text-slate-500 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <div class="flex items-center gap-2">
                <div class="text-teal-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><rect width="5" height="5" x="3" y="3" rx="1"></rect><rect width="5" height="5" x="16" y="3" rx="1"></rect><rect width="5" height="5" x="3" y="16" rx="1"></rect><path d="M21 16h-3a2 2 0 0 0-2 2v3"></path><path d="M21 21v.01"></path><path d="M12 7v3a2 2 0 0 1-2 2H7"></path><path d="M3 12h.01"></path><path d="M12 3h.01"></path><path d="M12 16v.01"></path><path d="M16 12h1"></path><path d="M21 12v.01"></path><path d="M12 21v-1"></path></svg>
                </div>
                <h1 class="text-base font-bold text-slate-900">Create AR Experience</h1>
            </div>
        </div>
    </header>

    <main class="py-10 px-4">
        <div class="max-w-3xl mx-auto" x-data="imageUploader()">
            
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 text-red-600 rounded-lg border border-red-200 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <div x-show="errorMessage" x-transition style="display: none;" 
                 class="mb-6 p-4 bg-red-50 text-red-600 rounded-lg border border-red-200 text-sm flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span x-text="errorMessage" class="font-medium"></span>
                </div>
                <button type="button" @click="errorMessage = ''" class="text-red-400 hover:text-red-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <form action="{{ route('user.ar.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Experience Title</label>
                    <input type="text" name="title" required placeholder="e.g. Product Brochure AR" 
                        class="w-full px-4 py-3 bg-slate-100 border-transparent rounded-lg text-slate-900 placeholder-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">AR Image / Infographic</label>
                    <div class="relative w-full">
                        <input type="file" name="image" id="file-upload" accept=".jpg,.jpeg,.png" required class="hidden" @change="fileChosen">
                        <label for="file-upload" 
                            class="flex flex-col items-center justify-center w-full h-48 px-4 border-2 border-slate-300 border-dashed rounded-xl cursor-pointer bg-white hover:bg-slate-50 transition-colors overflow-hidden group"
                            :class="{'border-red-300 bg-red-50': errorMessage}">
                            
                            <div x-show="!imageUrl" class="flex flex-col items-center justify-center text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-slate-400 mb-3 group-hover:text-indigo-500 transition-colors" :class="{'text-red-400': errorMessage}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                <p class="text-sm font-medium text-slate-600" :class="{'text-red-500': errorMessage}">Click or drag to upload an image</p>
                                <p class="text-xs text-slate-400 mt-1" :class="{'text-red-400': errorMessage}">JPG, PNG up to 5MB</p>
                            </div>

                            <div x-show="imageUrl" style="display: none;" class="relative w-full h-full flex items-center justify-center">
                                <img :src="imageUrl" class="max-h-full max-w-full object-contain">
                                <div class="absolute inset-0 bg-slate-900/50 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                    <span class="text-white text-sm font-medium bg-slate-900/80 px-4 py-2 rounded-lg">Ganti Gambar</span>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Narration Text</label>
                    <textarea x-model="narrationText" name="narration" required rows="5" placeholder="Enter the narration text that will be converted to voice..." 
                        class="w-full px-4 py-3 bg-slate-100 border-transparent rounded-lg text-slate-900 placeholder-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all resize-none"></textarea>
                    <p class="text-xs text-slate-500 mt-2">This text will be converted to voice narration using TTS</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4">
                    <button type="button" @click="openPreview()" class="w-full flex items-center justify-center gap-2 py-3.5 px-6 rounded-lg border-2 border-teal-500 bg-white text-teal-600 font-bold hover:bg-teal-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                        Preview AR
                    </button>
                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-3.5 px-6 rounded-lg btn-gradient text-white font-bold shadow-lg shadow-indigo-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                        Generate QR Code
                    </button>
                </div>

            </form>

            <div x-show="showPreviewModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center">
                <div x-show="showPreviewModal" x-transition.opacity.duration.300ms class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closePreview()"></div>

                <div x-show="showPreviewModal" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                     class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden flex flex-col items-center">
                    
                    <div class="w-full p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <h3 class="font-bold text-slate-900 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                            Preview AR
                        </h3>
                        <button @click="closePreview()" type="button" class="text-slate-400 hover:text-slate-700 transition-colors p-1 bg-white rounded-md border border-slate-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <div class="p-6 w-full flex flex-col items-center">
                        <div class="w-full bg-slate-100 rounded-xl overflow-hidden flex items-center justify-center border border-slate-200 shadow-inner" style="height: 350px;">
                            <img :src="imageUrl" class="max-w-full max-h-full object-contain">
                        </div>

                        <div class="mt-6 w-full flex items-center justify-center gap-3 p-4 bg-indigo-50 text-indigo-700 rounded-xl border border-indigo-100 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" /></svg>
                            <span class="text-sm font-bold">Memutar Narasi AI...</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('imageUploader', () => ({
                imageUrl: null,
                errorMessage: '',
                showPreviewModal: false,
                narrationText: '',
                
                fileChosen(event) {
                    this.errorMessage = '';
                    let input = event.target;
                    
                    if (!input.files.length) return;
                    let file = input.files[0];

                    let allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                    if (!allowedTypes.includes(file.type)) {
                        this.errorMessage = 'Format file tidak didukung. Harap unggah file JPG atau PNG.';
                        input.value = '';
                        this.imageUrl = null;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                        return;
                    }

                    let maxSize = 5 * 1024 * 1024;
                    if (file.size > maxSize) {
                        this.errorMessage = 'Ukuran gambar terlalu besar. Maksimal 5MB.';
                        input.value = '';
                        this.imageUrl = null;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                        return;
                    }

                    this.fileToDataUrl(file, src => this.imageUrl = src);
                },
                
                fileToDataUrl(file, callback) {
                    let reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = e => callback(e.target.result);
                },

                openPreview() {
                    if (!this.imageUrl) {
                        this.errorMessage = 'Silakan unggah gambar terlebih dahulu untuk melihat Preview AR.';
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                        return;
                    }
                    
                    if (!this.narrationText.trim()) {
                        this.errorMessage = 'Silakan isi Narration Text terlebih dahulu.';
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                        return;
                    }

                    this.showPreviewModal = true;
                    this.playVoice();
                },

                closePreview() {
                    this.showPreviewModal = false;
                    window.speechSynthesis.cancel();
                },

                playVoice() {
                    window.speechSynthesis.cancel();

                    let textToSpeak = this.narrationText.trim();
                    if (textToSpeak !== '') {
                        let utterance = new SpeechSynthesisUtterance(textToSpeak);
                        utterance.lang = 'id-ID';
                        utterance.rate = 0.9;
                        
                        window.speechSynthesis.speak(utterance);
                    }
                }
            }))
        })
    </script>
</body>
</html>
