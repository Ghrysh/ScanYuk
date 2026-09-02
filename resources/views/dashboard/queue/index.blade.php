@extends('layouts.app')

@section('content')
<div class="max-w-[100rem] mx-auto w-full px-4 sm:px-6 lg:px-8 py-10" x-data="{
    copyLink(url) {
        navigator.clipboard.writeText(url);
        this.$store.toast?.show('Link berhasil disalin!', 'success') || alert('Link berhasil disalin!');
    },
    showStaffModal: false
}">

    @if(session('error'))
    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 flex items-center gap-3 text-sm font-bold">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
        {{ session('error') }}
    </div>
    @if(session('showUpgrade'))
    <a href="{{ route('pricing') }}" class="mb-6 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl btn-gradient text-white font-semibold text-sm transition-colors shadow-sm">
        Upgrade Paket Sekarang
    </a>
    @endif
    @endif
    
    @if(session('success'))
    <div class="mb-6 p-4 rounded-xl bg-teal-50 border border-teal-200 text-teal-700 flex items-center gap-3 text-sm font-bold">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 mb-1">Sistem Antrian</h1>
            <p class="text-slate-500">Kelola antrian digital dan pantau analitik layanan Anda</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <form action="{{ route('queue.index') }}" method="GET" class="flex flex-wrap gap-2">
                <select name="location_id" class="px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-sm font-medium outline-none focus:border-teal-500 shadow-sm">
                    <option value="">Semua Lokasi</option>
                    @foreach($locations as $loc)
                    <option value="{{ $loc->id }}" {{ $selectedLocationId == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                    @endforeach
                </select>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-sm font-medium outline-none focus:border-teal-500 shadow-sm">
                <input type="date" name="date_to" value="{{ $dateTo }}" class="px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-sm font-medium outline-none focus:border-teal-500 shadow-sm">
                <button type="submit" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 rounded-lg text-sm font-bold transition-colors shadow-sm">
                    Filter
                </button>
            </form>
            
            <a href="{{ route('queue.staff.login') }}" target="_blank" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-sm font-bold transition-colors shadow-sm inline-flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                Dashboard Petugas
            </a>

            <button @click="showStaffModal = true" class="px-4 py-2.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 border border-indigo-200 rounded-lg text-sm font-bold transition-colors shadow-sm inline-flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                List Pegawai
            </button>

            <a href="{{ route('queue.locations.create') }}" class="px-5 py-2.5 rounded-lg btn-gradient text-white font-bold text-sm transition-colors shadow-sm inline-flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Buat Lokasi
            </a>
        </div>
    </div>

    <!-- Analytics Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-sm font-bold text-slate-500 mb-1">Total Registrasi</div>
            <div class="text-2xl font-extrabold text-slate-900">{{ number_format($totalRegistrations) }}</div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-sm font-bold text-slate-500 mb-1">Selesai Dilayani</div>
            <div class="text-2xl font-extrabold text-slate-900">{{ number_format($totalServed) }}</div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-sm font-bold text-slate-500 mb-1">No-show / Batal</div>
            <div class="text-2xl font-extrabold text-slate-900">{{ number_format($totalNoShow) }}</div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-sm font-bold text-slate-500 mb-1">Rata-rata Tunggu</div>
            <div class="text-2xl font-extrabold text-slate-900">{{ $avgWaitMinutes }} <span class="text-sm text-slate-500">mnt</span></div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-sm font-bold text-slate-500 mb-1">Rata-rata Layan</div>
            <div class="text-2xl font-extrabold text-slate-900">{{ $avgServiceMinutes }} <span class="text-sm text-slate-500">mnt</span></div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-sm font-bold text-slate-500 mb-1">Populer</div>
            <div class="text-lg font-extrabold text-slate-900 truncate" title="{{ $popularService }}">{{ $popularService ?: '-' }}</div>
        </div>
    </div>

    <!-- Chart -->
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm mb-10">
        <h3 class="text-lg font-bold text-slate-900 mb-6">Tren Registrasi Antrian</h3>
        <div class="w-full h-[300px]">
            <canvas id="dailyChart"></canvas>
        </div>
    </div>

    <!-- Lokasi Antrian -->
    <h3 class="text-xl font-bold text-slate-900 mb-4">Daftar Lokasi Antrian</h3>
    @if($locations->isEmpty())
    <div class="bg-white rounded-xl border border-slate-200 p-10 text-center shadow-sm">
        <p class="text-slate-500 mb-4">Belum ada lokasi antrian yang dibuat.</p>
        <a href="{{ route('queue.locations.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl btn-gradient text-white font-semibold text-sm transition-colors shadow-sm">
            Buat Lokasi Sekarang
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($locations as $location)
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm flex flex-col hover:border-teal-300 transition-colors">
            <div class="p-6 border-b border-slate-100 flex-grow">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 mb-1">{{ $location->name }}</h3>
                        <p class="text-sm text-slate-500 line-clamp-2">{{ $location->address ?: 'Tidak ada alamat' }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $location->is_active ? 'bg-teal-50 text-teal-600' : 'bg-slate-100 text-slate-500' }}">
                        {{ $location->is_active ? 'Buka' : 'Tutup' }}
                    </span>
                </div>

                <div class="grid grid-cols-3 gap-3 mt-6">
                    <div class="bg-amber-50 p-3 rounded-lg border border-amber-100 text-center">
                        <div class="text-xl font-black text-amber-600 mb-1">{{ $location->today_waiting ?? 0 }}</div>
                        <div class="text-[10px] font-bold text-amber-700 uppercase tracking-wider">Menunggu</div>
                    </div>
                    <div class="bg-indigo-50 p-3 rounded-lg border border-indigo-100 text-center">
                        <div class="text-xl font-black text-indigo-600 mb-1">{{ $location->today_serving ?? 0 }}</div>
                        <div class="text-[10px] font-bold text-indigo-700 uppercase tracking-wider">Dilayani</div>
                    </div>
                    <div class="bg-green-50 p-3 rounded-lg border border-green-100 text-center">
                        <div class="text-xl font-black text-green-600 mb-1">{{ $location->today_completed ?? 0 }}</div>
                        <div class="text-[10px] font-bold text-green-700 uppercase tracking-wider">Selesai</div>
                    </div>
                </div>
            </div>
            
            <div class="p-4 bg-slate-50 flex items-center justify-between rounded-b-xl gap-2">
                <a href="{{ route('queue.locations.manage', $location->id) }}" class="flex-1 py-2 text-center bg-white border border-slate-200 rounded-lg text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors">
                    Kelola
                </a>
                @if($location->services()->count() > 0)
                <a href="{{ route('queue.locations.qr', $location->id) }}" class="px-4 py-2 bg-white border border-slate-200 rounded-lg text-slate-500 hover:text-teal-600 hover:bg-teal-50 transition-colors" title="Unduh QR Code">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                </a>
                @else
                <button type="button" onclick="showAppConfirm('Layanan Kosong', 'Anda belum menambahkan layanan apapun ke lokasi ini. Silakan klik Kelola dan tambahkan setidaknya 1 layanan (misal: Poli Umum) terlebih dahulu agar QR Code bisa digunakan.', null)" class="px-4 py-2 bg-white border border-slate-200 rounded-lg text-red-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Layanan Kosong">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </button>
                @endif
                <a href="{{ route('queue.display', $location->uuid) }}" target="_blank" class="px-4 py-2 bg-white border border-slate-200 rounded-lg text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="Buka TV Display">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Modal List Pegawai -->
    <div x-show="showStaffModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="showStaffModal" x-transition.opacity.duration.300ms class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showStaffModal = false"></div>

        <div x-show="showStaffModal" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            class="relative bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col overflow-hidden">
            
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="text-xl font-bold text-slate-900">Manajemen Pegawai Antrian</h3>
                <button @click="showStaffModal = false" class="text-slate-400 hover:text-slate-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-6 flex flex-col md:flex-row gap-6 bg-slate-50" x-data="{ 
                showForm: false, 
                editMode: false, 
                currentStaff: null, 
                formAction: '{{ route('queue.staff.store') }}',
                selectedLocationId: '',
                locationsData: {{ Illuminate\Support\Js::from($locations->map(function($l) { return ['id' => $l->id, 'has_booths' => $l->has_booths, 'counters' => $l->counters]; })) }},
                get selectedLocationData() {
                    return this.locationsData.find(l => l.id == this.selectedLocationId);
                }
            }">
                <!-- List Pegawai -->
                <div class="flex-1 bg-white border border-slate-200 rounded-xl overflow-hidden flex flex-col shadow-sm">
                    <div class="p-4 border-b border-slate-100 bg-white flex justify-between items-center">
                        <h4 class="font-bold text-slate-800">Daftar Pegawai</h4>
                        <button @click="showForm = true; editMode = false; currentStaff = null; selectedLocationId = ''; formAction = '{{ route('queue.staff.store') }}'" class="text-xs font-bold text-teal-600 hover:text-teal-700 bg-teal-50 px-3 py-1.5 rounded-lg">+ Tambah Pegawai</button>
                    </div>
                    <div class="overflow-y-auto max-h-[400px]">
                        @if($staffs->isEmpty())
                        <div class="p-6 text-center text-slate-500 text-sm">Belum ada pegawai.</div>
                        @else
                        <ul class="divide-y divide-slate-100">
                            @foreach($staffs as $staff)
                            <li class="p-4 hover:bg-slate-50 flex justify-between items-center">
                                <div>
                                    <div class="font-bold text-slate-900">{{ $staff->name }}</div>
                                    <div class="text-xs text-slate-500 font-mono">{{ $staff->username }}</div>
                                    <div class="text-xs text-teal-600 font-medium mt-0.5">📍 {{ $staff->location->name ?? 'Tidak ada lokasi' }}</div>
                                </div>
                                <div class="flex gap-2">
                                    <button @click="showForm = true; editMode = true; currentStaff = {{ Illuminate\Support\Js::from($staff) }}; formAction = '/dashboard/queue/staff/{{ $staff->id }}'; selectedLocationId = currentStaff.queue_location_id" class="text-indigo-500 p-1.5 hover:bg-indigo-50 rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                    <form action="{{ route('queue.staff.delete', $staff->id) }}" method="POST" @submit.prevent="if(typeof showAppConfirm === 'function') showAppConfirm('Konfirmasi', 'Hapus pegawai ini?', $event.target); else if(confirm('Hapus pegawai ini?')) $event.target.submit();">
                                        @csrf @method('DELETE')
                                        <button class="text-red-500 p-1.5 hover:bg-red-50 rounded">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                </div>

                <!-- Form Pegawai -->
                <div x-show="showForm" style="display: none;" class="w-full md:w-80 bg-white border border-slate-200 rounded-xl p-5 shadow-sm h-fit">
                    <h4 class="font-bold text-slate-800 border-b border-slate-100 pb-3 mb-4" x-text="editMode ? 'Edit Pegawai' : 'Tambah Pegawai Baru'"></h4>
                    <form :action="formAction" method="POST" class="space-y-4">
                        @csrf
                        <template x-if="editMode">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tugaskan di Lokasi <span class="text-red-500">*</span></label>
                            <select name="queue_location_id" x-model="selectedLocationId" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-teal-500">
                                <option value="">-- Pilih Lokasi --</option>
                                @foreach($locations as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div x-show="selectedLocationData && selectedLocationData.has_booths" style="display: none;">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tugaskan ke Booth / Loket</label>
                            <select name="queue_counter_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-teal-500">
                                <option value="">-- Pilih Loket (Opsional) --</option>
                                <template x-if="selectedLocationData && selectedLocationData.counters">
                                    <template x-for="counter in selectedLocationData.counters" :key="counter.id">
                                        <option :value="counter.id" x-text="counter.name" :selected="editMode && currentStaff && currentStaff.queue_counter_id == counter.id"></option>
                                    </template>
                                </template>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" :value="editMode ? currentStaff.name : ''" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-teal-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Username Login <span class="text-red-500">*</span></label>
                            <input type="text" name="username" :value="editMode ? currentStaff.username : ''" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-teal-500 font-mono">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Password <span x-show="!editMode" class="text-red-500">*</span></label>
                            <input type="password" name="password" :required="!editMode" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-teal-500">
                            <p x-show="editMode" class="text-[10px] text-slate-500 mt-1">Kosongkan jika tidak ingin mengubah password.</p>
                        </div>

                        <div class="pt-2 flex gap-2">
                            <button type="button" @click="showForm = false" class="flex-1 px-3 py-2 border border-slate-200 rounded-lg text-slate-600 text-sm font-bold hover:bg-slate-50">Batal</button>
                            <button type="submit" class="flex-1 px-3 py-2 btn-gradient text-white rounded-lg text-sm font-bold shadow-sm shadow-teal-200">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('dailyChart');
        if(!ctx) return;
        @php
    $formattedData = array_map(function($k, $v) { 
        return ['date' => $k, 'count' => $v]; 
    }, array_keys($dailyData), array_values($dailyData));
@endphp
        const dailyData = @json($formattedData);
        
        new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: dailyData.map(d => d.date),
                datasets: [{
                    label: 'Jumlah Antrian',
                    data: dailyData.map(d => d.count),
                    borderColor: '#14b8a6',
                    backgroundColor: 'rgba(20, 184, 166, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#14b8a6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        titleFont: { family: 'Inter', size: 13 },
                        bodyFont: { family: 'Inter', size: 14, weight: 'bold' },
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [5, 5], color: '#f1f5f9' },
                        ticks: { stepSize: 1, font: { family: 'Inter' } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Inter' } }
                    }
                }
            }
        });
    });
</script>
@endsection
