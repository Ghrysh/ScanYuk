@extends('layouts.app')

@section('content')
<div class="max-w-[100rem] mx-auto w-full px-4 sm:px-6 lg:px-8 py-10" x-data="{
    toastMessage: '',
    showToast: false,
    copyLink(url) {
        navigator.clipboard.writeText(url);
        this.toastMessage = 'Link berhasil disalin!';
        this.showToast = true;
        setTimeout(() => this.showToast = false, 3000);
    },
    tab: 'services',
    showAddServiceModal: false,
    showAddCounterModal: false,
    showAddStaffModal: false
}">
    
    <div class="mb-8">
        <a href="{{ route('queue.index') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-teal-600 transition-colors mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali ke Antrian
        </a>
        
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 mb-1">
                    {{ $location ? 'Kelola Lokasi: ' . $location->name : 'Buat Lokasi Baru' }}
                </h1>
                @if($location)
                <div class="flex items-center gap-3 mt-2">
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $location->is_active ? 'bg-teal-50 text-teal-600' : 'bg-slate-100 text-slate-500' }}">
                        {{ $location->is_active ? 'Status: Buka' : 'Status: Tutup' }}
                    </span>
                    <p class="text-slate-500 text-sm">{{ $location->address }}</p>
                </div>
                @else
                <p class="text-slate-500">Isi form di bawah untuk membuat lokasi antrian baru.</p>
                @endif
            </div>
        </div>
    </div>

    @if(!$location)
    {{-- CREATE FORM --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 md:p-8 max-w-3xl mx-auto">
        <form action="{{ route('queue.locations.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lokasi <span class="text-red-500">*</span></label>
                <input type="text" name="name" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all sm:text-sm" placeholder="Contoh: Kantor Cabang Sudirman">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Lengkap</label>
                <textarea name="address" rows="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all sm:text-sm" placeholder="Contoh: Jl. Jend. Sudirman No. 123..."></textarea>
                @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Total Kuota Antrian Harian</label>
                    <input type="number" name="daily_quota" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all sm:text-sm" placeholder="Kosongkan jika tak terbatas">
                    <p class="text-xs text-slate-500 mt-1">Kuota per hari untuk lokasi ini. Total semua kuota lokasi tidak boleh melebihi batas paket Anda.</p>
                </div>
            </div>

            <div x-data="{ showBooths: false }" class="space-y-4">
                <div class="flex items-center gap-3 p-4 bg-slate-50 border border-slate-200 rounded-lg">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" name="has_booths" value="1" x-model="showBooths" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-500"></div>
                        </div>
                        <div>
                            <span class="text-sm font-bold text-slate-900 block">Gunakan Sistem Booth / Loket</span>
                            <span class="text-xs text-slate-500">Aktifkan untuk melayani pelanggan pada beberapa booth/teller sekaligus.</span>
                        </div>
                    </label>
                </div>
                
                <div x-show="showBooths" class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-slate-50 border border-slate-200 rounded-lg" style="display: none;">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Sebutan Booth</label>
                        <input type="text" name="booth_name" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-lg text-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Contoh: Loket, Booth, Teller, Poli">
                        <p class="text-xs text-slate-500 mt-1">Sistem akan menamai loket secara otomatis (cth: Loket 1, Loket 2).</p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Jumlah Booth</label>
                        <input type="number" name="booth_count" min="1" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-lg text-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Contoh: 3">
                        <p class="text-xs text-slate-500 mt-1">Berapa banyak booth yang ingin dibuat otomatis.</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Integrasi AR QR Code</label>
                    <select name="ar_qr_code_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all sm:text-sm">
                        <option value="">-- Tanpa AR --</option>
                        @foreach($arQrCodes ?? [] as $qr)
                        <option value="{{ $qr->id }}">{{ $qr->title }} ({{ $qr->ar_type }})</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-slate-500 mt-1">Jika dipilih, pelanggan akan melihat AR Anda sebelum mengambil antrian.</p>
                </div>
            </div>

            <div x-data="{ 
                hours: {
                    mon: { label: 'Senin', active: true, open: '08:00', close: '17:00' },
                    tue: { label: 'Selasa', active: true, open: '08:00', close: '17:00' },
                    wed: { label: 'Rabu', active: true, open: '08:00', close: '17:00' },
                    thu: { label: 'Kamis', active: true, open: '08:00', close: '17:00' },
                    fri: { label: 'Jumat', active: true, open: '08:00', close: '17:00' },
                    sat: { label: 'Sabtu', active: false, open: '08:00', close: '14:00' },
                    sun: { label: 'Minggu', active: false, open: '08:00', close: '14:00' }
                }
            }">
                <label class="block text-sm font-bold text-slate-700 mb-4 mt-6">Jam Operasional</label>
                <input type="hidden" name="operational_hours" x-bind:value="JSON.stringify(hours)">
                
                <div class="space-y-3">
                    <template x-for="(data, day) in hours" :key="day">
                        <div class="flex items-center gap-4 p-3 bg-slate-50 rounded-lg border border-slate-200">
                            <label class="flex items-center gap-3 w-32 cursor-pointer">
                                <div class="relative">
                                    <input type="checkbox" x-model="data.active" class="sr-only peer">
                                    <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-teal-500"></div>
                                </div>
                                <span class="text-sm font-bold text-slate-700 capitalize" x-text="data.label"></span>
                            </label>
                            
                            <div class="flex items-center gap-2 flex-1" x-show="data.active">
                                <input type="time" x-model="data.open" class="px-2 py-1 bg-white border border-slate-300 rounded text-sm focus:border-teal-500 outline-none">
                                <span class="text-slate-500 text-xs">-</span>
                                <input type="time" x-model="data.close" class="px-2 py-1 bg-white border border-slate-300 rounded text-sm focus:border-teal-500 outline-none">
                            </div>
                            <div class="flex-1 text-slate-400 text-sm font-medium" x-show="!data.active">
                                Tutup
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="px-6 py-3 rounded-xl btn-gradient text-white font-bold transition-all shadow-md">
                    Simpan Lokasi
                </button>
            </div>
        </form>
    </div>

    @else
    {{-- MANAGE VIEW --}}
    
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
                <div class="w-full aspect-square bg-slate-50 rounded-lg border border-slate-200 flex items-center justify-center mb-4 p-4">
                    {{-- Simulasi QR Code, asumsikan kita generate SVG atau img src --}}
                    {!! QrCode::size(200)->generate(route('queue.register', $location->uuid)) !!}
                </div>
                @if($location->services()->count() > 0)
                <a href="{{ route('queue.locations.qr', $location->id) }}" class="w-full mb-2 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-bold transition-colors flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    Unduh QR Code
                </a>
                @else
                <button type="button" onclick="showAppConfirm('Layanan Kosong', 'Tambahkan minimal 1 layanan (di menu sebelah kanan) terlebih dahulu agar QR Code bisa diunduh dan digunakan.', null)" class="w-full mb-2 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-sm font-bold transition-colors flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    Unduh QR Code
                </button>
                @endif
                <a href="{{ route('queue.display', $location->uuid) }}" target="_blank" class="w-full py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg text-sm font-bold transition-colors flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    Display Antrian
                </a>
            </div>

            <nav class="bg-white rounded-xl border border-slate-200 shadow-sm p-2 flex flex-col gap-1">
                <button @click="tab = 'services'" :class="tab === 'services' ? 'bg-teal-50 text-teal-700 font-bold' : 'text-slate-600 hover:bg-slate-50'" class="px-4 py-3 rounded-lg text-sm text-left transition-colors flex items-center justify-between">
                    Layanan (Services)
                    <span class="bg-slate-100 text-slate-500 px-2 py-0.5 rounded text-xs">{{ $location->services->count() }}</span>
                </button>
                @if($location->has_booths)
                <button @click="tab = 'counters'" :class="tab === 'counters' ? 'bg-teal-50 text-teal-700 font-bold' : 'text-slate-600 hover:bg-slate-50'" class="px-4 py-3 rounded-lg text-sm text-left transition-colors flex items-center justify-between">
                    Loket (Counters)
                    <span class="bg-slate-100 text-slate-500 px-2 py-0.5 rounded text-xs">{{ $location->counters->count() }}</span>
                </button>
                @endif
                <button @click="tab = 'staff'" :class="tab === 'staff' ? 'bg-teal-50 text-teal-700 font-bold' : 'text-slate-600 hover:bg-slate-50'" class="px-4 py-3 rounded-lg text-sm text-left transition-colors flex items-center justify-between">
                    Petugas (Staff)
                    <span class="bg-slate-100 text-slate-500 px-2 py-0.5 rounded text-xs">{{ $location->staff->count() }}</span>
                </button>
                <button @click="tab = 'settings'" :class="tab === 'settings' ? 'bg-teal-50 text-teal-700 font-bold' : 'text-slate-600 hover:bg-slate-50'" class="px-4 py-3 rounded-lg text-sm text-left transition-colors">
                    Pengaturan Lokasi
                </button>
            </nav>
        </div>

        <div class="lg:col-span-3">
            {{-- TAB: SERVICES --}}
            <div x-show="tab === 'services'" class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden" style="display: none;">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-bold text-slate-900 text-lg">Daftar Layanan</h3>
                    <button @click="showAddServiceModal = true" class="px-4 py-2 bg-teal-500 hover:bg-teal-600 text-white rounded-lg text-sm font-bold transition-colors shadow-sm">
                        + Tambah Layanan
                    </button>
                </div>
                
                <div class="divide-y divide-slate-100">
                    @forelse($location->services as $svc)
                    <div class="p-4 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50 transition-colors">
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <span class="w-8 h-8 rounded bg-teal-50 text-teal-600 font-black flex items-center justify-center">{{ $svc->prefix }}</span>
                                <h4 class="font-bold text-slate-900">{{ $svc->name }}</h4>
                                @if(!$svc->is_active)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-200 text-slate-600 uppercase tracking-wider">Nonaktif</span>
                                @endif
                            </div>
                            <p class="text-sm text-slate-500">Estimasi: {{ $svc->estimated_duration_minutes }} menit/orang • Kuota Harian: {{ $svc->daily_quota ?: 'Tak Terbatas' }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <form action="{{ route('queue.services.delete', $svc->id) }}" method="POST" class="inline" @submit.prevent="if(typeof showAppConfirm === 'function') showAppConfirm('Konfirmasi', 'Hapus layanan ini?', $event.target); else if(confirm('Hapus layanan ini?')) $event.target.submit();">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-slate-500">Belum ada layanan yang dibuat.</div>
                    @endforelse
                </div>
            </div>

            {{-- TAB: COUNTERS --}}
            <div x-show="tab === 'counters'" class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden" style="display: none;">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-bold text-slate-900 text-lg">Daftar Loket (Counter)</h3>
                    <button @click="showAddCounterModal = true" class="px-4 py-2 bg-teal-500 hover:bg-teal-600 text-white rounded-lg text-sm font-bold transition-colors shadow-sm">
                        + Tambah Loket
                    </button>
                </div>
                
                <div class="divide-y divide-slate-100">
                    @forelse($location->counters as $counter)
                    <div class="p-4 sm:p-6 flex items-center justify-between hover:bg-slate-50 transition-colors">
                        <div>
                            <h4 class="font-bold text-slate-900">{{ $counter->name }}</h4>
                            <p class="text-sm text-slate-500">Status: {{ $counter->is_active ? 'Aktif' : 'Nonaktif' }}</p>
                        </div>
                        <form action="{{ route('queue.counters.delete', $counter->id) }}" method="POST" class="inline" @submit.prevent="if(typeof showAppConfirm === 'function') showAppConfirm('Konfirmasi', 'Hapus loket ini?', $event.target); else if(confirm('Hapus loket ini?')) $event.target.submit();">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                        </form>
                    </div>
                    @empty
                    <div class="p-8 text-center text-slate-500">Belum ada loket yang dibuat.</div>
                    @endforelse
                </div>
            </div>

            {{-- TAB: STAFF --}}
            <div x-show="tab === 'staff'" class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden" style="display: none;">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-bold text-slate-900 text-lg">Daftar Petugas</h3>
                    <button @click="showAddStaffModal = true" class="px-4 py-2 bg-teal-500 hover:bg-teal-600 text-white rounded-lg text-sm font-bold transition-colors shadow-sm">
                        + Tambah Petugas
                    </button>
                </div>
                
                <div class="divide-y divide-slate-100">
                    @forelse($location->staff as $staff)
                    <div class="p-4 sm:p-6 flex items-center justify-between hover:bg-slate-50 transition-colors">
                        <div>
                            <h4 class="font-bold text-slate-900">{{ $staff->name }}</h4>
                            <p class="text-sm text-slate-500">Counter: {{ $staff->counter ? $staff->counter->name : 'Belum Ditugaskan' }}</p>
                        </div>
                        <form action="{{ route('queue.staff.delete', $staff->id) }}" method="POST" class="inline" @submit.prevent="if(typeof showAppConfirm === 'function') showAppConfirm('Konfirmasi', 'Hapus petugas ini?', $event.target); else if(confirm('Hapus petugas ini?')) $event.target.submit();">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                        </form>
                    </div>
                    @empty
                    <div class="p-8 text-center text-slate-500">Belum ada petugas yang dibuat.</div>
                    @endforelse
                </div>
            </div>

            {{-- TAB: SETTINGS --}}
            <div x-show="tab === 'settings'" class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden p-6 md:p-8" style="display: none;">
                <h3 class="font-bold text-slate-900 text-lg mb-6">Pengaturan Lokasi</h3>
                <form action="{{ route('queue.locations.update', $location->id) }}" method="POST" class="space-y-6" x-data="{ hasBooths: {{ $location->has_booths ? 'true' : 'false' }} }">
                    @csrf @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center gap-3 p-4 bg-slate-50 border border-slate-200 rounded-lg">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <div class="relative">
                                    <input type="checkbox" name="is_active" value="1" {{ $location->is_active ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-500"></div>
                                </div>
                                <span class="text-sm font-bold text-slate-900">Lokasi Aktif / Buka</span>
                            </label>
                        </div>
                        <div class="flex items-center gap-3 p-4 bg-slate-50 border border-slate-200 rounded-lg">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <div class="relative">
                                    <input type="checkbox" name="has_booths" value="1" x-model="hasBooths" class="sr-only peer">
                                    <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-500"></div>
                                </div>
                                <div>
                                    <span class="text-sm font-bold text-slate-900 block">Sistem Booth / Loket</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div x-show="hasBooths" x-collapse class="bg-slate-50 p-4 border border-slate-200 rounded-lg">
                        @if($location->counters->count() > 0)
                            <div class="flex items-center gap-3 text-teal-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-teal-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                <span class="text-sm font-medium">Sistem loket aktif ({{ $location->counters->count() }} loket). Anda dapat menambah/mengedit loket di tab <b class="font-bold">Loket / Booth</b>. Jika dinonaktifkan, semua data loket akan terhapus.</span>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">Nama Panggilan (Opsional)</label>
                                    <input type="text" name="booth_name" class="w-full px-3 py-2 border rounded-lg text-sm" placeholder="Cth: Loket, Meja, Poli">
                                    <p class="text-[10px] text-slate-500 mt-1">Isi untuk generate loket massal. Kosongkan jika ingin buat manual nanti.</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">Jumlah (Opsional)</label>
                                    <input type="number" name="booth_count" class="w-full px-3 py-2 border rounded-lg text-sm" placeholder="Berapa banyak? (Cth: 3)">
                                </div>
                            </div>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lokasi</label>
                        <input type="text" name="name" value="{{ $location->name }}" required class="w-full px-4 py-2 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Alamat</label>
                        <textarea name="address" rows="3" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500">{{ $location->address }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Total Kuota Antrian Harian</label>
                            <input type="number" name="daily_quota" value="{{ $location->daily_quota }}" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500">
                            <p class="text-xs text-slate-500 mt-1">Total semua kuota lokasi tidak boleh melebihi batas paket Anda.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Integrasi AR QR Code</label>
                            <select name="ar_qr_code_id" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500">
                                <option value="">-- Tanpa AR --</option>
                                @foreach($arQrCodes ?? [] as $qr)
                                <option value="{{ $qr->id }}" {{ $location->ar_qr_code_id == $qr->id ? 'selected' : '' }}>{{ $qr->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div x-data="{ 
                        hours: {{ json_encode($location->operational_hours ?? [
                            'mon' => ['label' => 'Senin', 'active' => true, 'open' => '08:00', 'close' => '17:00'],
                            'tue' => ['label' => 'Selasa', 'active' => true, 'open' => '08:00', 'close' => '17:00'],
                            'wed' => ['label' => 'Rabu', 'active' => true, 'open' => '08:00', 'close' => '17:00'],
                            'thu' => ['label' => 'Kamis', 'active' => true, 'open' => '08:00', 'close' => '17:00'],
                            'fri' => ['label' => 'Jumat', 'active' => true, 'open' => '08:00', 'close' => '17:00'],
                            'sat' => ['label' => 'Sabtu', 'active' => false, 'open' => '08:00', 'close' => '14:00'],
                            'sun' => ['label' => 'Minggu', 'active' => false, 'open' => '08:00', 'close' => '14:00']
                        ]) }}
                    }">
                        <label class="block text-sm font-bold text-slate-700 mb-4 mt-6">Jam Operasional</label>
                        <input type="hidden" name="operational_hours" x-bind:value="JSON.stringify(hours)">
                        
                        <div class="space-y-3">
                            <template x-for="(data, day) in hours" :key="day">
                                <div class="flex items-center gap-4 p-3 bg-slate-50 rounded-lg border border-slate-200">
                                    <label class="flex items-center gap-3 w-32 cursor-pointer">
                                        <div class="relative">
                                            <input type="checkbox" x-model="data.active" class="sr-only peer">
                                            <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-teal-500"></div>
                                        </div>
                                        <span class="text-sm font-bold text-slate-700 capitalize" x-text="data.label"></span>
                                    </label>
                                    
                                    <div class="flex items-center gap-2 flex-1" x-show="data.active">
                                        <input type="time" x-model="data.open" class="px-2 py-1 bg-white border border-slate-300 rounded text-sm focus:border-teal-500 outline-none">
                                        <span class="text-slate-500 text-xs">-</span>
                                        <input type="time" x-model="data.close" class="px-2 py-1 bg-white border border-slate-300 rounded text-sm focus:border-teal-500 outline-none">
                                    </div>
                                    <div class="flex-1 text-slate-400 text-sm font-medium" x-show="!data.active">
                                        Tutup
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="px-6 py-2 rounded-xl btn-gradient text-white font-bold transition-all shadow-md">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>

                <div class="mt-10 pt-6 border-t border-red-100">
                    <h4 class="font-bold text-red-600 mb-2">Danger Zone</h4>
                    <p class="text-sm text-slate-500 mb-4">Menghapus lokasi akan menghapus semua data layanan, loket, petugas, dan tiket antrian yang terkait.</p>
                    <form action="{{ route('queue.locations.delete', $location->id) }}" method="POST" @submit.prevent="if(typeof showAppConfirm === 'function') showAppConfirm('Konfirmasi', 'Anda yakin ingin menghapus seluruh data lokasi ini? Tindakan ini tidak dapat dibatalkan.', $event.target); else if(confirm('Anda yakin ingin menghapus seluruh data lokasi ini? Tindakan ini tidak dapat dibatalkan.')) $event.target.submit();">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 rounded-lg text-sm font-bold transition-colors">
                            Hapus Lokasi Permanen
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODALS --}}
    {{-- Modal Add Service --}}
    <div x-show="showAddServiceModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showAddServiceModal = false"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
            <h3 class="font-bold text-lg mb-4">Tambah Layanan</h3>
            <form action="{{ route('queue.services.store', $location->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-1">Nama Layanan</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border rounded-lg" placeholder="Cth: Customer Service">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Prefix Kode (1 Huruf)</label>
                    <input type="text" name="prefix" required maxlength="1" class="w-full px-3 py-2 border rounded-lg uppercase" placeholder="Cth: A">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Estimasi Waktu (Menit)</label>
                    <input type="number" name="estimated_duration_minutes" value="10" class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div class="flex justify-end gap-3 pt-4 mt-2 border-t">
                    <button type="button" @click="showAddServiceModal = false" class="px-4 py-2 text-slate-600 bg-slate-100 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-teal-500 text-white rounded-lg font-bold">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Add Counter --}}
    <div x-show="showAddCounterModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showAddCounterModal = false"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
            <h3 class="font-bold text-lg mb-4">Tambah Loket</h3>
            <form action="{{ route('queue.counters.store', $location->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-1">Nama Loket</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border rounded-lg" placeholder="Cth: Loket 1">
                </div>
                <div class="flex justify-end gap-3 pt-4 mt-2 border-t">
                    <button type="button" @click="showAddCounterModal = false" class="px-4 py-2 text-slate-600 bg-slate-100 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-teal-500 text-white rounded-lg font-bold">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Add Staff --}}
    <div x-show="showAddStaffModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showAddStaffModal = false"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
            <h3 class="font-bold text-lg mb-4">Tambah Petugas</h3>
            <form action="{{ route('queue.staff.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="queue_location_id" value="{{ $location->id }}">
                <div>
                    <label class="block text-sm font-medium mb-1">Nama Petugas</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border rounded-lg" placeholder="Nama lengkap">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Username</label>
                    <input type="text" name="username" required class="w-full px-3 py-2 border rounded-lg" placeholder="Username untuk login">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Password</label>
                    <input type="password" name="password" required minlength="4" class="w-full px-3 py-2 border rounded-lg" placeholder="Minimal 4 karakter">
                </div>
                @if($location->has_booths)
                <div>
                    <label class="block text-sm font-medium mb-1">Tugaskan ke Loket / Booth</label>
                    <select name="queue_counter_id" class="w-full px-3 py-2 border rounded-lg">
                        <option value="">-- Pilih Loket (Opsional) --</option>
                        @foreach($location->counters as $counter)
                        <option value="{{ $counter->id }}">{{ $counter->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="flex justify-end gap-3 pt-4 mt-2 border-t">
                    <button type="button" @click="showAddStaffModal = false" class="px-4 py-2 text-slate-600 bg-slate-100 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-teal-500 text-white rounded-lg font-bold">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Toast UI --}}
    <div x-show="showToast" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         class="fixed bottom-4 right-4 z-50 flex items-center p-4 mb-4 text-slate-900 bg-white border border-slate-200 rounded-xl shadow-lg"
         style="display: none;">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-teal-600 bg-teal-100 rounded-lg">
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
            </svg>
            <span class="sr-only">Check icon</span>
        </div>
        <div class="ms-3 text-sm font-bold" x-text="toastMessage"></div>
    </div>
</div>
@endsection

