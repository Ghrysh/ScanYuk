<nav x-data="{ open: false }" class="sticky top-0 z-50 w-full bg-white/80 backdrop-blur-md border-b border-slate-200 shadow-sm rounded-b-3xl">
    
    <div class="max-w-[100rem] mx-auto px-6 md:px-12 py-4 flex items-center justify-between">
        
        <a href="{{ route('home') }}" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
            <div class="w-8 h-8 rounded bg-teal-50 text-teal-600 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-qr-code w-7 h-7"><rect width="5" height="5" x="3" y="3" rx="1"></rect><rect width="5" height="5" x="16" y="3" rx="1"></rect><rect width="5" height="5" x="3" y="16" rx="1"></rect><path d="M21 16h-3a2 2 0 0 0-2 2v3"></path><path d="M21 21v.01"></path><path d="M12 7v3a2 2 0 0 1-2 2H7"></path><path d="M3 12h.01"></path><path d="M12 3h.01"></path><path d="M12 16v.01"></path><path d="M16 12h1"></path><path d="M21 12v.01"></path><path d="M12 21v-1"></path></svg>
            </div>
            <span class="text-xl font-bold tracking-tight text-slate-900">ScanYuk</span>
        </a>

        <div class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-500">
            <a href="{{ route('consumer') }}" 
               class="transition-colors {{ request()->is('consumer') ? 'text-teal-600 font-bold' : 'text-slate-500 hover:text-slate-900' }}">
               Consumer
            </a>
            <a href="{{ route('business') }}"
                class="transition-colors {{ request()->is('business') ? 'text-teal-600 font-bold' : 'text-slate-500 hover:text-slate-900' }}">
                Business
            </a>
            <a href="{{ route('solutions') }}"
                class="transition-colors {{ request()->is('solutions') ? 'text-teal-600 font-bold' : 'text-slate-500 hover:text-slate-900' }}">
                Solutions
            </a>
            <a href="{{ route('pricing') }}"
                class="transition-colors {{ request()->is('pricing') ? 'text-teal-600 font-bold' : 'text-slate-500 hover:text-slate-900' }}">
                Pricing
            </a>
            <a href="{{ route('demo') }}"
                class="transition-colors {{ request()->is('demo') ? 'text-teal-600 font-bold' : 'text-slate-500 hover:text-slate-900' }}">
                Demo
            </a>
            <a href="{{ route('how-it-works') }}"
                class="transition-colors {{ request()->is('how-it-works') ? 'text-teal-600 font-bold' : 'text-slate-500 hover:text-slate-900' }}">
                How It Works
            </a>
        </div>

        <div class="hidden md:flex items-center gap-4">
            @guest
                <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 hover:text-slate-900 transition-colors">
                    Login
                </a>
                <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-lg btn-gradient text-white text-sm font-medium shadow-lg shadow-indigo-200 hover:opacity-90 transition-opacity">
                    Sign Up
                </a>
            @else
                <div class="flex items-center gap-4">
                    <span class="text-sm font-medium text-slate-600">
                        Hi, {{ Auth::user()->name }}
                    </span>
                    
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
            <button @click="open = true" type="button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-slate-700">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>
    </div>


    <div x-show="open" 
         style="display: none;" 
         class="absolute top-0 left-0 w-full bg-white z-50 flex flex-col">
        
        <div class="px-6 py-4 flex items-center justify-between border-b border-slate-100">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded bg-teal-50 text-teal-600 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-qr-code w-7 h-7"><rect width="5" height="5" x="3" y="3" rx="1"></rect><rect width="5" height="5" x="16" y="3" rx="1"></rect><rect width="5" height="5" x="3" y="16" rx="1"></rect><path d="M21 16h-3a2 2 0 0 0-2 2v3"></path><path d="M21 21v.01"></path><path d="M12 7v3a2 2 0 0 1-2 2H7"></path><path d="M3 12h.01"></path><path d="M12 3h.01"></path><path d="M12 16v.01"></path><path d="M16 12h1"></path><path d="M21 12v.01"></path><path d="M12 21v-1"></path></svg>
                </div>
                <span class="text-xl font-bold tracking-tight text-slate-900">ScanYuk</span>
            </div>

            <button @click="open = false" type="button" class="-m-2.5 rounded-md p-2.5 text-slate-900">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="px-6 py-8 flex flex-col space-y-6">
            <a href="{{ route('consumer') }}" 
               class="transition-colors {{ request()->is('consumer') ? 'text-teal-600 font-bold' : 'text-slate-500 hover:text-slate-900' }}">
               Consumer
            </a>
            <a href="{{ route('business') }}"
                class="transition-colors {{ request()->is('business') ? 'text-teal-600 font-bold' : 'text-slate-500 hover:text-slate-900' }}">
                Business
            </a>
            <a href="{{ route('solutions') }}"
                class="transition-colors {{ request()->is('solutions') ? 'text-teal-600 font-bold' : 'text-slate-500 hover:text-slate-900' }}">
                Solutions
            </a>
            <a href="{{ route('pricing') }}"
                class="transition-colors {{ request()->is('pricing') ? 'text-teal-600 font-bold' : 'text-slate-500 hover:text-slate-900' }}">
                Pricing
            </a>
            <a href="{{ route('demo') }}"
                class="transition-colors {{ request()->is('demo') ? 'text-teal-600 font-bold' : 'text-slate-500 hover:text-slate-900' }}">
                Demo
            </a>
            <a href="{{ route('how-it-works') }}"
                class="transition-colors {{ request()->is('how-it-works') ? 'text-teal-600 font-bold' : 'text-slate-500 hover:text-slate-900' }}">
                How It Works
            </a>
        </div>

        <div class="mt-auto px-6 pb-8 pt-4 border-t border-slate-100">
            @guest
                <div class="flex items-center gap-4 mt-4">
                    <a href="{{ route('login') }}" class="flex-1 text-center px-5 py-3 rounded-lg text-base font-bold text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-colors">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="flex-1 text-center px-5 py-3 rounded-lg btn-gradient text-white text-base font-bold shadow-lg shadow-indigo-200 hover:opacity-90 transition-opacity">
                        Sign Up
                    </a>
                </div>
            @else
                <div class="flex flex-col gap-4 mt-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 font-bold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-slate-900">{{ Auth::user()->name }}</span>
                            <span class="text-xs text-slate-500">{{ Auth::user()->email }}</span>
                        </div>
                    </div>
                    
                    <form action="{{ route('logout') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="w-full py-3 rounded-lg border border-red-200 text-red-600 font-bold hover:bg-red-50 transition-colors">
                            Logout
                        </button>
                    </form>
                </div>
            @endguest
        </div>
    </div>
</nav>