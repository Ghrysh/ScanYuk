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
        .glass-card { background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen pb-12 relative flex flex-col">
    <!-- Animated background -->
    <div class="absolute inset-0 z-0 opacity-20 pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-teal-500 rounded-full mix-blend-screen filter blur-3xl animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-64 h-64 bg-blue-500 rounded-full mix-blend-screen filter blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
    </div>

    <!-- Header -->
    <header class="sticky top-0 z-30 glass-card border-b border-slate-700/50 shadow-lg">
        <div class="max-w-2xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('tamagotchi.index', $username) }}" class="text-slate-400 hover:text-white transition bg-slate-800 p-2 rounded-full border border-slate-700">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                </a>
                <h1 class="font-bold text-lg text-white">Detail Journey</h1>
            </div>
            <!-- Bagikan Tombol Native Web Share API -->
            <button id="share-btn" onclick="shareJourney()" class="text-teal-400 bg-teal-500/10 hover:bg-teal-500/20 px-4 py-2 rounded-full text-sm font-semibold border border-teal-500/30 transition-colors flex items-center justify-center gap-2 min-w-[110px]">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" /></svg>
                <span>Bagikan</span>
            </button>
        </div>
    </header>

    <main class="flex-1 w-full max-w-2xl mx-auto px-4 mt-8 z-10 flex flex-col justify-center">
        <!-- The Journey Card to screenshot or show -->
        <div id="journey-card" class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-[2rem] border border-slate-700 p-8 shadow-2xl relative overflow-hidden">
            <!-- decorative circles inside card -->
            <div class="absolute -right-16 -top-16 w-48 h-48 bg-teal-500/10 rounded-full blur-2xl pointer-events-none"></div>
            
            <div class="flex flex-col items-center text-center">
                <div class="relative mb-6">
                    <div class="w-28 h-28 bg-gradient-to-br from-slate-700 to-slate-800 rounded-full flex items-center justify-center shadow-xl border border-slate-600 overflow-hidden relative">
                        @if($arType === '3d' && $file3dUrl)
                            <model-viewer src="{{ $file3dUrl }}" auto-rotate camera-controls disable-zoom disable-pan interaction-prompt="none" shadow-intensity="1" class="w-full h-full object-contain"></model-viewer>
                        @elseif($arType === '2d' && $imageUrl)
                            <img src="{{ $imageUrl }}" class="w-16 h-16 object-contain animate-bounce" style="animation-duration: 2s;">
                        @else
                            <img src="/ekspresi/{{ $journey->mood }}.png" class="w-16 h-16 object-contain animate-bounce" style="animation-duration: 2s;" onerror="this.src='/ekspresi/senang.png'">
                        @endif
                    </div>
                    
                    <div class="absolute -bottom-2 -right-2 bg-teal-500 text-white text-[10px] font-bold px-2 py-1 rounded-full shadow-lg border-2 border-slate-800 z-10">
                        {{ strtoupper($journey->mood) }}
                    </div>
                </div>

                <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-800 rounded-full border border-slate-700 mb-4">
                    <span class="w-2 h-2 rounded-full bg-teal-400 animate-pulse"></span>
                    <span class="text-xs font-semibold text-slate-300">Update Status</span>
                </div>

                <h2 class="text-2xl sm:text-3xl font-bold text-white mb-2 leading-tight">
                    "{{ $journey->status_text }}"
                </h2>
                
                <p class="text-slate-400 mb-8 font-medium">Jejak perjalanan <span class="text-teal-400">{{ $username }}</span></p>

                <div class="w-full bg-slate-800/80 rounded-2xl p-4 flex justify-between items-center border border-slate-700/50">
                    <div class="text-left">
                        <p class="text-xs text-slate-500 mb-1">Waktu</p>
                        <p class="text-sm font-bold text-slate-200">{{ $journey->created_at->format('d M Y') }}</p>
                        <p class="text-xs font-semibold text-teal-400">{{ $journey->created_at->format('H:i') }} WIB</p>
                    </div>
                    <div class="w-px h-10 bg-slate-700"></div>
                    <div class="text-right">
                        <p class="text-xs text-slate-500 mb-1">Health & Exp</p>
                        <p class="text-sm font-bold text-slate-200 flex items-center justify-end gap-1">
                            {{ round($journey->exp_points) }} 
                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 pt-6 border-t border-slate-700/50 flex items-center justify-between">
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
        async function shareJourney() {
            const btn = document.getElementById('share-btn');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<span class="animate-pulse">Memproses...</span>';
            btn.disabled = true;

            try {
                // Trik menangkap model-viewer (WebGL canvas biasanya kosong jika tidak ditangani)
                const viewer = document.querySelector('model-viewer');
                let tempImg = null;
                if (viewer) {
                    const dataUrl = viewer.toDataURL('image/png');
                    tempImg = document.createElement('img');
                    tempImg.src = dataUrl;
                    tempImg.className = viewer.className;
                    viewer.style.display = 'none';
                    viewer.parentNode.insertBefore(tempImg, viewer);
                }

                // Ambil screenshot dari journey-card
                const card = document.getElementById('journey-card');
                const canvas = await html2canvas(card, {
                    backgroundColor: '#0f172a',
                    scale: 2 // Resolusi tinggi
                });

                // Kembalikan model-viewer
                if (viewer && tempImg) {
                    viewer.style.display = '';
                    tempImg.remove();
                }

                canvas.toBlob(async (blob) => {
                    const file = new File([blob], `journey-{{ $username }}.png`, { type: 'image/png' });
                    
                    if (navigator.canShare && navigator.canShare({ files: [file] })) {
                        // Minta pengguna klik lagi untuk share (mencegah error user gesture expired)
                        btn.innerHTML = 'Kirim 🚀';
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
                            // Kembalikan tombol ke semula
                            btn.innerHTML = originalHTML;
                            btn.onclick = shareJourney;
                        };
                    } else {
                        // Fallback browser jadul / desktop: Download gambar
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
</body>
</html>
