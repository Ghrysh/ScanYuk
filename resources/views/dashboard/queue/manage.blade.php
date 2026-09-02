@extends('layouts.app')

@section('content')
<div class="max-w-[100rem] mx-auto w-full px-4 sm:px-6 lg:px-8 py-10" x-data="{
    copyLink(url) {
        navigator.clipboard.writeText(url);
        this.$store.toast?.show('Link berhasil disalin!', 'success') || alert('Link berhasil disalin!');
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
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 md:p-8 max-w-3xl">
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

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Integrasi AR QR Code (Opsional)</label>
                <select name="qr_code_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all sm:text-sm">
                    <option value="">-- Pilih AR QR Code --</option>
                    @foreach($arQrCodes ?? [] as $qr)
                    <option value="{{ $qr->id }}">{{ $qr->title }} ({{ $qr->ar_type }})</option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-500 mt-1">Jika dipilih, pelanggan akan melihat AR Anda sebelum mengambil antrian.</p>
            </div>

            <div x-data="{ 
                hours: {
                    monday: { active: true, open: '08:00', close: '17:00' },
                    tuesday: { active: true, open: '08:00', close: '17:00' },
                    wednesday: { active: true, open: '08:00', close: '17:00' },
                    thursday: { active: true, open: '08:00', close: '17:00' },
                    friday: { active: true, open: '08:00', close: '17:00' },
                    saturday: { active: false, open: '08:00', close: '14:00' },
                    sunday: { active: false, open: '08:00', close: '14:00' }
                }
            }">
                <label class="block text-sm font-bold text-slate-700 mb-4">Jam Operasional</label>
                <input type="hidden" name="operational_hours" x-bind:value="JSON.stringify(hours)">
                
                <div class="space-y-3">
                    <template x-for="(data, day) in hours" :key="day">
                        <div class="flex items-center gap-4 p-3 bg-slate-50 rounded-lg border border-slate-200">
                            <label class="flex items-center gap-3 w-32 cursor-pointer">
                                <div class="relative">
                                    <input type="checkbox" x-model="data.active" class="sr-only peer">
                                    <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-teal-500"></div>
                                </div>
                                <span class="text-sm font-bold text-slate-700 capitalize" x-text="day"></span>
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
                <button @click="copyLink('{{ route('queue.register', $location->uuid) }}')" class="w-full mb-2 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-bold transition-colors flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" /></svg>
                    Copy Link Antrian
                </button>
                <a href="{{ route('queue.display', $location->uuid) }}" target="_blank" class="w-full py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg text-sm font-bold transition-colors flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    Buka TV Display
                </a>
            </div>

            <nav class="bg-white rounded-xl border border-slate-200 shadow-sm p-2 flex flex-col gap-1">
                <button @click="tab = 'services'" :class="tab === 'services' ? 'bg-teal-50 text-teal-700 font-bold' : 'text-slate-600 hover:bg-slate-50'" class="px-4 py-3 rounded-lg text-sm text-left transition-colors flex items-center justify-between">
                    Layanan (Services)
                    <span class="bg-slate-100 text-slate-500 px-2 py-0.5 rounded text-xs">{{ $location->services->count() }}</span>
                </button>
                <button @click="tab = 'counters'" :class="tab === 'counters' ? 'bg-teal-50 text-teal-700 font-bold' : 'text-slate-600 hover:bg-slate-50'" class="px-4 py-3 rounded-lg text-sm text-left transition-colors flex items-center justify-between">
                    Loket (Counters)
                    <span class="bg-slate-100 text-slate-500 px-2 py-0.5 rounded text-xs">{{ $location->counters->count() }}</span>
                </button>
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
                            <form action="{{ route('queue.services.destroy', $svc->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus layanan ini?')">
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
                        <form action="{{ route('queue.counters.destroy', $counter->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus loket ini?')">
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
                        <form action="{{ route('queue.staff.destroy', $staff->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus petugas ini?')">
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
                <form action="{{ route('queue.locations.update', $location->id) }}" method="POST" class="space-y-6">
                    @csrf @method('PUT')
                    
                    <div class="flex items-center gap-3 p-4 bg-slate-50 border border-slate-200 rounded-lg">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <div class="relative">
                                <input type="checkbox" name="is_active" value="1" {{ $location->is_active ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-500"></div>
                            </div>
                            <span class="text-sm font-bold text-slate-900">Lokasi Aktif / Buka</span>
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lokasi</label>
                        <input type="text" name="name" value="{{ $location->name }}" required class="w-full px-4 py-2 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Alamat</label>
                        <textarea name="address" rows="3" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500">{{ $location->address }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Integrasi AR QR Code</label>
                        <select name="qr_code_id" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500">
                            <option value="">-- Tanpa AR --</option>
                            @foreach($arQrCodes ?? [] as $qr)
                            <option value="{{ $qr->id }}" {{ $location->qr_code_id == $qr->id ? 'selected' : '' }}>{{ $qr->title }}</option>
                            @endforeach
                        </select>
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
                    <form action="{{ route('queue.locations.destroy', $location->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus seluruh data lokasi ini? Tindakan ini tidak dapat dibatalkan.')">
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
            <form action="{{ route('queue.staff.store', $location->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-1">Nama Petugas</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border rounded-lg" placeholder="Nama lengkap">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">PIN (4-6 Digit)</label>
                    <input type="password" name="pin" required pattern="[0-9]{4,6}" class="w-full px-3 py-2 border rounded-lg" placeholder="1234">
                    <p class="text-xs text-slate-500 mt-1">Gunakan angka saja.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Tugaskan ke Loket</label>
                    <select name="queue_counter_id" class="w-full px-3 py-2 border rounded-lg">
                        <option value="">-- Pilih Loket (Opsional) --</option>
                        @foreach($location->counters as $counter)
                        <option value="{{ $counter->id }}">{{ $counter->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-3 pt-4 mt-2 border-t">
                    <button type="button" @click="showAddStaffModal = false" class="px-4 py-2 text-slate-600 bg-slate-100 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-teal-500 text-white rounded-lg font-bold">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection
