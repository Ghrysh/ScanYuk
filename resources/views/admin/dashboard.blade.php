@extends('layouts.app')

@section('content')
<div class="max-w-[100rem] mx-auto w-full px-4 sm:px-6 lg:px-8 py-8" x-data="{ activeTab: '{{ request('active_tab', session('active_tab', 'overview')) }}' }">

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
        
        <div class="flex space-x-2 border-b border-slate-200 pb-2 overflow-x-auto">
            <button @click="activeTab = 'overview'" 
                :class="activeTab === 'overview' ? 'bg-teal-500 text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-100'"
                class="px-5 py-2 rounded-full text-sm font-semibold transition-all duration-200 whitespace-nowrap">
                Overview
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

    <div x-show="activeTab === 'overview'" x-transition.opacity.duration.300ms>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <div class="flex items-center gap-2 text-slate-500 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    <span class="text-sm font-medium">Total Users</span>
                </div>
                <h3 class="text-4xl font-bold text-slate-900">{{ number_format($totalUsers) }}</h3>
            </div>

            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <div class="flex items-center gap-2 text-slate-500 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                    <span class="text-sm font-medium">Active QR Codes</span>
                </div>
                <h3 class="text-4xl font-bold text-slate-900">{{ number_format($totalQrCodes) }}</h3>
            </div>

            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <div class="flex items-center gap-2 text-slate-500 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                    <span class="text-sm font-medium">Total Scans</span>
                </div>
                <h3 class="text-4xl font-bold text-slate-900">{{ number_format($totalScans) }}</h3>
            </div>

            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <div class="flex items-center gap-2 text-slate-500 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                    <span class="text-sm font-medium">Revenue</span>
                </div>
                <h3 class="text-3xl font-bold text-slate-900">
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
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <div class="relative max-w-xs w-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    <input type="text" placeholder="Cari user..." @input.debounce.300ms="fetchUsers($event.target.value)" class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:bg-white focus:border-teal-500 focus:ring-1 focus:ring-teal-500 outline-none transition-all">
                </div>
                <button @click="showAddUserModal = true" class="bg-teal-500 hover:bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-sm whitespace-nowrap transition-colors">
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

        <div class="p-4 border-t border-slate-100">
            {{ $users->appends(['active_tab' => 'users', 'txn_page' => request('txn_page')])->links() }}
        </div>

        <div x-show="showModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center">
            <div x-show="showModal" x-transition.opacity.duration.300ms class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showModal = false"></div>
            <div x-show="showModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="relative bg-white rounded-2xl shadow-2xl max-w-sm w-full mx-4 p-6 overflow-hidden">
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

                <div class="flex gap-3">
                    <button @click="showModal = false" type="button" class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition-colors">Batal</button>
                    <button @click="submitForm()" type="button" :class="(action === 'suspend' || action === 'delete') ? 'bg-red-600 hover:bg-red-700 shadow-red-200/50' : 'bg-teal-600 hover:bg-teal-700 shadow-teal-200/50'" class="flex-1 px-4 py-2.5 rounded-xl text-white font-semibold shadow-lg transition-all hover:-translate-y-0.5">
                        Ya, <span x-text="action === 'delete' ? 'Hapus' : (action === 'suspend' ? 'Suspend' : 'Aktifkan')"></span>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="showAddUserModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center">
            <div x-show="showAddUserModal" x-transition.opacity class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showAddUserModal = false"></div>
            <div x-show="showAddUserModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="text-xl font-bold text-slate-900">Tambah Akun User</h3>
                    <button @click="showAddUserModal = false" class="text-slate-400 hover:text-slate-600"><svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                </div>
                <form action="{{ route('admin.users.store') }}" method="POST" class="p-6 space-y-4">
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
                    <div class="pt-4 flex gap-3">
                        <button @click="showAddUserModal = false" type="button" class="flex-1 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50">Batal</button>
                        <button type="submit" class="flex-1 py-3 rounded-xl bg-teal-500 hover:bg-teal-600 text-white font-bold shadow-lg">Buat Akun</button>
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
                // Parsing angka dari string features
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
                        <th class="px-6 py-4">Paket</th>
                        <th class="px-6 py-4">Harga</th>
                        <th class="px-6 py-4">Image</th>
                        <th class="px-6 py-4">Voice</th>
                        <th class="px-6 py-4">Total Scan</th>
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

        <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center">
            <div x-show="showEditModal" x-transition.opacity.duration.300ms class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showEditModal = false"></div>

            <div x-show="showEditModal" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 overflow-hidden">
                
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="text-xl font-bold text-slate-900">Edit Paket: <span x-text="pkgName" class="text-teal-600"></span></h3>
                    <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Nama Paket</label>
                    <input type="text" name="name" x-model="pkgName" required class="p-6 space-y-4 w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all">
                </div>

                <form :action="`/admin/packages/${pkgId}`" method="POST" class="p-6 space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Harga (Rp)</label>
                        <input type="number" name="price" x-model="pkgPrice" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all">
                    </div>

                    <div class="grid grid-cols-3 gap-4">
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

                    <div class="pt-4 flex gap-3">
                        <button @click="showEditModal = false" type="button" class="flex-1 py-3 px-4 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-all">Batal</button>
                        <button type="submit" class="flex-1 py-3 px-4 rounded-xl btn-gradient text-white font-bold shadow-lg shadow-indigo-200 hover:-translate-y-0.5 transition-all">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'transaksi'" style="display: none;" class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden" x-transition.opacity.duration.300ms>
        <div class="p-6 border-b border-slate-100">
            <h2 class="text-lg font-bold text-slate-900">Riwayat Transaksi</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Paket</th>
                        <th class="px-6 py-4">Jumlah</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($transactions as $txn)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-xs font-mono text-slate-500">{{ $txn->id }}</td>
                        <td class="px-6 py-4 text-slate-900">{{ $txn->user->name }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ strtolower($txn->package->name) == 'pemula' ? 'bg-teal-50 text-teal-600' : 
                                (strtolower($txn->package->name) == 'profesional' ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600') }}">
                                {{ $txn->package->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-900">Rp{{ number_format($txn->amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                {{ $txn->status == 'Berhasil' ? 'bg-teal-100 text-teal-700' : 
                                ($txn->status == 'Pending' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                                {{ $txn->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-500">{{ $txn->created_at->format('Y-m-d') }}</td>
                    </tr>   
                    @endforeach
                </tbody>
            </table>
            <div class="p-4 border-t border-slate-100">
            {{ $transactions->appends(['active_tab' => 'transaksi', 'users_page' => request('users_page')])->links() }}
            </div>
        </div>
    </div>

</div>
@endsection