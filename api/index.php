<?php
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

// Membantu saat dijalankan lokal dengan PHP built-in server:
// php -S localhost:8000 api/index.php
if (strpos($path, '/assets/') === 0) {
    $assetFile = __DIR__ . '/..' . $path;
    if (is_file($assetFile)) {
        return false;
    }
}

$routes = [
    '/' => 'index.php',
    '/index.php' => 'index.php',
    '/profil' => 'profil.php',
    '/profil/' => 'profil.php',
    '/profil.php' => 'profil.php',
    '/layanan' => 'layanan.php',
    '/layanan/' => 'layanan.php',
    '/layanan.php' => 'layanan.php',
    '/portofolio' => 'portofolio.php',
    '/portofolio/' => 'portofolio.php',
    '/portofolio.php' => 'portofolio.php',
    '/kontak' => 'kontak.php',
    '/kontak/' => 'kontak.php',
    '/kontak.php' => 'kontak.php',
];

chdir(__DIR__ . '/..');
$page = $routes[$path] ?? 'index.php';
require __DIR__ . '/../' . $page;
