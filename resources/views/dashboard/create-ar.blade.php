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
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.4.0/model-viewer.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .btn-gradient { background: linear-gradient(90deg, #14b8a6 0%, #8b5cf6 100%); transition: opacity 0.3s ease; }
        .btn-gradient:hover { opacity: 0.9; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .pointer-events-none-children model-viewer { pointer-events: none; }
        
        .dual-range input[type="range"] {
            -webkit-appearance: none;
            appearance: none; 
            background: transparent;
            pointer-events: none;
        }
        .dual-range input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            pointer-events: all;
            width: 30px;
            height: 56px;
            cursor: ew-resize;
        }
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
            <div class="flex items-center gap-2 sm:gap-4">
                <a href="{{ route('user.dashboard') }}" class="p-2 -ml-2 rounded-lg text-slate-500 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                </a>
                <div class="w-8 h-8 rounded bg-teal-50 text-teal-600 flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-qr-code w-5 h-5"><rect width="5" height="5" x="3" y="3" rx="1"></rect><rect width="5" height="5" x="16" y="3" rx="1"></rect><rect width="5" height="5" x="3" y="16" rx="1"></rect><path d="M21 16h-3a2 2 0 0 0-2 2v3"></path><path d="M21 21v.01"></path><path d="M12 7v3a2 2 0 0 1-2 2H7"></path><path d="M3 12h.01"></path><path d="M12 3h.01"></path><path d="M12 16v.01"></path><path d="M16 12h1"></path><path d="M21 12v.01"></path><path d="M12 21v-1"></path></svg>
                </div>
                <h1 class="hidden sm:block text-lg font-bold text-slate-900">Buat AR</h1>
            </div>
            <div class="flex p-1 bg-slate-100 rounded-lg border border-slate-200 w-full sm:w-auto mt-2 sm:mt-0 overflow-x-auto">
                <button @click="mainTab = 'custom'" :class="mainTab === 'custom' ? 'bg-white text-teal-600 shadow-sm font-bold' : 'text-slate-500 hover:text-slate-700'" class="flex-1 sm:flex-none px-3 sm:px-6 py-1.5 rounded-md text-xs sm:text-sm transition-all whitespace-nowrap">Custom AR</button>
                <button @click="mainTab = 'template'" :class="mainTab === 'template' ? 'bg-white text-teal-600 shadow-sm font-bold' : 'text-slate-500 hover:text-slate-700'" class="flex-1 sm:flex-none px-3 sm:px-6 py-1.5 rounded-md text-xs sm:text-sm transition-all whitespace-nowrap">Template</button>
            </div>
        </div>
    </header>

    <main class="py-6 px-4 w-full max-w-4xl mx-auto pb-32">
        <div x-show="mainTab === 'custom'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <form id="ar-form" @submit.prevent="submitForm" action="{{ route('user.ar.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 md:space-y-8 bg-white p-5 md:p-8 rounded-2xl shadow-sm border border-slate-200">
                @csrf
                <input type="hidden" name="ar_type" :value="arType">
                <input type="hidden" name="selected_3d_id" :value="selectedLibrary3d">
                <input type="hidden" name="bgm_path" :value="isCustomBgm ? '' : (selectedMusic ? '/minio-proxy/bg_sounds/' + selectedMusic : '')">

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
                    <div class="flex flex-wrap gap-2 md:gap-4 border-b border-slate-200 mb-6">
                        <button type="button" @click="arType = '2d'; reset3d();" :class="arType === '2d' ? 'border-teal-500 text-teal-600' : 'border-transparent text-slate-500 hover:text-slate-700'" class="flex-1 md:flex-none pb-3 px-2 border-b-2 font-bold text-sm transition-colors text-center md:text-left">Gambar 2D (JPG/PNG)</button>
                        <button type="button" @click="arType = '3d'; reset2d();" :class="arType === '3d' ? 'border-teal-500 text-teal-600' : 'border-transparent text-slate-500 hover:text-slate-700'" class="flex-1 md:flex-none pb-3 px-2 border-b-2 font-bold text-sm transition-colors text-center md:text-left">Objek 3D (GLB)</button>
                    </div>

                    <div x-show="arType === '2d'">
                        <label class="block text-sm font-bold text-slate-900 mb-2">Upload Gambar 2D</label>
                        <input type="file" name="image" id="image-upload" accept=".jpg,.jpeg,.png" class="hidden" @change="handle2dUpload">
                        <label for="image-upload" class="flex flex-col items-center justify-center w-full h-48 md:h-56 px-4 border-2 border-slate-300 border-dashed rounded-xl cursor-pointer bg-slate-50 hover:bg-slate-100 transition-colors overflow-hidden relative">
                            <div x-show="!imageUrl2d" class="text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 md:w-10 md:h-10 text-slate-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                <span class="text-xs md:text-sm font-medium text-slate-600">Klik untuk upload gambar</span>
                            </div>
                            <img x-show="imageUrl2d" :src="imageUrl2d" class="max-h-full max-w-full object-contain">
                        </label>
                    </div>

                    <div x-show="arType === '3d'" style="display: none;" class="space-y-6">
                        <div class="p-4 md:p-5 border border-slate-200 rounded-xl bg-slate-50" :class="selectedLibrary3d ? 'opacity-50 grayscale' : ''">
                            <label class="block text-sm font-bold text-slate-900 mb-2">Opsi 1: Upload Model 3D Sendiri (.glb)</label>
                            <div class="mb-4">
                                <input type="text" name="asset_name" x-model="upload3dDisplayName" placeholder="Ketik nama objek..." class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm focus:border-teal-500 outline-none" :disabled="selectedLibrary3d !== ''">
                            </div>
                            <input type="file" name="file_3d" id="glb-upload" accept=".glb" class="hidden" @change="handle3dUpload" :disabled="selectedLibrary3d !== ''">
                            <label for="glb-upload" class="flex items-center gap-3 w-full p-3 md:p-4 border border-slate-300 border-dashed rounded-lg cursor-pointer bg-white hover:border-teal-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 md:w-6 md:h-6 text-teal-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs md:text-sm font-bold text-slate-700 truncate" x-text="upload3dName ? upload3dName : 'Pilih file .glb dari perangkatmu'"></p>
                                    <p class="text-[10px] md:text-xs text-slate-400">Maksimal 50MB</p>
                                </div>
                            </label>
                        </div>
                        <div class="text-center text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-widest">ATAU</div>
                        <div class="p-4 md:p-5 border border-slate-200 rounded-xl bg-slate-50" :class="upload3dName ? 'opacity-50 grayscale' : ''">
                            <label class="block text-sm font-bold text-slate-900 mb-2">Opsi 2: Pilih dari Library ScanYuk</label>
                            <div class="relative mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-2.5 h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                <input type="text" x-model="search3d" placeholder="Cari objek 3D..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg text-sm focus:border-teal-500 outline-none" :disabled="upload3dName !== ''">
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2 md:gap-3 max-h-72 overflow-y-auto p-1 no-scrollbar">
                                <template x-for="item in filtered3d()" :key="item.id">
                                    <label class="flex flex-col bg-white border border-slate-200 rounded-xl cursor-pointer hover:border-teal-500 hover:shadow-md transition-all overflow-hidden" :class="selectedLibrary3d === item.id ? 'border-teal-500 ring-2 ring-teal-500 shadow-md' : ''">
                                        
                                        <div class="h-20 md:h-24 bg-slate-100 relative pointer-events-none-children flex items-center justify-center overflow-hidden">
                                            
                                            <div x-show="modelStates[item.id]?.state === 'idle' || modelStates[item.id]?.state === 'oversize'" 
                                                @click.prevent="toggleDownload(item.id)"
                                                class="absolute inset-0 z-10 flex flex-col items-center justify-center bg-slate-100/90 cursor-pointer hover:bg-slate-200 transition-colors pointer-events-auto"
                                                title="Klik untuk mendownload">
                                                <svg class="w-6 h-6 md:w-8 md:h-8 text-teal-500 mb-1 drop-shadow-sm hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                                <span class="text-[8px] md:text-[9px] font-bold text-slate-500 uppercase tracking-wider text-center" x-text="modelStates[item.id]?.state === 'oversize' ? 'Unduh' : 'Unduh Model'"></span>
                                            </div>

                                            <div x-show="modelStates[item.id]?.state === 'downloading'" 
                                                @click.prevent="toggleDownload(item.id)"
                                                class="absolute inset-0 z-10 flex flex-col items-center justify-center bg-slate-100/90 cursor-pointer hover:bg-slate-200 transition-opacity duration-300 pointer-events-auto"
                                                title="Klik untuk Pause">
                                                <div class="relative flex items-center justify-center w-8 h-8 md:w-10 md:h-10 mb-0.5">
                                                    <svg class="w-full h-full text-slate-300" viewBox="0 0 36 36"><path stroke-dasharray="100, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke="currentColor" stroke-width="3" fill="none" /></svg>
                                                    <svg class="absolute inset-0 w-full h-full text-teal-500 transition-all duration-200 ease-out" viewBox="0 0 36 36">
                                                        <path :stroke-dasharray="(modelStates[item.id]?.progress || 0) + ', 100'" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round" />
                                                    </svg>
                                                    <div class="absolute inset-0 flex items-center justify-center">
                                                        <span class="text-[8px] md:text-[10px] font-bold text-teal-600" x-text="(modelStates[item.id]?.progress || 0) + '%'"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <template x-if="modelStates[item.id]?.state === 'loaded'">
                                                <model-viewer 
                                                    :src="modelStates[item.id].url" 
                                                    class="w-full h-full" 
                                                    disable-zoom 
                                                    disable-pan 
                                                    shadow-intensity="0" 
                                                    exposure="1"
                                                    environment-image="neutral" 
                                                    loading="eager">
                                                </model-viewer>
                                            </template>

                                        </div>
                                        
                                        <div class="p-2 border-t border-slate-100 flex items-start gap-1">
                                            <input type="radio" x-model="selectedLibrary3d" :value="item.id" class="mt-0.5 text-teal-500 focus:ring-teal-500" :disabled="upload3dName !== ''">
                                            <span class="text-[10px] md:text-xs font-bold text-slate-700 leading-tight line-clamp-2" x-text="item.name"></span>
                                        </div>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-200">
                    <label class="block text-sm font-bold text-slate-900 mb-4">Background Music / BGM (Opsional)</label>
                    
                    <div class="mb-4 p-3 md:p-4 border border-slate-200 rounded-xl bg-white shadow-sm" :class="selectedMusic !== '' ? 'opacity-50 grayscale' : ''">
                        <label class="block text-[11px] md:text-sm font-bold text-slate-700 mb-2">Upload Musik Sendiri (MP3/WAV)</label>
                        <input type="file" name="custom_bgm" id="bgm-upload" accept="audio/*" class="hidden" @change="handleBgmUpload" :disabled="selectedMusic !== ''">
                        <label for="bgm-upload" class="flex items-center gap-3 w-full p-2 md:p-3 border border-slate-300 border-dashed rounded-lg cursor-pointer hover:border-indigo-500 bg-slate-50 hover:bg-white transition-colors">
                            <svg class="w-5 h-5 md:w-6 md:h-6 text-indigo-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" /></svg>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs md:text-sm font-bold text-slate-700 truncate" x-text="customBgmFile ? customBgmFile.name : 'Pilih file audio dari perangkatmu'"></p>
                            </div>
                        </label>
                        <button type="button" x-show="customBgmFile" @click="clearCustomBgm()" class="text-[10px] md:text-xs text-red-500 font-bold mt-2 hover:underline">Batal / Hapus Upload</button>
                    </div>

                    <div class="text-center text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">ATAU</div>
                    
                    <div class="relative mb-4" :class="isCustomBgm ? 'opacity-50 grayscale pointer-events-none' : ''">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-2.5 h-4 w-4 md:h-5 md:w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        <input type="text" x-model="searchMusic" placeholder="Cari dari Library..." class="w-full pl-9 md:pl-10 pr-4 py-2 border border-slate-300 rounded-lg text-xs md:text-sm focus:border-indigo-500 outline-none">
                    </div>

                    <div class="space-y-2 max-h-48 overflow-y-auto pr-1 no-scrollbar" :class="isCustomBgm ? 'opacity-50 grayscale pointer-events-none' : ''">
                        <label class="flex items-center gap-3 p-2 md:p-3 bg-white border border-slate-200 rounded-xl cursor-pointer hover:border-indigo-500" :class="selectedMusic === '' && !isCustomBgm ? 'border-indigo-500 ring-1 ring-indigo-500 bg-indigo-50' : ''">
                            <input type="radio" x-model="selectedMusic" value="" @change="clearMusic()" class="text-indigo-600 focus:ring-indigo-500">
                            <span class="text-xs md:text-sm font-bold text-slate-700">Tanpa Musik Latar</span>
                        </label>

                        <template x-for="music in filteredMusic()" :key="music.path">
                            <div @click="selectAndPlayMusic(music)" 
                                 class="flex items-center justify-between p-2 md:p-3 bg-white border rounded-xl cursor-pointer transition-all group"
                                 :class="selectedMusic === music.path ? 'border-teal-500 bg-teal-50/50 ring-1 ring-teal-500 shadow-sm' : 'border-slate-200 hover:border-teal-300 hover:shadow-sm'">
                                
                                <div class="flex items-center gap-2 md:gap-3 min-w-0">
                                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-white shadow-sm border border-slate-200 flex items-center justify-center text-teal-500 group-hover:scale-105 transition-transform flex-shrink-0">
                                        <svg x-show="selectedMusic !== music.path || (selectedMusic === music.path && !isBgmPlaying && !isBgmLoading)" class="w-3 h-3 md:w-4 md:h-4 ml-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11v11.78a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                                        <svg x-show="selectedMusic === music.path && isBgmLoading" class="animate-spin w-4 h-4 text-teal-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        <div x-show="selectedMusic === music.path && isBgmPlaying" class="flex items-end gap-0.5 h-3 md:h-4">
                                            <div class="w-1 bg-teal-500 animate-[bounce_1s_infinite] rounded-t-sm" style="height: 100%"></div>
                                            <div class="w-1 bg-teal-500 animate-[bounce_1s_infinite] rounded-t-sm" style="height: 60%; animation-delay: 0.2s"></div>
                                            <div class="w-1 bg-teal-500 animate-[bounce_1s_infinite] rounded-t-sm" style="height: 80%; animation-delay: 0.4s"></div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <span class="text-xs md:text-sm font-bold text-slate-700 truncate" x-text="music.name"></span>
                                        <span class="text-[9px] md:text-[10px]" :class="selectedMusic === music.path ? 'text-teal-600 font-bold' : 'text-slate-400'">
                                            <span x-show="selectedMusic !== music.path">Ketuk untuk memutar</span>
                                            <span x-show="selectedMusic === music.path && isBgmLoading">Memuat audio...</span>
                                            <span x-show="selectedMusic === music.path && isBgmPlaying">Sedang diputar</span>
                                            <span x-show="selectedMusic === music.path && !isBgmPlaying && !isBgmLoading">Di-pause</span>
                                        </span>
                                    </div>
                                </div>
                                <input type="radio" x-model="selectedMusic" :value="music.path" class="hidden pointer-events-none">
                            </div>
                        </template>
                    </div>

                    <div x-show="bgmDuration > 0 && (selectedMusic !== '' || isCustomBgm)" x-transition.opacity.duration.300ms class="mt-6 border-t border-slate-200 pt-5">
                        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-3 gap-2">
                            <label class="flex items-center gap-2 text-xs md:text-sm font-bold text-slate-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 md:w-5 md:h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>Potong Bagian Lagu</span>
                            </label>
                            <div class="flex items-center gap-3">
                                <span class="text-[10px] md:text-xs font-bold text-slate-500" x-text="formatTime(bgmStart) + ' - ' + formatTime(bgmEnd)"></span>
                                <span class="text-[10px] md:text-xs font-bold text-teal-600 bg-teal-50 border border-teal-200 px-2 py-1 rounded-md" x-text="Math.floor(bgmEnd - bgmStart) + ' Detik'"></span>
                            </div>
                        </div>

                        <div class="relative w-full h-12 md:h-14 bg-slate-800 rounded-xl overflow-hidden shadow-inner select-none mb-2 dual-range group">
                            <div class="absolute inset-0 flex items-center justify-between px-1 opacity-40 pointer-events-none">
                                <template x-for="i in 30">
                                    <div class="w-1 bg-teal-200 rounded-full transition-all duration-200" :style="`height: ${Math.random() * 60 + 20}%`"></div>
                                </template>
                            </div>
                            
                            <div class="absolute top-0 bottom-0 bg-teal-500/30 border-x-4 border-teal-400 transition-all duration-75 pointer-events-none shadow-[0_0_15px_rgba(20,184,166,0.4)]"
                                 :style="`left: ${(bgmStart / audioDuration) * 100}%; width: ${((bgmEnd - bgmStart) / audioDuration) * 100}%`">
                                 <div class="absolute top-1/2 -left-1.5 w-1 h-5 md:h-6 bg-white rounded-full -translate-y-1/2 opacity-90 shadow-md"></div>
                                 <div class="absolute top-1/2 -right-1.5 w-1 h-5 md:h-6 bg-white rounded-full -translate-y-1/2 opacity-90 shadow-md"></div>
                            </div>

                            <input type="range" min="0" :max="audioDuration" step="0.1" x-model.number="bgmStart" @input="limitCrop('start')" @change="seekCrop()" class="absolute inset-0 w-full h-full opacity-0 z-10">
                            <input type="range" min="0" :max="audioDuration" step="0.1" x-model.number="bgmEnd" @input="limitCrop('end')" @change="seekCrop()" class="absolute inset-0 w-full h-full opacity-0 z-20">
                        </div>
                        
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mt-2 gap-2">
                            <p class="text-[9px] md:text-[10px] text-slate-400 italic flex-1">*Geser garis kotak hijau di atas untuk memotong lagu.</p>
                            <div class="flex items-center gap-1.5" title="Atur volume latar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 md:h-4 md:w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M17.536 6.464a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" /></svg>
                                <input type="range" x-model="bgmVolume" @input="updateVolume()" min="0.05" max="1" step="0.05" class="w-16 h-1.5 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                            </div>
                        </div>
                        
                        <input type="hidden" name="bgm_start" :value="bgmStart">
                        <input type="hidden" name="bgm_end" :value="bgmEnd">
                        <input type="hidden" name="bgm_volume" :value="bgmVolume"> 
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-200">
                    <label class="block text-sm font-bold text-slate-900 mb-4">Mode Narasi Suara (Opsional)</label>
                    <input type="hidden" name="narration_mode" :value="narrationMode">
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
                        
                        <div class="p-4 md:p-5 border-2 rounded-xl transition-all relative flex flex-col" :class="narrationMode === 'text' ? 'border-teal-500 bg-white shadow-sm' : 'border-slate-200 bg-slate-50 opacity-60 grayscale'">
                            <div x-show="narrationMode !== 'text'" @click="narrationMode = 'text'" class="absolute inset-0 z-10 cursor-pointer flex items-center justify-center bg-slate-100/50 backdrop-blur-[1px] rounded-xl opacity-0 hover:opacity-100 transition-opacity">
                                <span class="bg-slate-900 text-white px-4 py-2 rounded-lg text-xs md:text-sm font-bold shadow-lg">Gunakan Fitur Ini</span>
                            </div>
                            
                            <div class="flex items-start gap-2 md:gap-3 mb-4">
                                <input type="radio" x-model="narrationMode" value="text" class="w-4 h-4 mt-0.5 text-teal-600 focus:ring-teal-500">
                                <div class="flex flex-col items-start gap-1 md:gap-2">
                                    <label class="text-xs md:text-sm font-bold text-slate-900 leading-none mt-0.5">Suara AI (Teks ke Suara)</label>
                                    <span class="text-[9px] md:text-[10px] font-extrabold bg-amber-100 text-amber-600 px-2 py-1 rounded-md uppercase tracking-wider border border-amber-200">
                                        Pilih Suara: Coming Soon
                                    </span>
                                </div>
                            </div>

                            <textarea x-model="narrationText" name="narration" rows="3" placeholder="Ketik teks yang akan dibacakan oleh suara AI..." class="w-full px-3 py-2 md:px-4 md:py-3 border border-slate-200 rounded-xl text-xs md:text-sm text-slate-900 focus:border-teal-500 outline-none resize-none flex-1" :disabled="narrationMode !== 'text'"></textarea>
                        </div>

                        <div class="p-4 md:p-5 border-2 rounded-xl transition-all relative" :class="narrationMode === 'audio' ? 'border-teal-500 bg-white shadow-sm' : 'border-slate-200 bg-slate-50 opacity-60 grayscale'">
                            <div x-show="narrationMode !== 'audio'" @click="narrationMode = 'audio'" class="absolute inset-0 z-10 cursor-pointer flex items-center justify-center bg-slate-100/50 backdrop-blur-[1px] rounded-xl opacity-0 hover:opacity-100 transition-opacity">
                                <span class="bg-slate-900 text-white px-4 py-2 rounded-lg text-xs md:text-sm font-bold shadow-lg">Gunakan Fitur Ini</span>
                            </div>

                            <div class="flex items-center gap-2 mb-4 text-teal-600">
                                <input type="radio" x-model="narrationMode" value="audio" class="w-4 h-4 text-teal-600 focus:ring-teal-500">
                                <label class="text-xs md:text-sm font-bold text-slate-900">Rekam Suara Mandiri</label>
                            </div>

                            <div class="flex flex-col items-center justify-center h-full gap-2 md:gap-3 pb-2 md:pb-4">
                                <p class="text-[10px] md:text-xs text-slate-500 text-center mb-2" x-show="!recordedAudioUrl">Gunakan mic HP/Laptop Anda. Max 1 Menit.</p>
                                
                                <button type="button" x-show="!isRecording && !recordedAudioUrl" @click="startRecording()" class="px-4 py-2 md:px-5 md:py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-full text-xs md:text-sm font-bold flex items-center gap-2 transition-all shadow-md" :disabled="narrationMode !== 'audio'">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7 4a3 3 0 016 0v4a3 3 0 11-6 0V4zm4 10.93A7.001 7.001 0 0017 8a1 1 0 10-2 0A5 5 0 015 8a1 1 0 00-2 0 7.001 7.001 0 006 6.93V17H6a1 1 0 100 2h8a1 1 0 100-2h-3v-2.07z" clip-rule="evenodd" /></svg> Mulai Merekam
                                </button>
                                
                                <button type="button" x-show="isRecording" @click="stopRecording()" class="px-4 py-2 md:px-5 md:py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-full text-xs md:text-sm font-bold flex items-center gap-2 transition-all animate-pulse shadow-md">
                                    <div class="w-2 h-2 md:w-3 md:h-3 bg-red-500 rounded-full"></div> Berhenti
                                </button>

                                <div x-show="recordedAudioUrl" class="w-full flex flex-col items-center gap-2">
                                    <audio :src="recordedAudioUrl" controls class="w-full h-8 md:h-10"></audio>
                                    <button type="button" @click="deleteRecording()" class="text-[10px] md:text-xs text-red-500 font-bold underline hover:text-red-700">Hapus & Rekam Ulang</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 pt-4 border-t border-slate-200">
                    <button type="button" @click="openModal()" class="w-full flex items-center justify-center gap-2 py-3 px-6 rounded-xl border-2 border-teal-500 text-teal-600 text-sm md:text-base font-bold hover:bg-teal-50 transition-colors">
                        Preview AR
                    </button>
                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 px-6 rounded-xl btn-gradient text-white text-sm md:text-base font-bold shadow-lg shadow-indigo-200">
                        Generate QR Code
                    </button>
                </div>
            </form>
        </div>

        <div x-show="mainTab === 'template'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="relative mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-4 top-3 h-5 w-5 md:h-6 md:w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                <input type="text" x-model="searchTemplate" placeholder="Cari template siap pakai..." class="w-full pl-12 pr-4 py-2.5 md:py-3 bg-white border border-slate-200 rounded-xl text-slate-900 focus:border-indigo-500 shadow-sm outline-none text-sm md:text-lg">
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                <div x-show="filteredTemplates().length === 0" class="col-span-full text-center py-10 text-slate-400 font-medium text-sm md:text-base">
                    Belum ada template AR yang tersedia.
                </div>
                
                <template x-for="tpl in filteredTemplates()" :key="tpl.id">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-lg hover:border-indigo-300 transition-all group cursor-pointer" @click="previewTemplate(tpl)">
                        <div class="h-32 md:h-40 bg-slate-100 flex items-center justify-center relative overflow-hidden pointer-events-none-children">
                            <template x-if="tpl.ar_type === '2d'"><img :src="getAssetUrl(tpl.file_path)" class="w-full h-full object-cover"></template>
                            <template x-if="tpl.ar_type === '3d'">
                                <model-viewer :src="getAssetUrl(tpl.file_path)" class="w-full h-full" disable-zoom disable-pan auto-rotate exposure="1.2"></model-viewer>
                            </template>
                            <div class="absolute inset-0 bg-slate-900/10 group-hover:bg-transparent transition-colors"></div>
                            <span class="absolute top-2 left-2 md:top-3 md:left-3 px-2 md:px-2.5 py-0.5 md:py-1 rounded-md text-[10px] md:text-xs font-bold shadow-sm" :class="tpl.ar_type === '3d' ? 'bg-indigo-600 text-white' : 'bg-teal-500 text-white'" x-text="'AR ' + tpl.ar_type.toUpperCase()"></span>
                        </div>
                        <div class="p-3 md:p-5">
                            <h3 class="font-bold text-slate-900 text-sm md:text-lg truncate mb-0.5 md:mb-1" x-text="tpl.title"></h3>
                            <p class="text-xs md:text-sm text-slate-500 line-clamp-2" x-text="tpl.narration"></p>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div x-show="showModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div x-show="showModal" x-transition.opacity class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm" @click="closeModal()"></div>
            <div x-show="showModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden flex flex-col items-center border border-white/20">
                <div class="w-full p-3 md:p-4 border-b border-slate-100 flex items-center justify-between bg-white">
                    <h3 class="font-bold text-slate-900 text-sm md:text-base truncate pr-2" x-text="previewData.title"></h3>
                    <button @click="closeModal()" type="button" class="text-slate-400 hover:text-red-500 transition-colors p-1 bg-slate-50 rounded-full hover:bg-red-50 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 md:w-6 md:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                
                <div x-show="isTemplateLoading" class="p-6 md:p-10 w-full flex flex-col items-center justify-center h-[250px] md:h-[300px] bg-slate-50">
                    <div class="relative w-12 h-12 md:w-16 md:h-16 mb-4">
                        <svg class="w-full h-full text-slate-200" viewBox="0 0 36 36"><path stroke-dasharray="100, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke="currentColor" stroke-width="3" fill="none" /></svg>
                        <svg class="absolute inset-0 w-full h-full text-indigo-500 transition-all duration-200 ease-out" viewBox="0 0 36 36"><path :stroke-dasharray="templateProgress + ', 100'" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round" /></svg>
                        <div class="absolute inset-0 flex items-center justify-center text-indigo-600 font-bold text-xs md:text-sm" x-text="templateProgress + '%'"></div>
                    </div>
                    <h4 class="font-bold text-slate-800 text-sm md:text-lg">Mempersiapkan AR...</h4>
                    <p class="text-[10px] md:text-xs text-slate-500 mt-1 text-center font-medium">Sedang mengunduh 3D & Audio</p>
                </div>

                <div x-show="!isTemplateLoading" class="p-4 md:p-6 w-full flex flex-col items-center">
                    <div class="w-full bg-slate-100 rounded-2xl overflow-hidden flex items-center justify-center relative shadow-inner border border-slate-200 h-[250px] md:h-[300px]">
                        
                        <div x-show="audioBlocked" @click="resumeAudio()" class="absolute inset-0 z-30 flex flex-col items-center justify-center bg-slate-900/70 backdrop-blur-sm cursor-pointer">
                            <div class="w-12 h-12 bg-teal-500 rounded-full flex items-center justify-center text-white mb-3 shadow-lg animate-pulse">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" /></svg>
                            </div>
                            <span class="text-white font-bold text-xs md:text-sm">Ketuk untuk Memutar AR & Suara</span>
                        </div>

                        <template x-if="previewData.type === '2d'"><img :src="previewData.src" class="max-w-full max-h-full object-contain"></template>
                        <template x-if="previewData.type === '3d'">
                            <div class="w-full h-full relative"> 
                                <div x-show="isPreviewLoading" class="absolute inset-0 z-20 flex flex-col items-center justify-center bg-slate-900/80">
                                    <div class="w-10 h-10 md:w-12 md:h-12 border-4 border-teal-500 border-t-transparent rounded-full animate-spin mb-3"></div>
                                    <p class="text-white font-bold text-xs md:text-sm mb-2">Mensinkronisasi Media...</p>
                                    <div class="w-32 md:w-48 bg-slate-700 rounded-full h-1.5 md:h-2">
                                        <div class="bg-teal-500 h-1.5 md:h-2 rounded-full" :style="`width: ${previewProgress}%`"></div>
                                    </div>
                                </div>   
                                <model-viewer id="preview-model-viewer" :src="previewData.src" auto-rotate camera-controls shadow-intensity="1" exposure="1.2" class="w-full h-full"></model-viewer>
                            </div>
                        </template>

                        <div class="absolute bottom-3 left-3 flex gap-1 md:gap-2">
                            <span x-show="previewData.music" class="px-2 py-1 bg-black/60 backdrop-blur-md rounded-full text-white text-[9px] md:text-xs font-medium flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" /></svg> BGM</span>
                            <span x-show="previewData.hasNarration" class="px-2 py-1 bg-teal-500/90 backdrop-blur-md rounded-full text-white text-[9px] md:text-xs font-medium flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 animate-pulse" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" /></svg> Suara</span>
                        </div>
                    </div>
                    
                    <div class="mt-4 md:mt-6 w-full flex flex-col gap-3">
                        <template x-if="isFromTemplate">
                            <button @click="useTemplate()" class="w-full py-3 px-6 rounded-xl btn-gradient text-white text-sm md:text-base font-bold shadow-lg flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Gunakan Template Ini
                            </button>
                        </template>
                        <template x-if="!isFromTemplate">
                            <button @click="triggerSubmit()" class="w-full py-3 px-6 rounded-xl btn-gradient text-white text-sm md:text-base font-bold shadow-lg flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg> Generate QR Code Sekarang
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
                init() {
                    this.loadVoices();
                    
                    this.library3dList.forEach(item => {
                        this.modelStates[item.id] = { 
                            state: 'idle', 
                            progress: 0, 
                            url: null, 
                            path: item.path,
                            downloadedBytes: 0,
                            chunks: [],
                            totalBytes: 0,
                            abortController: null
                        };
                    });

                    setTimeout(() => {
                        this.sortAndStartQueue();
                    }, 1000);
                },

                mainTab: 'custom', arType: '2d', title: '',
                imageUrl2d: null, upload3dName: '', upload3dDisplayName: '',
                selectedLibrary3d: '', local3dUrl: null, 
                
                selectedMusic: '', bgmVolume: 0.3,
                audioDuration: 100, bgmStart: 0, bgmEnd: 30, bgmDuration: 0, isBgmLoading: false, isBgmPlaying: false,
                customBgmFile: null, customBgmUrl: null, isCustomBgm: false,
                
                narrationMode: 'text', narrationText: '', availableVoices: [], selectedVoice: '',
                isRecording: false, mediaRecorder: null, audioChunks: [],
                recordedAudioBlob: null, recordedAudioUrl: null,
                isGenerating: false, progress: 0, estimatedTime: 'Menghitung...', uploadError: null,
                search3d: '', searchMusic: '', searchTemplate: '',
                showModal: false, isFromTemplate: false,
                previewData: { title: '', type: '', src: '', music: '', hasNarration: false, fullData: null, musicStart: 0, musicEnd: 0 },

                currentAudioPlayer: null, narrationPlayer: null, playingMusicPath: null,
                modalBgmPlayer: null, modalVoicePlayer: null, audioBlocked: false,

                library3dList: @json($library3dList),
                musicList: @json($musicList),
                templates: @json($templates),

                modelStates: {},
                downloadQueue: [],
                isBackgroundDownloading: false,

                isTemplateLoading: false, templateProgress: 0,
                isPreviewLoading: false, previewProgress: 0,

                getAssetUrl(path) {
                    if (!path) return '';
                    if (path.startsWith('http') || path.startsWith('/')) return path;
                    return '/' + path;
                },

                async sortAndStartQueue() {
                    let sizePromises = this.library3dList.map(item => {
                        return fetch(item.path, { method: 'HEAD' })
                            .then(res => {
                                let size = res.headers.get('content-length');
                                let parsedSize = size ? parseInt(size) : 0;
                                this.modelStates[item.id].totalBytes = parsedSize; 
                                return { id: item.id, size: parsedSize || 999999999 };
                            })
                            .catch(() => ({ id: item.id, size: 999999999 }));
                    });
                    let results = await Promise.all(sizePromises);
                    results.sort((a, b) => a.size - b.size);

                    this.downloadQueue = results.filter(r => r.size < 5242880).map(r => r.id);
                    
                    results.filter(r => r.size >= 5242880).forEach(r => {
                        if(this.modelStates[r.id]) this.modelStates[r.id].state = 'oversize';
                    });

                    this.processQueue();
                },

                async processQueue() {
                    if (this.downloadQueue.length === 0) return;
                    if (this.isBackgroundDownloading) return;

                    this.isBackgroundDownloading = true;
                    let nextId = this.downloadQueue.shift();

                    if (this.modelStates[nextId] && (this.modelStates[nextId].state === 'idle' || this.modelStates[nextId].state === 'oversize')) {
                        await this.downloadModel(nextId);
                    }

                    this.isBackgroundDownloading = false;
                    this.processQueue();
                },

                formatBytes(bytes) {
                    if (!bytes || bytes === 0 || bytes === 999999999) return 'Menghitung...';
                    const k = 1024;
                    const sizes = ['B', 'KB', 'MB', 'GB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
                },

                formatTime(seconds) {
                    if(!seconds || isNaN(seconds)) return "0:00";
                    let min = Math.floor(seconds / 60);
                    let sec = Math.floor(seconds % 60);
                    return min + ":" + (sec < 10 ? '0' : '') + sec;
                },

                toggleDownload(id) {
                    let state = this.modelStates[id];
                    if (state.state === 'downloading') {
                        if (state.abortController) state.abortController.abort();
                    } else if (state.state === 'idle' || state.state === 'paused' || state.state === 'oversize') {
                        if(!this.downloadQueue.includes(id)) {
                            this.downloadQueue.unshift(id); 
                        }
                        this.processQueue();
                    }
                },

                async downloadModel(id) {
                    let state = this.modelStates[id];
                    if (state.state === 'loaded') return;

                    state.state = 'downloading';
                    state.abortController = new AbortController();
                    
                    let startByte = state.downloadedBytes || 0;
                    let headers = {};
                    
                    if (startByte > 0) {
                        headers['Range'] = `bytes=${startByte}-`;
                    }

                    try {
                        let response = await fetch(state.path, {
                            headers: headers,
                            signal: state.abortController.signal
                        });

                        if (!response.ok) throw new Error("Gagal mengunduh");

                        if (response.status === 200 && startByte > 0) {
                            startByte = 0;
                            state.chunks = [];
                            state.downloadedBytes = 0;
                        }

                        let contentLength = response.headers.get('content-length') || response.headers.get('content-range')?.split('/')[1];
                        if (contentLength && !state.totalBytes) {
                            state.totalBytes = startByte + parseInt(contentLength, 10);
                        }

                        let reader = response.body.getReader();
                        state.chunks = state.chunks || [];

                        while (true) {
                            const {done, value} = await reader.read();
                            if (done) break;
                            
                            state.chunks.push(value);
                            state.downloadedBytes += value.length;
                            
                            if (state.totalBytes) {
                                state.progress = Math.round((state.downloadedBytes / state.totalBytes) * 100);
                            } else {
                                state.progress = Math.min(99, state.progress + 1);
                            }
                        }

                        let blob = new Blob(state.chunks, { type: 'model/gltf-binary' });
                        state.url = URL.createObjectURL(blob);
                        state.state = 'loaded';
                        state.progress = 100;
                        state.chunks = [];

                    } catch (err) {
                        if (err.name === 'AbortError') {
                            state.state = 'paused';
                        } else {
                            state.state = 'idle';
                            state.downloadedBytes = 0;
                            state.chunks = [];
                        }
                    }
                },

                filtered3d() { return this.library3dList.filter(i => (i.name || '').toLowerCase().includes(this.search3d.toLowerCase())); },
                filteredMusic() { return this.musicList.filter(i => (i.name || '').toLowerCase().includes(this.searchMusic.toLowerCase())); },
                filteredTemplates() { return this.templates.filter(i => (i.title || '').toLowerCase().includes(this.searchTemplate.toLowerCase()) || (i.narration || '').toLowerCase().includes(this.searchTemplate.toLowerCase())); },

                reset2d() { this.imageUrl2d = null; document.getElementById('image-upload').value = ''; },
                reset3d() { this.upload3dName = ''; this.upload3dDisplayName = ''; document.getElementById('glb-upload').value = ''; this.selectedLibrary3d = ''; if (this.local3dUrl) { URL.revokeObjectURL(this.local3dUrl); this.local3dUrl = null; } },
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
                
                handleBgmUpload(e) {
                    let file = e.target.files[0];
                    if(!file) return;
                    this.isCustomBgm = true;
                    this.selectedMusic = '';
                    this.customBgmFile = file;
                    if(this.customBgmUrl) URL.revokeObjectURL(this.customBgmUrl);
                    this.customBgmUrl = URL.createObjectURL(file);
                    this.previewBgm(this.customBgmUrl);
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
                clearMusic() {
                    this.selectedMusic = '';
                    this.clearCustomBgm();
                },
                selectAndPlayMusic(music) {
                    if (this.selectedMusic === music.path) {
                        if (this.currentAudioPlayer) {
                            if (this.isBgmPlaying || this.isBgmLoading) {
                                this.currentAudioPlayer.pause();
                            } else {
                                if (this.currentAudioPlayer.currentTime < Number(this.bgmStart) || this.currentAudioPlayer.currentTime >= Number(this.bgmEnd)) {
                                    this.currentAudioPlayer.currentTime = Number(this.bgmStart);
                                }
                                this.currentAudioPlayer.play();
                            }
                        }
                        return;
                    }

                    this.selectedMusic = music.path;
                    this.isCustomBgm = false;
                    this.clearCustomBgm();
                    let src = '/minio-proxy/bg_sounds/' + music.path;
                    this.previewBgm(src);
                },

                triggerSubmit() {
                    this.closeModal();
                    let form = document.getElementById('ar-form');
                    if(form) form.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
                },

                submitForm(e) {
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

                openModal() {
                    if(this.arType === '2d' && !this.imageUrl2d) return alert('Upload gambar 2D dulu!');
                    if(this.arType === '3d' && !this.upload3dName && !this.selectedLibrary3d) return alert('Pilih atau upload objek 3D dulu!');
                    
                    let src3d = '';
                    if (this.arType === '3d') {
                        if (this.upload3dName && this.local3dUrl) src3d = this.local3dUrl; 
                        else if (this.selectedLibrary3d) {
                            let state = this.modelStates[this.selectedLibrary3d];
                            src3d = (state && state.state === 'loaded') ? state.url : this.library3dList.find(i => i.id == this.selectedLibrary3d).path;
                        }
                    }

                    let hasNarr = (this.narrationMode === 'text' && this.narrationText) || (this.narrationMode === 'audio' && this.recordedAudioUrl);
                    let previewMusicSrc = this.isCustomBgm ? this.customBgmUrl : (this.selectedMusic ? '/minio-proxy/bg_sounds/' + this.selectedMusic : '');

                    this.isFromTemplate = false;
                    this.previewData = {
                        title: this.title || 'Preview Custom AR', type: this.arType,
                        src: this.arType === '2d' ? this.imageUrl2d : src3d,
                        music: previewMusicSrc, 
                        musicStart: this.bgmStart, musicEnd: this.bgmEnd,
                        hasNarration: hasNarr
                    };
                    
                    this.showModal = true;
                    this.isTemplateLoading = false;
                    this.isPreviewLoading = true;
                    this.previewProgress = 0;
                    this.stopAllAudio();

                    this.syncAndPlayModal();
                },

                async previewTemplate(tpl) {
                    this.isFromTemplate = true;
                    this.showModal = true;
                    this.isTemplateLoading = true;
                    this.templateProgress = 0;
                    this.stopAllAudio();

                    let src3d = this.getAssetUrl(tpl.file_path);
                    let rawBgm = tpl.bgm_path ? this.getAssetUrl(tpl.bgm_path) : '';
                    let tStart = 0; let tEnd = null;

                    if (rawBgm.includes('#t=')) {
                        let parts = rawBgm.split('#t=');
                        rawBgm = parts[0];
                        let times = parts[1].split(',');
                        tStart = parseFloat(times[0]);
                        tEnd = parseFloat(times[1]);
                    }

                    this.previewData = {
                        title: tpl.title,
                        type: tpl.ar_type,
                        src: '', 
                        music: rawBgm,
                        musicStart: tStart,
                        musicEnd: tEnd,
                        hasNarration: !!tpl.narration,
                        fullData: tpl
                    };

                    let finalBlobUrl = src3d;
                    try {
                        if (tpl.ar_type === '3d') {
                            finalBlobUrl = await new Promise((resolve, reject) => {
                                let xhr = new XMLHttpRequest();
                                xhr.open('GET', src3d, true);
                                xhr.responseType = 'blob';
                                xhr.onprogress = (e) => {
                                    if (e.lengthComputable) this.templateProgress = Math.round((e.loaded / e.total) * 90);
                                };
                                xhr.onload = () => {
                                    if (xhr.status >= 200 && xhr.status < 300) resolve(URL.createObjectURL(xhr.response));
                                    else reject('Gagal Unduh 3D');
                                };
                                xhr.onerror = reject;
                                xhr.send();
                            });
                        } else {
                            this.templateProgress = 90;
                        }

                        this.templateProgress = 100;
                        this.previewData.src = finalBlobUrl;
                        this.isTemplateLoading = false;
                        
                        this.isPreviewLoading = true;
                        this.syncAndPlayModal();

                    } catch (err) {
                        alert('Gagal memuat template. Periksa koneksi internet Anda.');
                        this.closeModal();
                    }
                },

                async syncAndPlayModal() {
                    let promises = [];

                    if (this.previewData.type === '3d') {
                        promises.push(new Promise(resolve => {
                            this.$nextTick(() => {
                                const viewer = document.getElementById('preview-model-viewer');
                                if(!viewer) return resolve();

                                const onProgress = (e) => {
                                    this.previewProgress = Math.round(e.detail.totalProgress * 100);
                                    if (e.detail.totalProgress === 1) {
                                        viewer.removeEventListener('progress', onProgress);
                                        resolve();
                                    }
                                };
                                viewer.addEventListener('progress', onProgress);

                                setTimeout(() => {
                                    viewer.removeEventListener('progress', onProgress);
                                    resolve();
                                }, 3000); 
                            });
                        }));
                    } else {
                        this.previewProgress = 100;
                    }

                    if (this.previewData.music) {
                        this.modalBgmPlayer = new Audio(this.previewData.music);
                        this.modalBgmPlayer.preload = 'auto';
                        this.modalBgmPlayer.volume = this.bgmVolume || 0.3;
                        let mStart = this.previewData.musicStart || 0;
                        let mEnd = this.previewData.musicEnd || null;

                        promises.push(new Promise(resolve => {
                            this.modalBgmPlayer.addEventListener('canplaythrough', () => {
                                this.modalBgmPlayer.currentTime = mStart;
                                resolve();
                            }, {once: true});
                            this.modalBgmPlayer.addEventListener('error', resolve, {once: true});
                            this.modalBgmPlayer.load();
                        }));

                        this.modalBgmPlayer.ontimeupdate = () => {
                            if (mEnd && this.modalBgmPlayer.currentTime >= mEnd) {
                                this.modalBgmPlayer.currentTime = mStart;
                            }
                        };
                    }

                    let voiceSrc = null;
                    if (!this.isFromTemplate && this.narrationMode === 'audio' && this.recordedAudioUrl) {
                        voiceSrc = this.recordedAudioUrl;
                    }

                    if (voiceSrc) {
                        this.modalVoicePlayer = new Audio(voiceSrc);
                        this.modalVoicePlayer.preload = 'auto';
                        promises.push(new Promise(resolve => {
                            this.modalVoicePlayer.addEventListener('canplaythrough', resolve, {once: true});
                            this.modalVoicePlayer.addEventListener('error', resolve, {once: true});
                            this.modalVoicePlayer.load();
                        }));
                    }

                    await Promise.all(promises);

                    this.isPreviewLoading = false;
                    
                    let playPromises = [];
                    if (this.modalBgmPlayer) playPromises.push(this.modalBgmPlayer.play());
                    if (this.modalVoicePlayer) playPromises.push(this.modalVoicePlayer.play());

                    if (playPromises.length > 0) {
                        Promise.all(playPromises).then(() => {
                            this.playTTS();
                        }).catch(e => {
                            this.audioBlocked = true;
                        });
                    } else {
                        this.playTTS();
                    }
                },

                playTTS() {
                    if (this.previewData.hasNarration) {
                        let text = this.isFromTemplate ? this.previewData.fullData.narration : (this.narrationMode === 'text' ? this.narrationText : null);
                        if (text && (!this.isFromTemplate && this.narrationMode === 'text' || this.isFromTemplate)) {
                            let utterance = new SpeechSynthesisUtterance(text);
                            utterance.lang = 'id-ID';
                            if(this.selectedVoice) {
                                let voice = this.availableVoices.find(v => v.voiceURI === this.selectedVoice);
                                if(voice) utterance.voice = voice;
                            }
                            window.speechSynthesis.speak(utterance);
                        }
                    }
                },

                resumeAudio() {
                    this.audioBlocked = false;
                    if(this.modalBgmPlayer) this.modalBgmPlayer.play().catch(e=>{});
                    if(this.modalVoicePlayer) this.modalVoicePlayer.play().catch(e=>{});
                    this.playTTS();
                },

                useTemplate() {
                    let tpl = this.previewData.fullData;
                    this.title = tpl.title; this.arType = tpl.ar_type; this.narrationText = tpl.narration; this.selectedMusic = tpl.bgm_path;
                    this.narrationMode = 'text';
                    if(tpl.ar_type === '2d') this.imageUrl2d = this.getAssetUrl(tpl.file_path);
                    else { let matched3d = this.library3dList.find(item => item.path === tpl.file_path); if(matched3d) this.selectedLibrary3d = matched3d.id; }
                    this.closeModal(); this.mainTab = 'custom'; window.scrollTo({ top: 0, behavior: 'smooth' });
                },

                previewBgm(src) {
                    this.stopAllAudio();
                    this.isBgmLoading = true;
                    this.currentAudioPlayer = new Audio(src);
                    this.currentAudioPlayer.preload = 'auto'; 
                    
                    this.currentAudioPlayer.addEventListener('waiting', () => { this.isBgmLoading = true; this.isBgmPlaying = false; });
                    this.currentAudioPlayer.addEventListener('playing', () => { this.isBgmLoading = false; this.isBgmPlaying = true; });
                    this.currentAudioPlayer.addEventListener('pause', () => { this.isBgmPlaying = false; });
                    
                    this.currentAudioPlayer.addEventListener('loadedmetadata', () => {
                        this.audioDuration = this.currentAudioPlayer.duration;
                        this.bgmDuration = this.audioDuration;
                        this.bgmStart = 0;
                        this.bgmEnd = this.audioDuration;
                        this.currentAudioPlayer.currentTime = Number(this.bgmStart);
                        this.currentAudioPlayer.volume = this.bgmVolume;
                        
                        this.currentAudioPlayer.play().catch(e => { this.isBgmLoading = false; });
                        this.playingMusicPath = src;
                    });

                    this.currentAudioPlayer.addEventListener('timeupdate', () => {
                        if (this.bgmEnd && this.currentAudioPlayer.currentTime >= Number(this.bgmEnd)) {
                            this.currentAudioPlayer.currentTime = Number(this.bgmStart);
                            this.currentAudioPlayer.play(); 
                        }
                    });
                },
                
                updateVolume() { if(this.currentAudioPlayer) this.currentAudioPlayer.volume = this.bgmVolume; },

                limitCrop(type) {
                    if (Number(this.bgmStart) >= Number(this.bgmEnd) - 0.5) {
                        if (type === 'start') this.bgmStart = Number(this.bgmEnd) - 0.5;
                        if (type === 'end') this.bgmEnd = Number(this.bgmStart) + 0.5;
                    }
                },
                seekCrop() {
                    if (this.currentAudioPlayer) {
                        this.currentAudioPlayer.currentTime = Number(this.bgmStart);
                        if (this.currentAudioPlayer.paused) {
                            this.currentAudioPlayer.play().catch(e=>{});
                        }
                    }
                },
                
                stopAllAudio() {
                    window.speechSynthesis.cancel();
                    if(this.currentAudioPlayer) { this.currentAudioPlayer.pause(); }
                    if(this.narrationPlayer) { this.narrationPlayer.pause(); this.narrationPlayer.currentTime = 0; }
                    if(this.modalBgmPlayer) { this.modalBgmPlayer.pause(); this.modalBgmPlayer = null; }
                    if(this.modalVoicePlayer) { this.modalVoicePlayer.pause(); this.modalVoicePlayer = null; }
                    this.playingMusicPath = null;
                    this.isBgmLoading = false;
                    this.isBgmPlaying = false;
                    this.audioBlocked = false;
                },

                closeModal() {
                    this.showModal = false;
                    this.stopAllAudio();
                    if (this.isFromTemplate && this.previewData.src && this.previewData.src.startsWith('blob:')) {
                        URL.revokeObjectURL(this.previewData.src);
                        this.previewData.src = '';
                    }
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
                }
            }
        }
    </script>
</body>
</html>