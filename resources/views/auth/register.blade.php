<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Account - ScanYuk</title>
    
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
<body class="font-sans antialiased text-slate-600 bg-white bg-grid-pattern min-h-screen flex items-center justify-center p-4 relative">

    <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
        <div class="absolute top-[-10%] left-[-5%] w-96 h-96 bg-purple-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob"></div>
        <div class="absolute bottom-[-10%] right-[-5%] w-96 h-96 bg-teal-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-2000"></div>
    </div>

    <div class="w-full max-w-[450px] bg-white rounded-2xl shadow-xl border border-slate-100 p-8 relative z-10">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center gap-2 mb-4">
                <div class="w-8 h-8 rounded bg-teal-50 text-teal-600 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-qr-code w-7 h-7"><rect width="5" height="5" x="3" y="3" rx="1"></rect><rect width="5" height="5" x="16" y="3" rx="1"></rect><rect width="5" height="5" x="3" y="16" rx="1"></rect><path d="M21 16h-3a2 2 0 0 0-2 2v3"></path><path d="M21 21v.01"></path><path d="M12 7v3a2 2 0 0 1-2 2H7"></path><path d="M3 12h.01"></path><path d="M12 3h.01"></path><path d="M12 16v.01"></path><path d="M16 12h1"></path><path d="M21 12v.01"></path><path d="M12 21v-1"></path></svg>
                </div>
                <span class="text-xl font-bold tracking-tight text-slate-900">ScanYuk</span>
            </div>
            
            <h2 class="text-2xl font-bold text-slate-900">Create Account</h2>
            
            <div class="mt-3 inline-flex items-center gap-2 px-3 py-1 rounded-full border text-xs font-semibold
                {{ $plan === 'free' ? 'bg-slate-100 text-slate-600 border-slate-200' : 'bg-teal-50 text-teal-700 border-teal-200' }}">
                <span>Paket:</span>
                <span class="uppercase">{{ $plan }}</span>
            </div>
        </div>

        <form x-data="registerForm()" @submit.prevent="submitForm" class="space-y-5" action="{{ route('register') }}" method="POST" id="regForm">
            @csrf
            
            <input type="hidden" name="role" value="{{ $plan }}">
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Full Name</label>
                <input x-model="formData.name" name="name" type="text" required 
                    class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all placeholder-slate-400 sm:text-sm" placeholder="John Doe">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
                <div class="flex gap-2">
                    <input x-model="formData.email" name="email" type="email" required :readonly="otpSent"
                        class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all placeholder-slate-400 sm:text-sm" placeholder="you@example.com">
                    
                    <button type="button" @click="sendOtp" :disabled="isLoading || (otpSent && timer > 0)"
                        class="whitespace-nowrap px-4 py-2 border border-teal-500 text-teal-600 rounded-lg text-sm font-semibold hover:bg-teal-50 disabled:opacity-50 disabled:bg-slate-50 disabled:border-slate-300 disabled:text-slate-400 disabled:cursor-not-allowed transition-all flex items-center gap-2">
                        <span x-show="isLoading">
                            <svg class="animate-spin h-4 w-4 text-teal-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </span>
                        <span x-text="otpSent ? (timer > 0 ? `Kirim Ulang (${timer})` : 'Kirim Ulang') : 'Kirim OTP'"></span>
                    </button>
                </div>
                <p x-show="emailError" x-text="emailError" class="text-red-500 text-xs mt-1 font-medium"></p>
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div x-show="otpSent" x-transition.opacity.duration.300ms class="space-y-2 p-4 bg-slate-50 rounded-xl border border-slate-100">
                <div class="flex justify-between items-center mb-1">
                    <label class="block text-sm font-bold text-slate-700">Kode Verifikasi</label>
                    <span class="text-xs text-slate-500">Cek email Anda</span>
                </div>
                
                <div class="flex gap-2 justify-between">
                    <template x-for="(digit, index) in 6">
                        <input type="text" maxlength="1" inputmode="numeric" x-model="otpDigits[index]" 
                            @input="focusNext($event.target, index)"
                            @keydown.backspace="focusPrev($event.target, index)"
                            class="w-10 h-12 md:w-12 md:h-12 text-center text-lg md:text-xl font-bold bg-white border border-slate-200 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-100 focus:outline-none transition-all text-slate-800 shadow-sm">
                    </template>
                </div>
                <input type="hidden" name="otp_combined" :value="otpDigits.join('')">
                @error('otp_combined') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
                <div class="relative" x-data="{ show: false }">
                    <input :type="show ? 'text' : 'password'" name="password" required 
                        class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all placeholder-slate-400 sm:text-sm" placeholder="••••••••">
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                        <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        <svg x-show="show" style="display: none;" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-5.557 5.919" /></svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full py-3 px-4 rounded-lg btn-gradient text-white font-bold shadow-lg shadow-indigo-200 hover:opacity-90 transition-all hover:-translate-y-0.5">
                Create Account
            </button>

            <div class="text-center text-sm pt-2">
                <span class="text-slate-500">Already have an account?</span>
                <a href="{{ route('login') }}" class="font-semibold text-teal-600 hover:text-teal-700 ml-1">
                    Sign in
                </a>
            </div>
        </form>
    </div>

    <script>
        function registerForm() {
            return {
                formData: { name: '', email: '' },
                otpSent: false,
                isLoading: false,
                timer: 0,
                otpDigits: ['', '', '', '', '', ''],
                emailError: '',

                async sendOtp() {
                    if (!this.formData.email) {
                        this.emailError = 'Email wajib diisi.'; return;
                    }
                    this.isLoading = true;
                    this.emailError = '';

                    try {
                        const response = await fetch('{{ route("send-otp") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ email: this.formData.email })
                        });
                        
                        const data = await response.json();

                        if (response.ok) {
                            this.otpSent = true;
                            this.startTimer();
                        } else {
                            this.emailError = data.errors?.email ? data.errors.email[0] : 'Gagal mengirim OTP.';
                        }
                    } catch (e) {
                        console.error(e);
                        this.emailError = 'Terjadi kesalahan jaringan/server.';
                    } finally {
                        this.isLoading = false;
                    }
                },

                startTimer() {
                    this.timer = 60; 
                    const interval = setInterval(() => {
                        if (this.timer > 0) this.timer--;
                        else clearInterval(interval);
                    }, 1000);
                },

                focusNext(target, index) {
                    if (target.value.length === 1 && index < 5) {
                        target.parentElement.children[index + 1].focus();
                    }
                },
                focusPrev(target, index) {
                    if (target.value.length === 0 && index > 0) {
                        target.parentElement.children[index - 1].focus();
                    }
                },

                submitForm() {
                    if (this.otpSent && this.otpDigits.join('').length === 6) {
                        document.getElementById('regForm').submit();
                    } else if(!this.otpSent) {
                        this.emailError = 'Harap verifikasi email terlebih dahulu.';
                    } else {
                        alert('Kode OTP belum lengkap.');
                    }
                }
            }
        }
    </script>
</body>
</html>