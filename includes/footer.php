    </main>

    <footer class="bg-brand-navy text-white">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:px-6 md:grid-cols-[1.4fr_.7fr_.9fr] lg:px-8">
            <div>
                <div class="mb-5 flex items-center gap-3">
                    <img class="h-14 w-14 rounded-xl border border-white/20 object-cover" src="assets/img/logo-elrahma.jpeg" alt="Logo <?= $site['name']; ?>">
                    <div>
                        <strong class="block leading-tight"><?= $site['name']; ?></strong>
                        <small class="text-white/60"><?= $site['tagline']; ?></small>
                    </div>
                </div>
                <p class="max-w-xl text-sm leading-7 text-white/65"><?= $site['description']; ?></p>
            </div>
            <div>
                <h4 class="mb-4 font-bold">Menu Utama</h4>
                <div class="space-y-2 text-sm text-white/65">
                    <a class="block hover:text-white" href="profil.php">Profil Koperasi</a>
                    <a class="block hover:text-white" href="layanan.php">Layanan</a>
                    <a class="block hover:text-white" href="portofolio.php">Program</a>
                    <a class="block hover:text-white" href="kontak.php">Kontak</a>
                </div>
            </div>
            <div>
                <h4 class="mb-4 font-bold">Hubungi Kami</h4>
                <div class="space-y-3 text-sm leading-6 text-white/65">
                    <p><?= $site['address']; ?></p>
                    <p><?= $site['phone']; ?><br><?= $site['email']; ?></p>
                    <p><?= $site['office_hours']; ?></p>
                </div>
            </div>
        </div>
        <div class="border-t border-white/10 py-4">
            <div class="mx-auto max-w-7xl px-4 text-sm text-white/55 sm:px-6 lg:px-8">&copy; <?= $site['year']; ?> <?= $site['name']; ?>. Hak cipta dilindungi.</div>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html>
