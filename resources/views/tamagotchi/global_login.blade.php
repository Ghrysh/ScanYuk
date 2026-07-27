<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>Login Tamagotchi Web</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-6 relative overflow-hidden">
    <!-- Animated background -->
    <div class="absolute inset-0 z-0 opacity-30">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-teal-500 rounded-full mix-blend-screen filter blur-3xl opacity-30 animate-pulse"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-blue-500 rounded-full mix-blend-screen filter blur-3xl opacity-30 animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative z-10 w-full max-w-md">
        <div class="bg-slate-800/60 backdrop-blur-xl rounded-3xl p-8 shadow-2xl border border-slate-700/50">
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-gradient-to-br from-teal-400 to-blue-500 rounded-full mx-auto flex items-center justify-center mb-4 shadow-lg shadow-teal-500/30">
                    <img src="/ekspresi/senang.png" class="w-12 h-12 object-contain" alt="Tamagotchi">
                </div>
                <h1 class="text-2xl font-bold text-white mb-2">Login <span class="text-teal-400">Tamagotchi</span></h1>
                <p class="text-slate-400 text-sm">Masukkan username dan password Tamagotchi Anda untuk melihat perjalanan.</p>
            </div>

            @if(session('error'))
                <div class="bg-red-500/10 border border-red-500/50 text-red-400 text-sm rounded-xl p-3 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('tamagotchi.global-login-post') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-slate-300 text-sm font-semibold mb-2">Username</label>
                    <input type="text" name="username" required autocomplete="off"
                        class="w-full bg-slate-900/50 border border-slate-700 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors"
                        placeholder="Masukkan username Anda">
                </div>
                <div class="mb-6">
                    <label class="block text-slate-300 text-sm font-semibold mb-2">Password</label>
                    <input type="password" name="password" required
                        class="w-full bg-slate-900/50 border border-slate-700 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors"
                        placeholder="••••••••">
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-teal-500 to-blue-600 hover:from-teal-400 hover:to-blue-500 text-white font-bold py-3 px-4 rounded-xl shadow-lg transition-transform transform hover:-translate-y-0.5">
                    Masuk ke Journey
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <a href="{{ url('/') }}" class="text-slate-500 hover:text-slate-300 text-sm transition-colors">Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</body>
</html>
