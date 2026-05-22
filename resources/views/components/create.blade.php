<style>
    /* Helper agar logic Javascript tetap berjalan mulus di Tailwind */
    .d-none { display: none !important; }
    .step-panel { display: none; }
    .step-panel.active { display: block; animation: fadein 0.4s ease; }
    @keyframes fadein { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    
    .wiz-step { transition: all 0.3s; }
    .wiz-step .num { transition: all 0.3s; border: 2px solid #e2e8f0; background: #f8fafc; color: #94a3b8; }
    .wiz-step.active .num { background: #0d9488; border-color: #0d9488; color: white; }
    .wiz-step.done .num { background: #10b981; border-color: #10b981; color: white; }
    .wiz-step.active .text-label { color: #1e293b; font-weight: 700; }
    .wiz-step.done .text-label { color: #10b981; font-weight: 700; }
    
    .wiz-connector { transition: all 0.4s; background-color: #e2e8f0; }
    .wiz-connector.done { background-color: #10b981; }

    .tpl-card, .marker-card { transition: all 0.2s; border: 2px solid transparent; }
    .tpl-card.selected, .marker-card.selected { border-color: #0d9488 !important; background-color: #f0fdfa !important; }
    
    .prog-bar { transition: width 0.6s ease; }
    .prog-bar.indeterminate { width: 40% !important; animation: slide-prog 1.4s ease-in-out infinite; }
    @keyframes slide-prog { 0% { transform: translateX(-150%); } 100% { transform: translateX(350%); } }

    .badge-status { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 99px; font-size: 12px; font-weight: 600; }
    .badge-status .dot { width: 8px; height: 8px; border-radius: 50%; }
    .badge-status.processing { background: #fef3c7; color: #d97706; }
    .badge-status.processing .dot { background: #d97706; animation: blink 1.2s ease infinite; }
    .badge-status.ready { background: #d1fae5; color: #059669; }
    .badge-status.ready .dot { background: #059669; }
    .badge-status.failed { background: #fee2e2; color: #dc2626; }
    .badge-status.failed .dot { background: #dc2626; }
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }
    
    /* Mode Tabs */
    .mode-tab { transition: all 0.2s; }
    .mode-tab.active { background: #0d9488; color: white; border-color: #0d9488; }
</style>

<div class="max-w-5xl mx-auto w-full font-sans">
    
    {{-- ===== WIZARD HEADER ===== --}}
    <div class="flex items-center justify-between sm:justify-center overflow-x-auto bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-sm mb-6 gap-2 sm:gap-0 hide-scrollbar">
        <div class="flex items-center gap-2 flex-shrink-0 wiz-step active" id="wiz-1">
            <div class="num w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center text-xs font-bold">1</div>
            <span class="text-label text-xs sm:text-sm font-semibold text-slate-400">Upload Marker</span>
        </div>
        <div class="wiz-connector h-0.5 w-6 sm:w-12 mx-2 sm:mx-4 flex-shrink-0" id="conn-1"></div>
        
        <div class="flex items-center gap-2 flex-shrink-0 wiz-step" id="wiz-2">
            <div class="num w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center text-xs font-bold">2</div>
            <span class="text-label text-xs sm:text-sm font-semibold text-slate-400">Pilih Konten AR</span>
        </div>
        <div class="wiz-connector h-0.5 w-6 sm:w-12 mx-2 sm:mx-4 flex-shrink-0" id="conn-2"></div>
        
        <div class="flex items-center gap-2 flex-shrink-0 wiz-step" id="wiz-3">
            <div class="num w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center text-xs font-bold">3</div>
            <span class="text-label text-xs sm:text-sm font-semibold text-slate-400">Preview & Posisi</span>
        </div>
        <div class="wiz-connector h-0.5 w-6 sm:w-12 mx-2 sm:mx-4 flex-shrink-0" id="conn-3"></div>
        
        <div class="flex items-center gap-2 flex-shrink-0 wiz-step" id="wiz-4">
            <div class="num w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center text-xs font-bold">4</div>
            <span class="text-label text-xs sm:text-sm font-semibold text-slate-400">Generate AR</span>
        </div>
    </div>

    {{-- ===== STEP 1: Upload Gambar Marker ===== --}}
    <div class="step-panel active" id="step-1">
        <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2 font-bold text-slate-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                Step 1 — Upload Gambar Marker
            </div>
            <div class="p-6">
                <p class="text-xs sm:text-sm text-slate-500 mb-6">Upload gambar yang akan dijadikan marker AR. Gambar dengan banyak detail dan kontras tinggi menghasilkan tracking lebih baik.</p>

                <div class="mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <div class="font-bold text-sm text-slate-700">Marker Tersedia</div>
                        <button type="button" onclick="resetMarkerUpload()" class="text-xs font-semibold text-teal-600 hover:text-teal-700 bg-teal-50 px-3 py-1.5 rounded-lg transition">
                            + Upload Baru
                        </button>
                    </div>
                    <div id="marker-grid" class="grid grid-cols-3 sm:grid-cols-5 gap-3">
                        <div class="text-xs text-slate-400 col-span-full">Memuat marker...</div>
                    </div>
                </div>

                {{-- Drop zone --}}
                <div class="border-2 border-dashed border-slate-300 bg-slate-50 hover:bg-teal-50 hover:border-teal-500 transition-colors rounded-2xl p-10 text-center cursor-pointer group" id="marker-drop-zone">
                    <input type="file" id="marker-file-input" accept=".jpg,.jpeg,.png" class="d-none">
                    <div class="text-slate-400 group-hover:text-teal-500 mb-3 flex justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                    </div>
                    <p class="font-bold text-slate-700 mb-1">Drag & drop gambar di sini</p>
                    <p class="text-xs text-slate-500 mb-4">JPG, PNG — max 10MB</p>
                    <button type="button" class="px-5 py-2 bg-slate-800 text-white text-xs font-bold rounded-xl hover:bg-slate-700 transition shadow-md" onclick="document.getElementById('marker-file-input').click()">
                        Pilih File Gambar
                    </button>
                </div>

                {{-- After upload --}}
                <div id="marker-after-upload" class="d-none mt-4 bg-slate-50 p-5 rounded-2xl border border-slate-100">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center bg-white p-3 rounded-xl border border-slate-200 shadow-sm">
                            <img id="marker-img-preview" src="" alt="Preview" class="w-full h-auto max-h-40 object-contain rounded-lg border border-slate-100">
                            <p id="marker-fname" class="mt-3 text-xs font-semibold text-slate-600 truncate"></p>
                            <button type="button" class="mt-2 text-[11px] font-bold text-red-500 hover:text-red-600" onclick="resetMarkerUpload()">Ganti Marker</button>
                        </div>
                        <div class="md:col-span-2 flex flex-col justify-center">
                            <h6 class="font-bold text-slate-800 text-sm mb-1">Status Konversi Marker</h6>
                            <p class="text-xs text-slate-500 mb-4">Gambar sedang dikonversi menjadi file <code>.mind</code> untuk tracking AI.</p>

                            <div id="mstatus-uploading" class="d-none mb-3 text-sm font-semibold text-slate-600 animate-pulse">Mengupload...</div>

                            <div id="mstatus-processing" class="d-none mb-3">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge-status processing"><span class="dot"></span> Memproses marker...</span>
                                    <span id="prog-text" class="text-[10px] font-bold text-slate-600">0%</span>
                                </div>
                                <div class="prog-wrap">
                                    <div class="prog-bar bg-amber-500 h-full transition-all duration-500" id="marker-progbar" style="width:0%"></div>
                                </div>
                                <p id="eta-text" class="small text-muted mt-2">Estimasi: memuat...</p>
                            </div>

                            <div id="mstatus-ready" class="d-none mb-3">
                                <div class="mb-2"><span class="badge-status ready"><span class="dot"></span> Marker siap digunakan!</span></div>
                                <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                                    <div class="bg-emerald-500 h-full w-full rounded-full"></div>
                                </div>
                            </div>

                            <div id="mstatus-failed" class="d-none mb-3">
                                <span class="badge-status failed"><span class="dot"></span> Gagal memproses marker</span>
                                <button type="button" class="block mt-2 text-xs font-bold text-red-600 underline" onclick="resetMarkerUpload()">Coba Lagi</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-8 pt-4 border-t border-slate-100">
                    <button id="btn-next-1" class="px-6 py-2.5 bg-gradient-to-r from-teal-500 to-indigo-600 text-white text-sm font-bold rounded-xl shadow-md hover:opacity-90 disabled:opacity-50 disabled:cursor-not-allowed transition" disabled onclick="goToStep(2)">
                        Lanjut ke Konten AR &rarr;
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== STEP 2: Pilih Konten AR ===== --}}
    <div class="step-panel" id="step-2">
        <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2 font-bold text-slate-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                Step 2 — Pilih Konten 3D/AR
            </div>
            <div class="p-6">
                
                <div class="flex p-1 bg-slate-100 rounded-lg w-fit mb-6 gap-1 border border-slate-200">
                    <button class="mode-tab active px-4 py-1.5 rounded-md text-sm font-medium text-slate-500 hover:text-slate-700" id="tab-template" onclick="switchMode('template')">3D Pack</button>
                    <button class="mode-tab px-4 py-1.5 rounded-md text-sm font-medium text-slate-500 hover:text-slate-700" id="tab-gltf" onclick="switchMode('gltf')">GLB / GLTF</button>
                    <button class="mode-tab px-4 py-1.5 rounded-md text-sm font-medium text-slate-500 hover:text-slate-700" id="tab-blend" onclick="switchMode('blend')">Blender (.blend)</button>
                </div>

                {{-- MODE: Template --}}
                <div id="panel-template" class="step-panel">
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm mb-6">
                        <label class="block text-sm font-bold text-slate-900 mb-2">Pilih dari Library 3D ScanYuk</label>
                        <div class="flex p-1 bg-slate-200/50 rounded-lg w-fit mb-4 gap-1 border border-slate-200">
                            <button type="button" id="tab-lib-model" class="px-4 py-1.5 rounded-md text-sm font-bold bg-white text-teal-600 shadow-sm transition-all" onclick="switchLibTab('model')">3D Model</button>
                            <button type="button" id="tab-lib-animasi" class="px-4 py-1.5 rounded-md text-sm font-medium text-slate-500 hover:text-slate-700 transition-all" onclick="switchLibTab('animasi')">3D Animasi</button>
                        </div>
                        
                        <div class="relative mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-2.5 h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            <input type="text" id="search-3d" placeholder="Cari objek 3D..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg text-sm focus:border-teal-500 outline-none" onkeyup="filter3DPack()">
                        </div>

                        <div id="template-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2 md:gap-3 max-h-72 overflow-y-auto p-1 no-scrollbar">
                            </div>
                    </div>
                </div>

                {{-- MODE: GLTF --}}
                <div id="panel-gltf" class="step-panel">
                    <div class="border-2 border-dashed border-slate-300 bg-slate-50 hover:bg-teal-50 hover:border-teal-500 transition-colors rounded-2xl p-10 text-center cursor-pointer group" id="gltf-drop-zone">
                        <input type="file" id="gltf-file-input" accept=".glb,.gltf" class="d-none">
                        <div class="text-slate-400 group-hover:text-teal-500 mb-3 flex justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                        </div>
                        <p class="font-bold text-slate-700 mb-1">Upload file .glb atau .gltf</p>
                        <p class="text-xs text-slate-500 mb-4">Max 20MB. Animasi dan material didukung penuh.</p>
                        <button type="button" class="px-5 py-2 bg-slate-800 text-white text-xs font-bold rounded-xl hover:bg-slate-700" onclick="document.getElementById('gltf-file-input').click()">Pilih File</button>
                    </div>
                    <div id="gltf-chosen" class="d-none mt-4 p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <svg class="h-6 w-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <div>
                                <p class="text-sm font-bold text-emerald-800" id="gltf-fname"></p>
                                <p class="text-[11px] text-emerald-600">File siap digunakan.</p>
                            </div>
                        </div>
                        <button type="button" class="text-xs font-bold text-red-500 hover:text-red-700" onclick="resetGltf()">Ganti File</button>
                    </div>
                </div>

                {{-- MODE: Blend --}}
                <div id="panel-blend" class="step-panel">
                    <div class="border-2 border-dashed border-slate-300 bg-slate-50 hover:bg-amber-50 hover:border-amber-500 transition-colors rounded-2xl p-8 text-center cursor-pointer group mb-4" id="blend-drop-zone">
                        <input type="file" id="blend-file-input" accept=".blend" class="d-none">
                        <div class="text-slate-400 group-hover:text-amber-500 mb-2 flex justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        </div>
                        <p class="font-bold text-slate-700 mb-1">Upload file .blend</p>
                        <p class="text-xs text-slate-500 mb-4">File akan dikonversi ke .glb di server menggunakan Blender CLI.</p>
                        <button type="button" class="px-5 py-2 bg-amber-500 text-white text-xs font-bold rounded-xl hover:bg-amber-600" onclick="document.getElementById('blend-file-input').click()">Pilih File .blend</button>
                    </div>
                    
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex gap-3 text-amber-800 text-xs">
                        <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        <div>
                            <strong class="block mb-1">Penting sebelum upload:</strong>
                            Pastikan semua texture sudah di-<strong>pack</strong> ke dalam file .blend (<code>File &rarr; External Data &rarr; Pack All Into .blend</code>). Tanpa ini, texture tidak akan muncul.
                        </div>
                    </div>

                    <div id="blend-after-upload" class="d-none mt-4 p-5 bg-slate-50 border border-slate-200 rounded-xl">
                        <div id="blend-uploading" class="d-none text-sm font-semibold text-slate-600 animate-pulse">Mengupload dan memulai konversi...</div>
                        
                        <div id="blend-processing" class="d-none">
                            <div class="mb-2"><span class="badge-status processing"><span class="dot"></span> Mengkonversi .blend &rarr; .glb...</span></div>
                            <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden mb-2">
                                <div class="prog-bar indeterminate bg-gradient-to-r from-amber-400 to-amber-500 h-full rounded-full"></div>
                            </div>
                            <p class="text-[11px] text-slate-500">Proses ini bisa memakan waktu 1–3 menit tergantung kompleksitas file.</p>
                        </div>
                        
                        <div id="blend-done" class="d-none">
                            <div class="mb-2"><span class="badge-status ready"><span class="dot"></span> Konversi selesai!</span></div>
                            <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                                <div class="bg-emerald-500 h-full w-full rounded-full"></div>
                            </div>
                        </div>
                        
                        <div id="blend-failed" class="d-none">
                            <span class="badge-status failed"><span class="dot"></span> Konversi gagal</span>
                            <p id="blend-error-msg" class="text-xs text-red-600 mt-2 font-semibold"></p>
                            <button class="mt-2 text-xs font-bold text-slate-600 border border-slate-300 px-3 py-1 rounded bg-white" onclick="resetBlend()">Coba Lagi</button>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between mt-8 pt-4 border-t border-slate-100">
                    <button class="px-5 py-2 text-slate-600 font-bold text-sm hover:text-slate-800 transition" onclick="goToStep(1)">
                        &larr; Kembali
                    </button>
                    <button id="btn-next-2" class="px-6 py-2.5 bg-gradient-to-r from-teal-500 to-indigo-600 text-white text-sm font-bold rounded-xl shadow-md hover:opacity-90 disabled:opacity-50 disabled:cursor-not-allowed transition" disabled onclick="goToStep(3)">
                        Lanjut ke Preview &rarr;
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== STEP 3: Preview & Posisi ===== --}}
    <div class="step-panel" id="step-3">
        <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2 font-bold text-slate-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                Step 3 — Preview 3D & Atur Posisi
            </div>
            <div class="p-6">
                <p class="text-xs sm:text-sm text-slate-500 mb-6">Geser, putar, dan atur ukuran model langsung di preview. Angka di form akan ikut berubah secara real-time.</p>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    {{-- Preview canvas --}}
                    <div class="lg:col-span-7">
                        <div class="w-full bg-slate-900 rounded-2xl overflow-hidden relative shadow-inner border border-slate-800" style="height: 400px;">
                            <canvas id="canvas-3d" class="w-full h-full block"></canvas>
                            <div class="absolute bottom-3 left-1/2 transform -translate-x-1/2 bg-black/60 text-white/80 text-[10px] px-3 py-1 rounded-full pointer-events-none backdrop-blur-sm">
                                Drag rotate &nbsp;&middot;&nbsp; Scroll zoom
                            </div>
                            <div id="canvas-loading" class="absolute inset-0 flex items-center justify-center bg-slate-900/80 backdrop-blur-sm">
                                <div class="text-center">
                                    <div class="inline-block w-8 h-8 border-4 border-teal-500 border-t-transparent rounded-full animate-spin mb-2"></div>
                                    <p class="text-xs text-slate-300">Memuat model 3D...</p>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Scale slider --}}
                        <div class="mt-4 bg-slate-50 border border-slate-200 rounded-xl p-4">
                            <h6 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Ukuran (Scale)</h6>
                            <div class="flex items-center gap-3">
                                <input type="range" class="w-full accent-teal-500" id="scale-slider" min="0.05" max="5" step="0.05" value="1">
                                <span id="scale-display" class="min-w-[40px] text-right text-sm font-bold text-teal-600">1.00</span>
                            </div>
                        </div>
                    </div>

                    {{-- Controls --}}
                    <div class="lg:col-span-5 space-y-4">
                        {{-- Position --}}
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                            <h6 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3">Posisi (Position)</h6>
                            <div class="grid grid-cols-3 gap-2">
                                <div class="flex flex-col gap-1">
                                    <span class="text-[10px] font-bold text-center rounded bg-red-100 text-red-600 py-0.5">X</span>
                                    <input type="number" id="pos-x" value="0" step="0.1" oninput="applyTransformFromForm()" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1.5 text-sm text-center focus:ring-1 focus:ring-teal-500 outline-none">
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[10px] font-bold text-center rounded bg-green-100 text-green-600 py-0.5">Y</span>
                                    <input type="number" id="pos-y" value="0" step="0.1" oninput="applyTransformFromForm()" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1.5 text-sm text-center focus:ring-1 focus:ring-teal-500 outline-none">
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[10px] font-bold text-center rounded bg-blue-100 text-blue-600 py-0.5">Z</span>
                                    <input type="number" id="pos-z" value="0" step="0.1" oninput="applyTransformFromForm()" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1.5 text-sm text-center focus:ring-1 focus:ring-teal-500 outline-none">
                                </div>
                            </div>
                        </div>

                        {{-- Rotation --}}
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                            <h6 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3">Rotasi (Degrees)</h6>
                            <div class="grid grid-cols-3 gap-2">
                                <div class="flex flex-col gap-1">
                                    <span class="text-[10px] font-bold text-center rounded bg-red-100 text-red-600 py-0.5">X</span>
                                    <input type="number" id="rot-x" value="0" step="1" oninput="applyTransformFromForm()" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1.5 text-sm text-center focus:ring-1 focus:ring-teal-500 outline-none">
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[10px] font-bold text-center rounded bg-green-100 text-green-600 py-0.5">Y</span>
                                    <input type="number" id="rot-y" value="0" step="1" oninput="applyTransformFromForm()" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1.5 text-sm text-center focus:ring-1 focus:ring-teal-500 outline-none">
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-[10px] font-bold text-center rounded bg-blue-100 text-blue-600 py-0.5">Z</span>
                                    <input type="number" id="rot-z" value="0" step="1" oninput="applyTransformFromForm()" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1.5 text-sm text-center focus:ring-1 focus:ring-teal-500 outline-none">
                                </div>
                            </div>
                        </div>

                        {{-- Orbit animation panel --}}
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4" id="orbit-panel">
                            <h6 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3">Orbit Keliling Marker</h6>
                            <div class="flex items-center gap-2 mb-3">
                                <button id="btn-orbit" class="flex-1 bg-white border border-slate-300 text-slate-700 text-xs font-bold py-1.5 rounded-lg shadow-sm hover:bg-slate-50" onclick="toggleOrbit()">
                                    <i class="bi bi-play-circle" id="orbit-icon"></i> Mulai Orbit
                                </button>
                                <button class="bg-white border border-slate-300 text-slate-700 px-3 py-1.5 rounded-lg shadow-sm hover:bg-slate-50" onclick="toggleOrbitDir()" title="Balik arah">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" id="orbit-dir-icon"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                </button>
                            </div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-[10px] text-slate-500 min-w-[35px]">Speed</span>
                                <input type="range" class="w-full accent-teal-500" id="orbit-speed" min="0.1" max="3" step="0.1" value="0.5">
                                <span id="orbit-speed-val" class="text-xs font-bold text-teal-600 min-w-[25px] text-right">0.5×</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] text-slate-500 min-w-[35px]">Radius</span>
                                <input type="range" class="w-full accent-teal-500" id="orbit-radius" min="0.5" max="4" step="0.1" value="1.5">
                                <span id="orbit-radius-val" class="text-xs font-bold text-teal-600 min-w-[25px] text-right">1.5</span>
                            </div>
                        </div>

                        {{-- Anim Clip --}}
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4" id="anim-clip-panel" style="display:none">
                            <h6 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Animasi Aktif</h6>
                            <select id="anim-clip-select" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-teal-500 outline-none" onchange="switchAnimClip(this.value)">
                            </select>
                        </div>

                        <button class="w-full py-2 text-xs font-bold text-slate-500 hover:text-slate-800 border border-slate-200 bg-white rounded-xl shadow-sm transition" onclick="resetTransform()">
                            Reset Posisi & Skala
                        </button>
                        
                        <div class="hidden">
                            <img id="preview-marker-thumb" src="" class="hidden">
                        </div>
                    </div>
                </div>

                <div class="flex justify-between mt-8 pt-4 border-t border-slate-100">
                    <button class="px-5 py-2 text-slate-600 font-bold text-sm hover:text-slate-800 transition" onclick="goToStep(2)">
                        &larr; Kembali
                    </button>
                    <button class="px-6 py-2.5 bg-gradient-to-r from-teal-500 to-indigo-600 text-white text-sm font-bold rounded-xl shadow-md hover:opacity-90 transition" onclick="goToStep(4)">
                        Lanjut Review &rarr;
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== STEP 4: Generate AR ===== --}}
    <div class="step-panel" id="step-4">
        <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2 font-bold text-slate-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Step 4 — Review & Generate AR
            </div>
            <div class="p-6">
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6 bg-slate-50 p-6 rounded-2xl border border-slate-100">
                    <div class="md:col-span-1">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Marker</p>
                        <img id="gen-marker-img" src="" class="w-full h-auto aspect-square object-cover rounded-xl border border-slate-200 shadow-sm bg-white">
                    </div>
                    <div class="md:col-span-3">
                        <div class="mb-5 bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">Nama / Judul AR <span class="text-red-500">*</span></label>
                            <input type="text" id="marker-project-title" placeholder="Masukkan nama project AR Anda..." class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-teal-500 font-semibold outline-none transition-all" required>
                        </div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Ringkasan Project</p>
                        <div id="review-summary" class="space-y-3">
                            <div class="flex border-b border-slate-200 pb-2">
                                <span class="w-1/3 text-xs text-slate-500">Tipe Konten</span>
                                <span class="w-2/3 text-sm font-bold text-slate-800" id="gen-type">—</span>
                            </div>
                            <div class="flex border-b border-slate-200 pb-2">
                                <span class="w-1/3 text-xs text-slate-500">Model / Template</span>
                                <span class="w-2/3 text-sm font-bold text-slate-800 truncate" id="gen-model">—</span>
                            </div>
                            <div class="flex border-b border-slate-200 pb-2">
                                <span class="w-1/3 text-xs text-slate-500">Scale</span>
                                <span class="w-2/3 text-sm font-bold text-slate-800" id="gen-scale">1.00</span>
                            </div>
                            <div class="flex border-b border-slate-200 pb-2">
                                <span class="w-1/3 text-xs text-slate-500">Position</span>
                                <span class="w-2/3 text-sm font-bold text-slate-800" id="gen-position">X: 0, Y: 0, Z: 0</span>
                            </div>
                            <div class="flex">
                                <span class="w-1/3 text-xs text-slate-500">Rotation</span>
                                <span class="w-2/3 text-sm font-bold text-slate-800" id="gen-rotation">X: 0°, Y: 0°, Z: 0°</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Hidden form --}}
                <form id="generate-form" action="{{ route('user.marker.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="title" id="form-title">
                    <input type="hidden" name="marker_id"   id="form-marker-id">
                    <input type="hidden" name="type"        id="form-type">
                    <input type="hidden" name="template_id" id="form-template-id">
                    <input type="hidden" name="scale"        id="form-scale"        value="1">
                    <input type="hidden" name="position"     id="form-position"     value="[0,0,0]">
                    <input type="hidden" name="rotation"     id="form-rotation"     value="[0,0,0]">
                    <input type="hidden" name="orbit_active" id="form-orbit-active" value="0">
                    <input type="hidden" name="orbit_speed"  id="form-orbit-speed"  value="0.5">
                    <input type="hidden" name="orbit_radius" id="form-orbit-radius" value="1.5">
                    <input type="hidden" name="orbit_dir"    id="form-orbit-dir"    value="1">
                    <input type="hidden" name="anim_clip"    id="form-anim-clip"    value="*">
                    <div id="form-config-fields"></div>
                    <input type="file"   id="form-model-file" name="model" class="d-none">
                    <input type="hidden" name="blend_project_id" id="form-blend-project-id">
                </form>

                <div class="flex justify-between mt-8 pt-4 border-t border-slate-100">
                    <button class="px-5 py-2 text-slate-600 font-bold text-sm hover:text-slate-800 transition" onclick="goToStep(3)">
                        &larr; Kembali
                    </button>
                    <button id="btn-generate" class="px-8 py-3 bg-emerald-500 text-white text-sm font-bold rounded-xl shadow-md hover:bg-emerald-600 transition flex items-center gap-2" onclick="submitGenerate()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" /></svg>
                        Generate AR Sekarang!
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="gen-progress-modal" class="fixed inset-0 z-[200] flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-3xl p-8 max-w-sm w-full mx-4 shadow-2xl flex flex-col items-center text-center border border-white/20">
            <div class="w-16 h-16 border-4 border-slate-100 border-t-teal-500 rounded-full animate-spin mb-6"></div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Menggenerate AR...</h3>
            <div class="w-full bg-slate-100 rounded-full h-3 mb-3 overflow-hidden">
                <div id="gen-progress-bar" class="bg-gradient-to-r from-teal-400 to-indigo-500 h-3 rounded-full transition-all duration-300" style="width: 0%"></div>
            </div>
            <div class="flex justify-between w-full text-sm font-semibold">
                <span id="gen-progress-percent" class="text-indigo-600">0%</span>
                <span class="text-slate-500">Membangun Matrix AR...</span>
            </div>
        </div>
    </div>
</div>

<script type="module">
import * as THREE from 'three';
import { GLTFLoader }    from 'three/addons/loaders/GLTFLoader.js';
import { DRACOLoader }   from 'three/addons/loaders/DRACOLoader.js';
import { OrbitControls } from 'three/addons/controls/OrbitControls.js';

// ─── STATE ───────────────────────────────────────────────────────────────────
const state = {
    step: 1,
    // Marker
    markerId: null,
    markerStatus: null,
    markerImageUrl: null,
    markerPollingTimer: null,
    // Mode
    mode: 'template',
    // Template
    selectedTemplateId: null,
    selectedTemplateName: null,
    selectedTemplateUrl: null,
    templateConfig: {},
    // GLTF
    gltfFile: null,
    gltfBlob: null,
    // Blend
    blendProjectId: null,   // server-created project id after async conversion
    blendGlbUrl: null,       // GLB url returned after conversion
    blendPollingTimer: null,
    blendStatus: null,       // null | uploading | processing | done | failed
    // Transform
    scale: 1.0,
    position: [0, 0, 0],
    rotation: [0, 0, 0],
};

// ─── THREE.JS ─────────────────────────────────────────────────────────────────
let renderer, scene, camera, orbitControls, mixer, clock, animFrame;
let previewModel = null;
let markerPlane  = null;  // marker sebagai objek 3D di scene
let pivotGroup   = null;  // group untuk orbit: model dimasukkan ke sini
let allClips     = [];    // semua AnimationClip dari GLB
let activeAction = null;  // AnimationAction yang sedang play

// Orbit state
const orbitState = {
    active:  false,
    speed:   0.5,    // rad/s
    dir:     1,      // 1 = CW, -1 = CCW
    radius:  1.5,
    angle:   0,
};

function initThree() {
    const canvas = document.getElementById('canvas-3d');
    const wrap = canvas.parentElement;

    // Ambil ukuran nyata; offsetWidth lebih reliable saat elemen baru visible
    const W = wrap.offsetWidth  || wrap.clientWidth  || 600;
    const H = wrap.offsetHeight || wrap.clientHeight || 400;

    // Jika renderer sudah ada (user balik ke step 3), cukup resize — jangan init ulang
    if (renderer) {
        renderer.setSize(W, H);
        camera.aspect = W / H;
        camera.updateProjectionMatrix();
        return;
    }

    renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true });
    renderer.setSize(W, H);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.shadowMap.enabled = true;
    // SRGBColorSpace wajib agar warna GLB Blender tampil benar
    renderer.outputColorSpace  = THREE.SRGBColorSpace;
    // LinearToneMapping = tidak ada perubahan warna — paling akurat untuk preview
    renderer.toneMapping       = THREE.LinearToneMapping;
    renderer.toneMappingExposure = 1.0;

    scene = new THREE.Scene();
    scene.background = new THREE.Color(0x0f172a); // Tailwind slate-900

    camera = new THREE.PerspectiveCamera(45, W / H, 0.01, 500);
    camera.position.set(0, 1.5, 4);

    // Lights
    const amb = new THREE.AmbientLight(0xffffff, 0.6);
    scene.add(amb);
    const dir = new THREE.DirectionalLight(0xffffff, 1.2);
    dir.position.set(5, 8, 5);
    scene.add(dir);
    const fill = new THREE.DirectionalLight(0x8899ff, 0.4);
    fill.position.set(-5, 2, -5);
    scene.add(fill);

    // Grid tipis sebagai lantai referensi
    const grid = new THREE.GridHelper(6, 12, 0x334155, 0x1e293b);
    scene.add(grid);

    orbitControls = new OrbitControls(camera, renderer.domElement);
    orbitControls.enableDamping = true;
    orbitControls.dampingFactor = 0.08;

    // Listen for orbit changes to update form fields
    orbitControls.addEventListener('change', () => {
        if (previewModel) syncFormFromModel();
    });

    clock = new THREE.Clock();

    function animate() {
        animFrame = requestAnimationFrame(animate);
        const delta = clock.getDelta();

        if (mixer) mixer.update(delta);

        // Orbit: putar pivotGroup mengelilingi Y axis (di atas marker)
        if (orbitState.active && pivotGroup) {
            orbitState.angle += orbitState.speed * orbitState.dir * delta;

            // Posisi model pada lingkaran orbit
            const r = orbitState.radius;
            pivotGroup.position.x = Math.sin(orbitState.angle) * r;
            pivotGroup.position.z = Math.cos(orbitState.angle) * r;

            // Model selalu menghadap ke tengah marker (pusat orbit)
            pivotGroup.rotation.y = orbitState.angle;
        }

        orbitControls.update();
        renderer.render(scene, camera);
    }
    animate();

    window.addEventListener('resize', () => {
        const W2 = wrap.clientWidth, H2 = wrap.clientHeight;
        camera.aspect = W2 / H2;
        camera.updateProjectionMatrix();
        renderer.setSize(W2, H2);
    });
}

// DRACOLoader shared instance (Blender sering export dengan Draco compression)
const dracoLoader = new DRACOLoader();
dracoLoader.setDecoderPath('https://unpkg.com/three@0.160.0/examples/jsm/libs/draco/gltf/');

            function loadModelIntoPreview(url) {
                initThree(); 
                
                const loadingEl = document.getElementById('canvas-loading');
                if (loadingEl) {
                    loadingEl.style.display = 'flex';
                    loadingEl.innerHTML = `
                        <div class="text-center">
                            <div class="inline-block w-8 h-8 border-4 border-teal-500 border-t-transparent rounded-full animate-spin mb-2"></div>
                            <p class="text-xs text-slate-300">Memuat model 3D...</p>
                        </div>
                    `;
                }

                // Timeout fallback — cegah stuck loading selamanya
                const loadTimeout = setTimeout(() => {
                    if (loadingEl && loadingEl.style.display !== 'none') {
                        loadingEl.innerHTML = `
                            <div class="text-center bg-white p-3 rounded-xl shadow-lg border border-red-100 max-w-[90%] mx-auto">
                                <p class="text-red-500 text-[11px] font-bold mb-2">Timeout memuat model 3D.<br>File mungkin terlalu besar atau format tidak didukung.</p>
                                <button type="button" onclick="document.getElementById('canvas-loading').style.display='none'" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded text-[10px] font-bold transition">Tutup</button>
                            </div>`;
                    }
                }, 30000); // 30 detik timeout

                if (previewModel) { scene.remove(previewModel); previewModel = null; }
                if (mixer) { mixer.stopAllAction(); mixer = null; }
                if (pivotGroup) { scene.remove(pivotGroup); pivotGroup = null; }

                const loader = new GLTFLoader(); 
                loader.setDRACOLoader(dracoLoader);
                
                // Bypass KHR extension agar tidak crash untuk model lawas
                loader.register(function () {
                    return { 
                        name: 'KHR_materials_pbrSpecularGlossiness', 
                        extendMaterialParams: function () { return Promise.resolve(); } 
                    };
                });

                loader.load(url, (gltf) => {
                    clearTimeout(loadTimeout);
                    
                    previewModel = gltf.scene;
                    previewModel.updateMatrixWorld(true);

                    previewModel.traverse((node) => {
                        if (node.isMesh || node.isSkinnedMesh) {
                            if (!node.isSkinnedMesh) {
                                node.castShadow = true; 
                                node.receiveShadow = true;
                            }
                            if (node.material) {
                                const mats = Array.isArray(node.material) ? node.material : [node.material];
                                mats.forEach(mat => {
                                    if (mat) mat.needsUpdate = true;
                                });
                            }
                        }
                    });

                    const box = new THREE.Box3().setFromObject(previewModel);
                    const size = box.getSize(new THREE.Vector3());
                    const center = box.getCenter(new THREE.Vector3());
                    
                    let maxDim = Math.max(size.x, size.y, size.z);
                    if (!maxDim || !isFinite(maxDim) || maxDim === 0) maxDim = 1;
                    const norm = 1.2 / maxDim;
                    
                    previewModel.scale.setScalar(norm);
                    
                    const bottomY = isFinite(box.min.y) ? box.min.y * norm : 0;
                    const centerX = isFinite(center.x) ? center.x * norm : 0;
                    const centerZ = isFinite(center.z) ? center.z * norm : 0;
                    previewModel.position.set(-centerX, -bottomY, -centerZ);
                    
                    previewModel.userData = { _baseScale: norm, _bottomY: -bottomY };

                    window.orbitState.active = false; 
                    window.orbitState.angle = 0;
                    const btnOrbit = document.getElementById('btn-orbit');
                    if (btnOrbit) {
                        btnOrbit.innerHTML = '<i class="bi bi-play-circle" id="orbit-icon"></i> Mulai Orbit';
                    }

                    pivotGroup = new THREE.Group(); 
                    pivotGroup.add(previewModel); 
                    scene.add(pivotGroup);

                    allClips = gltf.animations || [];
                    const panel = document.getElementById('anim-clip-panel');
                    if (allClips.length > 0) {
                        mixer = new THREE.AnimationMixer(previewModel);
                        activeAction = mixer.clipAction(allClips[0]); 
                        activeAction.play();
                        const sel = document.getElementById('anim-clip-select');
                        if (sel) sel.innerHTML = allClips.map((c, i) => `<option value="${i}">${c.name || 'Clip ' + (i+1)}</option>`).join('');
                        if (panel) panel.style.display = '';
                    } else {
                        if (panel) panel.style.display = 'none';
                    }

                    window.applyTransformToModel();
                    if (loadingEl) loadingEl.style.display = 'none';
                    
                }, undefined, (err) => {
                    clearTimeout(loadTimeout);
                    console.error('Error load 3D:', err);
                    if (loadingEl) {
                        loadingEl.innerHTML = `
                            <div class="text-center bg-white p-3 rounded-xl shadow-lg border border-red-100 max-w-[90%] mx-auto">
                                <p class="text-red-500 text-[11px] font-bold mb-2">Gagal memuat model 3D.<br>Pastikan format file valid (.glb).</p>
                                <button type="button" onclick="document.getElementById('canvas-loading').style.display='none'" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded text-[10px] font-bold transition">Tutup</button>
                            </div>`;
                    }
                });
            }


/** Apply form values → 3D model */
window.applyTransformFromForm = () => {
    state.position = [
        parseFloat(document.getElementById('pos-x').value) || 0,
        parseFloat(document.getElementById('pos-y').value) || 0,
        parseFloat(document.getElementById('pos-z').value) || 0,
    ];
    state.rotation = [
        parseFloat(document.getElementById('rot-x').value) || 0,
        parseFloat(document.getElementById('rot-y').value) || 0,
        parseFloat(document.getElementById('rot-z').value) || 0,
    ];
    applyTransformToModel();
};

function applyTransformToModel() {
    if (!previewModel) return;
    const baseY = previewModel.userData._bottomY || 0;

    // Posisi & rotasi pada previewModel (lokal di dalam pivotGroup)
    previewModel.position.set(
        state.position[0],
        state.position[1] + baseY,
        state.position[2]
    );
    previewModel.rotation.set(
        THREE.MathUtils.degToRad(state.rotation[0]),
        THREE.MathUtils.degToRad(state.rotation[1]),
        THREE.MathUtils.degToRad(state.rotation[2])
    );
    const base = previewModel.userData._baseScale || 1;
    previewModel.scale.setScalar(state.scale * base);

    // Saat tidak orbit: pivotGroup ikut posisi state
    if (!orbitState.active && pivotGroup) {
        pivotGroup.position.set(0, 0, 0);
        pivotGroup.rotation.set(0, 0, 0);
    }
}

/** Read 3D model transform → update form fields */
function syncFormFromModel() {
    if (!previewModel) return;
    const p      = previewModel.position;
    const bottomY = previewModel.userData._bottomY || 0;

    document.getElementById('pos-x').value = p.x.toFixed(2);
    document.getElementById('pos-y').value = (p.y - bottomY).toFixed(2);
    document.getElementById('pos-z').value = p.z.toFixed(2);

    const r = previewModel.rotation;
    document.getElementById('rot-x').value = THREE.MathUtils.radToDeg(r.x).toFixed(1);
    document.getElementById('rot-y').value = THREE.MathUtils.radToDeg(r.y).toFixed(1);
    document.getElementById('rot-z').value = THREE.MathUtils.radToDeg(r.z).toFixed(1);
}

window.resetTransform = () => {
    state.position = [0, 0, 0];
    state.rotation = [0, 0, 0];
    state.scale = 1;
    document.getElementById('pos-x').value = 0;
    document.getElementById('pos-y').value = 0;
    document.getElementById('pos-z').value = 0;
    document.getElementById('rot-x').value = 0;
    document.getElementById('rot-y').value = 0;
    document.getElementById('rot-z').value = 0;
    document.getElementById('scale-slider').value = 1;
    document.getElementById('scale-display').textContent = '1.00';
    applyTransformToModel();
};

// Scale slider
document.getElementById('scale-slider').addEventListener('input', function () {
    state.scale = parseFloat(this.value);
    document.getElementById('scale-display').textContent = state.scale.toFixed(2);
    applyTransformToModel();
});

// ─── STEP NAVIGATION ─────────────────────────────────────────────────────────
window.goToStep = (target) => {
    // Guards
    if (target === 2 && !state.markerId) return;
    if (target === 3 && !isStep2Complete()) return;
    if (target === 4) populateGenerateReview();

    // Tampilkan panel DULU agar canvas punya clientWidth/clientHeight nyata
    document.querySelectorAll('.step-panel').forEach(p => p.classList.remove('active'));
    document.getElementById(`step-${target}`).classList.add('active');

    // Update wizard header
    for (let i = 1; i <= 4; i++) {
        const el = document.getElementById(`wiz-${i}`);
        el.classList.remove('active', 'done');
        if (i < target) el.classList.add('done'), el.querySelector('.num').innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>';
        else if (i === target) el.classList.add('active'), el.querySelector('.num').textContent = i;
        else el.querySelector('.num').textContent = i;

        if (i < 4) {
            const conn = document.getElementById(`conn-${i}`);
            if (conn) conn.classList.toggle('done', i < target);
        }
    }
    state.step = target;

    // Step 3: init/resize Three.js SETELAH panel visible + browser reflow selesai
    if (target === 3) {
        requestAnimationFrame(() => {
            enterStep3();
        });
    }
};

// ─── STEP 1: MARKER ──────────────────────────────────────────────────────────
const markerInput = document.getElementById('marker-file-input');

// Drag & drop
const markerDZ = document.getElementById('marker-drop-zone');
markerDZ.addEventListener('dragover', e => { e.preventDefault(); markerDZ.classList.add('border-teal-500', 'bg-teal-50'); });
markerDZ.addEventListener('dragleave', () => markerDZ.classList.remove('border-teal-500', 'bg-teal-50'));
markerDZ.addEventListener('drop', e => {
    e.preventDefault(); markerDZ.classList.remove('border-teal-500', 'bg-teal-50');
    const file = e.dataTransfer.files[0];
    if (file) handleMarkerFile(file);
});

markerInput.addEventListener('change', () => {
    if (markerInput.files[0]) handleMarkerFile(markerInput.files[0]);
});

async function handleMarkerFile(file) {
    state.markerImageUrl = URL.createObjectURL(file);
    document.getElementById('marker-img-preview').src = state.markerImageUrl;
    document.getElementById('marker-fname').textContent = file.name;
    document.getElementById('marker-drop-zone').classList.add('d-none');
    document.getElementById('marker-after-upload').classList.remove('d-none');
    document.getElementById('mstatus-uploading').classList.remove('d-none');

    const fd = new FormData();
    fd.append('image', file);
    fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);

    try {
        const res = await fetch('/api/markers', {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: fd,
        });
        if (!res.ok) {
            const err = await res.json().catch(() => ({}));
            throw new Error(err.message || 'HTTP ' + res.status);
        }
        const data = await res.json();
        state.markerId = data.marker_id;

        document.getElementById('mstatus-uploading').classList.add('d-none');
        document.getElementById('mstatus-processing').classList.remove('d-none');
        startMarkerPolling();
    } catch (e) {
        console.error('Marker upload error:', e);
        document.getElementById('mstatus-uploading').classList.add('d-none');
        document.getElementById('mstatus-failed').classList.remove('d-none');
    }
}

function startMarkerPolling() {
    state.markerPollingTimer = setInterval(async () => {
        try {
            const res  = await fetch(`/api/marker/${state.markerId}`);
            const data = await res.json();
            
            if (data.progress) {
                const bar = document.getElementById('marker-progbar');
                const txt = document.getElementById('prog-text');
                const eta = document.getElementById('eta-text');
                
                bar.style.width = data.progress + '%';
                txt.textContent = data.progress + '%';
                if(data.eta) eta.textContent = 'Estimasi: ' + data.eta + ' detik lagi';
            }

            if (data.status === 'ready') {
                clearInterval(state.markerPollingTimer);
                document.getElementById('mstatus-processing').classList.add('d-none');
                document.getElementById('mstatus-ready').classList.remove('d-none');
                document.getElementById('btn-next-1').disabled = false;
                state.markerStatus = 'ready';
            } else if (data.status === 'failed') {
                clearInterval(state.markerPollingTimer);
                document.getElementById('mstatus-processing').classList.add('d-none');
                document.getElementById('mstatus-failed').classList.remove('d-none');
            }
        } catch (_) {}
    }, 3000);
}

window.resetMarkerUpload = () => {
    clearInterval(state.markerPollingTimer);
    state.markerId = null; state.markerStatus = null; state.markerImageUrl = null;
    document.getElementById('marker-after-upload').classList.add('d-none');
    document.getElementById('marker-drop-zone').classList.remove('d-none');
    ['mstatus-uploading','mstatus-processing','mstatus-ready','mstatus-failed'].forEach(id => {
        document.getElementById(id).classList.add('d-none');
    });
    document.getElementById('btn-next-1').disabled = true;
    markerInput.value = '';
    document.getElementById('marker-fname').textContent = '';
    document.querySelectorAll('.marker-card').forEach(c => c.classList.remove('selected'));
};

async function loadMarkerLibrary() {
    const grid = document.getElementById('marker-grid');
    grid.innerHTML = '<div class="text-xs text-slate-400 col-span-full">Memuat marker siap pakai...</div>';

    try {
        const res = await fetch('/api/markers');
        if (!res.ok) throw new Error('Gagal memuat marker');
        const markers = await res.json();
        if (!markers.length) {
            grid.innerHTML = '<div class="text-xs text-slate-400 col-span-full">Belum ada marker siap pakai.</div>';
            return;
        }

        grid.innerHTML = markers.map(marker => `
            <div class="marker-card relative flex flex-col items-center gap-2 p-2 bg-slate-50 border border-slate-200 rounded-xl cursor-pointer hover:border-teal-500 group" data-id="${marker.id}" data-image-url="${marker.image_url}">
                
                <button type="button" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition shadow-md hover:bg-red-600 z-10" onclick="event.stopPropagation(); deleteMarker(${marker.id})" title="Hapus Marker">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                </button>

                <img src="${marker.image_url}" alt="Marker" class="w-full aspect-square object-cover rounded-lg border border-white">
                <div class="text-[10px] font-bold text-slate-600 truncate w-full text-center">Marker #${marker.id}</div>
            </div>
        `).join('');

        grid.querySelectorAll('.marker-card').forEach(card => {
            card.addEventListener('click', () => {
                selectExistingMarker(card.dataset.id, card.dataset.imageUrl);
            });
        });
    } catch (e) {
        grid.innerHTML = '<div class="text-red-500 text-xs col-span-full">Gagal memuat marker.</div>';
    }
}

// TAMBAHKAN FUNGSI INI DI BAWAH loadMarkerLibrary()
window.deleteMarker = async (markerId) => {
    if (!confirm('Apakah Anda yakin ingin menghapus marker ini?')) return;
    
    try {
        const res = await fetch(`/api/markers/${markerId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        if (res.ok) {
            // Jika marker yang sedang dipilih dihapus, reset state
            if (state.markerId === markerId) {
                resetMarkerUpload();
            }
            // Muat ulang daftar marker
            loadMarkerLibrary();
        } else {
            alert('Gagal menghapus marker. Pastikan Anda memiliki akses.');
        }
    } catch (e) {
        console.error('Delete error:', e);
        alert('Terjadi kesalahan saat menghapus marker.');
    }
};

window.selectExistingMarker = (markerId, imageUrl) => {
    state.markerId = Number(markerId);
    state.markerStatus = 'ready';
    state.markerImageUrl = imageUrl;

    document.getElementById('marker-img-preview').src = imageUrl;
    document.getElementById('marker-fname').textContent = `Marker #${markerId}`;
    document.getElementById('marker-drop-zone').classList.add('d-none');
    document.getElementById('marker-after-upload').classList.remove('d-none');
    document.getElementById('mstatus-uploading').classList.add('d-none');
    document.getElementById('mstatus-processing').classList.add('d-none');
    document.getElementById('mstatus-ready').classList.remove('d-none');
    document.getElementById('mstatus-failed').classList.add('d-none');
    document.getElementById('btn-next-1').disabled = false;

    document.querySelectorAll('.marker-card').forEach(c => c.classList.toggle('selected', c.dataset.id === String(markerId)));
};

loadMarkerLibrary();

// ─── STEP 2: MODE ────────────────────────────────────────────────────────────
window.switchMode = (mode) => {
    state.mode = mode;
    ['template', 'gltf', 'blend'].forEach(m => {
        const tab = document.getElementById('tab-' + m);
        const panel = document.getElementById('panel-' + m);
        
        if (m === mode) {
            // Tambahkan if (tab) dan if (panel) agar tidak error jika elemen tidak ada
            if (tab) tab.classList.add('active', 'text-teal-600', 'border-teal-500');
            if (panel) {
                panel.classList.add('active');
                panel.style.display = 'block';
            }
        } else {
            if (tab) tab.classList.remove('active', 'text-teal-600', 'border-teal-500');
            if (panel) {
                panel.classList.remove('active');
                panel.style.display = 'none';
            }
        }
    });
    checkStep2();
};

function isStep2Complete() {
    if (state.mode === 'template') return !!state.selectedTemplateId;
    if (state.mode === 'gltf')     return !!state.gltfFile;
    if (state.mode === 'blend')    return state.blendStatus === 'done';
    return false;
}

function checkStep2() {
    document.getElementById('btn-next-2').disabled = !isStep2Complete();
}

// GLTF upload
const gltfInput = document.getElementById('gltf-file-input');
const gltfDZ = document.getElementById('gltf-drop-zone');

gltfDZ.addEventListener('dragover', e => { e.preventDefault(); gltfDZ.classList.add('border-teal-500', 'bg-teal-50'); });
gltfDZ.addEventListener('dragleave', () => gltfDZ.classList.remove('border-teal-500', 'bg-teal-50'));
gltfDZ.addEventListener('drop', e => {
    e.preventDefault(); gltfDZ.classList.remove('border-teal-500', 'bg-teal-50');
    if(e.dataTransfer.files[0]) handleGltf(e.dataTransfer.files[0]);
});

gltfInput.addEventListener('change', () => {
    if (gltfInput.files[0]) handleGltf(gltfInput.files[0]);
});

function handleGltf(file) {
    state.gltfFile = file;
    state.gltfBlob = URL.createObjectURL(file);
    document.getElementById('gltf-fname').textContent = file.name;
    document.getElementById('gltf-drop-zone').classList.add('d-none');
    document.getElementById('gltf-chosen').classList.remove('d-none');
    checkStep2();
}

window.resetGltf = () => {
    state.gltfFile = null; state.gltfBlob = null;
    document.getElementById('gltf-drop-zone').classList.remove('d-none');
    document.getElementById('gltf-chosen').classList.add('d-none');
    gltfInput.value = '';
    checkStep2();
};

// BLEND upload
const blendInput = document.getElementById('blend-file-input');
blendInput.addEventListener('change', async () => {
    const file = blendInput.files[0];
    if (!file) return;

    document.getElementById('blend-drop-zone').classList.add('d-none');
    document.getElementById('blend-after-upload').classList.remove('d-none');
    document.getElementById('blend-uploading').classList.remove('d-none');
    state.blendStatus = 'uploading';

    const fd = new FormData();
    fd.append('model', file);
    fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);

    try {
        const res = await fetch('/api/blend-upload', {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: fd,
        });

        if (res.status === 413) {
            throw new Error('File terlalu besar. Naikan limit di server.');
        }
        if (!res.ok) {
            const err = await res.json().catch(() => ({}));
            throw new Error(err.message || `Server error HTTP ${res.status}`);
        }

        const data = await res.json();
        state.blendProjectId = data.project_id;

        document.getElementById('blend-uploading').classList.add('d-none');
        document.getElementById('blend-processing').classList.remove('d-none');
        state.blendStatus = 'processing';
        startBlendPolling();
    } catch (e) {
        console.error('Blend upload error:', e);
        document.getElementById('blend-uploading').classList.add('d-none');
        document.getElementById('blend-failed').classList.remove('d-none');
        document.getElementById('blend-error-msg').textContent = e.message || 'Upload gagal';
        state.blendStatus = 'failed';
    }
});

function startBlendPolling() {
    let pollCount = 0;
    const MAX_POLLS = 90;

    state.blendPollingTimer = setInterval(async () => {
        pollCount++;
        if (pollCount > MAX_POLLS) {
            clearInterval(state.blendPollingTimer);
            state.blendStatus = 'failed';
            document.getElementById('blend-processing').classList.add('d-none');
            document.getElementById('blend-failed').classList.remove('d-none');
            document.getElementById('blend-error-msg').textContent = 'Timeout: konversi melebihi 6 menit.';
            return;
        }

        try {
            const res  = await fetch(`/api/blend-status/${state.blendProjectId}`, {
                headers: { 'Accept': 'application/json' }
            });
            if (!res.ok) return;

            const data = await res.json();

            if (data.status === 'ready' && data.model_url) {
                clearInterval(state.blendPollingTimer);
                state.blendGlbUrl = data.model_url;
                state.blendStatus = 'done';
                document.getElementById('blend-processing').classList.add('d-none');
                document.getElementById('blend-done').classList.remove('d-none');
                checkStep2();

            } else if (data.status === 'failed') {
                clearInterval(state.blendPollingTimer);
                state.blendStatus = 'failed';
                document.getElementById('blend-processing').classList.add('d-none');
                document.getElementById('blend-failed').classList.remove('d-none');
                document.getElementById('blend-error-msg').textContent = 'Konversi Blender gagal di server.';
            }
        } catch(_) {}
    }, 4000);
}

window.resetBlend = () => {
    clearInterval(state.blendPollingTimer);
    state.blendProjectId = null; state.blendGlbUrl = null; state.blendStatus = null;
    document.getElementById('blend-drop-zone').classList.remove('d-none');
    document.getElementById('blend-after-upload').classList.add('d-none');
    ['blend-uploading','blend-processing','blend-done','blend-failed'].forEach(id => {
        document.getElementById(id).classList.add('d-none');
    });
    blendInput.value = '';
    checkStep2();
};

// ─── STEP 3: PREVIEW ─────────────────────────────────────────────────────────
function enterStep3() {
    if (!renderer) initThree();

    document.getElementById('preview-marker-thumb').src = state.markerImageUrl;

    loadMarkerPlane(state.markerImageUrl);

    let modelUrl = null;
    if (state.mode === 'template' && state.selectedTemplateUrl) {
        modelUrl = state.selectedTemplateUrl;
    } else if (state.mode === 'gltf' && state.gltfBlob) {
        modelUrl = state.gltfBlob;
    } else if (state.mode === 'blend' && state.blendGlbUrl) {
        modelUrl = state.blendGlbUrl;
    }

    if (modelUrl) {
        loadModelIntoPreview(modelUrl);
    } else {
        document.getElementById('canvas-loading').innerHTML = '<p class="text-xs text-slate-400">Tidak ada model untuk di-preview.</p>';
    }
}

// ── Marker plane 3D ──────────────────────────────────────────────────────────
function loadMarkerPlane() {
    if (markerPlane) { scene.remove(markerPlane); markerPlane = null; }

    const createLabelSprite = (text, color) => {
        const canvas = document.createElement('canvas');
        canvas.width = 256; canvas.height = 128;
        const ctx = canvas.getContext('2d');
        ctx.font = '700 48px Arial'; ctx.fillStyle = color; ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
        ctx.fillText(text, canvas.width / 2, canvas.height / 2);
        const texture = new THREE.CanvasTexture(canvas); texture.colorSpace = THREE.SRGBColorSpace;
        const material = new THREE.SpriteMaterial({ map: texture, transparent: true, depthTest: false, depthWrite: false });
        const sprite = new THREE.Sprite(material); sprite.scale.set(0.3, 0.15, 1);
        return sprite;
    };

    const markerGroup = new THREE.Group();
    markerGroup.position.set(0, 0.001, 0);

    const armLen = 0.4; const armW = 0.05;
    const plusMat = new THREE.MeshBasicMaterial({ color: 0xffffff, transparent: true, opacity: 0.95, depthTest: false });

    const hMesh = new THREE.Mesh(new THREE.PlaneGeometry(armLen * 2, armW), plusMat); hMesh.rotation.x = -Math.PI / 2;
    const vMesh = new THREE.Mesh(new THREE.PlaneGeometry(armW, armLen * 2), plusMat); vMesh.rotation.x = -Math.PI / 2;

    const dot = new THREE.Mesh(new THREE.CircleGeometry(armW * 0.8, 24), new THREE.MeshBasicMaterial({ color: 0x0d9488, transparent: true, opacity: 1, depthTest: false }));
    dot.rotation.x = -Math.PI / 2; dot.position.y = 0.001;

    const axisMaterialX = new THREE.LineBasicMaterial({ color: 0xef4444, transparent: true, opacity: 0.9, depthTest: false });
    const axisMaterialY = new THREE.LineBasicMaterial({ color: 0x22c55e, transparent: true, opacity: 0.9, depthTest: false });
    const axisMaterialZ = new THREE.LineBasicMaterial({ color: 0x3b82f6, transparent: true, opacity: 0.9, depthTest: false });

    const xLine = new THREE.Line(new THREE.BufferGeometry().setFromPoints([new THREE.Vector3(-armLen, 0.001, 0), new THREE.Vector3(armLen, 0.001, 0)]), axisMaterialX);
    const zLine = new THREE.Line(new THREE.BufferGeometry().setFromPoints([new THREE.Vector3(0, 0.001, -armLen), new THREE.Vector3(0, 0.001, armLen)]), axisMaterialZ);
    const yLine = new THREE.Line(new THREE.BufferGeometry().setFromPoints([new THREE.Vector3(0, 0.001, 0), new THREE.Vector3(0, 0.4, 0)]), axisMaterialY);

    const xLabel = createLabelSprite('X', '#ef4444'); xLabel.position.set(armLen + 0.15, 0.01, 0);
    const zLabel = createLabelSprite('Z', '#3b82f6'); zLabel.position.set(0, 0.01, armLen + 0.15);
    const yLabel = createLabelSprite('Y', '#22c55e'); yLabel.position.set(0, 0.45, 0);

    markerGroup.add(hMesh, vMesh, dot, xLine, zLine, yLine, xLabel, zLabel, yLabel);
    markerGroup.name = 'markerGroup'; markerPlane = markerGroup;
    scene.add(markerGroup);

    camera.position.set(0, 2.5, 3.5);
    orbitControls.target.set(0, 0.3, 0); orbitControls.update();
}

// ─── STEP 4: GENERATE REVIEW ─────────────────────────────────────────────────
function populateGenerateReview() {
    document.getElementById('gen-marker-img').src = state.markerImageUrl;

    const typeLabels = { template: 'Template', gltf: 'GLB/GLTF', blend: 'Blend (dikonversi)' };
    document.getElementById('gen-type').textContent   = typeLabels[state.mode] || state.mode;

    let modelName = '—';
    if (state.mode === 'template') modelName = state.selectedTemplateName;
    else if (state.mode === 'gltf')    modelName = state.gltfFile?.name;
    else if (state.mode === 'blend')   modelName = `Project #${state.blendProjectId}`;
    document.getElementById('gen-model').textContent = modelName;

    const s = parseFloat(document.getElementById('scale-slider').value);
    state.scale = s;
    state.position = [
        parseFloat(document.getElementById('pos-x').value)||0,
        parseFloat(document.getElementById('pos-y').value)||0,
        parseFloat(document.getElementById('pos-z').value)||0,
    ];
    state.rotation = [
        parseFloat(document.getElementById('rot-x').value)||0,
        parseFloat(document.getElementById('rot-y').value)||0,
        parseFloat(document.getElementById('rot-z').value)||0,
    ];

    document.getElementById('gen-scale').textContent    = s.toFixed(2);
    document.getElementById('gen-position').textContent = `X: ${state.position[0]}, Y: ${state.position[1]}, Z: ${state.position[2]}`;
    document.getElementById('gen-rotation').textContent = `X: ${state.rotation[0]}°, Y: ${state.rotation[1]}°, Z: ${state.rotation[2]}°`;
}

// ── Orbit controls ───────────────────────────────────────────────────────────
window.toggleOrbit = () => {
    orbitState.active = !orbitState.active;
    const btn  = document.getElementById('btn-orbit');
    const icon = document.getElementById('orbit-icon');

    if (orbitState.active) {
        icon.className  = 'bi bi-pause-circle';
        btn.innerHTML   = '<i class="bi bi-pause-circle" id="orbit-icon"></i> Pause Orbit';
        btn.classList.add('bg-teal-50', 'text-teal-700', 'border-teal-500');
        
        orbitState.angle = 0;
        if (pivotGroup) {
            pivotGroup.position.set(0, 0, orbitState.radius);
            pivotGroup.rotation.y = 0;
        }
    } else {
        icon.className  = 'bi bi-play-circle';
        btn.innerHTML   = '<i class="bi bi-play-circle" id="orbit-icon"></i> Mulai Orbit';
        btn.classList.remove('bg-teal-50', 'text-teal-700', 'border-teal-500');

        if (pivotGroup) {
            pivotGroup.position.set(0, 0, 0);
            pivotGroup.rotation.set(0, 0, 0);
        }
    }
};

window.toggleOrbitDir = () => {
    orbitState.dir *= -1;
    document.getElementById('orbit-dir-icon').style.transform = orbitState.dir === 1 ? '' : 'scaleX(-1)';
};

document.getElementById('orbit-speed')?.addEventListener('input', function () {
    orbitState.speed = parseFloat(this.value);
    document.getElementById('orbit-speed-val').textContent = this.value + '×';
});

document.getElementById('orbit-radius')?.addEventListener('input', function () {
    orbitState.radius = parseFloat(this.value);
    document.getElementById('orbit-radius-val').textContent = this.value;
});

// ── Switch animasi clip ───────────────────────────────────────────────────────
window.switchAnimClip = (index) => {
    if (!mixer || !allClips[index]) return;
    if (activeAction) activeAction.fadeOut(0.3);
    activeAction = mixer.clipAction(allClips[index]);
    activeAction.reset().fadeIn(0.3).play();
};

window.submitGenerate = () => {
    const titleInput = document.getElementById('marker-project-title');
    if (!titleInput || !titleInput.value.trim()) {
        alert('Nama / Judul AR wajib diisi sebelum men-generate!');
        if(titleInput) titleInput.focus();
        return;
    }

    // Suntikkan teks nama ke hidden form input
    document.getElementById('form-title').value = titleInput.value.trim();

    const baseScale = previewModel?.userData._baseScale || 1;
    const bottomY   = previewModel?.userData._bottomY   || 0;
    const scaleAR = state.scale * baseScale;
    const posYAR = state.position[1] - bottomY;

    document.getElementById('form-marker-id').value  = state.markerId;
    document.getElementById('form-type').value        = state.mode;
    document.getElementById('form-scale').value        = scaleAR;
    document.getElementById('form-position').value     = JSON.stringify([state.position[0],posYAR,state.position[2],]);
    document.getElementById('form-rotation').value     = JSON.stringify(state.rotation);
    document.getElementById('form-orbit-active').value = orbitState.active ? '1' : '0';
    document.getElementById('form-orbit-speed').value  = orbitState.speed;
    document.getElementById('form-orbit-radius').value = orbitState.radius;
    document.getElementById('form-orbit-dir').value    = orbitState.dir;
    
    const clipSel = document.getElementById('anim-clip-select');
    let animClipValue = '*';
    if (Array.isArray(allClips) && allClips.length === 1) {
        animClipValue = allClips[0].name || '*';
    } else if (clipSel) {
        const val = clipSel.value || '*';
        if (val === '*') {
            animClipValue = '*';
        } else {
            const idx = parseInt(val);
            if (!isNaN(idx) && Array.isArray(allClips) && allClips[idx] && allClips[idx].name) {
                animClipValue = allClips[idx].name;
            } else {
                animClipValue = val;
            }
        }
    }
    document.getElementById('form-anim-clip').value = animClipValue;

    if (state.mode === 'template') {
        document.getElementById('form-template-id').value = state.selectedTemplateId;
        const configContainer = document.getElementById('tpl-config-fields');
        const configHidden    = document.getElementById('form-config-fields');
        if(configContainer && configHidden) {
            configHidden.innerHTML = '';
            configContainer.querySelectorAll('input[id^="tpl-field-"]').forEach(inp => {
                const key = inp.id.replace('tpl-field-', '');
                const h   = document.createElement('input');
                h.type = 'hidden'; h.name = `config[${key}]`; h.value = inp.value;
                configHidden.appendChild(h);
            });
        }
    } else if (state.mode === 'gltf' && state.gltfFile) {
        const dt = new DataTransfer();
        dt.items.add(state.gltfFile);
        document.getElementById('form-model-file').files = dt.files;
    } else if (state.mode === 'blend') {
        document.getElementById('form-blend-project-id').value = state.blendProjectId;
    }

    // ─── EKSEKUSI AJAX UPLOAD & REDIRECT ───
    const form = document.getElementById('generate-form');
    const formData = new FormData(form);
    
    const modal = document.getElementById('gen-progress-modal');
    const progressBar = document.getElementById('gen-progress-bar');
    const progressPercent = document.getElementById('gen-progress-percent');
    
    // Tampilkan modal progress
    modal.classList.remove('hidden');
    
    const xhr = new XMLHttpRequest();
    xhr.open('POST', form.action, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    
    // Track persentase unggahan data / file glb
    xhr.upload.addEventListener('progress', (e) => {
        if (e.lengthComputable) {
            const percent = Math.round((e.loaded / e.total) * 92); // Sisakan 8% untuk proses kompilasi server
            progressBar.style.width = percent + '%';
            progressPercent.textContent = percent + '%';
        }
    });
    
    xhr.onload = () => {
        if (xhr.status >= 200 && xhr.status < 300) {
            progressBar.style.width = '100%';
            progressPercent.textContent = '100%';
            // Sukses! Alihkan halaman langsung ke dashboard user
            setTimeout(() => {
                window.location.href = JSON.parse(xhr.responseText).redirect_url;
            }, 500);
        } else {
            modal.classList.add('hidden');
            alert('Gagal membuat AR. Mohon periksa kembali file Anda atau hubungi admin.');
        }
    };
    
    xhr.onerror = () => {
        modal.classList.add('hidden');
        alert('Koneksi jaringan terputus.');
    };
    
    xhr.send(formData);
};

// ─── LIBRARY 3D PACK (Pengganti Template) ────────────────────────────────────

window.library3DData = @json($library3dList ?? \App\Models\ArAsset::all() ?? []);
window.modelStates = {}; 
window.abortControllers = {};

// BATAS AUTO DOWNLOAD (Dalam MB). Ubah angka 3 ini sesuai selera Anda.
const MAX_AUTO_DOWNLOAD_MB = 3; 

// Fungsi format byte menjadi MB/KB
function formatBytes(bytes) {
    if (!bytes || bytes === 0) return '0 B';
    const k = 1024, sizes = ['B', 'KB', 'MB', 'GB'], i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
}

// Fungsi cek ukuran file tanpa mengunduh isinya (menggunakan HEAD)
async function checkFileSize(id, url) {
    try {
        const res = await fetch(url, { method: 'HEAD' });
        const size = parseInt(res.headers.get('content-length') || '0', 10);
        window.modelStates[id].total = size;
        
        window.modelStates[id].state = 'idle'; // Selalu set idle dulu
        updateItemPreview(id);
        
        // AUTO DOWNLOAD JIKA KECIL (Agar jadi Blob dan tidak macet di Step 3)
        if (size > 0 && size <= (MAX_AUTO_DOWNLOAD_MB * 1024 * 1024)) {
            window.toggleDownload(id, null); 
        }
    } catch(e) {
        window.modelStates[id].state = 'idle';
        updateItemPreview(id);
    }
}

// Fungsi Download & Pause manual (Stream)
window.toggleDownload = async (id, event) => {
    if(event) { event.preventDefault(); event.stopPropagation(); }
    
    const mState = window.modelStates[id];
    
    if (mState.state === 'idle' || mState.state === 'paused') {
        mState.state = 'downloading';
        updateItemPreview(id);
        
        window.abortControllers[id] = new AbortController();
        try {
            const res = await fetch(mState.validUrl, { signal: window.abortControllers[id].signal });
            const total = parseInt(res.headers.get('content-length') || mState.total || '0', 10);
            mState.total = total;
            
            const reader = res.body.getReader();
            let downloaded = 0;
            let chunks = [];
            
            while(true) {
                const {done, value} = await reader.read();
                if(done) break;
                chunks.push(value);
                downloaded += value.length;
                mState.downloaded = downloaded;
                if(total) mState.progress = Math.round((downloaded/total) * 100);
                updateItemPreview(id);
            }
            
            // PERBAIKAN: Identitas file ditambahkan di sini
            const blob = new Blob(chunks, { type: 'model/gltf-binary' });
            mState.blobUrl = URL.createObjectURL(blob);
            mState.state = 'loaded';
            updateItemPreview(id);
            
        } catch(e) {
            if (e.name === 'AbortError') mState.state = 'paused';
            else mState.state = 'idle';
            updateItemPreview(id);
        }
    } else if (mState.state === 'downloading') {
        if(window.abortControllers[id]) window.abortControllers[id].abort(); 
    }
};

// Fungsi update khusus untuk 1 kotak preview (mencegah kedip / re-render seluruh halaman)
function updateItemPreview(id) {
    const box = document.getElementById(`preview-box-${id}`);
    if(!box) return;
    const mState = window.modelStates[id];
    
    let html = '';
    if (mState.state === 'checking') {
        html = `<div class="text-[10px] text-slate-400 font-bold animate-pulse">Menghitung ukuran...</div>`;
    } else if (mState.state === 'loaded') {
        html = `<model-viewer src="${mState.blobUrl || mState.validUrl}" class="w-full h-full" disable-zoom disable-pan shadow-intensity="0" exposure="1" environment-image="neutral" auto-rotate></model-viewer>`;
    } else if (mState.state === 'idle') {
        html = `
        <div onclick="toggleDownload(${id}, event)" class="absolute inset-0 z-10 flex flex-col items-center justify-center bg-slate-100/90 cursor-pointer hover:bg-slate-200 transition-colors">
            <svg class="w-7 h-7 md:w-8 md:h-8 text-teal-500 mb-1 hover:scale-110 transition-transform drop-shadow-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            <span class="text-[9px] md:text-[10px] font-bold text-slate-700">${formatBytes(mState.total)}</span>
            <span class="text-[7px] md:text-[8px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Klik Unduh</span>
        </div>`;
    } else if (mState.state === 'paused') {
        html = `
        <div onclick="toggleDownload(${id}, event)" class="absolute inset-0 z-10 flex flex-col items-center justify-center bg-slate-100/90 cursor-pointer hover:bg-slate-200 transition-colors">
            <div class="relative flex items-center justify-center w-7 h-7 md:w-8 md:h-8 mb-1 opacity-60">
                <svg class="w-full h-full text-slate-300" viewBox="0 0 36 36"><path stroke-dasharray="100, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke="currentColor" stroke-width="3" fill="none" /></svg>
                <svg class="absolute inset-0 w-full h-full text-amber-500" viewBox="0 0 36 36">
                    <path stroke-dasharray="${mState.progress || 0}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round" />
                </svg>
                <div class="absolute inset-0 flex items-center justify-center text-amber-600 bg-amber-50 rounded-full w-4 h-4 md:w-5 md:h-5 m-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 ml-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" /></svg>
                </div>
            </div>
            <span class="text-[6px] md:text-[7px] font-bold text-amber-600 uppercase tracking-wider text-center px-1">Di-pause</span>
        </div>`;
    } else if (mState.state === 'downloading') {
        html = `
        <div onclick="toggleDownload(${id}, event)" class="absolute inset-0 z-10 flex flex-col items-center justify-center bg-slate-100/90 cursor-pointer hover:bg-slate-200 transition-colors" title="Klik untuk Pause">
            <div class="relative flex items-center justify-center w-7 h-7 md:w-8 md:h-8 mb-1">
                <svg class="w-full h-full text-slate-300" viewBox="0 0 36 36"><path stroke-dasharray="100, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke="currentColor" stroke-width="3" fill="none" /></svg>
                <svg class="absolute inset-0 w-full h-full text-teal-500 transition-all duration-200" viewBox="0 0 36 36">
                    <path stroke-dasharray="${mState.progress || 0}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round" />
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-[7px] md:text-[8px] font-bold text-teal-700">${mState.progress || 0}%</span>
                </div>
            </div>
            <span class="text-[6px] md:text-[7px] font-bold text-teal-600 tracking-widest text-center px-1">${formatBytes(mState.downloaded)} / ${formatBytes(mState.total)}</span>
        </div>`;
    }
    
    box.innerHTML = html;
}

// --- MULAI DARI SINI: LOGIKA TAB & FILTER YANG SUDAH DIBERSIHKAN ---

window.activeLibTab = 'model';

window.switchLibTab = (tab) => {
    window.activeLibTab = tab;
    const btnModel = document.getElementById('tab-lib-model');
    const btnAnimasi = document.getElementById('tab-lib-animasi');
    
    // Update style tombol
    btnModel.className = tab === 'model' ? 'px-4 py-1.5 rounded-md text-sm font-bold bg-white text-teal-600 shadow-sm transition-all' : 'px-4 py-1.5 rounded-md text-sm font-medium text-slate-500 hover:text-slate-700 transition-all';
    btnAnimasi.className = tab === 'animasi' ? 'px-4 py-1.5 rounded-md text-sm font-bold bg-white text-teal-600 shadow-sm transition-all' : 'px-4 py-1.5 rounded-md text-sm font-medium text-slate-500 hover:text-slate-700 transition-all';
    
    window.filter3DPack();
};

window.loadTemplateLibrary = () => {
    const grid = document.getElementById('template-grid');
    if (!grid) return;
    if (!window.library3DData || window.library3DData.length === 0) {
        grid.innerHTML = '<div class="text-sm text-slate-500 col-span-full text-center py-4">Belum ada objek 3D.</div>';
        return;
    }
    window.filter3DPack();
};

window.filter3DPack = () => {
    const query = document.getElementById('search-3d').value.toLowerCase();
    const filtered = window.library3DData.filter(item => {
        let rawUrl = item.path || item.model_url || item.file_path || '';
        let matchesSearch = (item.name || '').toLowerCase().includes(query);
        let isAnimasi = rawUrl.includes('3d_animasi'); 
        let matchesTab = window.activeLibTab === 'animasi' ? isAnimasi : !isAnimasi;
        
        return matchesSearch && matchesTab;
    });
    render3DPacks(filtered);
};

function render3DPacks(items) {
    const grid = document.getElementById('template-grid');
    if (!grid) return;
    if (items.length === 0) {
        grid.innerHTML = '<p class="text-xs text-slate-400 col-span-full text-center py-4">Objek 3D tidak ditemukan.</p>';
        return;
    }

    grid.innerHTML = items.map(item => {
        const isSelected = state.selectedTemplateId === item.id;
        
        let rawUrl = item.path || item.model_url || item.file_path || '';
        let validUrl = rawUrl ? ((rawUrl.startsWith('http') || rawUrl.startsWith('/')) ? rawUrl : '/' + rawUrl) : '';

        // Setup State Cek Download
        if (!window.modelStates[item.id]) {
            window.modelStates[item.id] = { state: 'checking', total: 0, progress: 0, validUrl: validUrl, blobUrl: null };
            checkFileSize(item.id, validUrl); 
        }

        return `
        <label class="flex flex-col bg-white border ${isSelected ? 'border-teal-500 ring-2 ring-teal-500 shadow-md' : 'border-slate-200'} rounded-xl cursor-pointer hover:border-teal-500 hover:shadow-md transition-all overflow-hidden" onclick="select3DPack(${item.id})">
            
            <div id="preview-box-${item.id}" class="h-24 md:h-32 bg-slate-100 relative flex items-center justify-center overflow-hidden">
            </div>
            
            <div class="p-2 border-t border-slate-100 flex items-start gap-1 pointer-events-none">
                <input type="radio" name="library3d" value="${item.id}" class="mt-0.5 text-teal-500 focus:ring-teal-500" ${isSelected ? 'checked' : ''}>
                <span class="text-[10px] md:text-xs font-bold text-slate-700 leading-tight line-clamp-2">${item.name}</span>
            </div>
        </label>
        `;
    }).join('');

    items.forEach(item => updateItemPreview(item.id));
}

window.select3DPack = (id) => {
    const selectedItem = window.library3DData.find(item => item.id === id);
    if (!selectedItem) return;

    let rawUrl = selectedItem.path || selectedItem.model_url || selectedItem.file_path || '';
    let validUrl = rawUrl ? ((rawUrl.startsWith('http') || rawUrl.startsWith('/')) ? rawUrl : '/' + rawUrl) : '';

    state.selectedTemplateId = id;
    state.selectedTemplateName = selectedItem.name;

    if (window.modelStates[id] && window.modelStates[id].state === 'loaded' && window.modelStates[id].blobUrl) {
        state.selectedTemplateUrl = window.modelStates[id].blobUrl;
    } else {
        if (window.modelStates[id] && window.modelStates[id].state !== 'loaded') {
            alert("Model sedang diunduh. Harap tunggu indikator selesai (warna hijau) sebelum ke Step 3.");
        }
        state.selectedTemplateUrl = validUrl;
    }

    // PERBAIKAN: Ganti border langsung via DOM. Tidak ada lagi re-render grid yang merusak Tab!
    document.querySelectorAll('#template-grid label').forEach(label => {
        label.classList.remove('border-teal-500', 'ring-2', 'ring-teal-500', 'shadow-md');
        label.classList.add('border-slate-200');
        const radio = label.querySelector('input[type="radio"]');
        if(radio) radio.checked = false;
    });

    const selectedLabel = document.querySelector(`label[onclick="select3DPack(${id})"]`);
    if (selectedLabel) {
        selectedLabel.classList.remove('border-slate-200');
        selectedLabel.classList.add('border-teal-500', 'ring-2', 'ring-teal-500', 'shadow-md');
        const radio = selectedLabel.querySelector('input[type="radio"]');
        if(radio) radio.checked = true;
    }
    
    checkStep2(); 
};

// Pastikan inisialisasi awal saat pertama kali load
document.addEventListener('DOMContentLoaded', () => {
    window.loadTemplateLibrary();
    window.switchMode('template');
});
</script>