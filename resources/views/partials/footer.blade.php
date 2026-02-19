<footer class="w-full py-12 px-6 md:px-12 bg-white border-t border-slate-200 mt-auto shadow-[0_-5px_15px_-5px_rgba(0,0,0,0.05)] relative z-10">
    <div class="max-w-[90rem] mx-auto">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-8 mb-12">
            <div class="col-span-2 md:col-span-2">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-6 h-6 rounded bg-teal-50 text-teal-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-qr-code w-7 h-7 text-primary"><rect width="5" height="5" x="3" y="3" rx="1"></rect><rect width="5" height="5" x="16" y="3" rx="1"></rect><rect width="5" height="5" x="3" y="16" rx="1"></rect><path d="M21 16h-3a2 2 0 0 0-2 2v3"></path><path d="M21 21v.01"></path><path d="M12 7v3a2 2 0 0 1-2 2H7"></path><path d="M3 12h.01"></path><path d="M12 3h.01"></path><path d="M12 16v.01"></path><path d="M16 12h1"></path><path d="M21 12v.01"></path><path d="M12 21v-1"></path></svg>
                    </div>
                    <span class="font-bold text-slate-900">ScanYuk</span>
                </div>
                <p class="text-sm text-slate-500 max-w-xs leading-relaxed">
                    Platform AR QR Code untuk personal & bisnis.
                </p>
            </div>

            <div>
                <h4 class="font-semibold text-slate-900 mb-4 text-sm">Product</h4>
                <ul class="space-y-3 text-sm text-slate-500">
                    <li><a href="{{ route('consumer') }}" class="hover:text-indigo-600">Consumer</a></li>
                    <li><a href="{{ route('business') }}" class="hover:text-indigo-600">Business</a></li>
                    <li><a href="{{ route('pricing') }}" class="hover:text-indigo-600">Pricing</a></li>
                    <li><a href="{{ route('demo') }}" class="hover:text-indigo-600">Demo</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold text-slate-900 mb-4 text-sm">Resources</h4>
                <ul class="space-y-3 text-sm text-slate-500">
                    <li><a href="{{ route('how-it-works') }}" class="hover:text-indigo-600">How It Works</a></li>
                    <li><a href="{{ route('solutions') }}" class="hover:text-indigo-600">Solutions</a></li>
                    <li><a href="#" class="hover:text-indigo-600">Case Studies</a></li>
                    <li><a href="#" class="hover:text-indigo-600">Partners</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold text-slate-900 mb-4 text-sm">Company</h4>
                <ul class="space-y-3 text-sm text-slate-500">
                    <li><a href="#" class="hover:text-indigo-600">Security</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-indigo-600">Contact Sales</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-slate-200 pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-slate-400">
            <p>© 2026 ScanYuk. All rights reserved.</p>
            <div class="flex gap-6 mt-4 md:mt-0">
                <a href="#" class="hover:text-slate-600">Privacy</a>
                <a href="#" class="hover:text-slate-600">Terms</a>
            </div>
        </div>
    </div>
</footer>