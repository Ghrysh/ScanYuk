@extends('layouts.app') @section('content')
<main class="bg-slate-50 min-h-screen font-['Inter'] pb-24">
    
    <div class="pt-24 pb-12 px-6 text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4 tracking-tight">
            <span class="text-slate-900">Kebijakan </span>
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-teal-500 via-indigo-500 to-purple-600">Pengembalian Dana</span>
        </h1>
        <p class="text-slate-500 text-base md:text-lg max-w-2xl mx-auto font-medium">
            Terakhir diperbarui: 26 Februari 2026
        </p>
    </div>

    <div class="max-w-[900px] mx-auto px-4 sm:px-6 space-y-6">

        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 shadow-sm">
            <h2 class="text-[17px] md:text-[19px] font-bold text-slate-900 mb-4 tracking-tight">1. Ketentuan Umum</h2>
            <p class="text-sm md:text-[15px] text-slate-500 leading-relaxed font-medium">
                PT Berkah Teknologi Terkini ("Kami") berkomitmen untuk memberikan layanan terbaik kepada seluruh pengguna ScanYuk. Kebijakan pengembalian dana ini mengatur prosedur dan ketentuan pengembalian dana atas pembelian paket layanan di platform ScanYuk.
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 shadow-sm">
            <h2 class="text-[17px] md:text-[19px] font-bold text-slate-900 mb-4 tracking-tight">2. Hak Pengembalian Dana</h2>
            <p class="text-sm md:text-[15px] text-slate-500 leading-relaxed font-medium mb-4">
                Pengguna berhak mengajukan pengembalian dana dalam kondisi berikut:
            </p>
            <ul class="list-disc pl-5 text-sm md:text-[15px] text-slate-500 leading-relaxed font-medium space-y-3 marker:text-slate-400">
                <li>Pembayaran ganda (double payment) untuk paket yang sama.</li>
                <li>Layanan tidak dapat digunakan karena gangguan sistem yang berkepanjangan (lebih dari 72 jam) yang disebabkan oleh pihak kami.</li>
                <li>Kesalahan teknis pada proses pembayaran yang menyebabkan dana terdebit tanpa aktivasi paket.</li>
            </ul>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 shadow-sm">
            <h2 class="text-[17px] md:text-[19px] font-bold text-slate-900 mb-4 tracking-tight">3. Pengecualian Pengembalian Dana</h2>
            <p class="text-sm md:text-[15px] text-slate-500 leading-relaxed font-medium mb-4">
                Pengembalian dana <strong class="text-slate-800">tidak berlaku</strong> dalam kondisi berikut:
            </p>
            <ul class="list-disc pl-5 text-sm md:text-[15px] text-slate-500 leading-relaxed font-medium space-y-3 marker:text-slate-400">
                <li>Kuota paket (gambar, suara, atau scan) sudah digunakan sebagian atau seluruhnya.</li>
                <li>Pengguna berubah pikiran setelah melakukan pembelian dan kuota sudah diaktifkan.</li>
                <li>Akun pengguna disuspend karena pelanggaran Syarat & Ketentuan.</li>
                <li>Permintaan pengembalian diajukan lebih dari 7 (tujuh) hari kalender setelah tanggal pembelian.</li>
            </ul>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 shadow-sm">
            <h2 class="text-[17px] md:text-[19px] font-bold text-slate-900 mb-4 tracking-tight">4. Prosedur Pengajuan</h2>
            <div class="space-y-3 text-sm md:text-[15px] text-slate-500 leading-relaxed font-medium">
                <p>1. Kirim email ke <a href="mailto:info@scanyuk.com" class="text-teal-600 font-bold hover:underline transition-all">info@scanyuk.com</a> dengan subjek "Pengembalian Dana - [ID Transaksi]".</p>
                <p>2. Sertakan bukti pembayaran dan alasan pengembalian dana.</p>
                <p>3. Tim kami akan meninjau permintaan dalam waktu 3-5 hari kerja.</p>
                <p>4. Jika disetujui, dana akan dikembalikan dalam waktu 7-14 hari kerja melalui metode pembayaran yang sama.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 shadow-sm">
            <h2 class="text-[17px] md:text-[19px] font-bold text-slate-900 mb-4 tracking-tight">5. Kontak</h2>
            <p class="text-sm md:text-[15px] text-slate-500 leading-relaxed font-medium">
                Untuk pertanyaan terkait kebijakan pengembalian dana, silakan hubungi kami melalui email di <a href="mailto:info@scanyuk.com" class="text-teal-600 font-bold hover:underline transition-all">info@scanyuk.com</a> atau WhatsApp di <span class="text-teal-600 font-bold">(+62) 815-2022-225</span>.
            </p>
        </div>

    </div>

</main>
@endsection