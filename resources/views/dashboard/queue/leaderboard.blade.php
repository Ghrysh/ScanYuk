@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto pt-8 pb-12 px-4 sm:px-6 lg:px-8" x-data="{
    showPointsModal: false,
    selectedCustomer: null,
    pointsToAdd: 10,
    openModal(customer) {
        this.selectedCustomer = customer;
        this.pointsToAdd = 10;
        this.showPointsModal = true;
    }
}">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('queue.index') }}" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-slate-200 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                </a>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Leaderboard Customer</h1>
            </div>
            <p class="text-slate-500 ml-11">Daftar pelanggan yang telah selesai dilayani dan akumulasi poin mereka.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 rounded-xl bg-teal-50 border border-teal-200 text-teal-700 flex items-center gap-3 font-bold shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-6 py-4 font-bold text-slate-700 text-sm">Peringkat</th>
                        <th class="px-6 py-4 font-bold text-slate-700 text-sm">Nama Customer</th>
                        <th class="px-6 py-4 font-bold text-slate-700 text-sm">Nomor Telepon</th>
                        <th class="px-6 py-4 font-bold text-slate-700 text-sm text-center">Total Kunjungan</th>
                        <th class="px-6 py-4 font-bold text-slate-700 text-sm text-center">Poin Reward</th>
                        <th class="px-6 py-4 font-bold text-slate-700 text-sm text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($customers as $index => $customer)
                    <tr class="hover:bg-slate-50 transition-colors {{ $index < 3 ? 'bg-amber-50/30' : '' }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full font-black text-sm 
                                {{ $index == 0 ? 'bg-amber-100 text-amber-600 border border-amber-200' : 
                                  ($index == 1 ? 'bg-slate-200 text-slate-600 border border-slate-300' : 
                                  ($index == 2 ? 'bg-orange-100 text-orange-600 border border-orange-200' : 'bg-slate-100 text-slate-500')) }}">
                                {{ $index + 1 }}
                            </div>
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-900">
                            {{ $customer->name }}
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ $customer->phone ?: '-' }}
                        </td>
                        <td class="px-6 py-4 text-center font-semibold text-indigo-600">
                            {{ $customer->visits }}x
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 text-amber-600 font-black text-sm border border-amber-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                                {{ $customer->points }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button @click="openModal({{ $customer->toJson() }})" class="inline-flex items-center gap-2 px-4 py-2 bg-teal-50 hover:bg-teal-100 text-teal-600 border border-teal-200 rounded-lg text-sm font-bold transition-colors shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                                Beri Poin
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-4 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            <p class="font-medium text-lg text-slate-600 mb-1">Belum Ada Customer</p>
                            <p class="text-sm">Selesaikan antrian pelanggan untuk melihat klasemen di sini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Beri Poin -->
    <div x-show="showPointsModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="showPointsModal" x-transition.opacity.duration.300ms class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showPointsModal = false"></div>
        <div x-show="showPointsModal" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            class="relative bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 text-center">
            
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-teal-100 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
            </div>
            
            <h3 class="text-xl font-black text-slate-900 mb-1">Tambah Poin Reward</h3>
            <p class="text-slate-500 text-sm mb-6">Berikan poin tambahan kepada <span class="font-bold text-slate-700" x-text="selectedCustomer?.name"></span>.</p>
            
            <form :action="`/dashboard/queue/leaderboard/${selectedCustomer?.id}/add-points`" method="POST" class="w-full">
                @csrf
                <div class="mb-6">
                    <label class="block text-left text-sm font-bold text-slate-700 mb-2">Jumlah Poin</label>
                    <input type="number" name="points" x-model="pointsToAdd" min="1" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 text-center text-xl">
                </div>
                
                <div class="flex gap-3 w-full">
                    <button type="button" @click="showPointsModal = false" class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl transition-colors">Batal</button>
                    <button type="submit" class="flex-1 py-3 btn-gradient text-white font-bold rounded-xl shadow-lg transition-transform hover:-translate-y-0.5">Simpan Poin</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
