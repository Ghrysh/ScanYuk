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
</tr>   
@empty
<tr>
    <td colspan="6" class="px-6 py-8 text-center text-slate-400">Tidak ada transaksi yang cocok.</td>
</tr>
@endforelse