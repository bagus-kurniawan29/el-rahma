<?php
require_once __DIR__ . '/includes/app.php';
requireGuest();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';

    if (mb_strlen($name) < 3 || !filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($password) < 8) {
        flash('error', 'Lengkapi data dengan benar. Kata sandi minimal 8 karakter.');
        redirect('register.php');
    }

    try {
        $statement = db()->prepare('INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, "member")');
        $statement->execute([$name, $email, $phone, password_hash($password, PASSWORD_DEFAULT)]);
        $_SESSION['user_id'] = (int) db()->lastInsertId();
        session_regenerate_id(true);
        flash('success', 'Akun berhasil dibuat. Selamat datang di portal anggota.');
        redirect('dashboard.php');
    } catch (PDOException $exception) {
        if (str_contains($exception->getMessage(), 'UNIQUE')) {
            flash('error', 'Email tersebut sudah terdaftar.');
            redirect('register.php');
        }
        throw $exception;
    }
}

$pageTitle = 'Daftar Member - Koperasi Syariah El Rahma Lombok Rinjani';
include 'includes/header.php';
?>

<section class="min-h-[70vh] bg-slate-50 py-16"><div class="mx-auto max-w-lg px-4 sm:px-6"><?php include 'includes/flashes.php'; ?><div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-soft"><span class="font-bold text-brand-red">Keanggotaan</span><h1 class="mt-2 text-3xl font-black text-brand-navy">Buat akun member</h1><form class="mt-7 space-y-5" method="post"><input type="hidden" name="csrf_token" value="<?= csrfToken(); ?>"><label class="block text-sm font-bold text-slate-700">Nama Lengkap<input class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 font-normal outline-none focus:border-brand-blue" type="text" name="name" required minlength="3"></label><label class="block text-sm font-bold text-slate-700">Email<input class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 font-normal outline-none focus:border-brand-blue" type="email" name="email" required></label><label class="block text-sm font-bold text-slate-700">Nomor WhatsApp<input class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 font-normal outline-none focus:border-brand-blue" type="tel" name="phone" required></label><label class="block text-sm font-bold text-slate-700">Kata Sandi<input class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 font-normal outline-none focus:border-brand-blue" type="password" name="password" required minlength="8"></label><button class="w-full rounded-xl bg-brand-blue px-5 py-3 font-bold text-white hover:bg-brand-navy" type="submit">Daftar sebagai Member</button></form><p class="mt-6 text-center text-sm text-slate-500">Sudah punya akun? <a class="font-bold text-brand-blue" href="login.php">Masuk</a></p></div></div></section>

<?php include 'includes/footer.php'; ?>
