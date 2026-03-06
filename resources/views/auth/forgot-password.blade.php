<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Password - ScanYuk</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%230d9488' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect width='5' height='5' x='3' y='3' rx='1'%3E%3C/rect%3E%3Crect width='5' height='5' x='16' y='3' rx='1'%3E%3C/rect%3E%3Crect width='5' height='5' x='3' y='16' rx='1'%3E%3C/rect%3E%3Cpath d='M21 16h-3a2 2 0 0 0-2 2v3'%3E%3C/path%3E%3Cpath d='M21 21v.01'%3E%3C/path%3E%3Cpath d='M12 7v3a2 2 0 0 1-2 2H7'%3E%3C/path%3E%3Cpath d='M3 12h.01'%3E%3C/path%3E%3Cpath d='M12 3h.01'%3E%3C/path%3E%3Cpath d='M12 16v.01'%3E%3C/path%3E%3Cpath d='M16 12h1'%3E%3C/path%3E%3Cpath d='M21 12v.01'%3E%3C/path%3E%3Cpath d='M12 21v-1'%3E%3C/path%3E%3C/svg%3E">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: { primary: '#6366f1', secondary: '#14b8a6' }
                    }
                }
            }
        }
    </script>
    <style>
        .bg-grid-pattern {
            background-size: 40px 40px;
            background-image: linear-gradient(to right, rgba(0,0,0,0.03) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(0,0,0,0.03) 1px, transparent 1px);
        }
        .btn-gradient {
            background: linear-gradient(90deg, #14b8a6 0%, #8b5cf6 100%);
        }
    </style>
</head>
<body class="font-sans antialiased text-slate-600 bg-white bg-grid-pattern min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 hover:opacity-80 transition-opacity mb-6">
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><rect width="5" height="5" x="3" y="3" rx="1"></rect><rect width="5" height="5" x="16" y="3" rx="1"></rect><rect width="5" height="5" x="3" y="16" rx="1"></rect><path d="M21 16h-3a2 2 0 0 0-2 2v3"></path><path d="M21 21v.01"></path><path d="M12 7v3a2 2 0 0 1-2 2H7"></path><path d="M3 12h.01"></path><path d="M12 3h.01"></path><path d="M12 16v.01"></path><path d="M16 12h1"></path><path d="M21 12v.01"></path><path d="M12 21v-1"></path></svg>
                </div>
                <span class="text-2xl font-black tracking-tight text-slate-900">ScanYuk</span>
            </a>
            <h2 class="text-3xl font-extrabold text-slate-900 mb-2">Lupa Password?</h2>
            <p class="text-slate-500 text-sm">Masukkan email Anda untuk menerima link reset.</p>
        </div>

        <div class="bg-white py-8 px-4 shadow-2xl shadow-slate-200/50 sm:rounded-3xl sm:px-10 border border-slate-100">
            @if (session('status'))
                <div class="mb-6 p-4 rounded-xl bg-teal-50 border border-teal-200 text-sm text-teal-700 font-medium text-center">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Alamat Email</label>
                    <input id="email" type="email" name="email" required value="{{ old('email') }}" class="appearance-none block w-full px-4 py-3.5 border border-slate-300 rounded-xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all sm:text-sm">
                    @error('email') <p class="mt-2 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center py-3.5 px-4 rounded-xl shadow-lg shadow-indigo-200 text-sm font-bold text-white btn-gradient hover:opacity-90 transition-all">
                        Kirim Link Reset
                    </button>
                </div>
            </form>
            
            <div class="mt-8 text-center border-t border-slate-100 pt-6">
                <a href="{{ route('login') }}" class="text-sm font-bold text-slate-500 hover:text-slate-900 transition-colors flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Kembali ke halaman Login
                </a>
            </div>
        </div>
    </div>
</body>
</html>