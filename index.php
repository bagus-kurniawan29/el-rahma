<?php $pageTitle = 'Beranda - Koperasi Syariah El-Rahma'; include 'includes/header.php'; ?>

<section class="hero-section">
    <div class="container hero-grid">
        <div class="hero-copy">
            <span class="eyebrow">Company Profile Koperasi Syariah</span>
            <h1><?= $site['tagline']; ?></h1>
            <p><?= $site['description']; ?></p>
            <div class="hero-actions">
                <a href="profil.php" class="btn btn-primary">Lihat Profil</a>
                <a href="portofolio.php" class="btn btn-outline">Lihat Portofolio</a>
            </div>
        </div>
        <div class="hero-media">
            <div class="photo-placeholder">
                <img src="assets/img/kantor.png" alt="">
            </div>
        </div>
    </div>
</section>

<section class="quick-section">
    <div class="container">
        <div class="section-heading row-heading">
            <div>
                <span class="eyebrow">Tautan Cepat</span>
                <h2>Akses informasi koperasi</h2>
            </div>
            <p>Bagian ini dibuat ringkas seperti portal informasi koperasi: mudah dibaca dan langsung menuju halaman penting.</p>
        </div>
        <div class="quick-grid">
            <?php foreach ($quickLinks as $item): ?>
                <a class="quick-card" href="<?= $item['url']; ?>">
                    <strong><?= $item['title']; ?></strong>
                    <span><?= $item['desc']; ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="about-strip">
    <div class="container split-grid">
        <div>
            <span class="eyebrow">Tentang Kami</span>
            <h2>Mengelola kebutuhan anggota dengan prinsip syariah dan gotong royong.</h2>
        </div>
        <div>
            <p>Koperasi Syariah El-Rahma merupakan contoh lembaga koperasi yang berfokus pada simpanan anggota, pembiayaan produktif, serta pendampingan UMKM. Website ini disiapkan sebagai contoh company profile yang bisa langsung diedit sesuai data koperasi asli.</p>
            <a href="profil.php" class="text-link">Baca profil lengkap</a>
        </div>
    </div>
</section>

<section class="stats-section">
    <div class="container stats-grid">
        <?php foreach ($stats as $stat): ?>
            <div class="stat-card">
                <strong><?= $stat['number']; ?></strong>
                <span><?= $stat['label']; ?></span>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="section-pad">
    <div class="container">
        <div class="section-heading center-heading">
            <span class="eyebrow">Layanan Utama</span>
            <h2>Program yang dekat dengan kebutuhan anggota</h2>
        </div>
        <div class="service-grid">
            <?php foreach ($services as $service): ?>
                <article class="service-card">
                    <div class="service-icon"><?= $service['icon']; ?></div>
                    <h3><?= $service['title']; ?></h3>
                    <p><?= $service['desc']; ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section-pad bg-soft">
    <div class="container">
        <div class="section-heading row-heading">
            <div>
                <span class="eyebrow">Portofolio</span>
                <h2>Program dan kegiatan koperasi</h2>
            </div>
            <a href="portofolio.php" class="btn btn-outline small-btn">Lihat Semua</a>
        </div>
        <div class="portfolio-grid three-col">
            <?php foreach (array_slice($portfolio, 0, 3) as $item): ?>
                <article class="portfolio-card">
                    <div class="portfolio-image">Foto Kegiatan</div>
                    <div class="portfolio-body">
                        <span class="badge"><?= $item['category']; ?></span>
                        <h3><?= $item['title']; ?></h3>
                        <p><?= $item['desc']; ?></p>
                        <div class="meta-row"><span><?= $item['year']; ?></span><span><?= $item['status']; ?></span></div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section-pad">
    <div class="container">
        <div class="section-heading row-heading">
            <div>
                <span class="eyebrow">Informasi</span>
                <h2>Berita dan kegiatan terbaru</h2>
            </div>
        </div>
        <div class="news-list">
            <?php foreach ($news as $item): ?>
                <article class="news-item">
                    <span><?= $item['date']; ?></span>
                    <h3><?= $item['title']; ?></h3>
                    <p><?= $item['desc']; ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container cta-box">
        <div>
            <span class="eyebrow light">Butuh informasi?</span>
            <h2>Hubungi pengurus koperasi untuk pendaftaran anggota dan konsultasi layanan.</h2>
        </div>
        <a href="kontak.php" class="btn btn-light">Hubungi Kami</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
