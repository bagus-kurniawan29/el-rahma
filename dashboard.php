<?php
require_once __DIR__ . '/includes/app.php';
$user = requireLogin();

if ($user['role'] === 'admin') {
    require __DIR__ . '/dashboard-admin.php';
} else {
    require __DIR__ . '/dashboard-member.php';
}
