<?php

error_reporting(E_ALL);
ini_set('display_errors', getenv('APP_DEBUG') === 'true' ? '1' : '0');
$GLOBALS['app_database_error'] = null;

function databaseConfig(): array
{
    $databaseUrl = getenv('DATABASE_URL') ?: getenv('MYSQL_URL');
    if ($databaseUrl) {
        $parts = parse_url($databaseUrl);
        return [
            'host' => $parts['host'] ?? '127.0.0.1',
            'port' => (int) ($parts['port'] ?? 3306),
            'name' => ltrim($parts['path'] ?? '/db_el_rahma', '/'),
            'user' => urldecode($parts['user'] ?? 'root'),
            'pass' => urldecode($parts['pass'] ?? ''),
        ];
    }

    $local = require __DIR__ . '/../config/database.php';
    return [
        'host' => getenv('DB_HOST') ?: getenv('MYSQLHOST') ?: $local['host'],
        'port' => (int) (getenv('DB_PORT') ?: getenv('MYSQLPORT') ?: $local['port']),
        'name' => getenv('DB_NAME') ?: getenv('MYSQLDATABASE') ?: $local['name'],
        'user' => getenv('DB_USER') ?: getenv('MYSQLUSER') ?: $local['user'],
        'pass' => getenv('DB_PASS') ?: getenv('MYSQLPASSWORD') ?: $local['pass'],
    ];
}

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $config = databaseConfig();
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $config['name'])) {
        throw new RuntimeException('Nama database MySQL tidak valid.');
    }

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $config['host'], $config['port'], $config['name']);
    try {
        $pdo = new PDO($dsn, $config['user'], $config['pass'], $options);
    } catch (PDOException $exception) {
        if ((int) $exception->getCode() !== 1049) {
            throw $exception;
        }

        $serverDsn = sprintf('mysql:host=%s;port=%d;charset=utf8mb4', $config['host'], $config['port']);
        $server = new PDO($serverDsn, $config['user'], $config['pass'], $options);
        $server->exec(sprintf(
            'CREATE DATABASE IF NOT EXISTS `%s` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci',
            $config['name']
        ));
        $pdo = new PDO($dsn, $config['user'], $config['pass'], $options);
    }

    initializeDatabase($pdo);
    return $pdo;
}

function initializeDatabase(PDO $pdo): void
{
    $pdo->exec('CREATE TABLE IF NOT EXISTS branches (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL UNIQUE,
        slug VARCHAR(100) NOT NULL UNIQUE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

    $pdo->exec('CREATE TABLE IF NOT EXISTS users (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(150) NOT NULL,
        email VARCHAR(190) NOT NULL UNIQUE,
        phone VARCHAR(30) NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM("admin", "member") NOT NULL,
        branch_id INT UNSIGNED NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_users_branch FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

    $pdo->exec('CREATE TABLE IF NOT EXISTS applications (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT UNSIGNED NOT NULL,
        branch_id INT UNSIGNED NOT NULL,
        amount BIGINT UNSIGNED NOT NULL,
        term_months TINYINT UNSIGNED NOT NULL,
        purpose TEXT NOT NULL,
        activation_code VARCHAR(30) NOT NULL UNIQUE,
        status ENUM("pending", "approved", "rejected") NOT NULL DEFAULT "pending",
        admin_note TEXT NULL,
        submitted_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        reviewed_at TIMESTAMP NULL DEFAULT NULL,
        reviewed_by INT UNSIGNED NULL,
        INDEX idx_applications_branch_status (branch_id, status),
        INDEX idx_applications_user (user_id),
        CONSTRAINT fk_applications_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        CONSTRAINT fk_applications_branch FOREIGN KEY (branch_id) REFERENCES branches(id),
        CONSTRAINT fk_applications_reviewer FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

    $pdo->exec('CREATE TABLE IF NOT EXISTS sessions (
        id VARCHAR(128) PRIMARY KEY,
        data LONGTEXT NOT NULL,
        last_activity INT UNSIGNED NOT NULL,
        INDEX idx_sessions_activity (last_activity)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

    $branches = [
        ['Rempung', 'rempung'],
        ['Pringgabaya', 'pringgabaya'],
        ['Mataram', 'mataram'],
        ['Mamben', 'mamben'],
    ];
    $insertBranch = $pdo->prepare('INSERT IGNORE INTO branches (name, slug) VALUES (?, ?)');
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
    $insertUser = $pdo->prepare('INSERT IGNORE INTO users (name, email, phone, password, role, branch_id) VALUES (?, ?, ?, ?, ?, ?)');
    foreach ($adminAccounts as $account) {
        $findBranch->execute([$account[2]]);
        $branchId = (int) $findBranch->fetchColumn();
        $insertUser->execute([$account[0], $account[1], null, password_hash('Admin123!', PASSWORD_DEFAULT), 'admin', $branchId]);
    }
    $insertUser->execute(['Member Demo', 'member@elrahma.id', '081234567890', password_hash('Member123!', PASSWORD_DEFAULT), 'member', null]);
}

final class DatabaseSessionHandler implements SessionHandlerInterface
{
    public function __construct(private PDO $pdo) {}

    public function open(string $path, string $name): bool { return true; }
    public function close(): bool { return true; }

    public function read(string $id): string|false
    {
        $statement = $this->pdo->prepare('SELECT data FROM sessions WHERE id = ?');
        $statement->execute([$id]);
        return $statement->fetchColumn() ?: '';
    }

    public function write(string $id, string $data): bool
    {
        $statement = $this->pdo->prepare('INSERT INTO sessions (id, data, last_activity) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE data = VALUES(data), last_activity = VALUES(last_activity)');
        return $statement->execute([$id, $data, time()]);
    }

    public function destroy(string $id): bool
    {
        $statement = $this->pdo->prepare('DELETE FROM sessions WHERE id = ?');
        return $statement->execute([$id]);
    }

    public function gc(int $max_lifetime): int|false
    {
        $statement = $this->pdo->prepare('DELETE FROM sessions WHERE last_activity < ?');
        $statement->execute([time() - $max_lifetime]);
        return $statement->rowCount();
    }
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    $secureCookie = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || getenv('VERCEL') === '1';
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $secureCookie,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    try {
        session_set_save_handler(new DatabaseSessionHandler(db()), true);
        session_start();
    } catch (Throwable $exception) {
        $GLOBALS['app_database_error'] = $exception;
        error_log('El Rahma database connection failed: ' . $exception->getMessage());
        session_save_path(sys_get_temp_dir());
        @session_start();
    }
}

function databaseAvailable(): bool
{
    return $GLOBALS['app_database_error'] === null;
}

function databaseUnavailableMessage(): string
{
    return 'Database MySQL belum terhubung. Atur DATABASE_URL atau DB_HOST, DB_NAME, DB_USER, dan DB_PASS pada Environment Variables Vercel, lalu lakukan redeploy.';
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
    if (!databaseAvailable() || empty($_SESSION['user_id'])) {
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
