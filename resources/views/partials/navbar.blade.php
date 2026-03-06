@php
    $isProdukActive = request()->routeIs(['consumer', 'business', 'pricing', 'demo']);
    $isSolusiActive = request()->routeIs(['solutions', 'how-it-works', 'partners']);
    $isInformasiActive = request()->routeIs(['faq', 'contact', 'security', 'terms', 'refund-policy']);
@endphp

<nav x-data="{ openMobile: false }" class="sticky top-0 z-50 w-full bg-white/80 backdrop-blur-md border-b border-slate-200 shadow-sm rounded-b-3xl">
    
    <div class="max-w-[100rem] mx-auto px-6 md:px-12 py-4 flex items-center justify-between">
        
        <a href="{{ route('home') }}" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
            <div class="w-8 h-8 rounded bg-teal-50 text-teal-600 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-qr-code w-7 h-7"><rect width="5" height="5" x="3" y="3" rx="1"></rect><rect width="5" height="5" x="16" y="3" rx="1"></rect><rect width="5" height="5" x="3" y="16" rx="1"></rect><path d="M21 16h-3a2 2 0 0 0-2 2v3"></path><path d="M21 21v.01"></path><path d="M12 7v3a2 2 0 0 1-2 2H7"></path><path d="M3 12h.01"></path><path d="M12 3h.01"></path><path d="M12 16v.01"></path><path d="M16 12h1"></path><path d="M21 12v.01"></path><path d="M12 21v-1"></path></svg>
            </div>
            <span class="text-xl font-bold tracking-tight text-slate-900">ScanYuk</span>
        </a>

        <div class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-600">
            
            <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative py-2">
                <button type="button" class="flex items-center gap-1 transition-colors {{ $isProdukActive ? 'text-teal-600 font-bold' : 'hover:text-slate-900' }}" :class="open ? '{{ $isProdukActive ? '' : 'text-slate-900' }}' : ''">
                    Produk
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''"><path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" /></svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" class="absolute left-0 top-full mt-0 w-48 bg-white border border-slate-100 rounded-xl shadow-xl overflow-hidden py-2" style="display: none;">
                    <a href="{{ route('consumer') }}" class="block px-5 py-2.5 transition-colors {{ request()->routeIs('consumer') ? 'bg-teal-50 text-teal-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-teal-600' }}">Personal</a>
                    <a href="{{ route('business') }}" class="block px-5 py-2.5 transition-colors {{ request()->routeIs('business') ? 'bg-teal-50 text-teal-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-teal-600' }}">Bisnis</a>
                    <a href="{{ route('pricing') }}" class="block px-5 py-2.5 transition-colors {{ request()->routeIs('pricing') ? 'bg-teal-50 text-teal-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-teal-600' }}">Harga</a>
                    <a href="{{ route('demo') }}" class="block px-5 py-2.5 transition-colors {{ request()->routeIs('demo') ? 'bg-teal-50 text-teal-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-teal-600' }}">Demo</a>
                </div>
            </div>

            <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative py-2">
                <button type="button" class="flex items-center gap-1 transition-colors {{ $isSolusiActive ? 'text-teal-600 font-bold' : 'hover:text-slate-900' }}" :class="open ? '{{ $isSolusiActive ? '' : 'text-slate-900' }}' : ''">
                    Solusi
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''"><path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" /></svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" class="absolute left-0 top-full mt-0 w-48 bg-white border border-slate-100 rounded-xl shadow-xl overflow-hidden py-2" style="display: none;">
                    <a href="{{ route('solutions') }}" class="block px-5 py-2.5 transition-colors {{ request()->routeIs('solutions') ? 'bg-teal-50 text-teal-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-teal-600' }}">Solusi</a>
                    <a href="{{ route('how-it-works') }}" class="block px-5 py-2.5 transition-colors {{ request()->routeIs('how-it-works') ? 'bg-teal-50 text-teal-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-teal-600' }}">Cara Kerja</a>
                    <a href="{{ route('case-studies') }}" class="block px-5 py-2.5 transition-colors {{ request()->routeIs('case-studies') ? 'bg-teal-50 text-teal-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-teal-600' }}">Studi Kasus</a>
                    <a href="{{ route('partners') }}" class="block px-5 py-2.5 transition-colors {{ request()->routeIs('partners') ? 'bg-teal-50 text-teal-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-teal-600' }}">Mitra</a>
                </div>
            </div>

            <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative py-2">
                <button type="button" class="flex items-center gap-1 transition-colors {{ $isInformasiActive ? 'text-teal-600 font-bold' : 'hover:text-slate-900' }}" :class="open ? '{{ $isInformasiActive ? '' : 'text-slate-900' }}' : ''">
                    Informasi
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''"><path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" /></svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" class="absolute left-0 top-full mt-0 w-56 bg-white border border-slate-100 rounded-xl shadow-xl overflow-hidden py-2" style="display: none;">
                    <a href="{{ route('faq') }}" class="block px-5 py-2.5 transition-colors {{ request()->routeIs('faq') ? 'bg-teal-50 text-teal-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-teal-600' }}">FAQ</a>
                    <a href="{{ route('contact') }}" class="block px-5 py-2.5 transition-colors {{ request()->routeIs('contact') ? 'bg-teal-50 text-teal-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-teal-600' }}">Hubungi Kami</a>
                    <a href="{{ route('security') }}" class="block px-5 py-2.5 transition-colors {{ request()->routeIs('security') ? 'bg-teal-50 text-teal-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-teal-600' }}">Keamanan</a>
                    <a href="{{ route('terms') }}" class="block px-5 py-2.5 transition-colors {{ request()->routeIs('terms') ? 'bg-teal-50 text-teal-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-teal-600' }}">Syarat & Ketentuan</a>
                    <a href="{{ route('refund-policy') }}" class="block px-5 py-2.5 transition-colors {{ request()->routeIs('refund-policy') ? 'bg-teal-50 text-teal-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-teal-600' }}">Kebijakan Pengembalian</a>
                </div>
            </div>

        </div>

        <div class="hidden md:flex items-center gap-4">
            @guest
                <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 hover:text-slate-900 transition-colors">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-lg btn-gradient text-white text-sm font-medium shadow-lg shadow-indigo-200 hover:opacity-90 transition-opacity">
                    Daftar
                </a>
            @else
                <div class="flex items-center gap-4">
                    <span class="text-sm font-medium text-slate-600">
                        Hi, {{ Auth::user()->name }}
                    </span>
                    <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}" class="px-4 py-2 rounded-lg bg-teal-50 text-teal-600 text-sm font-bold hover:bg-teal-100 transition-colors">
                        Dashboard
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-600 text-sm font-medium hover:bg-slate-50 hover:text-red-600 transition-colors">
                            Logout
                        </button>
                    </form>
                </div>
            @endguest
        </div>

        <div class="flex md:hidden">
            <button @click="openMobile = true" type="button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-slate-700">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>
    </div>

    <div x-show="openMobile" style="display: none;" class="fixed inset-0 z-[100] flex">
        
        <div x-show="openMobile" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="openMobile = false"></div>

        <div x-show="openMobile" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="relative flex w-full max-w-sm flex-col bg-white shadow-2xl h-screen overflow-y-auto">
            
            <div class="px-6 py-4 flex items-center justify-between border-b border-slate-100 sticky top-0 bg-white z-10">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded bg-teal-50 text-teal-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-qr-code w-6 h-6"><rect width="5" height="5" x="3" y="3" rx="1"></rect><rect width="5" height="5" x="16" y="3" rx="1"></rect><rect width="5" height="5" x="3" y="16" rx="1"></rect><path d="M21 16h-3a2 2 0 0 0-2 2v3"></path><path d="M21 21v.01"></path><path d="M12 7v3a2 2 0 0 1-2 2H7"></path><path d="M3 12h.01"></path><path d="M12 3h.01"></path><path d="M12 16v.01"></path><path d="M16 12h1"></path><path d="M21 12v.01"></path><path d="M12 21v-1"></path></svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-slate-900">ScanYuk</span>
                </div>
                <button @click="openMobile = false" type="button" class="-m-2.5 rounded-md p-2.5 text-slate-900 hover:bg-slate-100 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="px-6 py-6 flex flex-col space-y-8 flex-1">
                
                <div>
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">PRODUK</h3>
                    <div class="flex flex-col space-y-4 pl-2 border-l-2 border-slate-100">
                        <a href="{{ route('consumer') }}" class="transition-colors {{ request()->routeIs('consumer') ? 'text-teal-600 font-bold' : 'text-slate-600 font-medium hover:text-teal-600' }}">Personal</a>
                        <a href="{{ route('business') }}" class="transition-colors {{ request()->routeIs('business') ? 'text-teal-600 font-bold' : 'text-slate-600 font-medium hover:text-teal-600' }}">Bisnis</a>
                        <a href="{{ route('pricing') }}" class="transition-colors {{ request()->routeIs('pricing') ? 'text-teal-600 font-bold' : 'text-slate-600 font-medium hover:text-teal-600' }}">Harga</a>
                        <a href="{{ route('demo') }}" class="transition-colors {{ request()->routeIs('demo') ? 'text-teal-600 font-bold' : 'text-slate-600 font-medium hover:text-teal-600' }}">Demo</a>
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">SOLUSI</h3>
                    <div class="flex flex-col space-y-4 pl-2 border-l-2 border-slate-100">
                        <a href="{{ route('solutions') }}" class="transition-colors {{ request()->routeIs('solutions') ? 'text-teal-600 font-bold' : 'text-slate-600 font-medium hover:text-teal-600' }}">Solusi</a>
                        <a href="{{ route('how-it-works') }}" class="transition-colors {{ request()->routeIs('how-it-works') ? 'text-teal-600 font-bold' : 'text-slate-600 font-medium hover:text-teal-600' }}">Cara Kerja</a>
                        <a href="{{ route('case-studies') }}" class="transition-colors {{ request()->routeIs('case-studies') ? 'text-teal-600 font-bold' : 'text-slate-600 font-medium hover:text-teal-600' }}">Studi Kasus</a>
                        <a href="{{ route('partners') }}" class="transition-colors {{ request()->routeIs('partners') ? 'text-teal-600 font-bold' : 'text-slate-600 font-medium hover:text-teal-600' }}">Mitra</a>
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">INFORMASI</h3>
                    <div class="flex flex-col space-y-4 pl-2 border-l-2 border-slate-100">
                        <a href="{{ route('faq') }}" class="transition-colors {{ request()->routeIs('faq') ? 'text-teal-600 font-bold' : 'text-slate-600 font-medium hover:text-teal-600' }}">FAQ</a>
                        <a href="{{ route('contact') }}" class="transition-colors {{ request()->routeIs('contact') ? 'text-teal-600 font-bold' : 'text-slate-600 font-medium hover:text-teal-600' }}">Hubungi Kami</a>
                        <a href="{{ route('security') }}" class="transition-colors {{ request()->routeIs('security') ? 'text-teal-600 font-bold' : 'text-slate-600 font-medium hover:text-teal-600' }}">Keamanan</a>
                        <a href="{{ route('terms') }}" class="transition-colors {{ request()->routeIs('terms') ? 'text-teal-600 font-bold' : 'text-slate-600 font-medium hover:text-teal-600' }}">Syarat & Ketentuan</a>
                        <a href="{{ route('refund-policy') }}" class="transition-colors {{ request()->routeIs('refund-policy') ? 'text-teal-600 font-bold' : 'text-slate-600 font-medium hover:text-teal-600' }}">Kebijakan Pengembalian</a>
                    </div>
                </div>

            </div>

            <div class="mt-auto px-6 py-6 border-t border-slate-100 bg-slate-50">
                @guest
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="w-full text-center px-4 py-3 rounded-xl border-2 border-slate-200 bg-white text-sm font-bold text-slate-700 hover:border-slate-300 transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="w-full text-center px-4 py-3 rounded-xl btn-gradient text-white text-sm font-bold shadow-lg shadow-teal-200/50 hover:opacity-90 transition-opacity">
                            Daftar
                        </a>
                    </div>
                @else
                    <div class="flex flex-col gap-4">
                        <div class="flex items-center gap-3 bg-white p-3 rounded-xl border border-slate-200">
                            <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 font-bold flex-shrink-0">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="flex flex-col min-w-0">
                                <span class="text-sm font-bold text-slate-900 truncate">{{ Auth::user()->name }}</span>
                                <span class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}" class="w-full text-center py-3 rounded-xl btn-gradient text-white text-sm font-bold shadow-md shadow-teal-200/50 hover:opacity-90 transition-opacity">
                                Dashboard
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="w-full m-0">
                                @csrf
                                <button type="submit" class="w-full py-3 rounded-xl border-2 border-red-100 bg-red-50 text-red-600 text-sm font-bold hover:bg-red-100 transition-colors">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>