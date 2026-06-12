<?php
require_once __DIR__ . '/app.php';
require_once __DIR__ . '/../data/data.php';
$currentPage = basename($_SERVER['PHP_SELF']);
function isActive($page, $currentPage) {
    return $page === $currentPage
        ? 'bg-blue-50 text-brand-navy'
        : 'text-slate-600 hover:bg-slate-50 hover:text-brand-navy';
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $pageTitle ?? $site['name']; ?></title>
    <meta name="description" content="<?= $site['description']; ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { red: '#ed1c24', blue: '#2e318b', navy: '#191c5f' }
                    },
                    boxShadow: { soft: '0 20px 45px rgba(21, 24, 68, .10)' }
                }
            }
        }
    </script>
</head>
<body class="bg-white font-sans text-slate-800 antialiased">
    <div class="bg-brand-navy text-xs text-white/80">
        <div class="mx-auto flex max-w-7xl flex-col gap-1 px-4 py-2 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <span><?= $site['office_hours']; ?></span>
            <span><?= $site['phone']; ?> &middot; <?= $site['email']; ?></span>
        </div>
    </div>

    <header class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur-xl">
        <div class="relative mx-auto flex min-h-20 max-w-7xl items-center justify-between gap-6 px-4 sm:px-6 lg:px-8">
            <a href="index.php" class="flex min-w-0 items-center gap-3" aria-label="Beranda <?= $site['name']; ?>">
                <img class="h-12 w-12 shrink-0 rounded-xl border border-slate-200 object-cover" src="assets/img/logo-elrahma.jpeg" alt="Logo <?= $site['name']; ?>">
                <div>
                    <strong class="block text-sm leading-tight text-brand-navy sm:text-base"><?= $site['short_name']; ?></strong>
                    <small class="text-xs text-slate-500">Koperasi Syariah</small>
                </div>
            </a>

            <button class="menu-toggle inline-flex rounded-full border border-slate-200 px-4 py-2 text-sm font-bold text-brand-navy md:hidden" type="button" aria-label="Buka menu" aria-expanded="false">Menu</button>

            <nav class="main-nav absolute left-4 right-4 top-[72px] hidden flex-col gap-1 rounded-2xl border border-slate-200 bg-white p-3 shadow-soft md:static md:flex md:flex-row md:items-center md:border-0 md:bg-transparent md:p-0 md:shadow-none">
                <a class="rounded-full px-4 py-2 text-sm font-semibold <?= isActive('index.php', $currentPage); ?>" href="index.php">Beranda</a>
                <a class="rounded-full px-4 py-2 text-sm font-semibold <?= isActive('profil.php', $currentPage); ?>" href="profil.php">Profil</a>
                <a class="rounded-full px-4 py-2 text-sm font-semibold <?= isActive('layanan.php', $currentPage); ?>" href="layanan.php">Layanan</a>
                <a class="rounded-full px-4 py-2 text-sm font-semibold <?= isActive('portofolio.php', $currentPage); ?>" href="portofolio.php">Program</a>
                <a class="rounded-full px-4 py-2 text-sm font-semibold <?= isActive('kontak.php', $currentPage); ?>" href="kontak.php">Kontak</a>
                <?php if (currentUser()): ?>
                    <a class="rounded-full bg-brand-blue px-4 py-2 text-sm font-bold text-white hover:bg-brand-navy" href="dashboard.php">Dashboard</a>
                <?php else: ?>
                    <a class="rounded-full bg-brand-blue px-4 py-2 text-sm font-bold text-white hover:bg-brand-navy" href="login.php">Masuk</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main>
