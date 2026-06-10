    </main>

    <footer class="site-footer">
        <div class="container footer-grid">
            <div>
                <div class="footer-brand">
                    <img src="assets/img/logo-elrahma.jpeg" alt="Logo <?= $site['name']; ?>">
                    <div>
                        <strong><?= $site['name']; ?></strong>
                        <small><?= $site['tagline']; ?></small>
                    </div>
                </div>
                <p class="footer-text">Contoh website company profile dan portofolio koperasi syariah berbasis PHP native. Silakan sesuaikan data resmi koperasi pada file <code>data/data.php</code>.</p>
            </div>
            <div>
                <h4>Menu</h4>
                <a href="profil.php">Company Profile</a>
                <a href="layanan.php">Layanan</a>
                <a href="portofolio.php">Portofolio</a>
                <a href="kontak.php">Kontak</a>
            </div>
            <div>
                <h4>Kontak</h4>
                <p><?= $site['address']; ?></p>
                <p><?= $site['phone']; ?><br><?= $site['email']; ?></p>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">© <?= $site['year']; ?> <?= $site['name']; ?>. All rights reserved.</div>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html>
