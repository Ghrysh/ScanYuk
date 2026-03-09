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
        
        <div class="mb-6 flex justify-between items-end">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Monitoring & Analitik</h2>
                <p class="text-slate-500 text-sm mt-1">Pantau performa bisnis dan interaksi pengguna.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Pengunjung Web</p>
                        <h3 class="text-2xl font-bold text-slate-900 mt-2">{{ number_format($webVisitors) }}</h3>
                        <p class="text-xs text-teal-600 mt-1 font-medium">Real-time Tracker Aktif</p>
                    </div>
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Pendaftar</p>
                        <h3 class="text-2xl font-bold text-slate-900 mt-2">{{ $totalUsers }}</h3>
                        <p class="text-xs text-teal-600 mt-1 font-medium">User Aktif</p>
                    </div>
                    <div class="p-3 bg-teal-50 text-teal-600 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Paket Terlaris</p>
                        <h3 class="text-2xl font-bold text-slate-900 mt-2">{{ $popularPackageName }}</h3>
                        <p class="text-xs text-slate-400 mt-1">Selain paket gratis</p>
                    </div>
                    <div class="p-3 bg-amber-50 text-amber-500 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Klik Tombol Scan</p>
                        <h3 class="text-2xl font-bold text-slate-900 mt-2">{{ number_format($scanClicks) }}</h3>
                        <p class="text-xs text-slate-400 mt-1">Interaksi di halaman Home</p>
                    </div>
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" /></svg>
                    </div>
                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm flex flex-col gap-6">
                
                <div>
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Pengguna Berdasarkan Paket</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-slate-600 flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-slate-300"></div> Paket Gratis</span>
                            <span class="text-sm font-bold text-slate-900">{{ $countFree }} Akun</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-slate-600 flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-teal-400"></div> Paket Pemula (Starter)</span>
                            <span class="text-sm font-bold text-slate-900">{{ $countStarter }} Akun</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-slate-600 flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-indigo-500"></div> Paket Profesional</span>
                            <span class="text-sm font-bold text-slate-900">{{ $countPro }} Akun</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-slate-600 flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-purple-600"></div> Paket Bisnis</span>
                            <span class="text-sm font-bold text-slate-900">{{ $countBusiness }} Akun</span>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100 border-dashed">

                <div>
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Rasio Konversi Transaksi</h3>
                    <div class="flex items-center gap-6">
                        <div class="flex-1 bg-slate-50 p-4 rounded-xl border border-slate-100 text-center">
                            <p class="text-xs text-slate-500 uppercase tracking-widest font-bold">Berhasil</p>
                            <p class="text-3xl font-black text-teal-600 mt-1">{{ $txnSuccess }}</p>
                        </div>
                        <div class="flex-1 bg-slate-50 p-4 rounded-xl border border-slate-100 text-center">
                            <p class="text-xs text-slate-500 uppercase tracking-widest font-bold">Gagal/Batal</p>
                            <p class="text-3xl font-black text-red-500 mt-1">{{ $txnFailed }}</p>
                        </div>
                    </div>
                    <p class="text-xs text-slate-400 mt-3 text-center">Bandingkan jumlah orang yang niat beli vs yang berhasil transfer.</p>
                </div>

            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm" x-data="{ showContactModal: false, activeMsg: null }">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-slate-900">Pesan Masuk (Contact Us)</h3>
                    <span class="px-2.5 py-1 bg-teal-100 text-teal-700 text-xs font-bold rounded-md">Aktif</span>
                </div>
                
                <div class="border border-slate-200 rounded-lg overflow-hidden h-[250px] overflow-y-auto no-scrollbar">
                    <table class="w-full text-left text-sm text-slate-600 relative">
                        <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200 sticky top-0 z-10">
                            <tr>
                                <th class="px-4 py-3">Pengirim</th>
                                <th class="px-4 py-3 hidden sm:table-cell">Topik</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($contactMessages as $msg)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="font-bold text-slate-900">{{ $msg->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $msg->company ?? 'Personal' }}</div>
                                </td>
                                <td class="px-4 py-3 truncate max-w-[150px] hidden sm:table-cell">{{ $msg->subject }}</td>
                                <td class="px-4 py-3 text-right">
                                    <button @click="activeMsg = {{ json_encode($msg) }}; showContactModal = true" class="text-teal-600 font-bold text-xs bg-teal-50 px-3 py-1.5 rounded-lg hover:bg-teal-100 transition-colors">Lihat</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-slate-400">Belum ada pesan masuk.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div x-show="showContactModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                    <div x-show="showContactModal" x-transition.opacity class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showContactModal = false"></div>
                    <div x-show="showContactModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 overflow-hidden flex flex-col">
                        <div class="flex justify-between items-center mb-5 border-b border-slate-100 pb-4">
                            <h3 class="text-xl font-bold text-slate-900">Detail Pesan</h3>
                            <button @click="showContactModal = false" class="text-slate-400 hover:text-slate-600 bg-slate-100 p-1.5 rounded-lg">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                        <div class="space-y-4 text-sm text-slate-600" x-if="activeMsg">
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Pengirim</p>
                                <p class="font-bold text-slate-900 text-base" x-text="activeMsg?.name"></p>
                                <p class="text-teal-600 font-medium" x-text="activeMsg?.email"></p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Perusahaan</p>
                                    <p class="font-semibold text-slate-800" x-text="activeMsg?.company || '-'"></p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal</p>
                                    <p class="font-semibold text-slate-800" x-text="new Date(activeMsg?.created_at).toLocaleDateString('id-ID')"></p>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Topik / Subjek</p>
                                <p class="font-bold text-slate-900" x-text="activeMsg?.subject || '-'"></p>
                            </div>
                            <div class="pt-2">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Pesan Isi</p>
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 whitespace-pre-wrap text-slate-700 leading-relaxed max-h-[150px] overflow-y-auto" x-text="activeMsg?.message"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection