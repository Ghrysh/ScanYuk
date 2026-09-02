import re

with open('resources/views/scanner.blade.php', 'r') as f:
    content = f.read()

button_html = """
    <!-- QUEUE REDIRECT BUTTON -->
    <div x-data="{
            queueUuid: new URLSearchParams(window.location.search).get('queue_uuid'),
            get queueUrl() {
                return this.queueUuid ? '/antrian/' + this.queueUuid + '/register' : '#';
            }
         }"
         x-show="queueUuid"
         style="display: none;"
         class="fixed bottom-6 left-0 right-0 z-[100] flex justify-center px-4">
        
        <a :href="queueUrl" class="w-full max-w-sm bg-gradient-to-r from-teal-500 to-indigo-500 hover:from-teal-400 hover:to-indigo-400 text-white rounded-2xl shadow-2xl shadow-teal-500/30 p-4 flex items-center justify-between transition-all transform hover:scale-[1.02] border border-white/20 backdrop-blur-md">
            <div class="flex items-center gap-4">
                <div class="bg-white/20 p-2 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7" /></svg>
                </div>
                <div>
                    <p class="font-bold text-lg leading-tight">Lanjutkan ke Antrian</p>
                    <p class="text-white/80 text-xs">Klik di sini setelah selesai melihat AR</p>
                </div>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
        </a>
    </div>

</body>"""

content = content.replace("</body>", button_html)

with open('resources/views/scanner.blade.php', 'w') as f:
    f.write(content)

print("SUCCESS")
