<?php $pageTitle = 'Layanan - Koperasi Syariah El-Rahma'; include 'includes/header.php'; ?>

<section class="page-hero compact-hero">
    <div class="container">
        <span class="eyebrow">Layanan</span>
        <h1>Layanan simpanan, pembiayaan, dan pemberdayaan anggota.</h1>
        <p>Susunan layanan dibuat sederhana agar mudah dipahami calon anggota dan bisa langsung disesuaikan dengan produk koperasi.</p>
    </div>
</section>

<section class="section-pad">
    <div class="container service-grid two-col">
        <?php foreach ($services as $service): ?>
            <article class="service-card large-card">
                <div class="service-icon"><?= $service['icon']; ?></div>
                <h3><?= $service['title']; ?></h3>
                <p><?= $service['desc']; ?></p>
                <ul class="mini-list">
                    <li>Proses administrasi jelas</li>
                    <li>Pendampingan oleh pengurus</li>
                    <li>Disesuaikan dengan kebutuhan anggota</li>
                </ul>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="section-pad bg-soft">
    <div class="container split-grid">
        <div>
            <span class="eyebrow">Alur Layanan</span>
            <h2>Calon anggota bisa memahami proses sejak awal.</h2>
        </div>
        <div class="timeline">
            <div><strong>01. Konsultasi</strong><span>Anggota menyampaikan kebutuhan simpanan atau pembiayaan.</span></div>
            <div><strong>02. Pemeriksaan Data</strong><span>Pengurus memeriksa kelengkapan administrasi dan tujuan penggunaan dana.</span></div>
            <div><strong>03. Akad dan Persetujuan</strong><span>Akad disepakati secara jelas sebelum layanan berjalan.</span></div>
            <div><strong>04. Monitoring</strong><span>Koperasi melakukan pendampingan dan evaluasi berkala.</span></div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
