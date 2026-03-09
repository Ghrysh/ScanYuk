<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Scan Yuk - Platform AR QR Code</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%230d9488' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect width='5' height='5' x='3' y='3' rx='1'%3E%3C/rect%3E%3Crect width='5' height='5' x='16' y='3' rx='1'%3E%3C/rect%3E%3Crect width='5' height='5' x='3' y='16' rx='1'%3E%3C/rect%3E%3Cpath d='M21 16h-3a2 2 0 0 0-2 2v3'%3E%3C/path%3E%3Cpath d='M21 21v.01'%3E%3C/path%3E%3Cpath d='M12 7v3a2 2 0 0 1-2 2H7'%3E%3C/path%3E%3Cpath d='M3 12h.01'%3E%3C/path%3E%3Cpath d='M12 3h.01'%3E%3C/path%3E%3Cpath d='M12 16v.01'%3E%3C/path%3E%3Cpath d='M16 12h1'%3E%3C/path%3E%3Cpath d='M21 12v.01'%3E%3C/path%3E%3Cpath d='M12 21v-1'%3E%3C/path%3E%3C/svg%3E">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script type="text/javascript">
        (function(c,l,a,r,i,t,y){
            c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
            t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
            y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
        })(window, document, "clarity", "script", "GANTI_DENGAN_ID_PROJECT_ANDA");
    </script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            text: '#1e293b',
                            subtext: '#64748b',
                            primary: '#6366f1',
                            secondary: '#14b8a6',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        .bg-grid-pattern {
            background-size: 50px 50px;
            background-image: 
                linear-gradient(to right, rgba(0,0,0,0.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(0,0,0,0.03) 1px, transparent 1px);
            background-color: #fafafa;
        }
        
        .text-gradient {
            background: linear-gradient(to right, #0d9488, #7c3aed);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-gradient {
            background: linear-gradient(90deg, #14b8a6 0%, #8b5cf6 100%);
            transition: opacity 0.3s ease;
        }
        .btn-gradient:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body class="font-sans antialiased text-brand-text bg-grid-pattern min-h-screen flex flex-col">

    @if(request()->is('admin*'))
        <nav class="sticky top-0 z-50 w-full bg-white/80 backdrop-blur-md border-b border-slate-200 shadow-sm">
            <div class="max-w-[100rem] mx-auto px-6 md:px-12 py-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded bg-teal-50 text-teal-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><rect width="5" height="5" x="3" y="3" rx="1"></rect><rect width="5" height="5" x="16" y="3" rx="1"></rect><rect width="5" height="5" x="3" y="16" rx="1"></rect><path d="M21 16h-3a2 2 0 0 0-2 2v3"></path><path d="M21 21v.01"></path><path d="M12 7v3a2 2 0 0 1-2 2H7"></path><path d="M3 12h.01"></path><path d="M12 3h.01"></path><path d="M12 16v.01"></path><path d="M16 12h1"></path><path d="M21 12v.01"></path><path d="M12 21v-1"></path></svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-slate-900">ScanYuk Admin</span>
                </div>
                
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" x2="9" y1="12" y2="12"></line></svg>
                        Logout
                    </button>
                </form>
            </div>
        </nav>

    @elseif(request()->is('dashboard*'))
        <nav class="sticky top-0 z-50 w-full bg-white/80 backdrop-blur-md border-b border-slate-200 shadow-sm">
            <div class="max-w-[100rem] mx-auto px-6 md:px-12 py-4 flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                    <div class="w-8 h-8 rounded bg-teal-50 text-teal-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="5" height="5" x="3" y="3" rx="1"></rect><rect width="5" height="5" x="16" y="3" rx="1"></rect><rect width="5" height="5" x="3" y="16" rx="1"></rect><path d="M21 16h-3a2 2 0 0 0-2 2v3"></path><path d="M21 21v.01"></path><path d="M12 7v3a2 2 0 0 1-2 2H7"></path><path d="M3 12h.01"></path><path d="M12 3h.01"></path><path d="M12 16v.01"></path><path d="M16 12h1"></path><path d="M21 12v.01"></path><path d="M12 21v-1"></path></svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-slate-900">ScanYuk</span>
                </a>
                
                <div class="flex items-center gap-6">
                    <div class="hidden md:flex items-center text-sm font-medium text-slate-600">
                        {{ Auth::user()->name }} <span class="mx-2 text-slate-300">•</span> 
                        <span class="text-teal-600 font-bold capitalize">{{ Auth::user()->role }}</span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" x2="9" y1="12" y2="12"></line></svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </nav>

    @else
        @include('partials.navbar')
    @endif

    <main class="flex-grow flex flex-col">
        @yield('content')
    </main>

    @if(!request()->is('admin*') && !request()->is('dashboard*'))
        @include('partials.footer')
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = encodeURIComponent(window.location.pathname);
            fetch('/sys-ping/v1?path=' + currentPath, {
                method: 'GET',
                keepalive: true,
                headers: { 'Accept': 'application/json' }
            }).catch(e => {});
        });
    </script>

</body>
</html>