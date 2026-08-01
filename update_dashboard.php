<?php
$content = file_get_contents('resources/views/admin/dashboard.blade.php');

$newBlock = <<<'BLADE'
    <div x-show="activeTab === 'seo'" style="display: none;" x-transition.opacity.duration.300ms
         x-data="{
             isAnalyzing: false,
             pagePath: '/',
             targetKeyword: '',
             recommendation: null,
             history: [],
             async init() {
                 this.fetchHistory();
             },
             async fetchHistory() {
                 try {
                     let res = await fetch('/admin/seo/recommendations');
                     this.history = await res.json();
                 } catch(e) {}
             },
             async analyze() {
                 if(!this.pagePath || !this.targetKeyword) {
                     alert('Harap isi halaman dan target keyword');
                     return;
                 }
                 this.isAnalyzing = true;
                 this.recommendation = null;
                 
                 let formData = new FormData();
                 formData.append('page_path', this.pagePath);
                 formData.append('target_keyword', this.targetKeyword);
                 formData.append('_token', document.querySelector('meta[name=csrf-token]').content);
                 
                 try {
                     let res = await fetch('/admin/seo/analyze', { method: 'POST', body: formData });
                     let data = await res.json();
                     if (data.success) {
                         this.recommendation = data.data;
                         this.fetchHistory();
                         if(data.warning) alert(data.warning);
                     } else {
                         alert(data.message || 'Gagal menganalisa');
                     }
                 } catch (e) {
                     alert('Terjadi kesalahan server');
                 } finally {
                     this.isAnalyzing = false;
                 }
             },
             async applyRec(id) {
                 if(!confirm('Terapkan perubahan ini secara otomatis ke halaman?')) return;
                 let formData = new FormData();
                 formData.append('_token', document.querySelector('meta[name=csrf-token]').content);
                 try {
                     let res = await fetch('/admin/seo/apply/' + id, { method: 'POST', body: formData });
                     let data = await res.json();
                     if(data.success) {
                         alert(data.message);
                         this.recommendation.status = 'applied';
                         this.fetchHistory();
                     }
                 } catch (e) {
                     alert('Gagal apply');
                 }
             },
             async updateManualStatus(id, newStatus) {
                 let formData = new FormData();
                 formData.append('_token', document.querySelector('meta[name=csrf-token]').content);
                 formData.append('status', newStatus);
                 try {
                     let res = await fetch('/admin/seo/update-manual-status/' + id, { method: 'POST', body: formData });
                     let data = await res.json();
                     if(data.success) {
                         this.recommendation.manual_status = newStatus;
                         this.fetchHistory();
                     }
                 } catch (e) {
                     alert('Gagal update status manual');
                 }
             }
         }">
         
         <div class="mb-6 flex flex-col md:flex-row justify-between md:items-end gap-4 bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
             <div class="flex-1 w-full flex flex-col sm:flex-row gap-4 items-end">
                <div class="w-full sm:w-1/3">
                    <label class="block text-sm font-bold text-slate-700 mb-1">Pilih Halaman</label>
                    <select x-model="pagePath" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg outline-none focus:border-teal-500">
                        <option value="/">Home (/)</option>
                        <option value="/pricing">Pricing (/pricing)</option>
                        <option value="/consumer">Consumer (/consumer)</option>
                        <option value="/business">Business (/business)</option>
                        <option value="/faq">FAQ (/faq)</option>
                    </select>
                </div>
                <div class="w-full sm:w-1/3">
                    <label class="block text-sm font-bold text-slate-700 mb-1">Target Keyword</label>
                    <input type="text" x-model="targetKeyword" placeholder="Misal: AR QR Code Scanner" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg outline-none focus:border-teal-500">
                </div>
                <div class="w-full sm:w-1/3">
                    <button @click="analyze()" :disabled="isAnalyzing" class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white font-bold rounded-lg shadow-md transition-all flex items-center justify-center gap-2">
                        <svg x-show="isAnalyzing" style="display: none;" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span x-text="isAnalyzing ? 'AI sedang menganalisa...' : '✨ Analisa dengan AI'"></span>
                    </button>
                </div>
             </div>
         </div>

         <template x-if="!recommendation">
             <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-8">
                 <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                     <h3 class="font-bold text-slate-800">Riwayat Rekomendasi SEO</h3>
                     <button @click="fetchHistory()" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Refresh
                     </button>
                 </div>
                 <div class="overflow-x-auto">
                     <table class="w-full text-left text-sm">
                         <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                             <tr>
                                 <th class="px-6 py-3 font-semibold">Waktu / Tipe</th>
                                 <th class="px-6 py-3 font-semibold">Halaman</th>
                                 <th class="px-6 py-3 font-semibold">Target Keyword</th>
                                 <th class="px-6 py-3 font-semibold">Status Auto</th>
                                 <th class="px-6 py-3 font-semibold">Tugas Programmer</th>
                                 <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                             </tr>
                         </thead>
                         <tbody class="divide-y divide-slate-100">
                             <template x-for="item in history" :key="item.id">
                                 <tr class="hover:bg-slate-50 transition-colors">
                                     <td class="px-6 py-4">
                                         <div class="text-slate-600 mb-1" x-text="new Date(item.created_at).toLocaleString('id-ID')"></div>
                                         <span x-show="item.ai_type === 'proactive'" class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-700">🤖 AI Proactive</span>
                                         <span x-show="item.ai_type === 'manual'" class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">👤 Manual Req</span>
                                     </td>
                                     <td class="px-6 py-4 font-semibold text-indigo-600" x-text="item.page_path"></td>
                                     <td class="px-6 py-4">
                                         <span class="text-slate-700 font-medium" x-text="item.target_keyword"></span>
                                         <div class="mt-1 flex items-center gap-1 text-[10px] text-slate-500">Skor: <span class="font-bold px-1 rounded text-white" :class="item.overall_score >= 80 ? 'bg-teal-500' : (item.overall_score >= 50 ? 'bg-amber-500' : 'bg-red-500')" x-text="item.overall_score"></span></div>
                                     </td>
                                     <td class="px-6 py-4">
                                         <span x-show="item.status === 'applied'" class="text-teal-600 font-bold text-xs bg-teal-50 px-2 py-1 rounded">Applied</span>
                                         <span x-show="item.status === 'pending'" class="text-amber-600 font-bold text-xs bg-amber-50 px-2 py-1 rounded">Pending</span>
                                     </td>
                                     <td class="px-6 py-4">
                                         <span x-show="item.manual_status === 'pending'" class="text-slate-600 font-bold text-xs border border-slate-200 px-2 py-1 rounded">⏳ Pending</span>
                                         <span x-show="item.manual_status === 'proses'" class="text-blue-600 font-bold text-xs bg-blue-50 px-2 py-1 rounded">👨‍💻 Proses</span>
                                         <span x-show="item.manual_status === 'selesai'" class="text-emerald-600 font-bold text-xs bg-emerald-50 px-2 py-1 rounded">✅ Selesai</span>
                                     </td>
                                     <td class="px-6 py-4 text-right">
                                         <button @click="recommendation = item" class="text-indigo-600 hover:text-indigo-800 font-semibold text-sm bg-indigo-50 px-3 py-1.5 rounded-lg transition-colors">Lihat Detail &rarr;</button>
                                     </td>
                                 </tr>
                             </template>
                             <tr x-show="history.length === 0">
                                 <td colspan="6" class="px-6 py-8 text-center text-slate-500">Belum ada riwayat analisa SEO. Silakan lakukan analisa pertama Anda.</td>
                             </tr>
                         </tbody>
                     </table>
                 </div>
             </div>
         </template>

         <template x-if="recommendation">
             <div class="bg-slate-50/50 rounded-xl mb-8">
                 <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                     <div>
                         <button @click="recommendation = null" class="text-sm font-semibold text-slate-500 hover:text-slate-800 mb-2 flex items-center gap-1">&larr; Kembali ke List</button>
                         <h3 class="text-2xl font-black text-slate-900 flex items-center gap-3">
                            <span class="text-indigo-600" x-text="recommendation.page_path"></span>
                            <span x-show="recommendation.ai_type === 'proactive'" class="px-2.5 py-1 rounded-md text-xs font-bold bg-indigo-100 text-indigo-700 tracking-wide uppercase">🤖 AI Proactive</span>
                         </h3>
                     </div>
                     <div class="flex items-center gap-3 mt-4 md:mt-0">
                        <span class="text-sm font-semibold text-slate-500">Skor SEO Target:</span>
                        <div class="px-4 py-1.5 rounded-full text-white font-black text-lg" :class="recommendation.overall_score >= 80 ? 'bg-teal-500' : (recommendation.overall_score >= 50 ? 'bg-amber-500' : 'bg-red-500')" x-text="recommendation.overall_score + '/100'"></div>
                     </div>
                 </div>
                 
                 <!-- BAGIAN 1: AUTO APPLY VARIABLES -->
                 <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-6">
                    <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                                <svg class="w-6 h-6 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                Perubahan Meta Otomatis
                            </h3>
                            <p class="text-sm text-slate-500 mt-1">Bagian ini dapat diterapkan secara instan ke website tanpa coding.</p>
                        </div>
                        <div>
                            <button x-show="recommendation.status !== 'applied'" @click="applyRec(recommendation.id)" class="px-6 py-2 bg-teal-500 hover:bg-teal-600 text-white font-bold rounded-xl shadow-lg shadow-teal-200 transition-all flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Terapkan Otomatis
                            </button>
                            <div x-show="recommendation.status === 'applied'" class="px-6 py-2 bg-slate-100 text-teal-600 font-bold rounded-xl flex items-center gap-2">
                                <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Sudah Diterapkan
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-200">
                            <h4 class="font-bold text-slate-800 mb-2 flex items-center gap-2 text-sm uppercase">Meta Title</h4>
                            <p class="text-slate-600 font-medium" x-text="recommendation.recommendations.meta_title"></p>
                        </div>
                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-200">
                            <h4 class="font-bold text-slate-800 mb-2 flex items-center gap-2 text-sm uppercase">Meta Description</h4>
                            <p class="text-slate-600 text-sm leading-relaxed" x-text="recommendation.recommendations.meta_description"></p>
                        </div>
                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-200 md:col-span-2">
                            <h4 class="font-bold text-slate-800 mb-2 flex items-center gap-2 text-sm uppercase">Heading (H1) Utama</h4>
                            <p class="text-slate-600 font-semibold text-lg" x-text="recommendation.recommendations.h1_heading"></p>
                        </div>
                    </div>
                    <div class="bg-slate-50 p-5 rounded-xl border border-slate-200">
                        <h4 class="font-bold text-slate-800 mb-3 flex items-center gap-2 text-sm uppercase">FAQ Schema (JSON-LD)</h4>
                        <div class="space-y-3">
                            <template x-for="(faq, index) in recommendation.recommendations.faq_schema" :key="index">
                                <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm">
                                    <p class="font-bold text-slate-800 text-sm mb-1" x-text="'Q: ' + faq.question"></p>
                                    <p class="text-slate-600 text-sm" x-text="'A: ' + faq.answer"></p>
                                </div>
                            </template>
                        </div>
                    </div>
                 </div>

                 <!-- BAGIAN 2: MANUAL TASKS -->
                 <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-6">
                    <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                                Tugas Teknisi / Programmer
                            </h3>
                            <p class="text-sm text-slate-500 mt-1">Bagian ini memerlukan tindakan manual dari developer.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-semibold text-slate-600">Status Tugas:</span>
                            <select :value="recommendation.manual_status" @change="updateManualStatus(recommendation.id, $event.target.value)" class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg outline-none font-bold text-sm"
                                :class="{'text-slate-600': recommendation.manual_status === 'pending', 'text-blue-600': recommendation.manual_status === 'proses', 'text-emerald-600': recommendation.manual_status === 'selesai'}">
                                <option value="pending">⏳ Pending</option>
                                <option value="proses">👨‍💻 Dalam Proses</option>
                                <option value="selesai">✅ Selesai</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-indigo-50/50 p-5 rounded-xl border border-indigo-100">
                            <h4 class="font-bold text-indigo-800 text-sm mb-3 uppercase flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg> Link Strategy</h4>
                            <p class="text-indigo-700 text-sm mb-3"><strong class="block text-indigo-900 mb-1">Backlink:</strong> <span x-text="recommendation.recommendations.backlink_strategy"></span></p>
                            <p class="text-indigo-700 text-sm"><strong class="block text-indigo-900 mb-1">Internal:</strong> <span x-text="recommendation.recommendations.internal_link_strategy"></span></p>
                        </div>
                        <div class="bg-amber-50/50 p-5 rounded-xl border border-amber-100">
                            <h4 class="font-bold text-amber-800 text-sm mb-3 uppercase flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> Image Optimization</h4>
                            <p class="text-amber-700 text-sm leading-relaxed" x-text="recommendation.recommendations.image_optimization"></p>
                        </div>
                        <div class="bg-rose-50/50 p-5 rounded-xl border border-rose-100">
                            <h4 class="font-bold text-rose-800 text-sm mb-3 uppercase flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg> Page Speed</h4>
                            <p class="text-rose-700 text-sm leading-relaxed" x-text="recommendation.recommendations.page_speed"></p>
                        </div>
                    </div>
                 </div>

             </div>
         </template>
    </div>
BLADE;

$startToken = '<div x-show="activeTab === \'seo\'" style="display: none;" x-transition.opacity.duration.300ms';
$endToken = '</template>' . "\n" . '    </div>';

$posStart = strpos($content, $startToken);
$posEnd = strpos($content, $endToken, $posStart);

if ($posStart !== false && $posEnd !== false) {
    $posEnd += strlen($endToken);
    $newContent = substr($content, 0, $posStart) . $newBlock . substr($content, $posEnd);
    file_put_contents('resources/views/admin/dashboard.blade.php', $newContent);
    echo "Successfully replaced the block!";
} else {
    echo "Could not find tokens.";
}
