@extends('layouts.app')

@section('content')

@php
    $initialTab = request('active_tab', session('active_tab', 'overview'));
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
            <button @click="activeTab = 'chatbot'" 
                :class="activeTab === 'chatbot' ? 'bg-teal-500 text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-100'"
                class="px-5 py-2 rounded-full text-sm font-semibold transition-all duration-200 whitespace-nowrap">
                Chatbot AI
            </button>
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
        </div>
    </div>

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
                this.pkgImage = parseInt(pkg.features[0]) || 0;
                this.pkgVoice = parseInt(pkg.features[1]) || 0;
                this.pkgScan = parseInt(pkg.features[2]) || 0;
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
                            <input type="number" name="image_limit" x-model="pkgImage" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:border-teal-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1 uppercase">Limit Voice</label>
                            <input type="number" name="voice_limit" x-model="pkgVoice" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:border-teal-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1 uppercase">Total Scan</label>
                            <input type="number" name="scan_limit" x-model="pkgScan" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:border-teal-500 outline-none">
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
            fetchTransactions(query) {
                fetch(`/admin/transactions/search?query=${query}`)
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('transaction-table-body').innerHTML = html;
                    });
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
    </div>

    <div x-show="activeTab === 'monitoring'" style="display: none;" x-transition.opacity.duration.300ms>
        
        <div class="mb-6 flex flex-col md:flex-row justify-between md:items-end gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Monitoring & Trafik</h2>
                <p class="text-slate-500 text-sm mt-1">Pantau perjalanan pengunjung di dalam website.</p>
            </div>
            
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
    
    <div x-show="activeTab === 'chatbot'" style="display: none;" x-transition.opacity.duration.300ms x-data="{ botTab: 'leads' }">
        
        <div class="mb-6 flex flex-col md:flex-row justify-between md:items-end gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Manajemen Chatbot AI</h2>
                <p class="text-slate-500 text-sm mt-1">Pantau percakapan pengguna & latih otak chatbot.</p>
            </div>
            
            <div class="flex bg-slate-100 p-1 rounded-xl w-fit">
                <button @click="botTab = 'leads'" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all" :class="botTab === 'leads' ? 'bg-white shadow-sm text-teal-600' : 'text-slate-500 hover:text-slate-700'">Inbox Follow Up</button>
                <button @click="botTab = 'knowledge'" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all" :class="botTab === 'knowledge' ? 'bg-white shadow-sm text-teal-600' : 'text-slate-500 hover:text-slate-700'">Latih Otak Bot (Knowledge)</button>
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
                                        {{ $lead->status === 'contacted' ? '✅ Selesai Dihubungi' : '⚠️ Belum Dihubungi' }}
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
                        
                        <div class="grid grid-cols-2 gap-4">
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