<footer class="w-full py-8 md:py-12 px-6 md:px-12 bg-slate-50 border-t border-slate-200 mt-auto relative z-10">
    <div class="max-w-[90rem] mx-auto">
        
        <div class="grid grid-cols-1 md:grid-cols-5 gap-8 md:gap-12 mb-8 md:mb-12">
            
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-6 h-6 rounded bg-teal-50 text-teal-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-qr-code w-5 h-5"><rect width="5" height="5" x="3" y="3" rx="1"></rect><rect width="5" height="5" x="16" y="3" rx="1"></rect><rect width="5" height="5" x="3" y="16" rx="1"></rect><path d="M21 16h-3a2 2 0 0 0-2 2v3"></path><path d="M21 21v.01"></path><path d="M12 7v3a2 2 0 0 1-2 2H7"></path><path d="M3 12h.01"></path><path d="M12 3h.01"></path><path d="M12 16v.01"></path><path d="M16 12h1"></path><path d="M21 12v.01"></path><path d="M12 21v-1"></path></svg>
                    </div>
                    <span class="font-bold text-slate-900 text-lg">ScanYuk</span>
                </div>
                
                <p class="text-sm text-slate-600 mb-4">
                    Platform AR QR Code untuk personal & bisnis.
                </p>
                
                <div class="text-sm text-slate-500 leading-relaxed">
                    <p class="font-bold text-slate-700 mb-1">PT Berkah Teknologi Terkini</p>
                    <p>Gedung Jaya Lomba 5 unit A.6</p>
                    <p>Jl. M H Thamrin No.12, RT.002/RW.001</p>
                    <p>Kb. Sirih, Kec. Menteng</p>
                    <p>Jakarta Pusat 10340</p>
                    <p class="mt-3 font-semibold text-slate-700">(+62) 815-2022-225</p>
                </div>
            </div>

            <div class="col-span-1 md:col-span-3 grid grid-cols-2 sm:grid-cols-3 gap-8">
                
                <div class="col-span-1">
                    <h4 class="font-bold text-slate-900 mb-4 text-sm">Produk</h4>
                    <ul class="space-y-3 text-sm text-slate-500">
                        <li><a href="{{ route('consumer') }}" class="hover:text-teal-600 transition-colors">Personal</a></li>
                        <li><a href="{{ route('business') }}" class="hover:text-teal-600 transition-colors">Bisnis</a></li>
                        <li><a href="{{ route('pricing') }}" class="hover:text-teal-600 transition-colors">Harga</a></li>
                        <li><a href="{{ route('demo') }}" class="hover:text-teal-600 transition-colors">Demo</a></li>
                    </ul>
                </div>

                <div class="col-span-1">
                    <h4 class="font-bold text-slate-900 mb-4 text-sm">Solusi</h4>
                    <ul class="space-y-3 text-sm text-slate-500">
                        <li><a href="{{ route('solutions') }}" class="hover:text-teal-600 transition-colors">Solusi</a></li>
                        <li><a href="{{ route('how-it-works') }}" class="hover:text-teal-600 transition-colors">Cara Kerja</a></li>
                        <li><a href="{{ route('case-studies') }}" class="hover:text-teal-600 transition-colors">Studi Kasus</a></li>
                        <li><a href="{{ route('partners') }}" class="hover:text-teal-600 transition-colors">Mitra</a></li>
                    </ul>
                </div>

                <div class="col-span-2 sm:col-span-1">
                    <h4 class="font-bold text-slate-900 mb-4 text-sm">Informasi</h4>
                    <ul class="space-y-3 text-sm text-slate-500">
                        <li><a href="{{ route('faq') }}" class="hover:text-teal-600 transition-colors">FAQ</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-teal-600 transition-colors">Hubungi Kami</a></li>
                        <li><a href="{{ route('security') }}" class="hover:text-teal-600 transition-colors">Keamanan</a></li>
                    </ul>
                </div>

            </div>
        </div>

        <div class="border-t border-slate-200 pt-6 md:pt-8 flex flex-col md:flex-row justify-between items-center text-xs md:text-sm text-slate-500 gap-4 text-center md:text-left">
            <p>© 2026 ScanYuk — PT Berkah Teknologi Terkini. Hak cipta dilindungi.</p>
            <div class="flex flex-wrap justify-center gap-4 md:gap-6">
                <a href="{{ route('terms') }}" class="hover:text-teal-600 transition-colors">Syarat & Ketentuan</a>
                <a href="{{ route('refund-policy') }}" class="hover:text-teal-600 transition-colors">Kebijakan Pengembalian</a>
                <a href="{{ route('privacy') }}" class="hover:text-teal-600 transition-colors">Privasi</a>
            </div>
        </div>
        
    </div>
</footer>