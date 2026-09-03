import re

with open('resources/views/dashboard/queue/index.blade.php', 'r') as f:
    content = f.read()

bad_card = """<div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-sm font-bold text-slate-500 mb-1">Selesai Dilayani</div>
            <div class="text-2xl font-extrabold text-slate-900">{{ number_format($totalServed) }}</div>
        </div>"""

good_card = """<div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm relative group overflow-hidden">
            <div class="flex justify-between items-start mb-1 relative z-10">
                <div class="text-sm font-bold text-slate-500">Selesai Dilayani</div>
                <a href="{{ route('queue.leaderboard') }}" class="w-8 h-8 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center hover:bg-amber-100 hover:text-amber-600 transition-colors shadow-sm" title="Lihat Leaderboard Customer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                </a>
            </div>
            <div class="text-2xl font-extrabold text-slate-900 relative z-10">{{ number_format($totalServed) }}</div>
        </div>"""

if bad_card in content:
    content = content.replace(bad_card, good_card)
    with open('resources/views/dashboard/queue/index.blade.php', 'w') as f:
        f.write(content)
    print("SUCCESS")
else:
    print("NOT FOUND")
