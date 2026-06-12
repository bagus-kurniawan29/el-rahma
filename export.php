<?php
require_once __DIR__ . '/includes/app.php';
$user = requireLogin('admin');
$status = $_GET['status'] ?? 'all';
if (!in_array($status, ['all', 'pending', 'approved', 'rejected'], true)) {
    $status = 'all';
}

$query = 'SELECT applications.activation_code, users.name AS member_name, users.email, users.phone,
                 applications.amount, applications.term_months, applications.purpose, applications.status,
                 applications.admin_note, applications.submitted_at, applications.reviewed_at
          FROM applications JOIN users ON users.id = applications.user_id
          WHERE applications.branch_id = ?';
$params = [(int) $user['branch_id']];
if ($status !== 'all') {
    $query .= ' AND applications.status = ?';
    $params[] = $status;
}
$query .= ' ORDER BY applications.id DESC';
$statement = db()->prepare($query);
$statement->execute($params);

$filename = 'pengajuan-' . strtolower($user['branch_name']) . '-' . date('Y-m-d') . '.csv';
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: no-store');

$output = fopen('php://output', 'w');
fwrite($output, "\xEF\xBB\xBF");
fputcsv($output, ['Kode Aktivasi', 'Nama Member', 'Email', 'WhatsApp', 'Nominal', 'Tenor (Bulan)', 'Tujuan', 'Status', 'Catatan Admin', 'Tanggal Pengajuan', 'Tanggal Diproses'], ';');
while ($row = $statement->fetch()) {
    fputcsv($output, [
        $row['activation_code'], $row['member_name'], $row['email'], $row['phone'],
        $row['amount'], $row['term_months'], $row['purpose'], statusLabel($row['status']),
        $row['admin_note'], $row['submitted_at'], $row['reviewed_at'],
    ], ';');
}
fclose($output);
exit;
