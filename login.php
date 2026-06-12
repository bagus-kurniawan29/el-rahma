<?php
require_once __DIR__ . '/includes/app.php';
requireGuest();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && databaseAvailable()) {
    verifyCsrf();
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    $statement = db()->prepare('SELECT * FROM users WHERE email = ?');
    $statement->execute([$email]);
    $user = $statement->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];
        flash('success', 'Selamat datang, ' . $user['name'] . '.');
        redirect('dashboard.php');
    }

    flash('error', 'Email atau kata sandi tidak sesuai.');
    redirect('login.php');
}

$pageTitle = 'Masuk - Koperasi Syariah El Rahma Lombok Rinjani';
include 'includes/header.php';
?>

<section class="min-h-[70vh] bg-slate-50 py-16">
    <div class="mx-auto max-w-md px-4 sm:px-6">
        <?php include 'includes/flashes.php'; ?>
        <?php if (!databaseAvailable()): ?>
            <div class="mb-5 rounded-2xl border border-amber-200 bg-amber-50 p-5 text-sm leading-6 text-amber-900">
                <strong class="block">Login sementara tidak tersedia.</strong>
                <?= e(databaseUnavailableMessage()); ?>
            </div>
        <?php endif; ?>
        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-soft">
            <span class="font-bold text-brand-red">Portal Koperasi</span>
            <h1 class="mt-2 text-3xl font-black text-brand-navy">Masuk ke akun</h1>
            <p class="mt-3 text-sm leading-6 text-slate-500">Akses dashboard member atau dashboard admin cabang.</p>
            <form class="mt-7 space-y-5 <?= databaseAvailable() ? '' : 'pointer-events-none opacity-50'; ?>" method="post">
                <input type="hidden" name="csrf_token" value="<?= csrfToken(); ?>">
                <label class="block text-sm font-bold text-slate-700">Email
                    <input class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 font-normal outline-none focus:border-brand-blue focus:ring-4 focus:ring-blue-50" type="email" name="email" required autocomplete="email">
                </label>
                <label class="block text-sm font-bold text-slate-700">Kata Sandi
                    <input class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 font-normal outline-none focus:border-brand-blue focus:ring-4 focus:ring-blue-50" type="password" name="password" required autocomplete="current-password">
                </label>
                <button class="w-full rounded-xl bg-brand-blue px-5 py-3 font-bold text-white hover:bg-brand-navy" type="submit" <?= databaseAvailable() ? '' : 'disabled'; ?>>Masuk</button>
            </form>
            <p class="mt-6 text-center text-sm text-slate-500">Belum menjadi member? <a class="font-bold text-brand-blue" href="register.php">Daftar akun</a></p>
        </div>
        <div class="mt-5 rounded-2xl border border-blue-100 bg-blue-50 p-4 text-xs leading-6 text-blue-900">
            <strong>Akun uji member:</strong> member@elrahma.id / Member123!<br>
            <strong>Akun uji admin:</strong> admin.rempung@elrahma.id / Admin123!
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
