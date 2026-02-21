<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create AR Experience - ScanYuk</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.4.0/model-viewer.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .btn-gradient { background: linear-gradient(90deg, #14b8a6 0%, #8b5cf6 100%); transition: opacity 0.3s ease; }
        .btn-gradient:hover { opacity: 0.9; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .pointer-events-none-children model-viewer { pointer-events: none; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen" x-data="arCreator()">

    <div x-show="isGenerating" style="display: none;" class="fixed inset-0 z-[200] flex items-center justify-center">
        <div class="absolute inset-0 bg-slate-900/90 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-3xl p-8 max-w-sm w-full mx-4 shadow-2xl flex flex-col items-center text-center border border-slate-100">
            <div class="w-16 h-16 border-4 border-slate-100 border-t-teal-500 rounded-full animate-spin mb-6"></div>
            <h3 class="text-xl font-bold text-slate-900 mb-2" x-text="progress === 100 ? 'Menyelesaikan...' : 'Mengunggah & Merender...'"></h3>
            
            <div class="w-full bg-slate-100 rounded-full h-3 mb-3 overflow-hidden">
                <div class="bg-gradient-to-r from-teal-400 to-indigo-500 h-3 rounded-full transition-all duration-300" :style="'width: ' + progress + '%'"></div>
            </div>
            
            <div class="flex justify-between w-full text-sm">
                <span class="font-bold text-indigo-600" x-text="progress + '%'"></span>
                <span class="text-slate-500 font-medium" x-text="estimatedTime"></span>
            </div>
        </div>
    </div>

    <header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-[100rem] mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('user.dashboard') }}" class="p-2 -ml-2 rounded-lg text-slate-500 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                </a>
                <h1 class="text-lg font-bold text-slate-900">Buat AR</h1>
            </div>
            
            <div class="flex p-1 bg-slate-100 rounded-lg border border-slate-200">
                <button @click="mainTab = 'custom'" :class="mainTab === 'custom' ? 'bg-white text-teal-600 shadow-sm font-bold' : 'text-slate-500 hover:text-slate-700'" class="px-6 py-1.5 rounded-md text-sm transition-all">Custom AR</button>
                <button @click="mainTab = 'template'" :class="mainTab === 'template' ? 'bg-white text-teal-600 shadow-sm font-bold' : 'text-slate-500 hover:text-slate-700'" class="px-6 py-1.5 rounded-md text-sm transition-all">Template</button>
            </div>
        </div>
    </header>

    <main class="py-8 px-4 max-w-4xl mx-auto pb-32">
        <div x-show="mainTab === 'custom'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            
            <form @submit.prevent="submitForm" action="{{ route('user.ar.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8 bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-slate-200">
                @csrf
                <input type="hidden" name="ar_type" :value="arType">
                <input type="hidden" name="selected_3d_id" :value="selectedLibrary3d">
                <input type="hidden" name="bgm_path" :value="selectedMusic">
                <input type="hidden" name="bgm_volume" :value="bgmVolume"> 

                <div x-show="uploadError" style="display: none;" class="p-4 bg-red-50 border border-red-200 rounded-xl" x-transition>
                    <div class="flex items-center gap-2 text-red-600 font-bold mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                        Oops! Pembuatan AR Gagal:
                    </div>
                    <p class="text-sm text-red-500 font-medium" x-text="uploadError"></p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Judul AR (Title)</label>
                    <input type="text" name="title" x-model="title" required placeholder="Contoh: Brosur Promosi Akhir Tahun" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-200 outline-none transition-all">
                </div>

                <div>
                    <div class="flex gap-4 border-b border-slate-200 mb-6">
                        <button type="button" @click="arType = '2d'; reset3d();" :class="arType === '2d' ? 'border-teal-500 text-teal-600' : 'border-transparent text-slate-500 hover:text-slate-700'" class="pb-3 px-2 border-b-2 font-bold text-sm transition-colors">Gambar 2D (JPG/PNG)</button>
                        <button type="button" @click="arType = '3d'; reset2d();" :class="arType === '3d' ? 'border-teal-500 text-teal-600' : 'border-transparent text-slate-500 hover:text-slate-700'" class="pb-3 px-2 border-b-2 font-bold text-sm transition-colors">Objek 3D (GLB)</button>
                    </div>

                    <div x-show="arType === '2d'">
                        <label class="block text-sm font-bold text-slate-900 mb-2">Upload Gambar 2D</label>
                        <input type="file" name="image" id="image-upload" accept=".jpg,.jpeg,.png" class="hidden" @change="handle2dUpload">
                        <label for="image-upload" class="flex flex-col items-center justify-center w-full h-56 px-4 border-2 border-slate-300 border-dashed rounded-xl cursor-pointer bg-slate-50 hover:bg-slate-100 transition-colors overflow-hidden relative">
                            <div x-show="!imageUrl2d" class="text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-slate-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                <span class="text-sm font-medium text-slate-600">Klik untuk upload gambar</span>
                            </div>
                            <img x-show="imageUrl2d" :src="imageUrl2d" class="max-h-full max-w-full object-contain">
                        </label>
                    </div>

                    <div x-show="arType === '3d'" style="display: none;" class="space-y-6">
                        <div class="p-5 border border-slate-200 rounded-xl bg-slate-50" :class="selectedLibrary3d ? 'opacity-50 grayscale' : ''">
                            <label class="block text-sm font-bold text-slate-900 mb-2">Opsi 1: Upload Model 3D Sendiri (.glb)</label>
                            <div class="mb-4">
                                <input type="text" name="asset_name" x-model="upload3dDisplayName" placeholder="Ketik nama objek (Misal: Mobil BMW 3D)..." class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm focus:border-teal-500 outline-none" :disabled="selectedLibrary3d !== ''">
                                <p class="text-xs text-slate-400 mt-1">Nama ini akan ditampilkan di Library ScanYuk.</p>
                            </div>
                            <input type="file" name="file_3d" id="glb-upload" accept=".glb" class="hidden" @change="handle3dUpload" :disabled="selectedLibrary3d !== ''">
                            <label for="glb-upload" class="flex items-center gap-4 w-full p-4 border border-slate-300 border-dashed rounded-lg cursor-pointer bg-white hover:border-teal-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-teal-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-slate-700 truncate" x-text="upload3dName ? upload3dName : 'Pilih file .glb dari perangkatmu'"></p>
                                    <p class="text-xs text-slate-400">Maksimal 50MB</p>
                                </div>
                            </label>
                        </div>

                        <div class="text-center text-xs font-bold text-slate-400 uppercase tracking-widest">ATAU</div>

                        <div class="p-5 border border-slate-200 rounded-xl bg-slate-50" :class="upload3dName ? 'opacity-50 grayscale' : ''">
                            <label class="block text-sm font-bold text-slate-900 mb-2">Opsi 2: Pilih dari Library ScanYuk</label>
                            <div class="relative mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-2.5 h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                <input type="text" x-model="search3d" placeholder="Cari objek 3D (misal: mobil, cincin)..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg text-sm focus:border-teal-500 outline-none" :disabled="upload3dName !== ''">
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 max-h-72 overflow-y-auto p-1">
                                <template x-for="item in filtered3d()" :key="item.id">
                                    <label class="flex flex-col bg-white border border-slate-200 rounded-xl cursor-pointer hover:border-teal-500 hover:shadow-md transition-all overflow-hidden" :class="selectedLibrary3d === item.id ? 'border-teal-500 ring-2 ring-teal-500 shadow-md' : ''">
                                        <div class="h-24 bg-slate-100 relative pointer-events-none-children flex items-center justify-center">
                                            <model-viewer :src="item.path" class="w-full h-full" disable-zoom disable-pan auto-rotate exposure="1"></model-viewer>
                                        </div>
                                        <div class="p-3 border-t border-slate-100 flex items-start gap-2">
                                            <input type="radio" x-model="selectedLibrary3d" :value="item.id" class="mt-0.5 text-teal-500 focus:ring-teal-500" :disabled="upload3dName !== ''">
                                            <span class="text-xs font-bold text-slate-700 leading-tight" x-text="item.name"></span>
                                        </div>
                                    </label>
                                </template>
                                <div x-show="filtered3d().length === 0" class="col-span-full text-center py-4 text-sm text-slate-500">Tidak ada objek 3D yang sesuai pencarian.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-200">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-bold text-slate-900">Pilih Background Music (Opsional)</label>
                        <div class="flex items-center gap-3 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200" x-show="selectedMusic !== ''" x-transition>
                            <span class="text-xs font-semibold text-slate-600">Volume Musik:</span>
                            <div class="flex items-center gap-2" title="Atur volume musik latar untuk hasil AR nanti">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M17.536 6.464a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" /></svg>
                                <input type="range" x-model="bgmVolume" @input="updateVolume()" min="0.05" max="1" step="0.05" class="w-24 h-1.5 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                                <span class="text-xs font-bold text-indigo-600 w-8 text-right" x-text="Math.round(bgmVolume * 100) + '%'"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="relative mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-2.5 h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        <input type="text" x-model="searchMusic" placeholder="Cari musik (misal: ramadhan, cinematic)..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg text-sm focus:border-indigo-500 outline-none">
                    </div>

                    <div class="max-h-48 overflow-y-auto no-scrollbar grid grid-cols-1 md:grid-cols-2 gap-2">
                        <label class="flex items-center gap-3 p-3 bg-white border border-slate-200 rounded-lg cursor-pointer hover:border-indigo-500" :class="selectedMusic === '' ? 'border-indigo-500 ring-1 ring-indigo-500 bg-indigo-50' : ''">
                            <input type="radio" x-model="selectedMusic" value="" class="text-indigo-600 focus:ring-indigo-500" @change="stopAllAudio()">
                            <span class="text-sm font-medium text-slate-700">Tanpa Musik (Hanya Narasi)</span>
                        </label>
                        
                        <template x-for="music in filteredMusic()" :key="music.path">
                            <div class="flex items-center justify-between p-3 bg-white border border-slate-200 rounded-lg hover:border-indigo-500 transition-colors" :class="selectedMusic === music.path ? 'border-indigo-500 ring-1 ring-indigo-500 bg-indigo-50' : ''">
                                <label class="flex items-center gap-3 cursor-pointer flex-1 min-w-0">
                                    <input type="radio" x-model="selectedMusic" :value="music.path" class="text-indigo-600 focus:ring-indigo-500 flex-shrink-0">
                                    <span class="text-sm font-medium text-slate-700 truncate" x-text="music.name"></span>
                                </label>
                                <button type="button" @click="toggleAudio(music.path)" class="p-1.5 ml-2 text-slate-400 hover:text-indigo-600 bg-slate-100 hover:bg-indigo-100 rounded-full transition-colors flex-shrink-0">
                                    <svg x-show="playingMusicPath !== music.path" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" /></svg>
                                    <svg x-show="playingMusicPath === music.path" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-2">Teks Narasi Suara AI</label>
                    <textarea x-model="narrationText" name="narration" required rows="4" placeholder="Ketik teks yang akan dibacakan oleh suara AI saat AR muncul..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-200 outline-none transition-all resize-none"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-slate-200">
                    <button type="button" @click="openModal()" class="w-full flex items-center justify-center gap-2 py-3.5 px-6 rounded-xl border-2 border-teal-500 text-teal-600 font-bold hover:bg-teal-50 transition-colors">
                        Preview AR
                    </button>
                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-3.5 px-6 rounded-xl btn-gradient text-white font-bold shadow-lg shadow-indigo-200">
                        Generate QR Code
                    </button>
                </div>
            </form>
        </div>

        <div x-show="mainTab === 'template'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            
            <div class="relative mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-4 top-3.5 h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                <input type="text" x-model="searchTemplate" placeholder="Cari template siap pakai (misal: ramadhan, undangan)..." class="w-full pl-12 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 focus:border-indigo-500 shadow-sm outline-none text-lg">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="tpl in filteredTemplates()" :key="tpl.id">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-lg hover:border-indigo-300 transition-all group cursor-pointer" @click="previewTemplate(tpl)">
                        <div class="h-40 bg-slate-100 flex items-center justify-center relative overflow-hidden pointer-events-none-children">
                            <template x-if="tpl.ar_type === '2d'">
                                <img :src="tpl.file_path" class="w-full h-full object-cover">
                            </template>
                            <template x-if="tpl.ar_type === '3d'">
                                <model-viewer :src="tpl.file_path" class="w-full h-full" disable-zoom disable-pan auto-rotate exposure="1.2"></model-viewer>
                            </template>
                            <div class="absolute inset-0 bg-slate-900/10 group-hover:bg-transparent transition-colors"></div>
                            
                            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-md text-xs font-bold shadow-sm" :class="tpl.ar_type === '3d' ? 'bg-indigo-600 text-white' : 'bg-teal-500 text-white'" x-text="'AR ' + tpl.ar_type.toUpperCase()"></span>
                        </div>
                        <div class="p-5">
                            <h3 class="font-bold text-slate-900 text-lg truncate mb-1" x-text="tpl.title"></h3>
                            <p class="text-sm text-slate-500 line-clamp-2" x-text="tpl.narration"></p>
                        </div>
                    </div>
                </template>
                <div x-show="filteredTemplates().length === 0" class="col-span-full text-center py-10">
                    <p class="text-slate-500">Template tidak ditemukan.</p>
                </div>
            </div>
        </div>

        <div x-show="showModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center">
            <div x-show="showModal" x-transition.opacity class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm" @click="closeModal()"></div>
            <div x-show="showModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md mx-4 overflow-hidden flex flex-col items-center border border-white/20">
                <div class="w-full p-4 border-b border-slate-100 flex items-center justify-between bg-white">
                    <h3 class="font-bold text-slate-900" x-text="previewData.title"></h3>
                    <button @click="closeModal()" type="button" class="text-slate-400 hover:text-red-500 transition-colors p-1 bg-slate-50 rounded-full hover:bg-red-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="p-6 w-full flex flex-col items-center">
                    <div class="w-full bg-slate-100 rounded-2xl overflow-hidden flex items-center justify-center relative shadow-inner border border-slate-200" style="height: 300px;">
                        <template x-if="previewData.type === '2d'">
                            <img :src="previewData.src" class="max-w-full max-h-full object-contain">
                        </template>
                        <template x-if="previewData.type === '3d'">
                            <model-viewer :src="previewData.src" auto-rotate camera-controls shadow-intensity="1" exposure="1.2" class="w-full h-full"></model-viewer>
                        </template>

                        <div class="absolute bottom-4 left-4 flex gap-2">
                            <span x-show="previewData.music" class="px-3 py-1 bg-black/60 backdrop-blur-md rounded-full text-white text-xs font-medium flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" /></svg> BGM On</span>
                            <span class="px-3 py-1 bg-teal-500/90 backdrop-blur-md rounded-full text-white text-xs font-medium flex items-center gap-1 animate-pulse"><svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" /></svg> AI Speaking</span>
                        </div>

                        <template x-if="isFromTemplate">
                            <button @click="useTemplate()" class="mt-6 w-full py-3.5 px-6 rounded-xl btn-gradient text-white font-bold shadow-lg flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                Gunakan Template Ini
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function arCreator() {
            return {
                mainTab: 'custom', 
                arType: '2d',
                title: '',
                narrationText: '',
                imageUrl2d: null,
                upload3dName: '',
                upload3dDisplayName: '',
                selectedLibrary3d: '',
                selectedMusic: '',
                bgmVolume: 0.3,
                local3dUrl: null, 

                isGenerating: false,
                progress: 0,
                estimatedTime: 'Menghitung...',
                uploadError: null,

                search3d: '', searchMusic: '', searchTemplate: '',
                showModal: false, isFromTemplate: false,
                previewData: { title: '', type: '', src: '', music: '' },
                currentAudioPlayer: null, playingMusicPath: null,

                library3dList: @json($library3dList),
                musicList: @json($musicList),
                templates: @json($templates),

                reset2d() { this.imageUrl2d = null; document.getElementById('image-upload').value = ''; },
                
                reset3d() { 
                    this.upload3dName = ''; 
                    this.upload3dDisplayName = ''; 
                    document.getElementById('glb-upload').value = ''; 
                    this.selectedLibrary3d = ''; 
                    if (this.local3dUrl) { URL.revokeObjectURL(this.local3dUrl); this.local3dUrl = null; } 
                },
                
                handle2dUpload(e) {
                    let file = e.target.files[0];
                    if(!file) return;
                    let reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = e => this.imageUrl2d = e.target.result;
                },
                
                handle3dUpload(e) {
                    let file = e.target.files[0];
                    if(file) {
                        this.upload3dName = file.name;
                        this.selectedLibrary3d = '';
                        if (this.local3dUrl) URL.revokeObjectURL(this.local3dUrl); 
                        this.local3dUrl = URL.createObjectURL(file); 
                    }
                },

                submitForm(e) {
                    this.isGenerating = true;
                    this.progress = 0;
                    this.uploadError = null;
                    
                    let formData = new FormData(e.target);
                    let xhr = new XMLHttpRequest();
                    xhr.open('POST', e.target.action);
                    xhr.setRequestHeader('Accept', 'application/json');

                    let startTime = Date.now();

                    xhr.upload.addEventListener('progress', (evt) => {
                        if (evt.lengthComputable) {
                            let percentComplete = Math.round((evt.loaded / evt.total) * 90);
                            this.progress = percentComplete;

                            let timeElapsed = (Date.now() - startTime) / 1000; 
                            let uploadSpeed = evt.loaded / timeElapsed; 
                            let bytesRemaining = evt.total - evt.loaded;
                            let secondsRemaining = Math.round(bytesRemaining / uploadSpeed);

                            if (secondsRemaining > 60) {
                                this.estimatedTime = Math.floor(secondsRemaining / 60) + ' menit ' + (secondsRemaining % 60) + ' detik';
                            } else if (secondsRemaining > 0) {
                                this.estimatedTime = secondsRemaining + ' detik tersisa';
                            } else {
                                this.estimatedTime = 'Memproses data 3D & Merender QR Code...';
                            }
                        }
                    });

                    xhr.onload = () => {
                        if (xhr.status >= 200 && xhr.status < 300) {
                            this.progress = 100;
                            this.estimatedTime = 'Selesai! Mengalihkan ke Dashboard...';
                            let res = JSON.parse(xhr.responseText);
                            setTimeout(() => {
                                window.location.href = res.redirect_url;
                            }, 500);
                        } else {
                            this.isGenerating = false;
                            try {
                                let res = JSON.parse(xhr.responseText);
                                this.uploadError = res.error || "Gagal membuat AR";
                            } catch (err) {
                                this.uploadError = "Terjadi kesalahan server (Error " + xhr.status + "). Cek konfigurasi config/filesystems.php";
                            }
                        }
                    };

                    xhr.onerror = () => {
                        this.isGenerating = false;
                        this.uploadError = "Koneksi jaringan terputus. Pastikan file tidak terlalu besar.";
                    };

                    xhr.send(formData);
                },

                filtered3d() { return this.library3dList.filter(i => i.name.toLowerCase().includes(this.search3d.toLowerCase())); },
                filteredMusic() { return this.musicList.filter(i => i.name.toLowerCase().includes(this.searchMusic.toLowerCase())); },

                filteredTemplates() { return this.templates.filter(i => i.title.toLowerCase().includes(this.searchTemplate.toLowerCase()) || i.narration.toLowerCase().includes(this.searchTemplate.toLowerCase())); },

                previewTemplate(tpl) {
                    this.isFromTemplate = true;
                    this.previewData = {
                        title: tpl.title, type: tpl.ar_type, src: tpl.file_path, music: tpl.bgm_path, fullData: tpl
                    };
                    this.showModal = true;
                    if(tpl.bgm_path) this.previewAudio(tpl.bgm_path);
                    this.playVoice(tpl.narration);
                },

                useTemplate() {
                    let tpl = this.previewData.fullData;
                    
                    this.title = tpl.title;
                    this.arType = tpl.ar_type;
                    this.narrationText = tpl.narration;
                    this.selectedMusic = tpl.bgm_path;
                    
                    if(tpl.ar_type === '2d') {
                        this.imageUrl2d = tpl.file_path;
                    } else {
                        let matched3d = this.library3dList.find(item => item.path === tpl.file_path);
                        if(matched3d) this.selectedLibrary3d = matched3d.id;
                    }

                    this.closeModal();
                    this.mainTab = 'custom'; 
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },
                
                toggleAudio(path) { if (this.playingMusicPath === path) { this.stopAllAudio(); } else { this.previewAudio(path); } },
                previewAudio(path) {
                    this.stopAllAudio();
                    this.currentAudioPlayer = new Audio('/bg_sounds/' + path);
                    this.currentAudioPlayer.volume = this.bgmVolume;
                    this.currentAudioPlayer.play().catch(e => {});
                    this.playingMusicPath = path;
                    this.currentAudioPlayer.onended = () => { this.playingMusicPath = null; };
                },
                updateVolume() { if(this.currentAudioPlayer) this.currentAudioPlayer.volume = this.bgmVolume; },
                
                playVoice(text) {
                    window.speechSynthesis.cancel();
                    if(text) {
                        let utterance = new SpeechSynthesisUtterance(text);
                        utterance.lang = 'id-ID';
                        window.speechSynthesis.speak(utterance);
                    }
                },
                
                stopAllAudio() {
                    window.speechSynthesis.cancel();
                    if(this.currentAudioPlayer) { this.currentAudioPlayer.pause(); this.currentAudioPlayer.currentTime = 0; }
                    this.playingMusicPath = null;
                },

                openModal() {
                    if(this.arType === '2d' && !this.imageUrl2d) return alert('Upload gambar 2D dulu!');
                    if(this.arType === '3d' && !this.upload3dName && !this.selectedLibrary3d) return alert('Pilih atau upload objek 3D dulu!');
                    
                    let src3d = '';
                    if (this.arType === '3d') {
                        if (this.upload3dName && this.local3dUrl) {
                            src3d = this.local3dUrl; 
                        } else if (this.selectedLibrary3d) {
                            let found = this.library3dList.find(i => i.id == this.selectedLibrary3d);
                            src3d = found ? found.path : '';
                        }
                    }

                    this.previewData = {
                        title: this.title || 'Preview Custom AR',
                        type: this.arType,
                        src: this.arType === '2d' ? this.imageUrl2d : src3d,
                        music: this.selectedMusic
                    };
                    
                    this.showModal = true;
                    if(this.selectedMusic) this.previewAudio(this.selectedMusic);
                    this.playVoice(this.narrationText);
                },

                closeModal() {
                    this.showModal = false;
                    this.stopAllAudio();
                }
            }
        }
    </script>
</body>
</html>