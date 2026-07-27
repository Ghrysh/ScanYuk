<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>Journey - {{ $journey->status_text }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.4.0/model-viewer.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-grid-pattern {
            background-size: 50px 50px;
            background-image: 
                linear-gradient(to right, rgba(0,0,0,0.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(0,0,0,0.03) 1px, transparent 1px);
            background-color: #fafafa;
        }
    </style>
</head>
<body class="bg-grid-pattern text-slate-900 min-h-screen pb-12 relative flex flex-col">
    <!-- Animated background -->
    <div class="absolute inset-0 z-0 opacity-20 pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-teal-500 rounded-full mix-blend-screen filter blur-3xl animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-64 h-64 bg-blue-500 rounded-full mix-blend-screen filter blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
    </div>

    <!-- Header -->
    <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-xl border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('tamagotchi.index', $username) }}" class="text-slate-500 hover:text-slate-700 transition bg-slate-100 p-2 rounded-full border border-slate-200">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                </a>
                <h1 class="font-bold text-lg text-slate-800">Detail Journey</h1>
            </div>
            <!-- Bagikan Tombol Native Web Share API -->
            <button id="share-btn" onclick="shareJourney()" class="text-teal-400 bg-teal-500/10 hover:bg-teal-500/20 px-4 py-2 rounded-full text-sm font-semibold border border-teal-500/30 transition-colors flex items-center justify-center gap-2 min-w-[110px]">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" /></svg>
                <span>Bagikan</span>
            </button>
        </div>
    </header>

    <main class="flex-1 w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 z-10 flex flex-col justify-center">
        <!-- The Journey Card to screenshot or show -->
        <div id="journey-card" class="bg-white rounded-[2rem] border border-slate-200 p-6 md:p-10 shadow-sm relative overflow-hidden">
            <!-- decorative circles inside card -->
            <div class="absolute -right-16 -top-16 w-48 h-48 bg-teal-500/10 rounded-full blur-2xl pointer-events-none"></div>
            
            <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                <div class="relative shrink-0">
                    <div class="w-32 h-32 md:w-48 md:h-48 bg-slate-50 rounded-full flex items-center justify-center shadow-md border border-slate-200 overflow-hidden relative">
                        @if($arType === '3d' && $file3dUrl)
                            <model-viewer src="{{ $file3dUrl }}" auto-rotate camera-controls disable-zoom disable-pan interaction-prompt="none" shadow-intensity="1" class="w-full h-full object-contain"></model-viewer>
                        @elseif($arType === '2d' && $imageUrl)
                            <img src="{{ $imageUrl }}" class="w-20 h-20 md:w-28 md:h-28 object-contain animate-bounce" style="animation-duration: 2s;">
                        @else
                            <img src="/ekspresi/{{ $journey->mood }}.png" class="w-20 h-20 md:w-28 md:h-28 object-contain animate-bounce" style="animation-duration: 2s;" onerror="this.src='/ekspresi/senang.png'">
                        @endif
                    </div>
                    
                    <div class="absolute -bottom-2 -right-2 bg-teal-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm border-2 border-white z-10">
                        {{ strtoupper($journey->mood) }}
                    </div>
                </div>

                <div class="flex-1 flex flex-col items-center md:items-start text-center md:text-left w-full">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-50 rounded-full border border-slate-200 mb-4">
                        <span class="w-2 h-2 rounded-full bg-teal-500 animate-pulse"></span>
                        <span class="text-xs font-semibold text-slate-500">Update Status</span>
                    </div>

                    <h2 class="text-2xl md:text-4xl font-bold text-slate-800 mb-3 leading-tight">
                        "{{ $journey->status_text }}"
                    </h2>
                    <p class="text-slate-500 font-medium text-sm md:text-base {{ $journey->location_name ? 'mb-3' : 'mb-8' }}">Jejak perjalanan <span class="text-teal-600">{{ $username }}</span></p>

                    @if($journey->location_name)
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-50 rounded-full border border-slate-200 mb-8">
                        <svg class="w-4 h-4 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                        <span class="text-sm text-slate-600">{{ $journey->location_name }}</span>
                    </div>
                    @endif

                    <div class="w-full bg-slate-50 rounded-2xl p-4 md:p-6 flex justify-between items-center border border-slate-200">
                        <div class="text-left">
                            <p class="text-xs text-slate-500 mb-1">Waktu</p>
                            <p class="text-sm md:text-base font-bold text-slate-800">{{ $journey->created_at->format('d M Y') }}</p>
                            <p class="text-sm font-semibold text-teal-600">{{ $journey->created_at->format('H:i') }} WIB</p>
                        </div>
                        <div class="w-px h-12 bg-slate-200"></div>
                        <div class="text-right">
                            <p class="text-xs text-slate-500 mb-1">Health & Exp</p>
                            <p class="text-sm md:text-base font-bold text-slate-800 flex items-center justify-end gap-1">
                                {{ round($journey->exp_points) }} 
                                <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 pt-6 border-t border-slate-200 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded bg-teal-500 p-1">
                        <!-- Pseudo QR / Logo Icon -->
                        <svg class="w-full h-full text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                    </div>
                    <span class="text-xs font-bold tracking-widest text-slate-400">SCANYUK AR</span>
                </div>
                <div class="text-xs text-slate-500 italic">Virtual Journey</div>
            </div>
        </div>

    </main>

    <script>
        function cropAndCenter(dataUrl) {
            return new Promise((resolve) => {
                if (!dataUrl) return resolve('');
                const img = new Image();
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d', { willReadFrequently: true });
                    canvas.width = img.width;
                    canvas.height = img.height;
                    ctx.drawImage(img, 0, 0);

                    const pixels = ctx.getImageData(0, 0, canvas.width, canvas.height);
                    const l = pixels.data.length;
                    let bound = { top: null, left: null, right: null, bottom: null };

                    for (let i = 0; i < l; i += 4) {
                        if (pixels.data[i + 3] > 0) { 
                            const x = (i / 4) % canvas.width;
                            const y = Math.floor((i / 4) / canvas.width);
                            if (bound.top === null) bound.top = y;
                            if (bound.left === null || x < bound.left) bound.left = x;
                            if (bound.right === null || x > bound.right) bound.right = x;
                            if (bound.bottom === null || y > bound.bottom) bound.bottom = y;
                        }
                    }

                    if (bound.top === null) return resolve(dataUrl);

                    const cropWidth = bound.right - bound.left;
                    const cropHeight = bound.bottom - bound.top;
                    const size = Math.max(cropWidth, cropHeight);
                    
                    const finalCvs = document.createElement('canvas');
                    finalCvs.width = size;
                    finalCvs.height = size;
                    const finalCtx = finalCvs.getContext('2d');
                    
                    const dx = (size - cropWidth) / 2;
                    const dy = (size - cropHeight) / 2;
                    
                    finalCtx.drawImage(
                        canvas,
                        bound.left, bound.top, cropWidth, cropHeight,
                        dx, dy, cropWidth, cropHeight
                    );
                    
                    resolve(finalCvs.toDataURL('image/png'));
                };
                img.src = dataUrl;
            });
        }

        async function shareJourney() {
            const btn = document.getElementById('share-btn');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<span class="animate-pulse">Memproses...</span>';
            btn.disabled = true;

            try {
                const viewer = document.querySelector('model-viewer');
                let modelDataUrl = '';
                if (viewer) {
                    modelDataUrl = viewer.toDataURL('image/png');
                } else {
                    const fallbackImg = document.querySelector('#journey-card img');
                    if (fallbackImg) modelDataUrl = fallbackImg.src;
                }

                const finalImageUrl = await cropAndCenter(modelDataUrl);
                document.getElementById('share-model-img').src = finalImageUrl;

                const card = document.getElementById('share-card-container');
                const canvas = await html2canvas(card, {
                    backgroundColor: '#0f172a',
                    scale: 2
                });

                canvas.toBlob(async (blob) => {
                    const file = new File([blob], `journey-{{ $username }}.png`, { type: 'image/png' });
                    
                    if (navigator.canShare && navigator.canShare({ files: [file] })) {
                        btn.innerHTML = 'Kirim';
                        btn.disabled = false;
                        btn.onclick = async () => {
                            try {
                                await navigator.share({
                                    title: 'Journey {{ $username }} di ScanYuk AR',
                                    text: 'Lihat apa yang {{ $username }} temukan: "{{ $journey->status_text }}"',
                                    files: [file]
                                });
                            } catch (e) {
                                console.log('Share dibatalkan atau error:', e);
                            }
                            btn.innerHTML = originalHTML;
                            btn.onclick = shareJourney;
                        };
                    } else {
                        const link = document.createElement('a');
                        link.download = `journey-{{ $username }}.png`;
                        link.href = URL.createObjectURL(blob);
                        link.click();
                        
                        setTimeout(() => {
                            alert('Gambar berhasil disimpan ke perangkat Anda! Silakan upload secara manual.');
                        }, 500);
                        
                        btn.innerHTML = originalHTML;
                        btn.disabled = false;
                    }
                });
            } catch (err) {
                console.error(err);
                alert('Gagal membuat gambar.');
                btn.innerHTML = originalHTML;
                btn.disabled = false;
            }
        }
    </script>

    <!-- Hidden Share Card Template -->
    <div id="share-card-container" style="position: absolute; top: -9999px; left: -9999px; width: 480px; height: 853px; background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%); border-radius: 30px; overflow: hidden; font-family: 'Inter', sans-serif; display: flex; flex-direction: column; box-shadow: 0 0 0 10px rgba(255,255,255,0.3) inset;">
        
        <div style="padding: 40px 20px 0; text-align: center; z-index: 10;">
            <h2 style="color: white; margin: 0; font-size: 38px; font-weight: 900; text-transform: uppercase; letter-spacing: 3px; text-shadow: 0 4px 6px rgba(0,0,0,0.2);">AR JOURNEY</h2>
        </div>
        
        <div style="flex: 1; position: relative; margin-top: -10px;">
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 350px; height: 350px; background: radial-gradient(circle, rgba(255,255,255,0.7) 0%, rgba(255,255,255,0) 70%); border-radius: 50%; z-index: 1;"></div>
            
            <div style="position: absolute; bottom: 20%; left: 50%; transform: translateX(-50%); width: 280px; height: 50px; background: rgba(0,0,0,0.2); border-radius: 50%; filter: blur(5px); z-index: 1;"></div>
            
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 5;">
                <img id="share-model-img" src="" style="position: absolute; top: 50%; left: 50%; width: 350px; height: 350px; margin-left: -175px; margin-top: -175px; filter: drop-shadow(0 20px 25px rgba(0,0,0,0.3)); transform: scale(1.15); object-fit: contain;">
            </div>
            
            <div style="position: absolute; top: 20px; right: 20px; background: #3b82f6; border: 4px solid #fff; border-radius: 50%; width: 90px; height: 90px; box-shadow: 0 10px 15px rgba(0,0,0,0.2); z-index: 10; transform: rotate(15deg);">
                <div style="position: absolute; top: 18px; left: 0; width: 100%; text-align: center; font-size: 14px; font-weight: 900; color: #fff; text-transform: uppercase; line-height: 1; margin: 0; padding: 0;">Exp</div>
                <div style="position: absolute; top: 35px; left: 0; width: 100%; text-align: center; font-size: 34px; font-weight: 900; color: #fff; text-shadow: 0 2px 0 #1d4ed8; line-height: 1; margin: 0; padding: 0; transform: translateY(-12px);">{{ round($journey->exp_points) }}</div>
            </div>
        </div>
        
        <div style="background: rgba(255, 255, 255, 0.95); margin: 0 20px 20px; padding: 25px; border-radius: 25px; box-shadow: 0 15px 30px rgba(0,0,0,0.15); z-index: 10; position: relative;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <div style="flex: 1; padding-right: 15px;">
                    <p style="color: #64748b; margin: 0 0 4px 0; font-size: 14px; font-weight: 700; text-transform: uppercase;">@ {{ $username }}</p>
                    <p style="color: #1e293b; margin: 0; font-size: 20px; font-weight: 900; line-height: 1.2;">"{{ $journey->status_text }}"</p>
                </div>
                <div style="width: 60px; height: 60px; background: #f1f5f9; border-radius: 15px; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.05)); position: relative; flex-shrink: 0;">
                    @php
                        $emojiMap = ['senang' => '😊', 'suntuk' => '😐', 'marah' => '😠', 'menangis' => '😢', 'cape' => '😴', 'tidur' => '💤'];
                        $moodEmoji = $emojiMap[strtolower($journey->mood)] ?? '😊';
                    @endphp
                    <div style="position: absolute; top: 10px; left: 0; width: 100%; text-align: center; font-size: 38px; line-height: 1; margin: 0; padding: 0; transform: translateY(-16px);">{{ $moodEmoji }}</div>
                </div>
            </div>
            
            <div style="margin-top: 15px; padding-top: 15px; border-top: 2px dashed #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="color: #64748b; margin: 0; font-size: 14px; font-weight: 700;">{{ $journey->created_at->format('d M Y - H:i') }}</p>
                    @if($journey->location_name)
                    <p style="color: #ef4444; margin: 4px 0 0; font-size: 12px; font-weight: 700;">📍 {{ $journey->location_name }}</p>
                    @endif
                </div>
                <p style="color: #94a3b8; margin: 0; font-size: 14px; font-weight: 700;">ScanYuk WebAR</p>
            </div>
        </div>
    </div>
</body>
</html>
