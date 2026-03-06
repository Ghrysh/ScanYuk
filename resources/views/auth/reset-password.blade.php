<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password - ScanYuk</title>
    
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
            <h2 class="text-3xl font-extrabold text-slate-900 mb-2">Buat Password Baru</h2>
            <p class="text-slate-500 text-sm">Pastikan password baru Anda kuat dan aman.</p>
        </div>

        <div class="bg-white py-8 px-4 shadow-2xl shadow-slate-200/50 sm:rounded-3xl sm:px-10 border border-slate-100">
            <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ request('email') }}">

                @if ($errors->has('email'))
                    <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-sm text-red-600 font-medium mb-4 text-center">
                        {{ $errors->first('email') }}
                    </div>
                @endif

                <div>
                    <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Password Baru</label>
                    <input id="password" type="password" name="password" required minlength="8" placeholder="Minimal 8 karakter" class="appearance-none block w-full px-4 py-3.5 border border-slate-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all sm:text-sm">
                    @error('password') <p class="mt-2 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-2">Konfirmasi Password Baru</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8" placeholder="Ketik ulang password" class="appearance-none block w-full px-4 py-3.5 border border-slate-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all sm:text-sm">
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full flex justify-center py-3.5 px-4 rounded-xl shadow-lg shadow-indigo-200 text-sm font-bold text-white btn-gradient hover:opacity-90 transition-all">
                        Simpan & Login
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>