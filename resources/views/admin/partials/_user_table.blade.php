@forelse($users as $user)
<tr class="hover:bg-slate-50 transition-colors">
    <td class="px-6 py-4">
        <div class="font-bold text-slate-900">{{ $user->name }}</div>
        <div class="text-xs text-slate-500">{{ $user->email }}</div>
    </td>
    <td class="px-6 py-4">
        <span class="px-3 py-1 rounded-full text-xs font-semibold
            {{ $user->role == 'free' ? 'bg-slate-100 text-slate-600' : 
              ($user->role == 'starter' ? 'bg-teal-50 text-teal-600' : 
              ($user->role == 'professional' ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600')) }}">
            {{ ucfirst($user->role) }}
        </span>
    </td>
    <td class="px-6 py-4 font-medium">{{ $user->image ?? 0 }}</td>
    <td class="px-6 py-4 font-medium">{{ $user->voice ?? 0 }}</td>
    <td class="px-6 py-4 font-medium">{{ $user->scan ?? 0 }}</td>
    <td class="px-6 py-4">
        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ ($user->status ?? 'active') == 'active' ? 'bg-teal-100 text-teal-700' : 'bg-red-100 text-red-700' }}">
            {{ ucfirst($user->status ?? 'Active') }}
        </span>
    </td>
    <td class="px-6 py-4">
        @if($user->queue_status === 'active')
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">Aktif</span>
        @elseif($user->queue_status === 'pending')
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">Menunggu</span>
        @else
            <span class="text-slate-400 text-xs">-</span>
        @endif
    </td>
    <td class="px-6 py-4 text-right flex justify-end gap-2 items-center">
        @if($user->queue_status === 'pending')
        <form action="{{ route('admin.users.approve_queue', $user->id) }}" method="POST" class="m-0 p-0 inline">
            @csrf
            <button type="submit" class="text-slate-400 hover:text-green-500 transition-colors mr-2" title="Setujui Akses Sistem Antrian">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </button>
        </form>
        @endif

        <form id="toggle-form-{{ $user->id }}" action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="m-0 p-0">
            @csrf
            @method('PATCH')
        </form>
        <button type="button" @click="openModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->status }}')" class="text-slate-400 hover:text-amber-500 transition-colors" title="{{ $user->status == 'Aktif' ? 'Suspend Akun' : 'Aktifkan Akun' }}">
            @if($user->status == 'Aktif')
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
            @else
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
            @endif
        </button>

        <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="m-0 p-0">
            @csrf
            @method('DELETE')
        </form>
        <button type="button" @click="openDeleteModal({{ $user->id }}, '{{ addslashes($user->name) }}')" class="text-slate-400 hover:text-red-500 transition-colors" title="Hapus Akun">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
        </button>
    </td>
</tr>
@empty
<tr><td colspan="7" class="px-6 py-8 text-center text-slate-500">User tidak ditemukan.</td></tr>
@endforelse