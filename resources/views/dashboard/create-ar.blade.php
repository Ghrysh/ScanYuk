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
<body class="bg-slate-50 min-h-screen" x-data="arCreator()" x-init="loadVoices()">

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
            <div class="flex items-center gap-2 sm:gap-4">
                <a href="{{ route('user.dashboard') }}" class="p-2 -ml-2 rounded-lg text-slate-500 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                </a>
                <div class="w-8 h-8 rounded bg-teal-50 text-teal-600 flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-qr-code w-5 h-5"><rect width="5" height="5" x="3" y="3" rx="1"></rect><rect width="5" height="5" x="16" y="3" rx="1"></rect><rect width="5" height="5" x="3" y="16" rx="1"></rect><path d="M21 16h-3a2 2 0 0 0-2 2v3"></path><path d="M21 21v.01"></path><path d="M12 7v3a2 2 0 0 1-2 2H7"></path><path d="M3 12h.01"></path><path d="M12 3h.01"></path><path d="M12 16v.01"></path><path d="M16 12h1"></path><path d="M21 12v.01"></path><path d="M12 21v-1"></path></svg>
                </div>
                <h1 class="hidden sm:block text-lg font-bold text-slate-900">Buat AR</h1>
            </div>
            <div class="flex p-1 bg-slate-100 rounded-lg border border-slate-200">
                <button @click="mainTab = 'custom'" :class="mainTab === 'custom' ? 'bg-white text-teal-600 shadow-sm font-bold' : 'text-slate-500 hover:text-slate-700'" class="px-3 sm:px-6 py-1.5 rounded-md text-xs sm:text-sm transition-all">Custom AR</button>
                <button @click="mainTab = 'template'" :class="mainTab === 'template' ? 'bg-white text-teal-600 shadow-sm font-bold' : 'text-slate-500 hover:text-slate-700'" class="px-3 sm:px-6 py-1.5 rounded-md text-xs sm:text-sm transition-all">Template</button>
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
                                            <model-viewer :src="item.path" class="w-full h-full" disable-zoom disable-pan auto-rotate exposure="1" loading="lazy" shadow-intensity="0" environment-image="neutral"></model-viewer>
                                        </div>
                                        <div class="p-3 border-t border-slate-100 flex items-start gap-2">
                                            <input type="radio" x-model="selectedLibrary3d" :value="item.id" class="mt-0.5 text-teal-500 focus:ring-teal-500" :disabled="upload3dName !== ''">
                                            <span class="text-xs font-bold text-slate-700 leading-tight" x-text="item.name"></span>
                                        </div>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-200">
                    <label class="block text-sm font-bold text-slate-900 mb-4">Background Music / BGM (Opsional)</label>
                    
                    <div class="mb-4 p-4 border border-slate-200 rounded-xl bg-white shadow-sm" :class="selectedMusic !== '' ? 'opacity-50 grayscale' : ''">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Upload Musik Sendiri (MP3/WAV)</label>
                        <input type="file" name="custom_bgm" id="bgm-upload" accept="audio/*" class="hidden" @change="handleBgmUpload" :disabled="selectedMusic !== ''">
                        <label for="bgm-upload" class="flex items-center gap-4 w-full p-3 border border-slate-300 border-dashed rounded-lg cursor-pointer hover:border-indigo-500 bg-slate-50 hover:bg-white transition-colors">
                            <svg class="w-6 h-6 text-indigo-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" /></svg>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-slate-700 truncate" x-text="customBgmFile ? customBgmFile.name : 'Pilih file audio dari perangkatmu'"></p>
                            </div>
                        </label>
                        <button type="button" x-show="customBgmFile" @click="clearCustomBgm()" class="text-xs text-red-500 font-bold mt-2 hover:underline">Batal / Hapus Upload</button>
                    </div>

                    <div class="text-center text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">ATAU</div>
                    
                    <div class="relative mb-4" :class="isCustomBgm ? 'opacity-50 grayscale pointer-events-none' : ''">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-2.5 h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        <input type="text" x-model="searchMusic" placeholder="Cari dari Library (misal: ramadhan)..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg text-sm focus:border-indigo-500 outline-none">
                    </div>

                    <div class="max-h-48 overflow-y-auto no-scrollbar grid grid-cols-1 md:grid-cols-2 gap-2" :class="isCustomBgm ? 'opacity-50 grayscale pointer-events-none' : ''">
                        <label class="flex items-center gap-3 p-3 bg-white border border-slate-200 rounded-lg cursor-pointer hover:border-indigo-500" :class="selectedMusic === '' && !isCustomBgm ? 'border-indigo-500 ring-1 ring-indigo-500 bg-indigo-50' : ''">
                            <input type="radio" x-model="selectedMusic" value="" @change="clearMusic()" class="text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm font-medium text-slate-700">Tanpa Musik Latar</span>
                        </label>
                        
                        <template x-for="music in filteredMusic()" :key="music.path">
                            <div class="flex items-center justify-between p-3 bg-white border border-slate-200 rounded-lg hover:border-indigo-500 transition-colors" :class="selectedMusic === music.path ? 'border-indigo-500 ring-1 ring-indigo-500 bg-indigo-50' : ''">
                                <label class="flex items-center gap-3 cursor-pointer flex-1 min-w-0">
                                    <input type="radio" :value="music.path" x-model="selectedMusic" @change="selectLibraryMusic(music.path)" class="text-indigo-600 focus:ring-indigo-500 flex-shrink-0">
                                    <span class="text-sm font-medium text-slate-700 truncate" x-text="music.name"></span>
                                </label>
                                <button type="button" @click="toggleAudio(music.path)" class="p-1.5 ml-2 text-slate-400 hover:text-indigo-600 bg-slate-100 hover:bg-indigo-100 rounded-full transition-colors flex-shrink-0">
                                    <svg x-show="playingMusicPath !== ('/bg_sounds/' + music.path)" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" /></svg>
                                    <svg x-show="playingMusicPath === ('/bg_sounds/' + music.path)" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    <div x-show="bgmDuration > 0 && (selectedMusic !== '' || isCustomBgm)" class="mt-4 p-5 border border-indigo-200 bg-indigo-50/50 rounded-xl space-y-4" x-transition>
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <h4 class="text-sm font-bold text-indigo-900">Potong Durasi Musik (Crop)</h4>
                            </div>
                            <div class="flex items-center gap-2" title="Atur volume latar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M17.536 6.464a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" /></svg>
                                <input type="range" x-model="bgmVolume" @input="updateVolume()" min="0.05" max="1" step="0.05" class="w-20 h-1.5 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                                <span class="text-xs font-bold text-indigo-600 w-8 text-right" x-text="Math.round(bgmVolume * 100) + '%'"></span>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-white p-3 rounded-lg border border-indigo-100 shadow-sm">
                                <label class="flex justify-between text-xs font-bold text-slate-500 mb-3">
                                    <span class="text-indigo-600">Mulai (Start)</span>
                                    <span x-text="formatTime(bgmStart)" class="bg-indigo-100 px-2 py-0.5 rounded text-indigo-700"></span>
                                </label>
                                <input type="range" x-model="bgmStart" :max="bgmDuration" step="1" @input="if(Number(bgmStart) >= Number(bgmEnd)) bgmStart = bgmEnd - 1" class="w-full h-1.5 bg-indigo-200 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-indigo-100 shadow-sm">
                                <label class="flex justify-between text-xs font-bold text-slate-500 mb-3">
                                    <span class="text-indigo-600">Selesai (End)</span>
                                    <span x-text="formatTime(bgmEnd)" class="bg-indigo-100 px-2 py-0.5 rounded text-indigo-700"></span>
                                </label>
                                <input type="range" x-model="bgmEnd" :max="bgmDuration" step="1" @input="if(Number(bgmEnd) <= Number(bgmStart)) bgmEnd = Number(bgmStart) + 1" class="w-full h-1.5 bg-indigo-200 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                            </div>
                        </div>
                        <p class="text-xs text-indigo-500/80 font-medium italic mt-2">*Hanya rentang durasi ini yang akan diputar berulang di AR Anda.</p>
                        
                        <input type="hidden" name="bgm_start" :value="bgmStart">
                        <input type="hidden" name="bgm_end" :value="bgmEnd">
                        <input type="hidden" name="bgm_volume" :value="bgmVolume"> 
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-200">
                    <label class="block text-sm font-bold text-slate-900 mb-4">Mode Narasi Suara (Opsional)</label>
                    <input type="hidden" name="narration_mode" :value="narrationMode">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="p-5 border-2 rounded-xl transition-all relative flex flex-col" :class="narrationMode === 'text' ? 'border-teal-500 bg-white shadow-sm' : 'border-slate-200 bg-slate-50 opacity-60 grayscale'">
                            <div x-show="narrationMode !== 'text'" @click="narrationMode = 'text'" class="absolute inset-0 z-10 cursor-pointer flex items-center justify-center bg-slate-100/50 backdrop-blur-[1px] rounded-xl opacity-0 hover:opacity-100 transition-opacity">
                                <span class="bg-slate-900 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-lg">Gunakan Fitur Ini</span>
                            </div>
                            
                            <div class="flex items-start gap-3 mb-4">
                                <input type="radio" x-model="narrationMode" value="text" class="w-4 h-4 mt-0.5 text-teal-600 focus:ring-teal-500">
                                <div class="flex flex-col items-start gap-2">
                                    <label class="font-bold text-slate-900 leading-none mt-0.5">Suara AI (Teks ke Suara)</label>
                                    <span class="text-[10px] font-extrabold bg-amber-100 text-amber-600 px-2 py-1 rounded-md uppercase tracking-wider border border-amber-200">
                                        Pilih Suara: Coming Soon
                                    </span>
                                </div>
                            </div>

                            <textarea x-model="narrationText" name="narration" rows="3" placeholder="Ketik teks yang akan dibacakan oleh suara AI bawaan HP/Laptop pengguna..." class="w-full px-4 py-3 border border-slate-200 rounded-xl text-slate-900 focus:border-teal-500 outline-none resize-none flex-1" :disabled="narrationMode !== 'text'"></textarea>
                        </div>

                        <div class="p-5 border-2 rounded-xl transition-all relative" :class="narrationMode === 'audio' ? 'border-teal-500 bg-white shadow-sm' : 'border-slate-200 bg-slate-50 opacity-60 grayscale'">
                            <div x-show="narrationMode !== 'audio'" @click="narrationMode = 'audio'" class="absolute inset-0 z-10 cursor-pointer flex items-center justify-center bg-slate-100/50 backdrop-blur-[1px] rounded-xl opacity-0 hover:opacity-100 transition-opacity">
                                <span class="bg-slate-900 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-lg">Gunakan Fitur Ini</span>
                            </div>

                            <div class="flex items-center gap-2 mb-4 text-teal-600">
                                <input type="radio" x-model="narrationMode" value="audio" class="w-4 h-4 text-teal-600 focus:ring-teal-500">
                                <label class="font-bold text-slate-900">Rekam Suara Mandiri</label>
                            </div>

                            <div class="flex flex-col items-center justify-center h-full gap-3 pb-4">
                                <p class="text-xs text-slate-500 text-center mb-2" x-show="!recordedAudioUrl">Gunakan mic HP/Laptop Anda. Maksimal 1 Menit.</p>
                                
                                <button type="button" x-show="!isRecording && !recordedAudioUrl" @click="startRecording()" class="px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-full font-bold flex items-center gap-2 transition-all shadow-md" :disabled="narrationMode !== 'audio'">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7 4a3 3 0 016 0v4a3 3 0 11-6 0V4zm4 10.93A7.001 7.001 0 0017 8a1 1 0 10-2 0A5 5 0 015 8a1 1 0 00-2 0 7.001 7.001 0 006 6.93V17H6a1 1 0 100 2h8a1 1 0 100-2h-3v-2.07z" clip-rule="evenodd" /></svg> Mulai Merekam
                                </button>
                                
                                <button type="button" x-show="isRecording" @click="stopRecording()" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-full font-bold flex items-center gap-2 transition-all animate-pulse shadow-md">
                                    <div class="w-3 h-3 bg-red-500 rounded-full"></div> Berhenti
                                </button>

                                <div x-show="recordedAudioUrl" class="w-full flex flex-col items-center gap-2">
                                    <audio :src="recordedAudioUrl" controls class="w-full h-10"></audio>
                                    <button type="button" @click="deleteRecording()" class="text-xs text-red-500 font-bold underline hover:text-red-700">Hapus & Rekam Ulang</button>
                                </div>
                            </div>
                        </div>

                    </div>
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
                            <template x-if="tpl.ar_type === '2d'"><img :src="tpl.file_path" class="w-full h-full object-cover"></template>
                            <template x-if="tpl.ar_type === '3d'"><model-viewer :src="tpl.file_path" class="w-full h-full" disable-zoom disable-pan auto-rotate exposure="1.2"></model-viewer></template>
                            <div class="absolute inset-0 bg-slate-900/10 group-hover:bg-transparent transition-colors"></div>
                            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-md text-xs font-bold shadow-sm" :class="tpl.ar_type === '3d' ? 'bg-indigo-600 text-white' : 'bg-teal-500 text-white'" x-text="'AR ' + tpl.ar_type.toUpperCase()"></span>
                        </div>
                        <div class="p-5">
                            <h3 class="font-bold text-slate-900 text-lg truncate mb-1" x-text="tpl.title"></h3>
                            <p class="text-sm text-slate-500 line-clamp-2" x-text="tpl.narration"></p>
                        </div>
                    </div>
                </template>
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
                        <template x-if="previewData.type === '2d'"><img :src="previewData.src" class="max-w-full max-h-full object-contain"></template>
                        <template x-if="previewData.type === '3d'"><model-viewer :src="previewData.src" auto-rotate camera-controls shadow-intensity="1" exposure="1.2" class="w-full h-full"></model-viewer></template>

                        <div class="absolute bottom-4 left-4 flex gap-2">
                            <span x-show="previewData.music" class="px-3 py-1 bg-black/60 backdrop-blur-md rounded-full text-white text-xs font-medium flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" /></svg> BGM On</span>
                            <span x-show="previewData.hasNarration" class="px-3 py-1 bg-teal-500/90 backdrop-blur-md rounded-full text-white text-xs font-medium flex items-center gap-1 animate-pulse"><svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" /></svg> Suara Narasi</span>
                        </div>
                    </div>
                    <template x-if="isFromTemplate">
                        <button @click="useTemplate()" class="mt-6 w-full py-3.5 px-6 rounded-xl btn-gradient text-white font-bold shadow-lg flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Gunakan Template Ini
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </main>

    <script>
        function arCreator() {
            return {
                mainTab: 'custom', arType: '2d', title: '',
                imageUrl2d: null, upload3dName: '', upload3dDisplayName: '',
                selectedLibrary3d: '', local3dUrl: null, 
                
                // Variabel BGM
                selectedMusic: '', bgmVolume: 0.3,
                bgmStart: 0, bgmEnd: 100, bgmDuration: 0,
                customBgmFile: null, customBgmUrl: null, isCustomBgm: false,
                
                narrationMode: 'text', narrationText: '', availableVoices: [], selectedVoice: '',
                
                isRecording: false, mediaRecorder: null, audioChunks: [],
                recordedAudioBlob: null, recordedAudioUrl: null,

                isGenerating: false, progress: 0, estimatedTime: 'Menghitung...', uploadError: null,
                search3d: '', searchMusic: '', searchTemplate: '',
                showModal: false, isFromTemplate: false,
                previewData: { title: '', type: '', src: '', music: '', hasNarration: false },
                
                currentAudioPlayer: null, narrationPlayer: null, playingMusicPath: null,

                library3dList: @json($library3dList),
                musicList: @json($musicList),
                templates: @json($templates),

                formatTime(seconds) {
                    let m = Math.floor(seconds / 60);
                    let s = Math.floor(seconds % 60);
                    return m + ':' + (s < 10 ? '0' : '') + s;
                },

                loadVoices() {
                    let setVoices = () => {
                        let voices = window.speechSynthesis.getVoices();
                        let indoVoices = voices.filter(v => v.lang.toLowerCase().includes('id'));
                        let engVoices = voices.filter(v => v.lang.toLowerCase().includes('en'));
                        this.availableVoices = [...indoVoices, ...engVoices];
                        if(this.availableVoices.length > 0 && !this.selectedVoice) this.selectedVoice = this.availableVoices[0].voiceURI;
                    };
                    setVoices();
                    if (window.speechSynthesis.onvoiceschanged !== undefined) window.speechSynthesis.onvoiceschanged = setVoices;
                },

                async startRecording() {
                    try {
                        let stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                        this.mediaRecorder = new MediaRecorder(stream);
                        this.audioChunks = [];
                        
                        this.mediaRecorder.ondataavailable = e => { if(e.data.size > 0) this.audioChunks.push(e.data); };
                        this.mediaRecorder.onstop = () => {
                            let mime = this.mediaRecorder.mimeType || 'audio/webm';
                            let audioBlob = new Blob(this.audioChunks, { type: mime });
                            this.recordedAudioBlob = audioBlob;
                            if (this.recordedAudioUrl) URL.revokeObjectURL(this.recordedAudioUrl);
                            this.recordedAudioUrl = URL.createObjectURL(audioBlob);
                            this.mediaRecorder.stream.getTracks().forEach(t => t.stop());
                        };
                        this.mediaRecorder.start();
                        this.isRecording = true;
                    } catch (err) { alert("Izin mikrofon ditolak atau tidak tersedia di perangkat ini."); }
                },

                stopRecording() {
                    if(this.mediaRecorder && this.isRecording) {
                        this.mediaRecorder.stop();
                        this.isRecording = false;
                    }
                },

                deleteRecording() {
                    this.recordedAudioBlob = null;
                    if (this.recordedAudioUrl) URL.revokeObjectURL(this.recordedAudioUrl);
                    this.recordedAudioUrl = null;
                },

                reset2d() { this.imageUrl2d = null; document.getElementById('image-upload').value = ''; },
                reset3d() { 
                    this.upload3dName = ''; this.upload3dDisplayName = ''; document.getElementById('glb-upload').value = ''; this.selectedLibrary3d = ''; 
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
                        this.upload3dName = file.name; this.selectedLibrary3d = '';
                        if (this.local3dUrl) URL.revokeObjectURL(this.local3dUrl); 
                        this.local3dUrl = URL.createObjectURL(file); 
                    }
                },

                // LOGIKA UNTUK UPLOAD DAN CROP MUSIK
                handleBgmUpload(e) {
                    let file = e.target.files[0];
                    if(!file) return;
                    this.isCustomBgm = true;
                    this.selectedMusic = '';
                    this.customBgmFile = file;
                    if(this.customBgmUrl) URL.revokeObjectURL(this.customBgmUrl);
                    this.customBgmUrl = URL.createObjectURL(file);
                    this.loadDuration(this.customBgmUrl);
                },
                clearCustomBgm() {
                    this.isCustomBgm = false;
                    this.customBgmFile = null;
                    if(this.customBgmUrl) URL.revokeObjectURL(this.customBgmUrl);
                    this.customBgmUrl = null;
                    document.getElementById('bgm-upload').value = '';
                    this.bgmStart = 0; this.bgmEnd = 0; this.bgmDuration = 0;
                    this.stopAllAudio();
                },
                selectLibraryMusic(path) {
                    this.isCustomBgm = false;
                    this.selectedMusic = path;
                    this.clearCustomBgm();
                    this.selectedMusic = path;
                    this.loadDuration('/bg_sounds/' + path);
                },
                clearMusic() {
                    this.selectedMusic = '';
                    this.clearCustomBgm();
                },
                loadDuration(src) {
                    let tempAudio = new Audio(src);
                    tempAudio.onloadedmetadata = () => {
                        this.bgmDuration = Math.floor(tempAudio.duration);
                        this.bgmStart = 0;
                        this.bgmEnd = this.bgmDuration;
                    };
                },

                submitForm(e) {
                    if (this.narrationMode === 'audio' && !this.recordedAudioBlob && !this.narrationText) {
                         if(!confirm('Anda memilih mode rekam suara tapi belum merekam. Lanjutkan tanpa suara?')) return;
                    }
                    this.isGenerating = true; this.progress = 0; this.uploadError = null;
                    let formData = new FormData(e.target);
                    
                    if (this.narrationMode === 'audio' && this.recordedAudioBlob) {
                        let mime = this.recordedAudioBlob.type.toLowerCase();
                        let ext = mime.includes('mp4') ? 'mp4' : (mime.includes('ogg') ? 'ogg' : 'webm');
                        formData.append('custom_audio', this.recordedAudioBlob, 'rekaman.' + ext);
                    }
                    
                    let xhr = new XMLHttpRequest();
                    xhr.open('POST', e.target.action);
                    xhr.setRequestHeader('Accept', 'application/json');
                    let startTime = Date.now();

                    xhr.upload.addEventListener('progress', (evt) => {
                        if (evt.lengthComputable) {
                            this.progress = Math.round((evt.loaded / evt.total) * 90);
                            let timeElapsed = (Date.now() - startTime) / 1000; 
                            let secondsRemaining = Math.round((evt.total - evt.loaded) / (evt.loaded / timeElapsed));
                            if (secondsRemaining > 60) this.estimatedTime = Math.floor(secondsRemaining / 60) + ' m ' + (secondsRemaining % 60) + ' d';
                            else if (secondsRemaining > 0) this.estimatedTime = secondsRemaining + ' d tersisa';
                            else this.estimatedTime = 'Menyimpan...';
                        }
                    });

                    xhr.onload = () => {
                        if (xhr.status >= 200 && xhr.status < 300) {
                            this.progress = 100; this.estimatedTime = 'Selesai!';
                            setTimeout(() => { window.location.href = JSON.parse(xhr.responseText).redirect_url; }, 500);
                        } else {
                            this.isGenerating = false;
                            try { this.uploadError = JSON.parse(xhr.responseText).error || "Gagal membuat AR"; } 
                            catch (err) { this.uploadError = "Terjadi kesalahan server (Error " + xhr.status + ")."; }
                        }
                    };
                    xhr.onerror = () => { this.isGenerating = false; this.uploadError = "Koneksi terputus."; };
                    xhr.send(formData);
                },

                filtered3d() { return this.library3dList.filter(i => i.name.toLowerCase().includes(this.search3d.toLowerCase())); },
                filteredMusic() { return this.musicList.filter(i => i.name.toLowerCase().includes(this.searchMusic.toLowerCase())); },
                filteredTemplates() { return this.templates.filter(i => i.title.toLowerCase().includes(this.searchTemplate.toLowerCase()) || i.narration.toLowerCase().includes(this.searchTemplate.toLowerCase())); },

                previewTemplate(tpl) {
                    this.isFromTemplate = true;
                    this.previewData = { title: tpl.title, type: tpl.ar_type, src: tpl.file_path, music: tpl.bgm_path, hasNarration: !!tpl.narration, fullData: tpl };
                    this.showModal = true;
                    if(tpl.bgm_path) this.previewBgm('/bg_sounds/' + tpl.bgm_path);
                    this.playVoice(tpl.narration, null, null);
                },

                useTemplate() {
                    let tpl = this.previewData.fullData;
                    this.title = tpl.title; this.arType = tpl.ar_type; this.narrationText = tpl.narration; this.selectedMusic = tpl.bgm_path;
                    this.narrationMode = 'text';
                    if(tpl.ar_type === '2d') this.imageUrl2d = tpl.file_path;
                    else { let matched3d = this.library3dList.find(item => item.path === tpl.file_path); if(matched3d) this.selectedLibrary3d = matched3d.id; }
                    this.closeModal(); this.mainTab = 'custom'; window.scrollTo({ top: 0, behavior: 'smooth' });
                },
                
                toggleAudio(path) { 
                    let src = '/bg_sounds/' + path;
                    if (this.playingMusicPath === src) { this.stopAllAudio(); } else { this.previewBgm(src); } 
                },
                
                previewBgm(src) {
                    this.stopAllAudio();
                    this.currentAudioPlayer = new Audio(src);
                    this.currentAudioPlayer.volume = this.bgmVolume;
                    this.currentAudioPlayer.currentTime = Number(this.bgmStart);
                    
                    this.currentAudioPlayer.ontimeupdate = () => {
                        if(this.currentAudioPlayer.currentTime >= Number(this.bgmEnd)) {
                            this.currentAudioPlayer.currentTime = Number(this.bgmStart);
                        }
                    };
                    this.currentAudioPlayer.play().catch(e => {});
                    this.playingMusicPath = src;
                },
                updateVolume() { if(this.currentAudioPlayer) this.currentAudioPlayer.volume = this.bgmVolume; },
                
                playVoice(text, voiceUri, audioUrl) {
                    window.speechSynthesis.cancel();
                    if (this.narrationPlayer) { this.narrationPlayer.pause(); this.narrationPlayer.currentTime = 0; }
                    if (audioUrl) {
                        this.narrationPlayer = new Audio(audioUrl);
                        this.narrationPlayer.play().catch(e=>{});
                    } else if (text) {
                        let utterance = new SpeechSynthesisUtterance(text);
                        utterance.lang = 'id-ID';
                        if(voiceUri) {
                            let voice = this.availableVoices.find(v => v.voiceURI === voiceUri);
                            if(voice) utterance.voice = voice;
                        }
                        window.speechSynthesis.speak(utterance);
                    }
                },
                
                stopAllAudio() {
                    window.speechSynthesis.cancel();
                    if(this.currentAudioPlayer) { this.currentAudioPlayer.pause(); this.currentAudioPlayer.currentTime = 0; }
                    if(this.narrationPlayer) { this.narrationPlayer.pause(); this.narrationPlayer.currentTime = 0; }
                    this.playingMusicPath = null;
                },

                openModal() {
                    if(this.arType === '2d' && !this.imageUrl2d) return alert('Upload gambar 2D dulu!');
                    if(this.arType === '3d' && !this.upload3dName && !this.selectedLibrary3d) return alert('Pilih atau upload objek 3D dulu!');
                    
                    let src3d = '';
                    if (this.arType === '3d') {
                        if (this.upload3dName && this.local3dUrl) src3d = this.local3dUrl; 
                        else if (this.selectedLibrary3d) {
                            let found = this.library3dList.find(i => i.id == this.selectedLibrary3d);
                            src3d = found ? found.path : '';
                        }
                    }

                    let hasNarr = (this.narrationMode === 'text' && this.narrationText) || (this.narrationMode === 'audio' && this.recordedAudioUrl);
                    let previewMusicSrc = this.isCustomBgm ? this.customBgmUrl : (this.selectedMusic ? '/bg_sounds/' + this.selectedMusic : '');

                    this.previewData = {
                        title: this.title || 'Preview Custom AR', type: this.arType,
                        src: this.arType === '2d' ? this.imageUrl2d : src3d,
                        music: previewMusicSrc, hasNarration: hasNarr
                    };
                    
                    this.showModal = true;
                    if(previewMusicSrc) this.previewBgm(previewMusicSrc);
                    
                    if (this.narrationMode === 'audio') {
                        this.playVoice(null, null, this.recordedAudioUrl);
                    } else {
                        this.playVoice(this.narrationText, this.selectedVoice, null);
                    }
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