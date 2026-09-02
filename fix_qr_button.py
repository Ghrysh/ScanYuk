import re

with open('resources/views/dashboard/queue/index.blade.php', 'r') as f:
    content = f.read()

bad_qr = """<a href="{{ route('queue.locations.qr', $location->id) }}" class="px-4 py-2 bg-white border border-slate-200 rounded-lg text-slate-500 hover:text-teal-600 hover:bg-teal-50 transition-colors" title="Unduh QR Code">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                </a>"""

good_qr = """@if($location->services()->count() > 0)
                <a href="{{ route('queue.locations.qr', $location->id) }}" class="px-4 py-2 bg-white border border-slate-200 rounded-lg text-slate-500 hover:text-teal-600 hover:bg-teal-50 transition-colors" title="Unduh QR Code">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                </a>
                @else
                <button type="button" onclick="showAppConfirm('Layanan Kosong', 'Anda belum menambahkan layanan apapun ke lokasi ini. Silakan klik Kelola dan tambahkan layanan terlebih dahulu agar QR Code bisa digunakan.', null)" class="px-4 py-2 bg-white border border-slate-200 rounded-lg text-red-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Layanan Kosong">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </button>
                @endif"""

if bad_qr in content:
    content = content.replace(bad_qr, good_qr)
    with open('resources/views/dashboard/queue/index.blade.php', 'w') as f:
        f.write(content)
    print("SUCCESS index.blade.php")
else:
    print("NOT FOUND in index")
