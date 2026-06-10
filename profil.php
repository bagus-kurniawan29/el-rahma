<?php $pageTitle = 'Company Profile - Koperasi Syariah El-Rahma'; include 'includes/header.php'; ?>

<section class="page-hero compact-hero">
    <div class="container">
        <span class="eyebrow">Company Profile</span>
        <h1>Profil Perusahaan Koperasi Syariah</h1>
        <p>Halaman ini berisi contoh profil koperasi, mulai dari pengenalan lembaga, visi misi, nilai, sampai struktur pengurus.</p>
    </div>
</section>

<section class="section-pad">
    <div class="container split-grid align-start">
        <div class="sticky-note">
            <img src="assets/img/logo-elrahma.jpeg" alt="Logo <?= $site['name']; ?>">
            <h3><?= $site['name']; ?></h3>
            <p><?= $site['tagline']; ?></p>
        </div>
        <div class="content-block">
            <span class="eyebrow">Tentang Koperasi</span>
            <h2>Wadah ekonomi anggota yang dikelola secara amanah.</h2>
            <p><?= $site['name']; ?> adalah contoh koperasi syariah yang bergerak dalam pengelolaan simpanan anggota, pembiayaan syariah, dan pemberdayaan usaha kecil. Koperasi dibangun untuk memperkuat kebersamaan anggota, membantu kebutuhan permodalan, serta mendorong kegiatan ekonomi yang lebih tertib dan bermanfaat.</p>
            <p>Dalam praktiknya, koperasi dapat menyesuaikan akad dan mekanisme layanan berdasarkan ketentuan syariah, keputusan rapat anggota, dan aturan koperasi yang berlaku. Bagian ini bisa Anda ubah dengan sejarah, nomor badan hukum, alamat kantor, dan data pengurus koperasi yang sebenarnya.</p>

            <div class="profile-table">
                <div><strong>Nama Lembaga</strong><span><?= $site['name']; ?></span></div>
                <div><strong>Bidang</strong><span>Koperasi Simpan Pinjam dan Pembiayaan Syariah</span></div>
                <div><strong>Fokus Program</strong><span>Simpanan anggota, pembiayaan, UMKM, edukasi anggota</span></div>
                <div><strong>Wilayah Layanan</strong><span>Kota/Kabupaten sesuai domisili koperasi</span></div>
            </div>
        </div>
    </div>
</section>

<section class="section-pad bg-soft">
    <div class="container vision-grid">
        <div class="vision-card dark-card">
            <span class="eyebrow light">Visi</span>
            <h2>Menjadi koperasi syariah yang amanah, mandiri, dan bermanfaat bagi kesejahteraan anggota.</h2>
        </div>
        <div class="mission-card">
            <span class="eyebrow">Misi</span>
            <ul class="check-list">
                <li>Meningkatkan budaya menabung dan pengelolaan keuangan anggota.</li>
                <li>Menyediakan pembiayaan syariah yang jelas, adil, dan mudah dipahami.</li>
                <li>Mendampingi UMKM anggota agar lebih tertib dalam usaha dan administrasi.</li>
                <li>Menjalankan koperasi secara transparan melalui laporan dan rapat anggota.</li>
            </ul>
        </div>
    </div>
</section>

<section class="section-pad">
    <div class="container">
        <div class="section-heading center-heading">
            <span class="eyebrow">Nilai Koperasi</span>
            <h2>Prinsip kerja yang menjadi dasar pelayanan</h2>
        </div>
        <div class="value-grid">
            <?php foreach ($values as $value): ?>
                <div class="value-card"><?= $value; ?></div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section-pad bg-soft">
    <div class="container">
        <div class="section-heading row-heading">
            <div>
                <span class="eyebrow">Struktur</span>
                <h2>Contoh struktur pengurus</h2>
            </div>
            <p>Nama dan jabatan di bawah ini masih placeholder. Silakan ganti sesuai data resmi koperasi.</p>
        </div>
        <div class="team-grid">
            <div class="team-card"><strong>Ketua</strong><span>Nama Pengurus</span></div>
            <div class="team-card"><strong>Sekretaris</strong><span>Nama Pengurus</span></div>
            <div class="team-card"><strong>Bendahara</strong><span>Nama Pengurus</span></div>
            <div class="team-card"><strong>Dewan Pengawas Syariah</strong><span>Nama Pengawas</span></div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
