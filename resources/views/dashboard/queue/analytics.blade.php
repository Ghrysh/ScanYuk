@extends('layouts.app')

@section('content')
<div class="max-w-[100rem] mx-auto w-full px-4 sm:px-6 lg:px-8 py-10">
    
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <a href="{{ route('queue.index') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-teal-600 transition-colors mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali ke Antrian
            </a>
            <h1 class="text-3xl font-extrabold text-slate-900 mb-1">Analytics Antrian</h1>
            <p class="text-slate-500">Pantau performa layanan antrian Anda</p>
        </div>
        
        <form action="{{ route('queue.analytics') }}" method="GET" class="flex flex-col sm:flex-row gap-3 bg-white p-3 rounded-xl border border-slate-200 shadow-sm">
            <select name="location_id" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-teal-500">
                <option value="">Semua Lokasi</option>
                @foreach($locations as $loc)
                <option value="{{ $loc->id }}" {{ $selectedLocationId == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                @endforeach
            </select>
            <input type="date" name="date_from" value="{{ $dateFrom }}" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-teal-500">
            <input type="date" name="date_to" value="{{ $dateTo }}" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-teal-500">
            <button type="submit" class="px-4 py-2 bg-teal-500 hover:bg-teal-600 text-white rounded-lg text-sm font-bold transition-colors">
                Filter
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        {{-- Card 1 --}}
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center gap-2 text-slate-500 mb-4">
                <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-500 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
                <span class="text-sm font-bold text-slate-700">Total Registrasi</span>
            </div>
            <div class="text-3xl font-extrabold text-slate-900">{{ number_format($totalRegistrations) }}</div>
        </div>

        {{-- Card 2 --}}
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center gap-2 text-slate-500 mb-4">
                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-500 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <span class="text-sm font-bold text-slate-700">Selesai Dilayani</span>
            </div>
            <div class="text-3xl font-extrabold text-slate-900">{{ number_format($totalServed) }}</div>
        </div>

        {{-- Card 3 --}}
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center gap-2 text-slate-500 mb-4">
                <div class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <span class="text-sm font-bold text-slate-700">No-show / Batal</span>
            </div>
            <div class="text-3xl font-extrabold text-slate-900">{{ number_format($totalNoShow) }}</div>
        </div>

        {{-- Card 4 --}}
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center gap-2 text-slate-500 mb-4">
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <span class="text-sm font-bold text-slate-700">Rata-rata Menunggu</span>
            </div>
            <div class="text-3xl font-extrabold text-slate-900">{{ $avgWaitMinutes }} <span class="text-lg text-slate-500 font-medium">menit</span></div>
        </div>

        {{-- Card 5 --}}
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center gap-2 text-slate-500 mb-4">
                <div class="w-8 h-8 rounded-lg bg-green-50 text-green-500 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
                <span class="text-sm font-bold text-slate-700">Rata-rata Pelayanan</span>
            </div>
            <div class="text-3xl font-extrabold text-slate-900">{{ $avgServiceMinutes }} <span class="text-lg text-slate-500 font-medium">menit</span></div>
        </div>

        {{-- Card 6 --}}
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center gap-2 text-slate-500 mb-4">
                <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-500 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                </div>
                <span class="text-sm font-bold text-slate-700">Layanan Terpopuler</span>
            </div>
            <div class="text-xl font-extrabold text-slate-900 truncate" title="{{ $popularService }}">{{ $popularService ?: '-' }}</div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm mb-10">
        <h3 class="text-lg font-bold text-slate-900 mb-6">Tren Registrasi Antrian</h3>
        <div class="w-full h-[400px]">
            <canvas id="dailyChart"></canvas>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('dailyChart').getContext('2d');
        const dailyData = @json($dailyData);
        
        new Chart(ctx, {
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
