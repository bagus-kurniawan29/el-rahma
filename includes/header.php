<?php
require_once __DIR__ . '/../data/data.php';
$currentPage = basename($_SERVER['PHP_SELF']);
function isActive($page, $currentPage) {
    return $page === $currentPage ? 'active' : '';
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $pageTitle ?? $site['name']; ?></title>
    <meta name="description" content="<?= $site['description']; ?>">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="topbar">
        <div class="container topbar-inner">
            <span><?= $site['office_hours']; ?></span>
            <span><?= $site['phone']; ?> · <?= $site['email']; ?></span>
        </div>
    </div>

    <header class="site-header">
        <div class="container nav-wrap">
            <a href="index.php" class="brand" aria-label="Beranda <?= $site['name']; ?>">
                <img src="assets/img/logo-elrahma.jpeg" alt="Logo <?= $site['name']; ?>">
                <div>
                    <strong><?= $site['short_name']; ?></strong>
                    <small>Koperasi Syariah</small>
                </div>
            </a>

            <button class="menu-toggle" type="button" aria-label="Buka menu">Menu</button>

            <nav class="main-nav">
                <a class="<?= isActive('index.php', $currentPage); ?>" href="index.php">Beranda</a>
                <a class="<?= isActive('profil.php', $currentPage); ?>" href="profil.php">Company Profile</a>
                <a class="<?= isActive('layanan.php', $currentPage); ?>" href="layanan.php">Layanan</a>
                <a class="<?= isActive('portofolio.php', $currentPage); ?>" href="portofolio.php">Portofolio</a>
                <a class="<?= isActive('kontak.php', $currentPage); ?>" href="kontak.php">Kontak</a>
            </nav>
        </div>
    </header>

    <main>
