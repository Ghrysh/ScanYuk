import re

with open('resources/views/dashboard/queue/leaderboard.blade.php', 'r') as f:
    content = f.read()

target = """<p class="text-slate-500 ml-11">Daftar pelanggan yang telah selesai dilayani dan akumulasi poin mereka.</p>
        </div>
    </div>"""

new_button = """<p class="text-slate-500 ml-11 mb-4 md:mb-0">Daftar pelanggan yang telah selesai dilayani dan akumulasi poin mereka.</p>
        </div>
        <a href="{{ route('queue.leaderboard.display', auth()->id()) }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 border border-indigo-200 rounded-xl text-sm font-bold transition-colors shadow-sm whitespace-nowrap">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
            Display TV Leaderboard
        </a>
    </div>"""

if target in content:
    content = content.replace(target, new_button)
    with open('resources/views/dashboard/queue/leaderboard.blade.php', 'w') as f:
        f.write(content)
    print("SUCCESS")
else:
    print("NOT FOUND")
