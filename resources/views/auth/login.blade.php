<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - ScanYuk</title>
    
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

    @if(session('success') || session('status'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="fixed top-5 left-1/2 -translate-x-1/2 z-[100] flex items-center p-4 mb-4 text-teal-800 rounded-2xl bg-white border border-teal-200 shadow-xl shadow-teal-100/50" role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-white bg-teal-500 rounded-full"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></div>
        <div class="ms-3 text-sm font-bold pr-4">{{ session('success') ?? session('status') }}</div>
        <button type="button" @click="show = false" class="ms-auto -mx-1.5 -my-1.5 bg-white text-slate-400 rounded-lg p-1.5 hover:bg-slate-100 hover:text-slate-900 h-8 w-8"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></button>
    </div>
    @endif

    <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
        <div class="absolute top-[-10%] right-[-5%] w-96 h-96 bg-teal-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob"></div>
        <div class="absolute bottom-[-10%] left-[-5%] w-96 h-96 bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-2000"></div>
    </div>

    <div class="w-full max-w-[400px] bg-white rounded-2xl shadow-xl border border-slate-100 p-8 relative z-10">
        
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-2 mb-6 hover:opacity-80 transition-opacity">
                <div class="w-8 h-8 rounded bg-teal-50 text-teal-600 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-qr-code w-7 h-7"><rect width="5" height="5" x="3" y="3" rx="1"></rect><rect width="5" height="5" x="16" y="3" rx="1"></rect><rect width="5" height="5" x="3" y="16" rx="1"></rect><path d="M21 16h-3a2 2 0 0 0-2 2v3"></path><path d="M21 21v.01"></path><path d="M12 7v3a2 2 0 0 1-2 2H7"></path><path d="M3 12h.01"></path><path d="M12 3h.01"></path><path d="M12 16v.01"></path><path d="M16 12h1"></path><path d="M21 12v.01"></path><path d="M12 21v-1"></path></svg>
                </div>
                <span class="text-xl font-bold tracking-tight text-slate-900">ScanYuk</span>
            </a>
            <h2 class="text-2xl font-bold text-slate-900">Welcome Back</h2>
            <p class="mt-2 text-sm text-slate-500">Sign in to your account</p>
        </div>

        <form class="space-y-5" action="{{ route('login') }}" method="POST" x-data="{ showPassword: false }">
            @csrf

            @if(request()->query('payment') == 'success')
                <div class="mb-4 p-4 rounded-xl bg-teal-50 border border-teal-200 text-teal-700 flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-teal-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p class="text-sm font-bold">Pembayaran berhasil diproses! Akun Anda telah dibuat. Silakan login untuk memulai.</p>
                </div>
            @endif

            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
                <input id="email" name="email" type="email" autocomplete="email" required 
                    class="appearance-none block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all sm:text-sm" 
                    placeholder="you@example.com" value="{{ old('email') }}">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="password" class="block text-sm font-bold text-slate-700">Password</label>
                    <a href="{{ route('password.request') }}" class="text-xs font-bold text-teal-600 hover:text-teal-700 hover:underline transition-colors">
                        Lupa Password?
                    </a>
                </div>
                
                <div class="relative">
                    <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required 
                        class="appearance-none block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all sm:text-sm" 
                        placeholder="••••••••">
                    
                    <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                        <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        <svg x-show="showPassword" style="display: none;" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-5.557 5.919" /></svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full py-3 px-4 rounded-lg btn-gradient text-white font-bold shadow-lg shadow-indigo-200 hover:opacity-90 transition-all hover:-translate-y-0.5">
                Sign In
            </button>

            <div class="text-center text-sm pt-2">
                <span class="text-slate-500">Don't have an account?</span>
                <a href="{{ route('register') }}" class="font-semibold text-teal-600 hover:text-teal-700 ml-1">
                    Sign up
                </a>
            </div>

            @error('suspended')
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-center">
                <div class="flex justify-center mb-2">
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </div>
                </div>
                <h3 class="text-red-800 font-bold text-lg mb-1">{{ $message }}</h3>
                <p class="text-red-600 text-sm mb-4">Jika Anda merasa ini adalah sebuah kesalahan, silakan hubungi tim dukungan kami.</p>
                
                <a href="mailto:ptbtt01@gmail.com?subject=Banding Akun Suspended - ScanYuk" 
                class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700 transition-colors w-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    Hubungi Support
                </a>
            </div>
            @enderror
        </form>
    </div>

</body>
</html>