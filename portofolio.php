<?php $pageTitle = 'Portofolio - Koperasi Syariah El-Rahma'; include 'includes/header.php'; ?>

<section class="page-hero compact-hero">
    <div class="container">
        <span class="eyebrow">Portofolio Koperasi</span>
        <h1>Dokumentasi program, kegiatan, dan dampak untuk anggota.</h1>
        <p>Portofolio tidak dibuat seperti portofolio desain, tetapi seperti dokumentasi program koperasi: pembiayaan, simpanan, UMKM, edukasi, dan kegiatan sosial.</p>
    </div>
</section>

<section class="section-pad bg-soft">
    <div class="container">
        <div class="portfolio-grid three-col">
            <?php foreach ($portfolio as $item): ?>
                <article class="portfolio-card">
                    <div class="portfolio-image">Placeholder Foto</div>
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
    <div class="container split-grid">
        <div>
            <span class="eyebrow">Catatan Portofolio</span>
            <h2>Gunakan foto asli agar lebih dipercaya.</h2>
        </div>
        <div class="content-block">
            <p>Untuk versi final, bagian placeholder dapat diganti dengan foto RAT, foto pelatihan anggota, foto UMKM binaan, foto kantor, dokumentasi bazar, atau dokumentasi penyerahan program. Hindari memakai gambar yang terlalu generik agar company profile terasa lebih nyata.</p>
            <a href="kontak.php" class="btn btn-primary">Konsultasi Program</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
