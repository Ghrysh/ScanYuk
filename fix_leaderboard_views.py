import re

with open('resources/views/dashboard/queue/leaderboard.blade.php', 'r') as f:
    content = f.read()

# Add a button for "Tambah View" next to "Beri Poin"
# Find: <th class="px-6 py-4 font-bold text-slate-700 text-sm text-center">Poin Reward</th>
# Replace: ... and <th class="px-6 py-4 font-bold text-slate-700 text-sm text-center">Viewers</th>

content = content.replace(
    '<th class="px-6 py-4 font-bold text-slate-700 text-sm text-center">Poin Reward</th>',
    '<th class="px-6 py-4 font-bold text-slate-700 text-sm text-center">Poin Reward</th>\n                        <th class="px-6 py-4 font-bold text-slate-700 text-sm text-center">Viewers</th>'
)

# Find points td:
points_td = """<td class="px-6 py-4 text-center">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 text-amber-600 font-black text-sm border border-amber-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                                {{ $customer->points }}
                            </div>
                        </td>"""

views_td = """<td class="px-6 py-4 text-center">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-blue-600 font-black text-sm border border-blue-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                {{ $customer->views ?? 0 }}
                            </div>
                        </td>"""

if points_td in content:
    content = content.replace(points_td, points_td + '\n                        ' + views_td)

# Action td buttons
action_td = """<button @click="openModal({{ $customer->toJson() }})" class="inline-flex items-center gap-2 px-4 py-2 bg-teal-50 hover:bg-teal-100 text-teal-600 border border-teal-200 rounded-lg text-sm font-bold transition-colors shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                                Beri Poin
                            </button>"""

action_td_new = """<div class="flex items-center justify-end gap-2">
                                <button @click="openModal({{ $customer->toJson() }})" class="inline-flex items-center gap-2 px-3 py-1.5 bg-teal-50 hover:bg-teal-100 text-teal-600 border border-teal-200 rounded-lg text-sm font-bold transition-colors shadow-sm" title="Beri Poin">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                                    Poin
                                </button>
                                <button @click="openViewsModal({{ $customer->toJson() }})" class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 border border-blue-200 rounded-lg text-sm font-bold transition-colors shadow-sm" title="Tambah Viewers">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    View
                                </button>
                            </div>"""

if action_td in content:
    content = content.replace(action_td, action_td_new)


# Modals data variables
alpine_data = """showPointsModal: false,
    selectedCustomer: null,
    pointsToAdd: 10,"""

alpine_data_new = """showPointsModal: false,
    showViewsModal: false,
    selectedCustomer: null,
    pointsToAdd: 10,
    viewsToAdd: 50,
    openViewsModal(customer) {
        this.selectedCustomer = customer;
        this.viewsToAdd = 50;
        this.showViewsModal = true;
    },"""

if alpine_data in content:
    content = content.replace(alpine_data, alpine_data_new)

# Views Modal HTML
views_modal_html = """    <!-- Modal Beri Poin -->
    <div x-show="showPointsModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">"""

views_modal_html_new = """    <!-- Modal Tambah Viewers -->
    <div x-show="showViewsModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="showViewsModal" x-transition.opacity.duration.300ms class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showViewsModal = false"></div>
        <div x-show="showViewsModal" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            class="relative bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 text-center">
            
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
            </div>
            
            <h3 class="text-xl font-black text-slate-900 mb-1">Tambah Viewers Simulasi</h3>
            <p class="text-slate-500 text-sm mb-6">Tambahkan penonton untuk <span class="font-bold text-slate-700" x-text="selectedCustomer?.name"></span>.</p>
            
            <form :action="`/dashboard/queue/leaderboard/${selectedCustomer?.id}/add-views`" method="POST" class="w-full">
                @csrf
                <div class="mb-6">
                    <label class="block text-left text-sm font-bold text-slate-700 mb-2">Jumlah Viewers</label>
                    <input type="number" name="views" x-model="viewsToAdd" min="1" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-center text-xl">
                </div>
                
                <div class="flex gap-3 w-full">
                    <button type="button" @click="showViewsModal = false" class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl transition-colors">Batal</button>
                    <button type="submit" class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transition-transform hover:-translate-y-0.5">Simpan Viewers</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Beri Poin -->
    <div x-show="showPointsModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">"""

if views_modal_html in content:
    content = content.replace(views_modal_html, views_modal_html_new)
    
# Change colspan empty state
content = content.replace('<td colspan="6" class="px-6 py-12 text-center text-slate-500">', '<td colspan="7" class="px-6 py-12 text-center text-slate-500">')

with open('resources/views/dashboard/queue/leaderboard.blade.php', 'w') as f:
    f.write(content)
print("SUCCESS")
