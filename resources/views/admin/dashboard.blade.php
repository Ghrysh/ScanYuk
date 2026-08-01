@extends('layouts.app')

@section('content')

@php
    $userRole = auth()->check() ? auth()->user()->role : 'guest';
    $defaultTab = $userRole === 'admin' ? 'overview' : 'livechat';
    $initialTab = request('active_tab', session('active_tab', $defaultTab));
@endphp

<div class="max-w-[100rem] mx-auto w-full px-4 sm:px-6 lg:px-8 py-8" 
     x-data="{ 
        activeTab: '{{ $initialTab }}',
        init() {
            // Cek apakah ada tab tersimpan di memory browser. Jika url tidak memaksa tab tertentu, pakai yang tersimpan.
            let savedTab = localStorage.getItem('adminActiveTab');
            if (savedTab && !window.location.search.includes('active_tab')) {
                this.activeTab = savedTab;
            }
            
            // Simpan setiap kali tab berubah
            this.$watch('activeTab', value => {
                localStorage.setItem('adminActiveTab', value);
            });
        }
     }">

    @if(session('success'))
    <div x-data="{ show: true }" 
         x-show="show" 
         x-init="setTimeout(() => show = false, 5000)"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="mb-6 flex items-center justify-between p-4 bg-teal-50 border border-teal-200 text-teal-700 rounded-2xl shadow-sm shadow-teal-100">
        
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-teal-500 text-white flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
            </div>
            <p class="font-bold text-sm md:text-base">{{ session('success') }}</p>
        </div>

        <button @click="show = false" class="text-teal-400 hover:text-teal-600 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    @endif
    
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 mb-6">Admin Dashboard</h1>
        
        <div class="flex space-x-2 border-b border-slate-200 pb-2 overflow-x-auto no-scrollbar">
            @if($userRole === 'admin')
            <button @click="activeTab = 'overview'" 
                :class="activeTab === 'overview' ? 'bg-teal-500 text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-100'"
                class="px-5 py-2 rounded-full text-sm font-semibold transition-all duration-200 whitespace-nowrap">
                Overview
            </button>
            <button @click="activeTab = 'monitoring'" 
                :class="activeTab === 'monitoring' ? 'bg-teal-500 text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-100'"
                class="px-5 py-2 rounded-full text-sm font-semibold transition-all duration-200 whitespace-nowrap">
                Monitoring
            </button>
            @endif

            <button @click="activeTab = 'seo'" 
                :class="activeTab === 'seo' ? 'bg-teal-500 text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-100'"
                class="px-5 py-2 rounded-full text-sm font-semibold transition-all duration-200 whitespace-nowrap">
                AI SEO Rank
            </button>

            <button @click="activeTab = 'chatbot'" 
                :class="activeTab === 'chatbot' ? 'bg-teal-500 text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-100'"
                class="px-5 py-2 rounded-full text-sm font-semibold transition-all duration-200 whitespace-nowrap">
                Chatbot
            </button>
            <button @click="activeTab = 'livechat'" :class="activeTab === 'livechat' ? 'bg-teal-500 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-100'" class="px-5 py-2 rounded-full text-sm font-semibold transition-all">
                Live Chat (CS)
            </button>

            @if($userRole === 'admin')
            <button @click="activeTab = 'users'" 
                :class="activeTab === 'users' ? 'bg-teal-500 text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-100'"
                class="px-5 py-2 rounded-full text-sm font-semibold transition-all duration-200 whitespace-nowrap">
                Users
            </button>
            <button @click="activeTab = 'paket'" 
                :class="activeTab === 'paket' ? 'bg-teal-500 text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-100'"
                class="px-5 py-2 rounded-full text-sm font-semibold transition-all duration-200 whitespace-nowrap">
                Paket
            </button>
            <button @click="activeTab = 'transaksi'" 
                :class="activeTab === 'transaksi' ? 'bg-teal-500 text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-100'"
                class="px-5 py-2 rounded-full text-sm font-semibold transition-all duration-200 whitespace-nowrap">
                Transaksi
            </button>
            @endif
        </div>
    </div>

    @if($userRole === 'admin')
    <div x-show="activeTab === 'overview'" x-transition.opacity.duration.300ms style="display: none;">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div class="flex items-center gap-2 text-slate-500 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    <span class="text-sm font-medium">Total Users</span>
                </div>
                <h3 class="text-4xl font-bold text-slate-900">{{ number_format($totalUsers) }}</h3>
            </div>

            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div class="flex items-center gap-2 text-slate-500 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                    <span class="text-sm font-medium">Active QR Codes</span>
                </div>
                <h3 class="text-4xl font-bold text-slate-900">{{ number_format($totalQrCodes) }}</h3>
            </div>

            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div class="flex items-center gap-2 text-slate-500 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                    <span class="text-sm font-medium">Total Scans</span>
                </div>
                <h3 class="text-4xl font-bold text-slate-900">{{ number_format($totalScans) }}</h3>
            </div>

            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div class="flex items-center gap-2 text-slate-500 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                    <span class="text-sm font-medium">Revenue</span>
                </div>
                <h3 class="text-3xl font-bold text-slate-900 truncate">
                    @if($totalRevenue >= 1000000)
                        Rp{{ number_format($totalRevenue / 1000000, 1) }}M
                    @else
                        Rp{{ number_format($totalRevenue, 0, ',', '.') }}
                    @endif
                </h3>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <button @click="activeTab = 'paket'" class="text-left bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:border-teal-400 hover:shadow-md transition-all group">
                <div class="w-10 h-10 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center mb-4 group-hover:bg-teal-500 group-hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                </div>
                <h4 class="text-lg font-bold text-slate-900">Manage Packages</h4>
                <p class="text-sm text-slate-500 mt-1">Edit paket dan harga</p>
            </button>
            <button @click="activeTab = 'users'" class="text-left bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:border-teal-400 hover:shadow-md transition-all group">
                <div class="w-10 h-10 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center mb-4 group-hover:bg-teal-500 group-hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </div>
                <h4 class="text-lg font-bold text-slate-900">User Management</h4>
                <p class="text-sm text-slate-500 mt-1">Kelola user & suspend</p>
            </button>
            <button @click="activeTab = 'transaksi'" class="text-left bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:border-teal-400 hover:shadow-md transition-all group">
                <div class="w-10 h-10 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center mb-4 group-hover:bg-teal-500 group-hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                </div>
                <h4 class="text-lg font-bold text-slate-900">Transactions</h4>
                <p class="text-sm text-slate-500 mt-1">Lihat riwayat pembayaran</p>
            </button>
        </div>
    </div>

    <div x-show="activeTab === 'users'" 
         x-data="{ 
            showModal: false, 
            showAddUserModal: false,
            userId: '', 
            userName: '', 
            action: '', 
            fetchUsers(query) {
                fetch(`/admin/users/search?query=${query}`)
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('user-table-body').innerHTML = html;
                    });
            },
            openModal(id, name, currentStatus) { 
                this.userId = id; 
                this.userName = name; 
                this.action = currentStatus === 'active' ? 'suspend' : 'activate'; 
                this.showModal = true; 
            }, 
            openDeleteModal(id, name) {
                this.userId = id; 
                this.userName = name; 
                this.action = 'delete'; 
                this.showModal = true; 
            },
            submitForm() { 
                if(this.action === 'delete') {
                    document.getElementById('delete-form-' + this.userId).submit(); 
                } else {
                    document.getElementById('toggle-form-' + this.userId).submit(); 
                }
            }
         }" 
         style="display: none;" 
         class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden relative" 
         x-transition.opacity.duration.300ms>
         
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h2 class="text-lg font-bold text-slate-900">User Management</h2>
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                <div class="relative w-full sm:w-64">
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    <input type="text" placeholder="Cari user..." @input.debounce.300ms="fetchUsers($event.target.value)" class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:bg-white focus:border-teal-500 focus:ring-1 focus:ring-teal-500 outline-none transition-all">
                </div>
                <button @click="showAddUserModal = true" class="w-full sm:w-auto bg-teal-500 hover:bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-sm whitespace-nowrap transition-colors">
                    + Tambah Akun
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Paket</th>
                        <th class="px-6 py-4">Image</th>
                        <th class="px-6 py-4">Voice</th>
                        <th class="px-6 py-4">Scan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="user-table-body" class="divide-y divide-slate-100">
                    @include('admin.partials._user_table')
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100 flex overflow-x-auto">
            {{ $users->appends(['active_tab' => 'users', 'txn_page' => request('txn_page')])->links() }}
        </div>

        <div x-show="showModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div x-show="showModal" x-transition.opacity.duration.300ms class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showModal = false"></div>
            <div x-show="showModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="relative bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 overflow-hidden">
                <div class="flex justify-center mb-5">
                    <div x-show="action === 'suspend' || action === 'delete'" class="w-14 h-14 rounded-full bg-red-50 flex items-center justify-center text-red-500 border-[6px] border-red-50/50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path x-show="action === 'suspend'" stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            <path x-show="action === 'delete'" stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <div x-show="action === 'activate'" class="w-14 h-14 rounded-full bg-teal-50 flex items-center justify-center text-teal-500 border-[6px] border-teal-50/50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>

                <div class="text-center mb-8">
                    <h3 class="text-xl font-extrabold text-slate-900 mb-2" x-text="action === 'delete' ? 'Hapus Permanen?' : (action === 'suspend' ? 'Suspend User?' : 'Aktifkan User?')"></h3>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        Apakah Anda yakin ingin <span class="font-bold text-slate-700" x-text="action === 'delete' ? 'MENGHAPUS PERMANEN' : (action === 'suspend' ? 'menangguhkan' : 'mengaktifkan kembali')"></span> akun milik 
                        <br><span class="font-bold text-brand-primary" x-text="userName"></span>?
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <button @click="showModal = false" type="button" class="w-full sm:flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition-colors">Batal</button>
                    <button @click="submitForm()" type="button" :class="(action === 'suspend' || action === 'delete') ? 'bg-red-600 hover:bg-red-700 shadow-red-200/50' : 'bg-teal-600 hover:bg-teal-700 shadow-teal-200/50'" class="w-full sm:flex-1 px-4 py-2.5 rounded-xl text-white font-semibold shadow-lg transition-all hover:-translate-y-0.5">
                        Ya, <span x-text="action === 'delete' ? 'Hapus' : (action === 'suspend' ? 'Suspend' : 'Aktifkan')"></span>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="showAddUserModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div x-show="showAddUserModal" x-transition.opacity class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showAddUserModal = false"></div>
            <div x-show="showAddUserModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden max-h-[90vh] flex flex-col">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 flex-shrink-0">
                    <h3 class="text-xl font-bold text-slate-900">Tambah Akun User</h3>
                    <button @click="showAddUserModal = false" class="text-slate-400 hover:text-slate-600"><svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                </div>
                <form action="{{ route('admin.users.store') }}" method="POST" class="p-6 space-y-4 overflow-y-auto">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:border-teal-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Email</label>
                        <input type="email" name="email" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:border-teal-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Password</label>
                        <input type="password" name="password" required minlength="8" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:border-teal-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Pilih Paket</label>
                        <select name="package_id" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:border-teal-500 outline-none cursor-pointer">
                            @foreach($packages as $pkg)
                                <option value="{{ $pkg->id }}">{{ $pkg->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="pt-4 flex flex-col sm:flex-row gap-3">
                        <button @click="showAddUserModal = false" type="button" class="w-full sm:flex-1 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50">Batal</button>
                        <button type="submit" class="w-full sm:flex-1 py-3 rounded-xl bg-teal-500 hover:bg-teal-600 text-white font-bold shadow-lg">Buat Akun</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'paket'" 
        x-data="{ 
            showEditModal: false, 
            pkgId: '', 
            pkgName: '', 
            pkgPrice: 0, 
            pkgImage: 0, 
            pkgVoice: 0, 
            pkgScan: 0,
            openEdit(pkg) {
                this.pkgId = pkg.id;
                this.pkgName = pkg.name;
                this.pkgPrice = pkg.price;
                
                let f0 = (pkg.features[0] || '').toLowerCase();
                let f1 = (pkg.features[1] || '').toLowerCase();
                let f2 = (pkg.features[2] || '').toLowerCase();

                this.pkgImage = (f0.includes('terbatas') || f0.includes('unlimited')) ? '' : (parseInt(f0) || 0);
                this.pkgVoice = (f1.includes('terbatas') || f1.includes('unlimited')) ? '' : (parseInt(f1) || 0);
                this.pkgScan  = (f2.includes('terbatas') || f2.includes('unlimited')) ? '' : (parseInt(f2) || 0);
                
                this.showEditModal = true;
            }
        }"
        style="display: none;" 
        class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden relative" 
        x-transition.opacity.duration.300ms>
        
        <div class="p-6 border-b border-slate-100">
            <h2 class="text-lg font-bold text-slate-900">Manage Packages</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 whitespace-nowrap">Paket</th>
                        <th class="px-6 py-4 whitespace-nowrap">Harga</th>
                        <th class="px-6 py-4 whitespace-nowrap">Image</th>
                        <th class="px-6 py-4 whitespace-nowrap">Voice</th>
                        <th class="px-6 py-4 whitespace-nowrap">Total Scan</th>
                        <th class="px-6 py-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($packages as $pkg)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-900">{{ $pkg->name }}</td>
                        <td class="px-6 py-4">Rp{{ number_format($pkg->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">{{ (int) filter_var($pkg->features[0] ?? 0, FILTER_SANITIZE_NUMBER_INT) }}</td>
                        <td class="px-6 py-4">{{ (int) filter_var($pkg->features[1] ?? 0, FILTER_SANITIZE_NUMBER_INT) }}</td>
                        <td class="px-6 py-4">{{ (int) filter_var($pkg->features[2] ?? 0, FILTER_SANITIZE_NUMBER_INT) }}</td>
                        <td class="px-6 py-4">
                            <button @click="openEdit({{ $pkg->toJson() }})" class="text-slate-400 hover:text-teal-600 transition-colors" title="Edit Paket">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div x-show="showEditModal" x-transition.opacity.duration.300ms class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showEditModal = false"></div>

            <div x-show="showEditModal" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden max-h-[90vh] flex flex-col">
                
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 flex-shrink-0">
                    <h3 class="text-xl font-bold text-slate-900 truncate">Edit Paket: <span x-text="pkgName" class="text-teal-600"></span></h3>
                    <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form :action="`/admin/packages/${pkgId}`" method="POST" class="p-6 space-y-4 overflow-y-auto">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Nama Paket</label>
                            <input type="text" name="name" x-model="pkgName" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Harga (Rp)</label>
                            <input type="number" name="price" x-model="pkgPrice" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1 uppercase">Limit Image</label>
                            <input type="number" name="image_limit" x-model="pkgImage" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:border-teal-500 outline-none" placeholder="Kosongkan = Unlimited">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1 uppercase">Limit Voice</label>
                            <input type="number" name="voice_limit" x-model="pkgVoice" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:border-teal-500 outline-none" placeholder="Kosongkan = Unlimited">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1 uppercase">Total Scan</label>
                            <input type="number" name="scan_limit" x-model="pkgScan" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:border-teal-500 outline-none" placeholder="Kosongkan = Unlimited">
                        </div>
                    </div>

                    <div class="pt-4 flex flex-col sm:flex-row gap-3">
                        <button @click="showEditModal = false" type="button" class="w-full sm:flex-1 py-3 px-4 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-all">Batal</button>
                        <button type="submit" class="w-full sm:flex-1 py-3 px-4 rounded-xl btn-gradient text-white font-bold shadow-lg shadow-indigo-200 hover:-translate-y-0.5 transition-all">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'transaksi'" 
         x-data="{
            showRejectModal: false,
            rejectTxnId: null,
            showDetailModal: false,
            detailTxn: {},
            fetchTransactions(query) {
                fetch(`/admin/transactions/search?query=${query}`)
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('transaction-table-body').innerHTML = html;
                    });
            },
            openRejectModal(id) {
                this.rejectTxnId = id;
                this.showRejectModal = true;
            },
            openDetailModal(data) {
                this.detailTxn = data;
                this.showDetailModal = true;
            }
         }"
         style="display: none;" 
         class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden" 
         x-transition.opacity.duration.300ms>
         
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h2 class="text-lg font-bold text-slate-900">Riwayat Transaksi</h2>
            <div class="relative w-full sm:w-64">
                <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                <input type="text" placeholder="Cari transaksi..." @input.debounce.300ms="fetchTransactions($event.target.value)" class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:bg-white focus:border-teal-500 focus:ring-1 focus:ring-teal-500 outline-none transition-all">
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 whitespace-nowrap">ID</th>
                        <th class="px-6 py-4 whitespace-nowrap">User</th>
                        <th class="px-6 py-4 whitespace-nowrap">Paket</th>
                        <th class="px-6 py-4 whitespace-nowrap">Jumlah</th>
                        <th class="px-6 py-4 whitespace-nowrap">Status</th>
                        <th class="px-6 py-4 whitespace-nowrap">Tanggal</th>
                        <th class="px-6 py-4 whitespace-nowrap text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="transaction-table-body" class="divide-y divide-slate-100">
                    @include('admin.partials._transaction_table')
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100 flex overflow-x-auto">
            {{ $transactions->appends(['active_tab' => 'transaksi', 'users_page' => request('users_page')])->links() }}
        </div>

        <div x-show="showRejectModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div x-show="showRejectModal" x-transition.opacity.duration.300ms class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showRejectModal = false"></div>
            <div x-show="showRejectModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 overflow-hidden">
                <h3 class="font-bold text-slate-900 text-lg mb-4">Tolak Transaksi</h3>
                <form :action="`/admin/transactions/${rejectTxnId}/reject`" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Alasan Penolakan</label>
                        <textarea name="reject_reason" required rows="3" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:border-red-500 focus:ring-1 focus:ring-red-500 outline-none transition-all" placeholder="Tulis pesan untuk pengguna..."></textarea>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" @click="showRejectModal = false" class="flex-1 py-2 rounded-lg border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-colors">Batal</button>
                        <button type="submit" class="flex-1 py-2 rounded-lg bg-red-500 text-white font-bold hover:bg-red-600 transition-colors">Tolak Transaksi</button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="showDetailModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div x-show="showDetailModal" x-transition.opacity.duration.300ms class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showDetailModal = false"></div>
            <div x-show="showDetailModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 overflow-hidden max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-slate-900 text-lg">Detail Transaksi</h3>
                    <button @click="showDetailModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4 border-b border-slate-100 pb-4">
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Nama Pengguna</p>
                            <p class="font-semibold text-slate-900" x-text="detailTxn.name"></p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Email</p>
                            <p class="font-semibold text-slate-900" x-text="detailTxn.email"></p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Status Transaksi</p>
                            <span class="px-2 py-0.5 rounded text-xs font-semibold"
                                  :class="{
                                      'bg-amber-100 text-amber-700': detailTxn.status === 'Pending',
                                      'bg-teal-100 text-teal-700': ['Berhasil', 'Paid', 'Success', 'unsettled'].includes(detailTxn.status),
                                      'bg-red-100 text-red-700': ['Ditolak', 'Batal', 'Failed'].includes(detailTxn.status)
                                  }" x-text="detailTxn.status"></span>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Total Tagihan</p>
                            <p class="font-bold text-indigo-600" x-text="detailTxn.amount"></p>
                        </div>
                    </div>
                    
                    <div class="bg-slate-50 p-4 rounded-lg border border-slate-200 flex items-center justify-between text-center">
                        <div class="flex-1">
                            <p class="text-xs text-slate-500 mb-1">Paket Saat Ini</p>
                            <p class="font-bold text-slate-800 capitalize" x-text="detailTxn.current_role"></p>
                        </div>
                        <div class="px-4 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs text-slate-500 mb-1">Upgrade Paket</p>
                            <p class="font-bold text-teal-600 capitalize" x-text="detailTxn.package"></p>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm font-bold text-slate-700 mb-2">Bukti Pembayaran</p>
                        <template x-if="detailTxn.proof_url">
                            <a :href="detailTxn.proof_url" target="_blank" class="block border border-slate-200 rounded-lg overflow-hidden hover:border-teal-500 transition-colors group relative">
                                <img :src="detailTxn.proof_url" alt="Bukti Pembayaran" class="w-full object-contain max-h-64">
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="bg-white text-slate-900 px-3 py-1.5 rounded-lg text-sm font-bold shadow-lg flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                        Buka Gambar Penuh
                                    </span>
                                </div>
                            </a>
                        </template>
                        <template x-if="!detailTxn.proof_url">
                            <div class="bg-slate-50 border border-slate-200 border-dashed rounded-lg p-6 text-center text-slate-400">
                                Tidak ada bukti yang diunggah
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'monitoring'" style="display: none;" x-transition.opacity.duration.300ms>
        
        <div class="mb-6 flex flex-col md:flex-row justify-between md:items-end gap-4">
            <div class="flex bg-slate-100 p-1 rounded-xl w-fit">
                <a href="{{ route('admin.dashboard', ['active_tab' => 'monitoring', 'filter' => 'today']) }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ $filter == 'today' ? 'bg-white shadow-sm text-teal-600' : 'text-slate-500 hover:text-slate-700' }}">Hari Ini</a>
                <a href="{{ route('admin.dashboard', ['active_tab' => 'monitoring', 'filter' => 'month']) }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ $filter == 'month' ? 'bg-white shadow-sm text-teal-600' : 'text-slate-500 hover:text-slate-700' }}">Bulan Ini</a>
                <a href="{{ route('admin.dashboard', ['active_tab' => 'monitoring', 'filter' => 'year']) }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ $filter == 'year' ? 'bg-white shadow-sm text-teal-600' : 'text-slate-500 hover:text-slate-700' }}">Tahun Ini</a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm flex flex-col justify-center items-center text-center h-full">
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-2">Total Pengunjung</p>
                <h3 class="text-6xl font-black text-slate-900 mb-2">{{ number_format($totalVisitors) }}</h3>
                <p class="text-xs text-teal-600 font-medium bg-teal-50 px-3 py-1 rounded-full">
                    Sesi Aktif: {{ $filter == 'today' ? 'Hari ini' : ($filter == 'month' ? 'Bulan ini' : 'Tahun ini') }}
                </p>
            </div>

            <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-6 shadow-sm relative h-64">
                <canvas id="trafficChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm mb-8" x-data="{ showJourneyModal: false, activeJourney: null }">
            <div class="p-6 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-900">Perjalanan Pengunjung (Visitor Journey)</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 whitespace-nowrap">IP / Sesi</th>
                            <th class="px-6 py-4 w-[40%]">Alur Singkat</th>
                            <th class="px-6 py-4 whitespace-nowrap">Mulai</th>
                            <th class="px-6 py-4 whitespace-nowrap">Aktivitas Terakhir</th>
                            <th class="px-6 py-4 text-right whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($visitorLogs as $log)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ $log->ip_address }}</div>
                                <div class="text-xs text-slate-400 truncate w-24" title="{{ $log->session_id }}">ID: {{ substr($log->session_id, 0, 8) }}...</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap items-center gap-2">
                                    @if($log->page_journey && is_array($log->page_journey))
                                        @foreach(array_slice($log->page_journey, 0, 3) as $step)
                                            <div class="flex items-center gap-1 group relative">
                                                <span class="px-2 py-1 bg-indigo-50 border border-indigo-100 text-indigo-700 text-[11px] font-medium rounded shadow-sm truncate max-w-[120px]">
                                                    {{ $step['path'] == '/' ? '/ (Home)' : $step['path'] }}
                                                </span>
                                                
                                                @if(!$loop->last || count($log->page_journey) > 3)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                                @endif
                                            </div>
                                        @endforeach
                                        
                                        @if(count($log->page_journey) > 3)
                                            <span class="text-[11px] text-slate-400 font-bold bg-slate-100 px-2 py-1 rounded">
                                                +{{ count($log->page_journey) - 3 }} lagi
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-slate-400 italic text-xs">Belum ada data alur</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">{{ $log->created_at->format('H:i:s') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 bg-teal-50 text-teal-600 rounded-lg text-xs font-bold">{{ $log->updated_at->diffForHumans() }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($log->page_journey && count($log->page_journey) > 0)
                                    <button @click="activeJourney = {{ json_encode($log->page_journey) }}; showJourneyModal = true" class="text-indigo-600 font-bold text-xs bg-indigo-50 px-3 py-1.5 rounded-lg hover:bg-indigo-100 transition-colors whitespace-nowrap">Lihat Full</button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-8 text-center text-slate-400">Belum ada data pengunjung pada rentang waktu ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100 flex overflow-x-auto">
                {{ $visitorLogs->appends(['active_tab' => 'monitoring', 'filter' => $filter, 'users_page' => request('users_page'), 'txn_page' => request('txn_page')])->links() }}
            </div>

            <div x-show="showJourneyModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                <div x-show="showJourneyModal" x-transition.opacity class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showJourneyModal = false"></div>
                <div x-show="showJourneyModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 overflow-hidden flex flex-col max-h-[85vh]">
                    <div class="flex justify-between items-center mb-5 border-b border-slate-100 pb-4">
                        <h3 class="text-xl font-bold text-slate-900">Timeline Pengunjung</h3>
                        <button @click="showJourneyModal = false" class="text-slate-400 hover:text-slate-600 bg-slate-100 p-1.5 rounded-lg">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    
                    <div class="overflow-y-auto pr-4 pl-2 pb-4 space-y-4" x-if="activeJourney">
                        <div class="relative border-l-2 border-indigo-100 ml-3 pl-6 py-2 space-y-6">
                            <template x-for="(step, index) in activeJourney" :key="index">
                                <div class="relative">
                                    <div class="absolute -left-[33px] top-1.5 w-4 h-4 rounded-full bg-indigo-500 border-[3px] border-white shadow-sm"></div>
                                    
                                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-3.5 shadow-sm">
                                        <div class="flex justify-between items-start mb-1.5 gap-2">
                                            <span class="font-bold text-indigo-700 text-sm break-all leading-tight" x-text="step.path === '/' ? '/ (Home)' : step.path"></span>
                                            <span class="text-xs font-bold text-slate-500 bg-white border border-slate-200 px-2 py-0.5 rounded-md whitespace-nowrap shadow-sm" x-text="step.time"></span>
                                        </div>
                                        <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Langkah ke-<span x-text="index + 1"></span></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

            <div x-show="activeTab === 'seo'" style="display: none;" x-transition.opacity.duration.300ms
         x-data="{
             isAnalyzing: false,
             pagePath: '/',
             targetKeyword: '',
             recommendation: null,
             activeTasks: [],
             completedTasks: [],
             currentTaskType: null,
             showManualForm: false,
             async init() {
                 this.fetchHistory();
             },
             async fetchHistory() {
                 try {
                     let res = await fetch('/admin/seo/recommendations');
                     let raw = await res.json();
                     let active = [];
                     let completed = [];
                     raw.forEach(item => {
                         if (item.status === 'applied' && item.manual_status === 'selesai') {
                             completed.push(item);
                         } else {
                             if (item.status === 'pending') {
                                 active.push({...item, task_type: 'auto'});
                             }
                             if (item.manual_status !== 'selesai') {
                                 active.push({...item, task_type: 'programmer'});
                             }
                         }
                     });
                     this.activeTasks = active;
                     this.completedTasks = completed;
                 } catch(e) {}
             },
             async analyze() {
                 if(!this.pagePath || !this.targetKeyword) {
                     alert('Harap isi halaman dan target keyword');
                     return;
                 }
                 this.isAnalyzing = true;
                 this.recommendation = null;
                 
                 let formData = new FormData();
                 formData.append('page_path', this.pagePath);
                 formData.append('target_keyword', this.targetKeyword);
                 formData.append('_token', document.querySelector('meta[name=csrf-token]').content);
                 
                 try {
                     let res = await fetch('/admin/seo/analyze', { method: 'POST', body: formData });
                     let data = await res.json();
                     if (data.success) {
                         this.recommendation = data.data;
                         this.fetchHistory();
                         if(data.warning) alert(data.warning);
                     } else {
                         alert(data.message || 'Gagal menganalisa');
                     }
                 } catch (e) {
                     alert('Terjadi kesalahan server');
                 } finally {
                     this.isAnalyzing = false;
                 }
             },
             async applyRec(id) {
                 if(!confirm('Terapkan perubahan ini secara otomatis ke halaman?')) return;
                 let formData = new FormData();
                 formData.append('_token', document.querySelector('meta[name=csrf-token]').content);
                 try {
                     let res = await fetch('/admin/seo/apply/' + id, { method: 'POST', body: formData });
                     let data = await res.json();
                     if(data.success) {
                         alert(data.message);
                         this.recommendation.status = 'applied';
                         this.fetchHistory();
                     }
                 } catch (e) {
                     alert('Gagal apply');
                 }
             },
             async updateManualStatus(id, newStatus) {
                 let formData = new FormData();
                 formData.append('_token', document.querySelector('meta[name=csrf-token]').content);
                 formData.append('status', newStatus);
                 try {
                     let res = await fetch('/admin/seo/update-manual-status/' + id, { method: 'POST', body: formData });
                     let data = await res.json();
                     if(data.success) {
                         this.recommendation.manual_status = newStatus;
                         this.fetchHistory();
                     }
                 } catch (e) {
                     alert('Gagal update status manual');
                 }
             }
         }">
         <div class="mb-4 flex justify-end">
             <button @click="showManualForm = !showManualForm" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-4 py-2 rounded-lg transition-colors border border-indigo-100">
                 <span x-text="showManualForm ? 'Tutup Form Analisa Manual' : '+ Punya Strategi Sendiri? Analisa Manual'"></span>
             </button>
         </div>

         <div x-show="showManualForm" style="display: none;" x-transition class="mb-6 flex flex-col md:flex-row justify-between md:items-end gap-4 bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
             <div class="flex-1 w-full flex flex-col sm:flex-row gap-4 items-end">
                <div class="w-full sm:w-1/3">
                    <label class="block text-sm font-bold text-slate-700 mb-1">Pilih Halaman</label>
                    <select x-model="pagePath" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg outline-none focus:border-teal-500">
                        <option value="/">Home (/)</option>
                        <option value="/pricing">Pricing (/pricing)</option>
                        <option value="/consumer">Consumer (/consumer)</option>
                        <option value="/business">Business (/business)</option>
                        <option value="/faq">FAQ (/faq)</option>
                    </select>
                </div>
                <div class="w-full sm:w-1/3">
                    <label class="block text-sm font-bold text-slate-700 mb-1">Target Keyword</label>
                    <input type="text" x-model="targetKeyword" placeholder="Misal: AR QR Code Scanner" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg outline-none focus:border-teal-500">
                </div>
                <div class="w-full sm:w-1/3">
                    <button @click="analyze()" :disabled="isAnalyzing" class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white font-bold rounded-lg shadow-md transition-all flex items-center justify-center gap-2">
                        <svg x-show="isAnalyzing" style="display: none;" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span x-text="isAnalyzing ? 'AI sedang menganalisa...' : 'Analisa dengan AI'"></span>
                    </button>
                </div>
             </div>
         </div>

         <template x-if="!recommendation">
             <div>
                 <!-- Tabel Tugas Baru -->
                 <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-8">
                     <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                         <h3 class="font-bold text-slate-800">Daftar Rekomendasi SEO (Tugas Baru)</h3>
                         <button @click="fetchHistory()" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Refresh
                         </button>
                     </div>
                     <div class="overflow-x-auto">
                         <table class="w-full text-left text-sm">
                             <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                                 <tr>
                                     <th class="px-6 py-3 font-semibold">Tipe Tugas</th>
                                     <th class="px-6 py-3 font-semibold">Halaman</th>
                                     <th class="px-6 py-3 font-semibold">Target Keyword</th>
                                     <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                                 </tr>
                             </thead>
                             <tbody class="divide-y divide-slate-100">
                                 <template x-for="item in activeTasks" :key="item.id + '_' + item.task_type">
                                     <tr class="hover:bg-slate-50 transition-colors">
                                         <td class="px-6 py-4">
                                             <div x-show="item.task_type === 'auto'" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                <span>⚙️ Tugas Otomatis</span>
                                             </div>
                                             <div x-show="item.task_type === 'programmer'" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                                <span>👨‍💻 Tugas Programmer</span>
                                             </div>
                                             <div class="text-slate-400 text-xs mt-1" x-text="new Date(item.created_at).toLocaleString('id-ID')"></div>
                                         </td>
                                         <td class="px-6 py-4 font-semibold text-indigo-600" x-text="item.page_path"></td>
                                         <td class="px-6 py-4">
                                             <span class="text-slate-700 font-medium" x-text="item.target_keyword"></span>
                                         </td>
                                         <td class="px-6 py-4 text-right">
                                             <button @click="recommendation = item; currentTaskType = item.task_type" class="text-indigo-600 hover:text-indigo-800 font-semibold text-sm bg-indigo-50 px-3 py-1.5 rounded-lg transition-colors">Lihat Detail &rarr;</button>
                                         </td>
                                     </tr>
                                 </template>
                                 <tr x-show="activeTasks.length === 0">
                                     <td colspan="4" class="px-6 py-8 text-center text-slate-500">Hebat! Semua tugas SEO sudah selesai dikerjakan.</td>
                                 </tr>
                             </tbody>
                         </table>
                     </div>
                 </div>

                 <!-- Tabel Riwayat Selesai -->
                 <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-8">
                     <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                         <h3 class="font-bold text-slate-800">Riwayat Pekerjaan Selesai</h3>
                     </div>
                     <div class="overflow-x-auto">
                         <table class="w-full text-left text-sm opacity-70">
                             <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                                 <tr>
                                     <th class="px-6 py-3 font-semibold">Waktu / Tipe</th>
                                     <th class="px-6 py-3 font-semibold">Halaman</th>
                                     <th class="px-6 py-3 font-semibold">Target Keyword</th>
                                     <th class="px-6 py-3 font-semibold">Status Auto</th>
                                     <th class="px-6 py-3 font-semibold">Tugas Programmer</th>
                                 </tr>
                             </thead>
                             <tbody class="divide-y divide-slate-100">
                                 <template x-for="item in completedTasks" :key="item.id">
                                     <tr class="hover:bg-slate-50 transition-colors">
                                         <td class="px-6 py-4">
                                             <div class="text-slate-600 mb-1" x-text="new Date(item.created_at).toLocaleString('id-ID')"></div>
                                             <span x-show="item.ai_type === 'proactive'" class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-700">AI Proactive</span>
                                             <span x-show="item.ai_type === 'manual'" class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">Manual Req</span>
                                         </td>
                                         <td class="px-6 py-4 font-semibold text-indigo-600" x-text="item.page_path"></td>
                                         <td class="px-6 py-4">
                                             <span class="text-slate-700 font-medium" x-text="item.target_keyword"></span>
                                             <div class="mt-1 flex items-center gap-1 text-[10px] text-slate-500">Skor: <span class="font-bold px-1 rounded text-white" :class="item.overall_score >= 80 ? 'bg-teal-500' : (item.overall_score >= 50 ? 'bg-amber-500' : 'bg-red-500')" x-text="item.overall_score"></span></div>
                                         </td>
                                         <td class="px-6 py-4">
                                             <span class="text-teal-600 font-bold text-xs bg-teal-50 px-2 py-1 rounded">Applied</span>
                                         </td>
                                         <td class="px-6 py-4">
                                             <span class="text-emerald-600 font-bold text-xs bg-emerald-50 px-2 py-1 rounded">Selesai</span>
                                         </td>
                                     </tr>
                                 </template>
                                 <tr x-show="completedTasks.length === 0">
                                     <td colspan="5" class="px-6 py-8 text-center text-slate-500">Belum ada riwayat analisa SEO.</td>
                                 </tr>
                             </tbody>
                         </table>
                     </div>
                 </div>
             </div>
         </template>

         <template x-if="recommendation">
             <div class="bg-slate-50/50 rounded-xl mb-8">
                 <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                     <div>
                         <button @click="recommendation = null" class="text-sm font-semibold text-slate-500 hover:text-slate-800 mb-2 flex items-center gap-1">&larr; Kembali ke List</button>
                         <h3 class="text-2xl font-black text-slate-900 flex items-center gap-3">
                            <span class="text-indigo-600" x-text="recommendation.page_path"></span>
                            <span x-show="recommendation.ai_type === 'proactive'" class="px-2.5 py-1 rounded-md text-xs font-bold bg-indigo-100 text-indigo-700 tracking-wide uppercase">AI Proactive</span>
                         </h3>
                     </div>
                     <div class="flex items-center gap-3 mt-4 md:mt-0">
                        <span class="text-sm font-semibold text-slate-500">Skor SEO Target:</span>
                        <div class="px-4 py-1.5 rounded-full text-white font-black text-lg" :class="recommendation.overall_score >= 80 ? 'bg-teal-500' : (recommendation.overall_score >= 50 ? 'bg-amber-500' : 'bg-red-500')" x-text="recommendation.overall_score + '/100'"></div>
                     </div>
                 </div>
                 
                 <!-- BAGIAN 1: AUTO APPLY VARIABLES -->
                 <div x-show="currentTaskType === 'auto'" class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-6">
                    <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                                <svg class="w-6 h-6 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                Perubahan Meta Otomatis
                            </h3>
                            <p class="text-sm text-slate-500 mt-1">Bagian ini dapat diterapkan secara instan ke website tanpa coding.</p>
                        </div>
                        <div>
                            <button x-show="recommendation.status !== 'applied'" @click="applyRec(recommendation.id)" class="px-6 py-2 bg-teal-500 hover:bg-teal-600 text-white font-bold rounded-xl shadow-lg shadow-teal-200 transition-all flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Terapkan Otomatis
                            </button>
                            <div x-show="recommendation.status === 'applied'" class="px-6 py-2 bg-slate-100 text-teal-600 font-bold rounded-xl flex items-center gap-2">
                                <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Sudah Diterapkan
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-200">
                            <h4 class="font-bold text-slate-800 mb-2 flex items-center gap-2 text-sm uppercase">Meta Title</h4>
                            <p class="text-slate-600 font-medium" x-text="recommendation.recommendations.meta_title"></p>
                        </div>
                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-200">
                            <h4 class="font-bold text-slate-800 mb-2 flex items-center gap-2 text-sm uppercase">Meta Description</h4>
                            <p class="text-slate-600 text-sm leading-relaxed" x-text="recommendation.recommendations.meta_description"></p>
                        </div>
                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-200 md:col-span-2">
                            <h4 class="font-bold text-slate-800 mb-2 flex items-center gap-2 text-sm uppercase">Heading (H1) Utama</h4>
                            <p class="text-slate-600 font-semibold text-lg" x-text="recommendation.recommendations.h1_heading"></p>
                        </div>
                    </div>
                    <div class="bg-slate-50 p-5 rounded-xl border border-slate-200">
                        <h4 class="font-bold text-slate-800 mb-3 flex items-center gap-2 text-sm uppercase">FAQ Schema (JSON-LD)</h4>
                        <div class="space-y-3">
                            <template x-for="(faq, index) in recommendation.recommendations.faq_schema" :key="index">
                                <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm">
                                    <p class="font-bold text-slate-800 text-sm mb-1" x-text="'Q: ' + faq.question"></p>
                                    <p class="text-slate-600 text-sm" x-text="'A: ' + faq.answer"></p>
                                </div>
                            </template>
                        </div>
                    </div>
                 </div>

                 <!-- BAGIAN 2: MANUAL TASKS -->
                 <div x-show="currentTaskType === 'programmer'" class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-6">
                    <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                                Tugas Teknisi / Programmer
                            </h3>
                            <p class="text-sm text-slate-500 mt-1">Bagian ini memerlukan tindakan manual dari developer.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-semibold text-slate-600">Status Tugas:</span>
                            <select :value="recommendation.manual_status" @change="updateManualStatus(recommendation.id, $event.target.value)" class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg outline-none font-bold text-sm"
                                :class="{'text-slate-600': recommendation.manual_status === 'pending', 'text-blue-600': recommendation.manual_status === 'proses', 'text-emerald-600': recommendation.manual_status === 'selesai'}">
                                <option value="pending">Pending</option>
                                <option value="proses">Dalam Proses</option>
                                <option value="selesai">Selesai</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-indigo-50/50 p-5 rounded-xl border border-indigo-100">
                            <h4 class="font-bold text-indigo-800 text-sm mb-3 uppercase flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg> Link Strategy</h4>
                            <p class="text-indigo-700 text-sm mb-3"><strong class="block text-indigo-900 mb-1">Backlink:</strong> <span x-text="recommendation.recommendations.backlink_strategy"></span></p>
                            <p class="text-indigo-700 text-sm"><strong class="block text-indigo-900 mb-1">Internal:</strong> <span x-text="recommendation.recommendations.internal_link_strategy"></span></p>
                        </div>
                        <div class="bg-amber-50/50 p-5 rounded-xl border border-amber-100">
                            <h4 class="font-bold text-amber-800 text-sm mb-3 uppercase flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> Image Optimization</h4>
                            <p class="text-amber-700 text-sm leading-relaxed" x-text="recommendation.recommendations.image_optimization"></p>
                        </div>
                        <div class="bg-rose-50/50 p-5 rounded-xl border border-rose-100">
                            <h4 class="font-bold text-rose-800 text-sm mb-3 uppercase flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg> Page Speed</h4>
                            <p class="text-rose-700 text-sm leading-relaxed" x-text="recommendation.recommendations.page_speed"></p>
                        </div>
                    </div>
                 </div>

             </div>
         </template>
    </div>

    @endif
    
    <div x-show="activeTab === 'chatbot'" style="display: none;" x-transition.opacity.duration.300ms x-data="{ botTab: 'leads' }">
        
        <div class="mb-6 flex flex-col md:flex-row justify-between md:items-end gap-4">
            <div class="flex flex-col sm:flex-row bg-slate-100 p-1 rounded-xl w-full md:w-fit gap-1">
                <button @click="botTab = 'leads'" class="w-full sm:w-auto px-4 py-2 rounded-lg text-sm font-semibold transition-all" :class="botTab === 'leads' ? 'bg-white shadow-sm text-teal-600' : 'text-slate-500 hover:text-slate-700'">Inbox Follow Up</button>
                <button @click="botTab = 'knowledge'" class="w-full sm:w-auto px-4 py-2 rounded-lg text-sm font-semibold transition-all" :class="botTab === 'knowledge' ? 'bg-white shadow-sm text-teal-600' : 'text-slate-500 hover:text-slate-700'">Latih Otak Bot</button>
            </div>
        </div>

        <div x-show="botTab === 'leads'" class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden" 
             x-data="{ showChatModal: false, activeChat: [], activeLeadId: null, pollInterval: null,
                       openModal(id, history) {
                           this.activeLeadId = id;
                           this.activeChat = history || [];
                           this.showChatModal = true;
                           // Polling AJAX tiap 3 detik untuk realtime
                           this.pollInterval = setInterval(async () => {
                               let res = await fetch(`/admin/chatbot/leads/${id}/history`);
                               this.activeChat = await res.json();
                           }, 3000);
                       },
                       closeModal() {
                           this.showChatModal = false;
                           clearInterval(this.pollInterval);
                       }
             }">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 whitespace-nowrap">Pengguna</th>
                            <th class="px-6 py-4 whitespace-nowrap">Topik</th>
                            <th class="px-6 py-4">Status & Kontak Diberikan</th>
                            <th class="px-6 py-4 whitespace-nowrap">Waktu</th>
                            <th class="px-6 py-4 text-center whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($chatbotLeads as $lead)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                @if($lead->user)
                                    <div class="font-bold text-teal-700 flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" /></svg> {{ $lead->user->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $lead->user->email }}</div>
                                @else
                                    <div class="font-bold text-slate-700 flex items-center gap-1">👤 Guest / Visitor</div>
                                    <div class="text-xs text-slate-400">IP: {{ $lead->ip_address }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-800">{{ $lead->topic_context }}</td>
                            <td class="px-6 py-4">
                                @if($lead->contact_info === '-')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-50 text-blue-600 font-bold text-xs rounded-lg border border-blue-200">
                                        <span class="w-2 h-2 rounded-full bg-blue-500 animate-ping"></span> Chat Masih Aktif
                                    </span>
                                @else
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 font-bold text-xs rounded-lg border border-emerald-200 mb-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg> Chat Diakhiri
                                    </div><br>
                                    <span class="text-xs font-bold text-slate-700">Follow up via: <span class="text-indigo-600">{{ $lead->contact_info }}</span></span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500">{{ $lead->created_at->format('d M Y, H:i') }}</td>
                            <td class="px-6 py-4 text-center space-y-2">
                                <form action="{{ route('admin.chatbot.lead.status', $lead->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-[10px] font-bold px-2 py-1.5 rounded-lg border w-full transition-colors {{ $lead->status === 'contacted' ? 'bg-green-50 text-green-600 border-green-200 hover:bg-green-100' : 'bg-amber-50 text-amber-600 border-amber-200 hover:bg-amber-100' }}">
                                        {{ $lead->status === 'contacted' ? 'Selesai Dihubungi' : 'Belum Dihubungi' }}
                                    </button>
                                </form>
                                <button @click="openModal({{ $lead->id }}, {{ $lead->chat_history ?? '[]' }})" class="text-xs text-white bg-slate-800 hover:bg-slate-900 px-3 py-1.5 rounded-lg w-full font-semibold transition-colors flex items-center justify-center gap-1 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg> Pantau Chat
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-8 text-center text-slate-400">Belum ada user yang berinteraksi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100 flex overflow-x-auto">
                {{ $chatbotLeads->appends(['active_tab' => 'chatbot', 'leads_page' => request('leads_page')])->links() }}
            </div>

            <div x-show="showChatModal" style="display: none;" class="fixed inset-0 z-[200] flex items-center justify-center p-4">
                <div x-show="showChatModal" x-transition.opacity class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeModal()"></div>
                <div x-show="showChatModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden flex flex-col h-[600px] max-h-[90vh]">
                    <div class="bg-gradient-to-r from-slate-800 to-slate-900 p-4 flex items-center justify-between text-white flex-shrink-0 shadow-md">
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-sm">📡 Pantau Chat Langsung</h3>
                            <span class="flex h-2 w-2 relative"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span></span>
                        </div>
                        <button @click="closeModal()" class="hover:text-red-400 bg-white/10 p-1.5 rounded-lg transition-colors"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></button>
                    </div>
                    <div id="admin-chat-scroll" class="flex-1 overflow-y-auto p-5 bg-slate-50 space-y-4" x-init="$watch('activeChat', () => { setTimeout(() => { $el.scrollTop = $el.scrollHeight }, 50) })">
                        <template x-for="(msg, i) in activeChat" :key="i">
                            <div class="flex flex-col" :class="msg.sender === 'user' ? 'items-end' : 'items-start'">
                                <span class="text-[9px] text-slate-400 mb-1 px-1 font-bold" x-text="msg.sender === 'user' ? 'User' : 'Bot AI'"></span>
                                <div class="max-w-[85%] px-4 py-2.5 rounded-2xl text-sm shadow-sm" :class="msg.sender === 'user' ? 'bg-indigo-500 text-white rounded-tr-sm' : 'bg-white border border-slate-200 text-slate-700 rounded-tl-sm'" x-html="msg.text"></div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="botTab === 'knowledge'" class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden" x-data="{ showKnowModal: false, isEdit: false, form: { id: '', topic: '', intent: '', keywords: '', response: '' } }">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="text-sm font-bold text-slate-700">Daftar Pengetahuan Bot</h3>
                <button @click="isEdit = false; form = {id:'', topic:'Umum', intent:'', keywords:'', response:''}; showKnowModal = true" class="bg-teal-500 hover:bg-teal-600 text-white px-4 py-2 rounded-lg text-xs font-bold shadow-sm transition-colors">+ Tambah Respon</button>
            </div>
            
            <div class="overflow-x-auto max-h-[600px] overflow-y-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-100 text-slate-500 font-semibold sticky top-0 shadow-sm">
                        <tr>
                            <th class="px-6 py-3 whitespace-nowrap">Kategori / Topik</th>
                            <th class="px-6 py-3 whitespace-nowrap">Kata Kunci (Keywords)</th>
                            <th class="px-6 py-3 w-[40%]">Balasan Bot</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($chatbotKnowledges as $know)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-indigo-50 text-indigo-700 rounded text-xs font-bold whitespace-nowrap">{{ $know->topic }}</span><br>
                                <span class="text-[10px] text-slate-400 uppercase">{{ $know->intent_name }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @php $kwArr = json_decode($know->keywords, true) ?? []; @endphp
                                <div class="flex flex-wrap gap-1">
                                    @foreach($kwArr as $kw)
                                        <span class="px-1.5 py-0.5 bg-slate-200 text-slate-700 rounded text-[10px] font-medium">{{ $kw }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs leading-relaxed text-slate-700">{{ Str::limit($know->response, 100) }}</td>
                            <td class="px-6 py-4 text-right space-y-1">
                                <button @click="isEdit = true; form = { id: '{{$know->id}}', topic: '{{$know->topic}}', intent: '{{$know->intent_name}}', keywords: '{{ implode(', ', $kwArr) }}', response: `{{$know->response}}` }; showKnowModal = true" class="text-teal-600 hover:text-teal-800 text-xs font-bold px-2 w-full text-right">Edit</button>
                                <form action="{{ route('admin.chatbot.destroy', $know->id) }}" method="POST" onsubmit="return confirm('Hapus respon bot ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-bold px-2 w-full text-right">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div x-show="showKnowModal" style="display: none;" class="fixed inset-0 z-[200] flex items-center justify-center p-4">
                <div x-show="showKnowModal" x-transition.opacity class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showKnowModal = false"></div>
                <div x-show="showKnowModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden flex flex-col max-h-[90vh]">
                    <div class="p-5 border-b border-slate-100 flex justify-between bg-slate-50 flex-shrink-0">
                        <h3 class="font-bold text-slate-900" x-text="isEdit ? 'Edit Respon Bot' : 'Tambah Respon Bot'"></h3>
                        <button @click="showKnowModal = false" class="text-slate-400 hover:text-red-500"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                    </div>
                    <form :action="isEdit ? '/admin/chatbot/knowledge/' + form.id : '{{ route('admin.chatbot.store') }}'" method="POST" class="p-5 space-y-4 overflow-y-auto">
                        @csrf
                        <template x-if="isEdit"><input type="hidden" name="_method" value="PATCH"></template>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Pilih Topik Terkait</label>
                                <select name="topic" x-model="form.topic" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:border-teal-500 outline-none">
                                    <option value="Akun & Login">Akun & Login</option>
                                    <option value="Paket & Pembayaran">Paket & Pembayaran</option>
                                    <option value="Pembuatan AR & 3D">Pembuatan AR & 3D</option>
                                    <option value="Cara Scan & Kendala">Cara Scan & Kendala</option>
                                    <option value="Umum">Umum / Lainnya</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Kode Intent (Opsional)</label>
                                <input type="text" name="intent_name" x-model="form.intent" placeholder="cth: cara_login" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:border-teal-500 outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Kata Kunci (Pisahkan dengan koma)</label>
                            <textarea name="keywords" x-model="form.keywords" rows="2" placeholder="cth: lupa password, sandi, reset akun" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:border-teal-500 outline-none resize-none"></textarea>
                            <p class="text-[10px] text-slate-400 mt-1">Bot akan mengirim respon ini jika chat user mengandung salah satu kata kunci di atas.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Teks Balasan Bot</label>
                            <textarea name="response" x-model="form.response" rows="4" placeholder="Ketik balasan untuk pengguna di sini..." required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:border-teal-500 outline-none resize-none"></textarea>
                        </div>

                        <div class="pt-2 flex gap-3">
                            <button type="button" @click="showKnowModal = false" class="flex-1 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50">Batal</button>
                            <button type="submit" class="flex-1 py-2.5 rounded-xl bg-teal-500 text-white font-bold hover:bg-teal-600 shadow-md">Simpan Respon</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'livechat'" style="display: none;" 
         x-data="{ 
            pendingChats: [], activeChats: [], endedChats: [], currentChat: null, inputText: '',
            notifEnabled: false, unreadChats: {},
            showHistory: false, isFirstPoll: true,
            
            initLive() {
                if (Notification.permission === 'granted') this.notifEnabled = true;
                setInterval(() => this.pollData(), 3000);
                this.pollData();
            },
            
            enableNotif() {
                Notification.requestPermission().then(perm => { if (perm === 'granted') this.notifEnabled = true; });
                this.playPing();
            },

            getLastMsg(historyStr, defaultTopic) {
                let h = JSON.parse(historyStr || '[]');
                if(h.length > 0) {
                    let text = h[h.length - 1].text;
                    return text ? text.replace(/(<([^>]+)>)/gi, '') : defaultTopic;
                }
                return defaultTopic;
            },
            
            async pollData() {
                try {
                    let res = await fetch('/admin/live-chat/poll');
                    let data = await res.json();
                    let initial = this.isFirstPoll;
                    if (data.pending.length > this.pendingChats.length) {
                        if (!initial) {
                            this.playPing();
                            if (this.notifEnabled && Notification.permission === 'granted') {
                                let notif = new Notification('💬 Live Chat Baru!', { body: 'Ada user yang menunggu.', icon: '/favicon.ico' });
                                notif.onclick = function() { window.focus(); };
                            }
                        }
                    }
                    
                    data.active.forEach(act => {
                        let oldActive = this.activeChats.find(c => c.id === act.id);
                        let oldLen = oldActive ? JSON.parse(oldActive.chat_history || '[]').length : 0;
                        let newLen = JSON.parse(act.chat_history || '[]').length;
                        
                        if(newLen > oldLen) {
                            if(this.currentChat?.id === act.id) {
                                if(!initial) this.playPing(); 
                                setTimeout(() => { this.scrollDown() }, 100);
                            } else {
                                this.unreadChats[act.id] = true;
                                if(!initial) this.playPing();
                            }
                        }
                    });
                    
                    this.pendingChats = data.pending;
                    this.activeChats = data.active;
                    this.isFirstPoll = false;
                    this.endedChats = data.ended || [];
                    
                    if(this.currentChat) {
                        this.currentChat = this.activeChats.find(c => c.id === this.currentChat.id) || 
                                           this.endedChats.find(c => c.id === this.currentChat.id) || null;
                    }
                } catch(e) {}
            },

            openChat(chat) {
                this.unreadChats[chat.id] = false;
                this.currentChat = chat;
                setTimeout(()=>this.scrollDown(), 100);
            },

            async actionChat(id, action) {
                try {
                    await fetch('/admin/live-chat/action', {
                        method: 'POST', 
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                        body: JSON.stringify({ lead_id: id, action: action })
                    });
                    if(action === 'end' && this.currentChat) {
                        this.currentChat.live_chat_status = 'ended'; 
                    }
                    this.pollData();
                } catch(e) {}
            },

            async sendMessage() {
                if(!this.inputText.trim() || !this.currentChat) return;
                let msgText = this.inputText; this.inputText = '';
                
                let history = JSON.parse(this.currentChat.chat_history || '[]');
                history.push({ sender: 'admin', text: msgText, time: new Date().toLocaleTimeString('id-ID', {day: 'numeric', month: 'short', hour: '2-digit', minute:'2-digit'}) });
                this.currentChat.chat_history = JSON.stringify(history);
                this.scrollDown();
                
                try {
                    await fetch('/admin/live-chat/send', {
                        method: 'POST', 
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                        body: JSON.stringify({ lead_id: this.currentChat.id, message: msgText })
                    });
                    this.pollData();
                } catch(e) {}
            },

            playPing() { try { let ctx = new (window.AudioContext || window.webkitAudioContext)(); let osc = ctx.createOscillator(); let gain = ctx.createGain(); osc.connect(gain); gain.connect(ctx.destination); osc.type = 'sine'; osc.frequency.setValueAtTime(800, ctx.currentTime); osc.frequency.exponentialRampToValueAtTime(1200, ctx.currentTime + 0.1); gain.gain.setValueAtTime(0, ctx.currentTime); gain.gain.linearRampToValueAtTime(0.3, ctx.currentTime + 0.02); gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.5); osc.start(ctx.currentTime); osc.stop(ctx.currentTime + 0.5); } catch(e){} },
            scrollDown() { setTimeout(() => { let el = document.getElementById('live-chat-box'); if(el) el.scrollTo({ top: el.scrollHeight, behavior: 'smooth' }); }, 100); }
         }" 
         x-init="initLive()">
        
        <div x-show="!notifEnabled" class="bg-indigo-50 border border-indigo-200 p-4 rounded-xl mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-indigo-100 rounded-full text-indigo-500"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg></div>
                <div><h4 class="font-bold text-slate-800">Aktifkan Notifikasi</h4><p class="text-xs text-slate-500">Izinkan browser untuk memunculkan suara saat pesan masuk.</p></div>
            </div>
            <button @click="enableNotif()" class="whitespace-nowrap bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg text-xs font-bold transition-all">Izinkan Sekarang</button>
        </div>

        <div class="flex flex-col md:flex-row gap-6 h-[70vh]">
            <div class="w-full md:w-1/3 bg-white rounded-xl border border-slate-200 shadow-sm flex flex-col overflow-hidden">
                
                <div class="shrink-0 border-b border-slate-100">
                    <button @click="showHistory = !showHistory" class="w-full p-4 bg-slate-100/50 hover:bg-slate-100 flex justify-between items-center transition-colors">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <h3 class="font-bold text-slate-600 text-xs uppercase tracking-wider">Riwayat Obrolan</h3>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 transition-transform duration-300" :class="showHistory ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    
                    <div x-show="showHistory" x-transition.opacity style="display: none;" class="overflow-y-auto max-h-[30vh] bg-slate-50/30 border-t border-slate-100">
                        <template x-for="chat in endedChats" :key="chat.id">
                            <div @click="openChat(chat)" class="p-3 border-b border-slate-100 cursor-pointer hover:bg-white transition-colors" :class="currentChat?.id === chat.id ? 'bg-white border-l-4 border-slate-400' : ''">
                                <p class="text-sm font-bold text-slate-500 truncate">
                                    <span x-show="chat.user" x-text="chat.user?.name"></span>
                                    <span x-show="!chat.user">Guest (<span x-text="chat.ip_address"></span>)</span>
                                </p>
                                <p class="text-[10px] text-slate-400 truncate mt-0.5" x-text="getLastMsg(chat.chat_history, 'Selesai')"></p>
                            </div>
                        </template>
                        <div x-show="endedChats.length === 0" class="p-4 text-center text-[10px] text-slate-400 italic">Belum ada riwayat.</div>
                    </div>
                </div>

                <div class="p-4 bg-slate-50 border-b border-slate-100 flex justify-between items-center shrink-0">
                    <h3 class="font-bold text-slate-800">Menunggu (Pending)</h3>
                    <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full" x-show="pendingChats.length > 0" x-text="pendingChats.length"></span>
                </div>
                <div class="overflow-y-auto max-h-[25vh] shrink-0">
                    <template x-for="chat in pendingChats" :key="chat.id">
                        <div class="p-3 border-b border-slate-100 bg-amber-50/30">
                            <p class="text-xs font-bold text-slate-800 mb-1">
                                <span x-show="chat.user" x-text="chat.user?.name"></span>
                                <span x-show="!chat.user">Guest (<span x-text="chat.ip_address"></span>)</span>
                            </p>
                            <p class="text-[10px] text-slate-500 mb-2 truncate italic" x-text="getLastMsg(chat.chat_history, chat.topic_context)"></p>
                            <div class="flex gap-2">
                                <button @click="actionChat(chat.id, 'accept')" class="flex-1 bg-teal-500 hover:bg-teal-600 text-white text-[10px] font-bold py-1.5 rounded transition-colors">Terima</button>
                                <button @click="actionChat(chat.id, 'reject')" class="flex-1 bg-slate-200 hover:bg-red-500 hover:text-white text-slate-600 text-[10px] font-bold py-1.5 rounded transition-colors">Tolak</button>
                            </div>
                        </div>
                    </template>
                </div>
                
                <div class="p-4 bg-slate-50 border-y border-slate-100 shrink-0">
                    <h3 class="font-bold text-slate-800">Obrolan Aktif</h3>
                </div>
                <div class="overflow-y-auto flex-1 min-h-[100px]">
                    <template x-for="chat in activeChats" :key="chat.id">
                        <div @click="openChat(chat)" class="p-3 border-b border-slate-100 cursor-pointer hover:bg-slate-50 transition-colors relative" :class="currentChat?.id === chat.id ? 'bg-indigo-50 border-l-4 border-indigo-500' : ''">
                            <div x-show="unreadChats[chat.id] && currentChat?.id !== chat.id" class="absolute right-3 top-3 w-2.5 h-2.5 bg-red-500 rounded-full animate-ping shadow-[0_0_8px_rgba(239,68,68,0.8)]"></div>
                            <div x-show="unreadChats[chat.id] && currentChat?.id !== chat.id" class="absolute right-3 top-3 w-2.5 h-2.5 bg-red-500 rounded-full"></div>
                            
                            <p class="text-sm font-bold text-slate-800 pr-4 truncate">
                                <span x-show="chat.user" x-text="chat.user?.name"></span>
                                <span x-show="!chat.user">Guest (<span x-text="chat.ip_address"></span>)</span>
                            </p>
                            <p class="text-xs text-slate-500 truncate mt-0.5" x-text="getLastMsg(chat.chat_history, chat.topic_context)"></p>
                        </div>
                    </template>
                </div>
                
            </div>
            
            <div class="w-full md:w-2/3 bg-white rounded-xl border border-slate-200 shadow-sm flex flex-col">
                <template x-if="!currentChat">
                    <div class="flex-1 flex flex-col items-center justify-center text-slate-400">
                        <svg class="w-16 h-16 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                        <p>Pilih obrolan dari daftar untuk mulai membalas.</p>
                    </div>
                </template>
                
                <template x-if="currentChat">
                    <div class="flex flex-col h-full">
                        <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                            <div>
                                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                                    <span x-show="currentChat.user" x-text="currentChat.user?.name"></span>
                                    <span x-show="!currentChat.user">Guest (<span x-text="currentChat.ip_address"></span>)</span>
                                    <span x-show="currentChat.live_chat_status === 'active'" class="px-2 py-0.5 bg-green-100 text-green-700 text-[9px] uppercase rounded-full">Online</span>
                                </h3>
                                <p class="text-[10px] text-slate-500 font-mono mt-0.5">Session ID: <span x-text="currentChat.id"></span></p>
                            </div>
                            <button x-show="currentChat.live_chat_status === 'active'" @click="actionChat(currentChat.id, 'end')" class="text-xs bg-red-100 text-red-600 font-bold px-3 py-1.5 rounded-lg hover:bg-red-200 transition-colors">Akhiri Sesi</button>
                        </div>
                        
                        <div id="live-chat-box" class="flex-1 overflow-y-auto p-4 space-y-3 bg-slate-50/50">
                            <template x-for="(msg, i) in JSON.parse(currentChat.chat_history || '[]')" :key="i">
                                <div class="flex flex-col" :class="(msg.sender === 'admin' || msg.sender === 'bot') ? 'items-end' : 'items-start'">
                                    <div class="flex items-baseline gap-1.5 mb-0.5 px-1" :class="(msg.sender === 'admin' || msg.sender === 'bot') ? 'flex-row-reverse' : ''">
                                        <span class="text-[9px] text-slate-500 font-bold" x-text="msg.sender.toUpperCase()"></span>
                                        <span class="text-[8px] text-slate-400" x-show="msg.time" x-text="msg.time"></span>
                                    </div>
                                    <div class="max-w-[80%] px-3 py-2 rounded-xl text-sm shadow-sm" :class="(msg.sender === 'admin' || msg.sender === 'bot') ? 'bg-indigo-500 text-white rounded-tr-sm' : 'bg-white border border-slate-200 text-slate-700 rounded-tl-sm'" x-html="msg.text"></div>
                                </div>
                            </template>
                        </div>
                        
                        <form x-show="currentChat.live_chat_status === 'active'" @submit.prevent="sendMessage()" class="p-3 bg-white border-t border-slate-100 flex gap-2">
                            <input type="text" x-model="inputText" placeholder="Ketik balasan CS di sini..." class="flex-1 px-4 py-2.5 bg-slate-100 border-transparent rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-sm transition-all">
                            <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg font-bold text-sm hover:bg-indigo-700 transition-colors">Kirim</button>
                        </form>

                        <div x-show="currentChat.live_chat_status === 'ended'" class="p-4 bg-slate-100 text-center text-xs font-bold text-slate-500">
                            Sesi obrolan ini telah berakhir.
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartData = @json($chartData);
            const ctx = document.getElementById('trafficChart');
            
            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            label: chartData.labelName,
                            data: chartData.values,
                            borderColor: '#0d9488',
                            backgroundColor: 'rgba(20, 184, 166, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#0d9488',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, ticks: { stepSize: 1 } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }
        });
    </script>
</div>
@endsection