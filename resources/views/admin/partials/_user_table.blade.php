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
        <form id="toggle-form-{{ $user->id }}" action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="inline">
            @csrf
            @method('PATCH')
            @if(($user->status ?? 'active') === 'active')
                <button type="button" @click="openModal('{{ $user->id }}', '{{ $user->name }}', 'active')" class="text-slate-400 hover:text-red-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                </button>
            @else
                <button type="button" @click="openModal('{{ $user->id }}', '{{ $user->name }}', 'suspended')" class="text-slate-400 hover:text-teal-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </button>
            @endif
        </form>
    </td>
</tr>
@empty
<tr><td colspan="7" class="px-6 py-8 text-center text-slate-500">User tidak ditemukan.</td></tr>
@endforelse