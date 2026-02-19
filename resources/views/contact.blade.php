@extends('layouts.app')

@section('content')

<section class="w-full py-16 md:py-24 bg-white relative overflow-hidden">
    
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full max-w-6xl pointer-events-none z-0 opacity-30">
        <div class="absolute top-0 right-1/4 w-96 h-96 bg-indigo-50 rounded-full mix-blend-multiply filter blur-3xl animate-blob"></div>
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-teal-50 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000"></div>
    </div>

    <div class="max-w-[85rem] mx-auto px-6 md:px-12 relative z-10">
        
        <div class="text-center mb-12 md:mb-16">
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-slate-900 mb-4">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-500 to-indigo-600">Contact Sales</span>
            </h1>
            <p class="text-lg text-slate-500 max-w-2xl mx-auto px-4">
                Hubungi tim kami untuk request demo atau diskusikan kebutuhan bisnis Anda.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            
            <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-100 p-6 md:p-8 shadow-xl shadow-slate-100/50">
                <h2 class="text-2xl font-bold text-slate-900 mb-6">Request Demo</h2>
                
                @if(session('success'))
                    <div class="mb-6 p-4 bg-teal-50 border border-teal-200 text-teal-700 rounded-lg flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('contact.send') }}" method="POST" class="space-y-6">
                    @csrf <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-800 mb-2">Nama</label>
                            <input type="text" name="name" required placeholder="John Doe" value="{{ old('name') }}"
                                class="w-full px-4 py-3 bg-slate-100 border-transparent rounded-lg text-slate-900 placeholder-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-800 mb-2">Perusahaan</label>
                            <input type="text" name="company" required placeholder="PT Example" value="{{ old('company') }}"
                                class="w-full px-4 py-3 bg-slate-100 border-transparent rounded-lg text-slate-900 placeholder-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-800 mb-2">Email</label>
                        <input type="email" name="email" required placeholder="john@example.com" value="{{ old('email') }}"
                            class="w-full px-4 py-3 bg-slate-100 border-transparent rounded-lg text-slate-900 placeholder-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all 
                            @error('email') border-red-500 ring-1 ring-red-500 @enderror">
                        
                        @error('email')
                            <p class="text-red-500 text-sm mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-800 mb-2">Industri</label>
                            <input type="text" name="industry" required placeholder="e.g. Retail, Education" value="{{ old('industry') }}"
                                class="w-full px-4 py-3 bg-slate-100 border-transparent rounded-lg text-slate-900 placeholder-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-800 mb-2">Estimasi Volume QR</label>
                            <input type="text" name="volume" required placeholder="e.g. 100-500" value="{{ old('volume') }}"
                                class="w-full px-4 py-3 bg-slate-100 border-transparent rounded-lg text-slate-900 placeholder-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-800 mb-2">Pesan</label>
                        <textarea name="message" required rows="4" placeholder="Ceritakan kebutuhan Anda..." 
                            class="w-full px-4 py-3 bg-slate-100 border-transparent rounded-lg text-slate-900 placeholder-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all resize-none">{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-3.5 px-6 rounded-lg btn-gradient text-white font-bold text-base shadow-lg shadow-indigo-200 hover:opacity-90 hover:-translate-y-0.5 transition-all">
                        Kirim Pesan
                    </button>
                </form>
            </div>

            <div class="lg:col-span-5 space-y-8 lg:pl-8 mt-8 lg:mt-0">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 mb-3">Hubungi Kami</h2>
                    <p class="text-slate-500 leading-relaxed">
                        Tim kami siap membantu Anda menemukan solusi AR QR yang tepat. Response time kurang dari 24 jam.
                    </p>
                </div>

                <div class="space-y-4">
                    
                    <div class="flex items-start gap-4 p-6 bg-white border border-slate-200 rounded-xl hover:border-teal-300 hover:shadow-md transition-all group">
                        <div class="w-12 h-12 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center flex-shrink-0 group-hover:bg-teal-600 group-hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 mb-1">Email</h3>
                            <a href="mailto:sales@scanyuk.com" class="text-slate-500 hover:text-teal-600 transition-colors">sales@scanyuk.com</a>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 p-6 bg-white border border-slate-200 rounded-xl hover:border-teal-300 hover:shadow-md transition-all group">
                        <div class="w-12 h-12 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center flex-shrink-0 group-hover:bg-teal-600 group-hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 mb-1">Phone / WhatsApp</h3>
                            <p class="text-slate-500">+62 812 xxxx xxxx</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 p-6 bg-white border border-slate-200 rounded-xl hover:border-teal-300 hover:shadow-md transition-all group">
                        <div class="w-12 h-12 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center flex-shrink-0 group-hover:bg-teal-600 group-hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 mb-1">Lokasi</h3>
                            <p class="text-slate-500">Jakarta, Indonesia</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

@endsection