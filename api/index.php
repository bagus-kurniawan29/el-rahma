<?php
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
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
    '/login' => 'login.php',
    '/login/' => 'login.php',
    '/login.php' => 'login.php',
    '/register' => 'register.php',
    '/register/' => 'register.php',
    '/register.php' => 'register.php',
    '/logout' => 'logout.php',
    '/logout.php' => 'logout.php',
    '/dashboard' => 'dashboard.php',
    '/dashboard/' => 'dashboard.php',
    '/dashboard.php' => 'dashboard.php',
    '/export' => 'export.php',
    '/export.php' => 'export.php',
];

chdir(__DIR__ . '/..');
$page = $routes[$path] ?? 'index.php';
require __DIR__ . '/../' . $page;
