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
                 </div>
                 <div class="overflow-x-auto">
                     <table class="w-full text-left text-sm">
                         <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                             <tr>
                                 <th class="px-6 py-3 font-semibold">Waktu</th>
                                 <th class="px-6 py-3 font-semibold">Halaman</th>
                                 <th class="px-6 py-3 font-semibold">Target Keyword</th>
                                 <th class="px-6 py-3 font-semibold">Skor</th>
                                 <th class="px-6 py-3 font-semibold">Status</th>
                                 <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                             </tr>
                         </thead>
                         <tbody class="divide-y divide-slate-100">
                             <template x-for="item in history" :key="item.id">
                                 <tr class="hover:bg-slate-50 transition-colors">
                                     <td class="px-6 py-4 text-slate-600" x-text="new Date(item.created_at).toLocaleString('id-ID')"></td>
                                     <td class="px-6 py-4 font-semibold text-indigo-600" x-text="item.page_path"></td>
                                     <td class="px-6 py-4 text-slate-700" x-text="item.target_keyword"></td>
                                     <td class="px-6 py-4">
                                         <span class="px-2 py-1 rounded text-xs font-bold text-white" :class="item.overall_score >= 80 ? 'bg-teal-500' : (item.overall_score >= 50 ? 'bg-amber-500' : 'bg-red-500')" x-text="item.overall_score"></span>
                                     </td>
                                     <td class="px-6 py-4">
                                         <span x-show="item.status === 'applied'" class="text-teal-600 font-bold text-xs bg-teal-50 px-2 py-1 rounded">Applied</span>
                                         <span x-show="item.status === 'pending'" class="text-amber-600 font-bold text-xs bg-amber-50 px-2 py-1 rounded">Pending</span>
                                     </td>
                                     <td class="px-6 py-4 text-right">
                                         <button @click="recommendation = item" class="text-indigo-600 hover:text-indigo-800 font-semibold text-sm">Lihat Detail &rarr;</button>
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
             <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-8">
                 <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
                     <div>
                         <button @click="recommendation = null" class="text-sm font-semibold text-slate-500 hover:text-slate-800 mb-2 flex items-center gap-1">&larr; Kembali ke List</button>
                         <h3 class="text-xl font-bold text-slate-900">Detail Analisa: <span class="text-indigo-600" x-text="recommendation.page_path"></span></h3>
                     </div>
                     <div class="flex items-center gap-3">
                        <span class="text-sm font-semibold text-slate-500">Skor SEO:</span>
                        <div class="px-4 py-1.5 rounded-full text-white font-black text-lg" :class="recommendation.overall_score >= 80 ? 'bg-teal-500' : (recommendation.overall_score >= 50 ? 'bg-amber-500' : 'bg-red-500')" x-text="recommendation.overall_score + '/100'"></div>
                     </div>
                 </div>
                 
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                     <div class="bg-slate-50 p-5 rounded-xl border border-slate-200">
                         <h4 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                             <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg> Meta Title Dinamis
                         </h4>
                         <p class="text-slate-600 font-medium" x-text="recommendation.recommendations.meta_title"></p>
                     </div>
                     <div class="bg-slate-50 p-5 rounded-xl border border-slate-200">
                         <h4 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                             <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg> Meta Description
                         </h4>
                         <p class="text-slate-600 text-sm leading-relaxed" x-text="recommendation.recommendations.meta_description"></p>
                     </div>
                     <div class="bg-slate-50 p-5 rounded-xl border border-slate-200 md:col-span-2">
                         <h4 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                             <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg> Rekomendasi Heading (H1)
                         </h4>
                         <p class="text-slate-600 font-semibold text-lg" x-text="recommendation.recommendations.h1_heading"></p>
                     </div>
                 </div>

                 <div class="bg-slate-50 p-5 rounded-xl border border-slate-200 mb-6">
                     <h4 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                         <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Schema FAQ yang Disarankan (JSON-LD)
                     </h4>
                     <div class="space-y-3">
                         <template x-for="(faq, index) in recommendation.recommendations.faq_schema" :key="index">
                             <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm">
                                 <p class="font-bold text-slate-800 text-sm mb-1" x-text="'Q: ' + faq.question"></p>
                                 <p class="text-slate-600 text-sm" x-text="'A: ' + faq.answer"></p>
                             </div>
                         </template>
                     </div>
                 </div>

                 <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                     <div class="bg-indigo-50/50 p-5 rounded-xl border border-indigo-100">
                         <h4 class="font-bold text-indigo-800 text-sm mb-2 uppercase">Backlink & Internal Link</h4>
                         <p class="text-indigo-700 text-xs mb-2"><strong class="block">Backlink:</strong> <span x-text="recommendation.recommendations.backlink_strategy"></span></p>
                         <p class="text-indigo-700 text-xs"><strong class="block">Internal:</strong> <span x-text="recommendation.recommendations.internal_link_strategy"></span></p>
                     </div>
                     <div class="bg-teal-50/50 p-5 rounded-xl border border-teal-100">
                         <h4 class="font-bold text-teal-800 text-sm mb-2 uppercase">Image Optimization</h4>
                         <p class="text-teal-700 text-xs" x-text="recommendation.recommendations.image_optimization"></p>
                     </div>
                     <div class="bg-amber-50/50 p-5 rounded-xl border border-amber-100">
                         <h4 class="font-bold text-amber-800 text-sm mb-2 uppercase">Page Speed</h4>
                         <p class="text-amber-700 text-xs" x-text="recommendation.recommendations.page_speed"></p>
                     </div>
                 </div>

                 <div class="border-t border-slate-100 pt-6 flex justify-end">
                     <button x-show="recommendation.status !== 'applied'" @click="applyRec(recommendation.id)" class="px-8 py-3 bg-teal-500 hover:bg-teal-600 text-white font-bold rounded-xl shadow-lg shadow-teal-200 transition-all transform hover:-translate-y-1 flex items-center gap-2">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Apply Rekomendasi Otomatis
                     </button>
                     <div x-show="recommendation.status === 'applied'" style="display: none;" class="px-8 py-3 bg-slate-100 text-teal-600 font-bold rounded-xl flex items-center gap-2">
                         <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Sudah Diterapkan
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
