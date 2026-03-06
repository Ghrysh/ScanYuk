<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Password - ScanYuk</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .bg-grid-pattern { background-size: 40px 40px; background-image: linear-gradient(to right, rgba(0,0,0,0.03) 1px, transparent 1px), linear-gradient(to bottom, rgba(0,0,0,0.03) 1px, transparent 1px); }
        .btn-gradient { background: linear-gradient(90deg, #14b8a6 0%, #8b5cf6 100%); }
    </style>
</head>
<body class="font-sans antialiased text-slate-600 bg-white bg-grid-pattern min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    
    @if(session('status'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="fixed top-5 left-1/2 -translate-x-1/2 z-[100] flex items-center p-4 mb-4 text-teal-800 rounded-2xl bg-white border border-teal-200 shadow-xl shadow-teal-100/50" role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-white bg-teal-500 rounded-full"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></div>
        <div class="ms-3 text-sm font-bold pr-4">{{ session('status') }}</div>
        <button type="button" @click="show = false" class="ms-auto -mx-1.5 -my-1.5 bg-white text-slate-400 rounded-lg p-1.5 hover:bg-slate-100 hover:text-slate-900 h-8 w-8"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></button>
    </div>
    @endif

    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-slate-900 mb-2">Lupa Password?</h2>
            <p class="text-slate-500 text-sm">Masukkan email Anda untuk menerima link reset.</p>
        </div>

        <div class="bg-white py-8 px-4 shadow-2xl sm:rounded-3xl sm:px-10 border border-slate-100">
            <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Alamat Email</label>
                    <input id="email" type="email" name="email" required value="{{ old('email') }}" class="appearance-none block w-full px-4 py-3.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-teal-500 sm:text-sm" placeholder="you@example.com">
                    @error('email') <p class="mt-2 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                </div>
                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center py-3.5 px-4 rounded-xl text-sm font-bold text-white btn-gradient shadow-lg hover:opacity-90 transition-all hover:-translate-y-0.5">Kirim Link Reset</button>
                </div>
            </form>
            <div class="mt-8 text-center border-t border-slate-100 pt-6">
                <a href="{{ route('login') }}" class="text-sm font-bold text-slate-500 hover:text-slate-900 transition-colors">Kembali ke halaman Login</a>
            </div>
        </div>
    </div>

    @if(session('polling'))
    <script>
        let checkInterval = setInterval(() => {
            fetch('{{ route("check.reset.status") }}')
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'logged_in' || data.status === 'clicked') {
                        clearInterval(checkInterval);
                        window.location.href = data.redirect;
                    }
                }).catch(err => console.log(err));
        }, 2000);
    </script>
    @endif
</body>
</html>