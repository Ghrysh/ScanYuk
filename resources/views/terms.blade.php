@extends('layouts.app') @section('content')
<main class="bg-slate-50 min-h-screen font-['Inter'] pb-24">
    
    <div class="pt-24 pb-12 px-6 text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4 tracking-tight">
            <span class="text-slate-900">Syarat & </span>
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-teal-500 via-indigo-500 to-purple-600">Ketentuan</span>
        </h1>
        <p class="text-slate-500 text-base md:text-lg max-w-2xl mx-auto font-medium">
            Terakhir diperbarui: 26 Februari 2026
        </p>
    </div>

    <div class="max-w-[900px] mx-auto px-4 sm:px-6 space-y-6">

        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 shadow-sm">
            <h2 class="text-[17px] md:text-[19px] font-bold text-slate-900 mb-4 tracking-tight">1. Pendahuluan</h2>
            <p class="text-sm md:text-[15px] text-slate-500 leading-relaxed font-medium">
                Selamat datang di ScanYuk, layanan yang dikelola oleh PT Berkah Teknologi Terkini. Dengan mengakses dan menggunakan platform ScanYuk, Anda menyetujui untuk terikat oleh Syarat & Ketentuan ini. Jika Anda tidak menyetujui ketentuan ini, mohon untuk tidak menggunakan layanan kami.
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 shadow-sm">
            <h2 class="text-[17px] md:text-[19px] font-bold text-slate-900 mb-4 tracking-tight">2. Definisi</h2>
            <ul class="list-disc pl-5 text-sm md:text-[15px] text-slate-500 leading-relaxed font-medium space-y-3 marker:text-slate-400">
                <li><strong class="text-slate-700">"Platform"</strong> merujuk pada situs web dan layanan ScanYuk.</li>
                <li><strong class="text-slate-700">"Pengguna"</strong> merujuk pada setiap individu atau entitas yang mendaftar dan menggunakan Platform.</li>
                <li><strong class="text-slate-700">"Konten AR"</strong> merujuk pada gambar, narasi suara, dan materi lain yang diunggah oleh Pengguna.</li>
                <li><strong class="text-slate-700">"Kuota"</strong> merujuk pada batas penggunaan yang diberikan sesuai paket yang dipilih.</li>
            </ul>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 shadow-sm">
            <h2 class="text-[17px] md:text-[19px] font-bold text-slate-900 mb-4 tracking-tight">3. Pendaftaran Akun</h2>
            <ul class="list-disc pl-5 text-sm md:text-[15px] text-slate-500 leading-relaxed font-medium space-y-3 marker:text-slate-400">
                <li>Pengguna wajib memberikan informasi yang akurat dan lengkap saat mendaftar.</li>
                <li>Setiap akun bersifat personal dan tidak boleh dipindahtangankan.</li>
                <li>Pengguna bertanggung jawab penuh atas keamanan akun dan kata sandi mereka.</li>
                <li>Kami berhak menangguhkan atau menghapus akun yang melanggar ketentuan ini.</li>
            </ul>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 shadow-sm">
            <h2 class="text-[17px] md:text-[19px] font-bold text-slate-900 mb-4 tracking-tight">4. Penggunaan Layanan</h2>
            <p class="text-sm md:text-[15px] text-slate-500 leading-relaxed font-medium mb-3">
                Pengguna dilarang menggunakan Platform untuk:
            </p>
            <ul class="list-disc pl-5 text-sm md:text-[15px] text-slate-500 leading-relaxed font-medium space-y-3 marker:text-slate-400">
                <li>Mengunggah konten yang melanggar hukum, mengandung unsur SARA, pornografi, atau kekerasan.</li>
                <li>Menyebarkan malware, virus, atau kode berbahaya melalui QR Code.</li>
                <li>Melakukan aktivitas yang dapat mengganggu kinerja atau keamanan Platform.</li>
                <li>Menjual kembali akses atau kuota akun tanpa izin tertulis dari kami.</li>
            </ul>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 shadow-sm">
            <h2 class="text-[17px] md:text-[19px] font-bold text-slate-900 mb-4 tracking-tight">5. Kuota dan Pembayaran</h2>
            <ul class="list-disc pl-5 text-sm md:text-[15px] text-slate-500 leading-relaxed font-medium space-y-3 marker:text-slate-400">
                <li>Kuota yang dibeli tidak memiliki masa kedaluwarsa selama akun masih aktif.</li>
                <li>Pembayaran bersifat final setelah kuota diaktifkan, kecuali diatur lain dalam Kebijakan Pengembalian Dana.</li>
                <li>Harga paket dapat berubah sewaktu-waktu dengan pemberitahuan sebelumnya.</li>
                <li>Total scan dihitung secara kumulatif untuk semua QR Code dalam satu akun.</li>
            </ul>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 shadow-sm">
            <h2 class="text-[17px] md:text-[19px] font-bold text-slate-900 mb-4 tracking-tight">6. Hak Kekayaan Intelektual</h2>
            <ul class="list-disc pl-5 text-sm md:text-[15px] text-slate-500 leading-relaxed font-medium space-y-3 marker:text-slate-400">
                <li>Pengguna memiliki hak penuh atas Konten AR yang mereka buat dan unggah.</li>
                <li>Platform ScanYuk, termasuk desain, kode, dan merek dagang, adalah milik PT Berkah Teknologi Terkini.</li>
                <li>Pengguna memberikan lisensi terbatas kepada kami untuk menampilkan Konten AR melalui fitur pemindaian.</li>
            </ul>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 shadow-sm">
            <h2 class="text-[17px] md:text-[19px] font-bold text-slate-900 mb-4 tracking-tight">7. Batasan Tanggung Jawab</h2>
            <p class="text-sm md:text-[15px] text-slate-500 leading-relaxed font-medium">
                Kami berupaya menyediakan layanan dengan ketersediaan 99,9%. Namun, kami tidak bertanggung jawab atas kerugian yang timbul akibat gangguan layanan yang disebabkan oleh force majeure, pemeliharaan terjadwal, atau faktor di luar kendali kami.
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 shadow-sm">
            <h2 class="text-[17px] md:text-[19px] font-bold text-slate-900 mb-4 tracking-tight">8. Perubahan Ketentuan</h2>
            <p class="text-sm md:text-[15px] text-slate-500 leading-relaxed font-medium">
                Kami berhak mengubah Syarat & Ketentuan ini sewaktu-waktu. Perubahan akan diberitahukan melalui email atau pemberitahuan di Platform. Penggunaan berkelanjutan setelah perubahan dianggap sebagai persetujuan.
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 shadow-sm">
            <h2 class="text-[17px] md:text-[19px] font-bold text-slate-900 mb-4 tracking-tight">9. Hukum yang Berlaku</h2>
            <p class="text-sm md:text-[15px] text-slate-500 leading-relaxed font-medium">
                Syarat & Ketentuan ini tunduk pada hukum Republik Indonesia. Setiap sengketa akan diselesaikan melalui musyawarah, dan apabila tidak tercapai kesepakatan, akan diselesaikan di Pengadilan Negeri Jakarta Pusat.
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 md:p-8 shadow-sm">
            <h2 class="text-[17px] md:text-[19px] font-bold text-slate-900 mb-4 tracking-tight">10. Kontak</h2>
            <p class="text-sm md:text-[15px] text-slate-500 leading-relaxed font-medium">
                PT Berkah Teknologi Terkini<br>
                Gedung Jaya Lomba 5 unit A.6<br>
                Jl. M H Thamrin No.12, RT.002/RW.001<br>
                Kb. Sirih, Kec. Menteng, Jakarta Pusat 10340
            </p>
            <div class="mt-6 space-y-1 text-sm md:text-[15px] font-medium text-slate-500">
                <p>Email: <a href="mailto:info@scanyuk.com" class="text-teal-600 font-bold hover:underline transition-all">info@scanyuk.com</a></p>
                <p>Telepon: <span class="text-teal-600 font-bold">(+62) 815-2022-225</span></p>
            </div>
        </div>

    </div>

</main>
@endsection