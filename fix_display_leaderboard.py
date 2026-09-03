import re

with open('resources/views/queue/display-leaderboard.blade.php', 'r') as f:
    content = f.read()

# Replace x-data state:
x_data = "customers: {{ Illuminate\\Support\\Js::from($customers) }},"
x_data_new = """byPoints: {{ Illuminate\\Support\\Js::from($byPoints) }},
        byViews: {{ Illuminate\\Support\\Js::from($byViews) }},
        activeTab: 'points',
        get activeCustomers() {
            return this.activeTab === 'points' ? this.byPoints : this.byViews;
        },"""

content = content.replace(x_data, x_data_new)

# Fetch data response
fetch_old = """if (res.ok) {
                    const data = await res.json();
                    this.customers = data.customers;
                }"""
fetch_new = """if (res.ok) {
                    const data = await res.json();
                    this.byPoints = data.byPoints;
                    this.byViews = data.byViews;
                }"""

content = content.replace(fetch_old, fetch_new)

# Init polling and rotation
init_old = """        init() {
            this.updateClock();
            setInterval(() => this.updateClock(), 1000);
            // Polling every 5 seconds
            setInterval(() => this.fetchData(), 5000);
        }"""
init_new = """        init() {
            this.updateClock();
            setInterval(() => this.updateClock(), 1000);
            setInterval(() => this.fetchData(), 5000);
            setInterval(() => {
                this.activeTab = this.activeTab === 'points' ? 'views' : 'points';
            }, 10000); // Rotate every 10 seconds
        }"""

content = content.replace(init_old, init_new)

# Header Title
header_title = """<div>
                <h1 class="text-3xl font-black text-white text-shadow tracking-tight">Top Customers</h1>
                <p class="text-indigo-200 font-medium">Klasemen Pelanggan Setia</p>
            </div>"""
header_title_new = """<div>
                <h1 class="text-3xl font-black text-white text-shadow tracking-tight">Top Customers</h1>
                <div class="flex items-center gap-2 mt-2">
                    <button @click="activeTab = 'points'" :class="activeTab === 'points' ? 'bg-amber-500/20 text-amber-300 border-amber-500/50' : 'bg-white/5 text-white/50 border-transparent'" class="px-4 py-1.5 rounded-full border text-sm font-bold transition-all duration-300">Berdasarkan Poin</button>
                    <button @click="activeTab = 'views'" :class="activeTab === 'views' ? 'bg-blue-500/20 text-blue-300 border-blue-500/50' : 'bg-white/5 text-white/50 border-transparent'" class="px-4 py-1.5 rounded-full border text-sm font-bold transition-all duration-300">Berdasarkan Viewers</button>
                </div>
            </div>"""

content = content.replace(header_title, header_title_new)

# Loop and value
loop_old = "<template x-for=\"(customer, index) in customers\" :key=\"customer.id\">"
loop_new = "<template x-for=\"(customer, index) in activeCustomers\" :key=\"customer.id + '-' + activeTab\">"
content = content.replace(loop_old, loop_new)

# Conditional styles for point vs view
badge_old = """[
                                index === 0 ? 'bg-gradient-to-br from-amber-300 to-orange-500 text-white' : 
                                (index === 1 ? 'bg-gradient-to-br from-slate-200 to-slate-400 text-white' : 
                                (index === 2 ? 'bg-gradient-to-br from-orange-300 to-orange-600 text-white' : 
                                'bg-white/10 text-white/50'))
                            ]"""
badge_new = """[
                                index === 0 ? (activeTab === 'points' ? 'bg-gradient-to-br from-amber-300 to-orange-500 text-white' : 'bg-gradient-to-br from-blue-400 to-indigo-600 text-white') : 
                                (index === 1 ? 'bg-gradient-to-br from-slate-200 to-slate-400 text-white' : 
                                (index === 2 ? 'bg-gradient-to-br from-orange-300 to-orange-600 text-white' : 
                                'bg-white/10 text-white/50'))
                            ]"""
content = content.replace(badge_old, badge_new)

card_old = """[
                        index === 0 ? 'bg-gradient-to-r from-amber-500/20 to-orange-500/20 border-amber-500/50' : '',
                        index === 1 ? 'bg-gradient-to-r from-slate-300/10 to-slate-400/10 border-slate-300/40' : '',
                        index === 2 ? 'bg-gradient-to-r from-orange-800/20 to-amber-900/20 border-orange-700/40' : ''
                    ]"""
card_new = """[
                        index === 0 ? (activeTab === 'points' ? 'bg-gradient-to-r from-amber-500/20 to-orange-500/20 border-amber-500/50' : 'bg-gradient-to-r from-blue-500/20 to-indigo-500/20 border-blue-500/50') : '',
                        index === 1 ? 'bg-gradient-to-r from-slate-300/10 to-slate-400/10 border-slate-300/40' : '',
                        index === 2 ? 'bg-gradient-to-r from-orange-800/20 to-amber-900/20 border-orange-700/40' : ''
                    ]"""
content = content.replace(card_old, card_new)


points_old = """<div class="text-right">
                            <div class="text-4xl md:text-5xl font-black text-white text-shadow tabular-nums tracking-tighter" x-text="customer.points"></div>
                            <div class="text-indigo-300 font-bold uppercase tracking-widest text-xs mt-1">Poin Reward</div>
                        </div>
                        <template x-if="index === 0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 md:w-16 md:h-16 text-amber-400 drop-shadow-[0_0_15px_rgba(251,191,36,0.8)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                        </template>"""

points_new = """<div class="text-right">
                            <div class="text-4xl md:text-5xl font-black text-white text-shadow tabular-nums tracking-tighter" x-text="activeTab === 'points' ? customer.points : (customer.views ?? 0)"></div>
                            <div class="text-indigo-300 font-bold uppercase tracking-widest text-xs mt-1" x-text="activeTab === 'points' ? 'Poin Reward' : 'Viewers'"></div>
                        </div>
                        <template x-if="index === 0 && activeTab === 'points'">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 md:w-16 md:h-16 text-amber-400 drop-shadow-[0_0_15px_rgba(251,191,36,0.8)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                        </template>
                        <template x-if="index === 0 && activeTab === 'views'">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 md:w-16 md:h-16 text-blue-400 drop-shadow-[0_0_15px_rgba(96,165,250,0.8)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </template>"""

content = content.replace(points_old, points_new)

empty_old = "<template x-if=\"customers.length === 0\">"
empty_new = "<template x-if=\"activeCustomers.length === 0\">"
content = content.replace(empty_old, empty_new)

with open('resources/views/queue/display-leaderboard.blade.php', 'w') as f:
    f.write(content)
print("SUCCESS")
