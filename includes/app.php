<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    $sessionPath = __DIR__ . '/../data/sessions';
    if (!is_dir($sessionPath)) {
        mkdir($sessionPath, 0775, true);
    }
    session_save_path($sessionPath);
    @session_start();
}

const APP_DB_PATH = __DIR__ . '/../data/koperasi.sqlite';

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $pdo = new PDO('sqlite:' . APP_DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec('PRAGMA foreign_keys = ON');
    initializeDatabase($pdo);

    return $pdo;
}

function initializeDatabase(PDO $pdo): void
{
    $pdo->exec('CREATE TABLE IF NOT EXISTS branches (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL UNIQUE,
        slug TEXT NOT NULL UNIQUE
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        phone TEXT,
        password TEXT NOT NULL,
        role TEXT NOT NULL CHECK(role IN ("admin", "member")),
        branch_id INTEGER,
        created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY(branch_id) REFERENCES branches(id)
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS applications (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        branch_id INTEGER NOT NULL,
        amount INTEGER NOT NULL,
        term_months INTEGER NOT NULL,
        purpose TEXT NOT NULL,
        activation_code TEXT NOT NULL UNIQUE,
        status TEXT NOT NULL DEFAULT "pending" CHECK(status IN ("pending", "approved", "rejected")),
        admin_note TEXT,
        submitted_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
        reviewed_at TEXT,
        reviewed_by INTEGER,
        FOREIGN KEY(user_id) REFERENCES users(id),
        FOREIGN KEY(branch_id) REFERENCES branches(id),
        FOREIGN KEY(reviewed_by) REFERENCES users(id)
    )');

    $branches = [
        ['Rempung', 'rempung'],
        ['Pringgabaya', 'pringgabaya'],
        ['Mataram', 'mataram'],
        ['Mamben', 'mamben'],
    ];

    $insertBranch = $pdo->prepare('INSERT OR IGNORE INTO branches (name, slug) VALUES (?, ?)');
    foreach ($branches as $branch) {
        $insertBranch->execute($branch);
    }

    $adminAccounts = [
        ['Admin Rempung', 'admin.rempung@elrahma.id', 'rempung'],
        ['Admin Pringgabaya', 'admin.pringgabaya@elrahma.id', 'pringgabaya'],
        ['Admin Mataram', 'admin.mataram@elrahma.id', 'mataram'],
        ['Admin Mamben', 'admin.mamben@elrahma.id', 'mamben'],
    ];

    $findBranch = $pdo->prepare('SELECT id FROM branches WHERE slug = ?');
    $insertUser = $pdo->prepare('INSERT OR IGNORE INTO users (name, email, phone, password, role, branch_id) VALUES (?, ?, ?, ?, ?, ?)');
    foreach ($adminAccounts as $account) {
        $findBranch->execute([$account[2]]);
        $branchId = (int) $findBranch->fetchColumn();
        $insertUser->execute([$account[0], $account[1], null, password_hash('Admin123!', PASSWORD_DEFAULT), 'admin', $branchId]);
    }

    $insertUser->execute(['Member Demo', 'member@elrahma.id', '081234567890', password_hash('Member123!', PASSWORD_DEFAULT), 'member', null]);
}

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}

function currentUser(): ?array
{
    if (empty($_SESSION['user_id'])) {
        return null;
    }

    static $user = null;
    if ($user !== null) {
        return $user;
    }

    $statement = db()->prepare('SELECT users.*, branches.name AS branch_name FROM users LEFT JOIN branches ON branches.id = users.branch_id WHERE users.id = ?');
    $statement->execute([(int) $_SESSION['user_id']]);
    $user = $statement->fetch() ?: null;

    return $user;
}

function requireGuest(): void
{
    if (currentUser()) {
        redirect('dashboard.php');
    }
}

function requireLogin(?string $role = null): array
{
    $user = currentUser();
    if (!$user) {
        flash('error', 'Silakan masuk untuk melanjutkan.');
        redirect('login.php');
    }

    if ($role !== null && $user['role'] !== $role) {
        http_response_code(403);
        exit('Anda tidak memiliki akses ke halaman ini.');
    }

    return $user;
}

function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrf(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(419);
        exit('Sesi formulir berakhir. Muat ulang halaman dan coba lagi.');
    }
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function pullFlashes(): array
{
    $flashes = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $flashes;
}

function branches(): array
{
    return db()->query('SELECT * FROM branches ORDER BY id')->fetchAll();
}

function rupiah(int|float $amount): string
{
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

function statusLabel(string $status): string
{
    return ['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'][$status] ?? $status;
}

function statusClass(string $status): string
{
    return [
        'pending' => 'bg-amber-50 text-amber-700',
        'approved' => 'bg-emerald-50 text-emerald-700',
        'rejected' => 'bg-red-50 text-red-700',
    ][$status] ?? 'bg-slate-100 text-slate-600';
}

function activationCode(): string
{
    do {
        $code = 'ELR-' . strtoupper(bin2hex(random_bytes(3)));
        $statement = db()->prepare('SELECT COUNT(*) FROM applications WHERE activation_code = ?');
        $statement->execute([$code]);
    } while ((int) $statement->fetchColumn() > 0);

    return $code;
}
