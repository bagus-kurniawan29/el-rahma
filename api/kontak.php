<?php $pageTitle = 'Kontak - Koperasi Syariah El-Rahma'; include 'includes/header.php'; ?>

<section class="page-hero compact-hero">
    <div class="container">
        <span class="eyebrow">Kontak</span>
        <h1>Hubungi pengurus koperasi.</h1>
        <p>Gunakan halaman ini untuk pendaftaran anggota, pertanyaan layanan, atau konsultasi program koperasi.</p>
    </div>
</section>

<section class="section-pad">
    <div class="container contact-grid">
        <div class="contact-card">
            <h2>Informasi Kantor</h2>
            <p><strong>Alamat</strong><br><?= $site['address']; ?></p>
            <p><strong>Telepon/WhatsApp</strong><br><?= $site['phone']; ?></p>
            <p><strong>Email</strong><br><?= $site['email']; ?></p>
            <p><strong>Jam Layanan</strong><br><?= $site['office_hours']; ?></p>
            <a href="<?= $site['wa_link']; ?>" class="btn btn-primary">Chat WhatsApp</a>
        </div>
        <form class="contact-form" action="#" method="post">
            <h2>Formulir Pesan</h2>
            <label>Nama Lengkap
                <input type="text" name="nama" placeholder="Nama Anda">
            </label>
            <label>Nomor WhatsApp
                <input type="text" name="whatsapp" placeholder="08xxxxxxxxxx">
            </label>
            <label>Keperluan
                <select name="keperluan">
                    <option>Pendaftaran Anggota</option>
                    <option>Informasi Simpanan</option>
                    <option>Informasi Pembiayaan</option>
                    <option>Kemitraan UMKM</option>
                </select>
            </label>
            <label>Pesan
                <textarea name="pesan" rows="5" placeholder="Tulis pesan Anda"></textarea>
            </label>
            <button class="btn btn-primary" type="submit">Kirim Pesan</button>
            <small>Form ini masih contoh tampilan. Untuk mengirim pesan sungguhan, hubungkan ke database atau WhatsApp API.</small>
        </form>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
