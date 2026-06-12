<?php $pageTitle = 'Beranda - Koperasi Syariah El Rahma Lombok Rinjani'; include 'includes/header.php'; ?>

<section class="relative overflow-hidden bg-gradient-to-br from-slate-50 via-white to-blue-50 py-16 sm:py-24">
    <div class="absolute -right-32 top-16 h-80 w-80 rotate-12 rounded-[5rem] bg-brand-blue/5"></div>
    <div class="relative mx-auto grid max-w-7xl items-center gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
        <div>
            <span class="mb-4 inline-flex items-center gap-3 text-xs font-extrabold uppercase tracking-[.18em] text-brand-red"><span class="h-0.5 w-8 bg-brand-red"></span>Koperasi Syariah Lombok</span>
            <h1 class="text-4xl font-black leading-tight tracking-tight text-brand-navy sm:text-5xl lg:text-6xl"><?= $site['tagline']; ?></h1>
            <p class="mt-6 max-w-xl text-lg leading-8 text-slate-600"><?= $site['description']; ?></p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="register.php" class="rounded-full bg-brand-blue px-6 py-3 font-bold text-white shadow-lg shadow-blue-900/20 transition hover:bg-brand-navy">Daftar dan Ajukan Dana</a>
                <a href="login.php" class="rounded-full border border-slate-200 bg-white px-6 py-3 font-bold text-brand-navy transition hover:border-brand-blue">Masuk ke Dashboard</a>
            </div>
        </div>
        <div class="relative">
            <div class="overflow-hidden rounded-[2rem] bg-brand-navy p-3 shadow-soft">
                <img class="h-[480px] w-full rounded-[1.5rem] object-cover" src="assets/img/kantor.png" alt="Kantor Koperasi Syariah El Rahma Lombok Rinjani">
            </div>
            <div class="absolute -bottom-5 -left-3 max-w-xs rounded-2xl bg-white p-5 shadow-soft sm:-left-8">
                <strong class="block text-brand-navy">Amanah, Adil, Transparan</strong>
                <span class="mt-1 block text-sm text-slate-500">Layanan keuangan syariah yang tumbuh bersama anggota.</span>
            </div>
        </div>
    </div>
</section>

<section class="border-b border-slate-200 py-14">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-8 max-w-2xl"><span class="font-bold text-brand-red">Akses Cepat</span><h2 class="mt-2 text-3xl font-black text-brand-navy">Informasi untuk anggota</h2></div>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <?php foreach ($quickLinks as $item): ?>
                <a class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:border-brand-blue/30 hover:shadow-soft" href="<?= $item['url']; ?>">
                    <strong class="block text-brand-navy group-hover:text-brand-blue"><?= $item['title']; ?></strong>
                    <span class="mt-2 block text-sm leading-6 text-slate-500"><?= $item['desc']; ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="py-20">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
        <div><span class="font-bold text-brand-red">Tentang Kami</span><h2 class="mt-3 text-3xl font-black leading-tight text-brand-navy sm:text-4xl">Menguatkan ekonomi anggota dengan prinsip syariah dan kebersamaan.</h2></div>
        <div><p class="leading-8 text-slate-600"><?= $site['name']; ?> melayani simpanan anggota, pembiayaan produktif, pendampingan UMKM, dan kegiatan sosial. Setiap layanan diarahkan agar mudah dipahami, bertanggung jawab, dan memberi manfaat nyata.</p><a href="profil.php" class="mt-5 inline-block border-b-2 border-brand-red pb-1 font-bold text-brand-blue">Baca profil lengkap</a></div>
    </div>
</section>

<section class="bg-brand-navy py-8">
    <div class="mx-auto grid max-w-7xl gap-4 px-4 sm:grid-cols-2 sm:px-6 lg:grid-cols-4 lg:px-8">
        <?php foreach ($stats as $stat): ?><div class="rounded-2xl border border-white/10 bg-white/5 p-6 text-white"><strong class="block text-3xl"><?= $stat['number']; ?></strong><span class="text-sm text-white/65"><?= $stat['label']; ?></span></div><?php endforeach; ?>
    </div>
</section>

<section class="py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto mb-10 max-w-2xl text-center"><span class="font-bold text-brand-red">Layanan Utama</span><h2 class="mt-3 text-3xl font-black text-brand-navy sm:text-4xl">Solusi dekat dengan kebutuhan anggota</h2></div>
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <?php foreach ($services as $service): ?><article class="rounded-2xl border border-slate-200 p-6 shadow-sm"><span class="mb-5 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 font-black text-brand-blue"><?= $service['icon']; ?></span><h3 class="text-xl font-bold text-brand-navy"><?= $service['title']; ?></h3><p class="mt-3 leading-7 text-slate-500"><?= $service['desc']; ?></p></article><?php endforeach; ?>
        </div>
    </div>
</section>

<section class="bg-slate-50 py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-10 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between"><div><span class="font-bold text-brand-red">Program Koperasi</span><h2 class="mt-2 text-3xl font-black text-brand-navy">Kegiatan yang memberi dampak</h2></div><a href="portofolio.php" class="font-bold text-brand-blue">Lihat semua program</a></div>
        <div class="grid gap-5 md:grid-cols-3">
            <?php foreach (array_slice($portfolio, 0, 3) as $item): ?><article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"><div class="flex h-36 items-center justify-center bg-gradient-to-br from-blue-50 to-red-50 text-2xl font-black text-brand-blue/20">ELR</div><div class="p-6"><span class="rounded-full bg-red-50 px-3 py-1 text-xs font-bold text-brand-red"><?= $item['category']; ?></span><h3 class="mt-4 text-xl font-bold text-brand-navy"><?= $item['title']; ?></h3><p class="mt-3 leading-7 text-slate-500"><?= $item['desc']; ?></p></div></article><?php endforeach; ?>
        </div>
    </div>
</section>

<section class="py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-10"><span class="font-bold text-brand-red">Informasi</span><h2 class="mt-2 text-3xl font-black text-brand-navy">Kabar dan kegiatan terbaru</h2></div>
        <div class="grid gap-5 md:grid-cols-3"><?php foreach ($news as $item): ?><article class="rounded-2xl border border-slate-200 p-6"><span class="text-sm font-bold text-brand-red"><?= $item['date']; ?></span><h3 class="mt-3 text-xl font-bold text-brand-navy"><?= $item['title']; ?></h3><p class="mt-3 leading-7 text-slate-500"><?= $item['desc']; ?></p></article><?php endforeach; ?></div>
    </div>
</section>

<section class="pb-20">
    <div class="mx-auto flex max-w-7xl flex-col gap-8 rounded-[2rem] bg-gradient-to-br from-brand-blue to-brand-navy px-6 py-10 text-white sm:px-10 lg:flex-row lg:items-center lg:justify-between lg:px-12">
        <div><span class="text-sm font-bold uppercase tracking-widest text-white/70">Mari bergabung</span><h2 class="mt-3 max-w-3xl text-3xl font-black">Dapatkan informasi keanggotaan dan konsultasikan kebutuhan Anda.</h2></div><a href="kontak.php" class="shrink-0 rounded-full bg-white px-6 py-3 font-bold text-brand-navy">Hubungi Kami</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
