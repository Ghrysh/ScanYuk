@forelse($transactions as $txn)
<tr class="hover:bg-slate-50 transition-colors">
    <td class="px-6 py-4 text-xs font-mono text-slate-500">{{ $txn->id }}</td>
    <td class="px-6 py-4 text-slate-900">{{ $txn->user->name ?? 'User Dihapus' }}</td>
    <td class="px-6 py-4">
        <span class="px-3 py-1 rounded-full text-xs font-semibold
            {{ strtolower($txn->package->name ?? '') == 'pemula' ? 'bg-teal-50 text-teal-600' : 
            (strtolower($txn->package->name ?? '') == 'profesional' ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600') }}">
            {{ $txn->package->name ?? 'Unknown' }}
        </span>
    </td>
    <td class="px-6 py-4 font-bold text-slate-900">Rp{{ number_format($txn->amount, 0, ',', '.') }}</td>
    <td class="px-6 py-4">
        <span class="px-3 py-1 rounded-full text-xs font-semibold inline-block
            {{ in_array(strtolower($txn->status), ['berhasil', 'paid', 'success', 'unsettled']) ? 'bg-teal-100 text-teal-700' : 
            ($txn->status == 'Pending' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
            {{ in_array(strtolower($txn->status), ['berhasil', 'paid', 'success', 'unsettled']) ? 'Berhasil' : $txn->status }}
        </span>
    </td>
    <td class="px-6 py-4 text-slate-500">{{ $txn->created_at->format('Y-m-d') }}</td>
    <td class="px-6 py-4 text-right">
        @php
            $modalData = [
                'name' => $txn->user->name ?? 'User Dihapus',
                'email' => $txn->user->email ?? '-',
                'current_role' => $txn->user->role ?? '-',
                'package' => $txn->package->name ?? '-',
                'amount' => 'Rp' . number_format($txn->amount, 0, ',', '.'),
                'status' => $txn->status,
                'proof_url' => $txn->payment_proof ? asset('storage/' . $txn->payment_proof) : null
            ];
        @endphp
        
        @if($txn->status === 'Pending')
            <div class="flex items-center justify-end gap-2">
                <button type="button" @click='openDetailModal({!! json_encode($modalData, JSON_HEX_APOS) !!})' class="px-3 py-1.5 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded-lg text-xs font-bold hover:bg-indigo-100 transition-colors shadow-sm">Detail</button>
                <form action="{{ route('admin.transactions.confirm', $txn->id) }}" method="POST" class="m-0 p-0 inline">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 bg-teal-500 text-white rounded-lg text-xs font-bold hover:bg-teal-600 transition-colors shadow-sm">Terima</button>
                </form>
                <button type="button" @click="openRejectModal('{{ $txn->id }}')" class="px-3 py-1.5 bg-red-500 text-white rounded-lg text-xs font-bold hover:bg-red-600 transition-colors shadow-sm">Tolak</button>
            </div>
        @else
            <button type="button" @click='openDetailModal({!! json_encode($modalData, JSON_HEX_APOS) !!})' class="px-3 py-1.5 bg-slate-100 text-slate-700 border border-slate-200 rounded-lg text-xs font-bold hover:bg-slate-200 transition-colors shadow-sm inline-flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                Detail
            </button>
        @endif
    </td>
</tr>   
@empty
<tr>
    <td colspan="7" class="px-6 py-8 text-center text-slate-400">Tidak ada transaksi yang cocok.</td>
</tr>
@endforelse