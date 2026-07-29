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
            $jsonData = htmlspecialchars(json_encode($modalData), ENT_QUOTES, 'UTF-8');
        @endphp
        
        @if($txn->status === 'Pending')
            <div class="flex items-center justify-end gap-2">
                <button type="button" @click="openDetailModal({{ $jsonData }})" class="px-2 py-1 bg-slate-100 text-slate-600 rounded text-xs font-semibold hover:bg-slate-200">Detail Transaksi</button>
                <form action="{{ route('admin.transactions.confirm', $txn->id) }}" method="POST" class="m-0 p-0 inline">
                    @csrf
                    <button type="submit" class="px-2 py-1 bg-teal-500 text-white rounded text-xs font-semibold hover:bg-teal-600">Terima</button>
                </form>
                <button type="button" @click="openRejectModal('{{ $txn->id }}')" class="px-2 py-1 bg-red-500 text-white rounded text-xs font-semibold hover:bg-red-600">Tolak</button>
            </div>
        @else
            <button type="button" @click="openDetailModal({{ $jsonData }})" class="text-xs text-indigo-600 hover:underline">Detail Transaksi</button>
        @endif
    </td>
</tr>   
@empty
<tr>
    <td colspan="7" class="px-6 py-8 text-center text-slate-400">Tidak ada transaksi yang cocok.</td>
</tr>
@endforelse