<?php
$user = requireLogin('admin');
$branchId = (int) $user['branch_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $applicationId = (int) ($_POST['application_id'] ?? 0);
    $decision = $_POST['decision'] ?? '';
    $note = trim($_POST['admin_note'] ?? '');

    if (!in_array($decision, ['approved', 'rejected'], true)) {
        flash('error', 'Keputusan pengajuan tidak valid.');
        redirect('dashboard.php');
    }

    $check = db()->prepare('SELECT id, status FROM applications WHERE id = ? AND branch_id = ?');
    $check->execute([$applicationId, $branchId]);
    $application = $check->fetch();

    if (!$application || $application['status'] !== 'pending') {
        flash('error', 'Pengajuan tidak ditemukan atau sudah diproses.');
        redirect('dashboard.php');
    }

    $update = db()->prepare('UPDATE applications SET status = ?, admin_note = ?, reviewed_at = CURRENT_TIMESTAMP, reviewed_by = ? WHERE id = ? AND branch_id = ? AND status = "pending"');
    $update->execute([$decision, $note, (int) $user['id'], $applicationId, $branchId]);
    flash('success', $decision === 'approved' ? 'Pengajuan berhasil disetujui.' : 'Pengajuan telah ditolak.');
    redirect('dashboard.php');
}

$statusFilter = $_GET['status'] ?? 'all';
if (!in_array($statusFilter, ['all', 'pending', 'approved', 'rejected'], true)) {
    $statusFilter = 'all';
}

$query = 'SELECT applications.*, users.name AS member_name, users.email AS member_email, users.phone AS member_phone
          FROM applications JOIN users ON users.id = applications.user_id
          WHERE applications.branch_id = ?';
$params = [$branchId];
if ($statusFilter !== 'all') {
    $query .= ' AND applications.status = ?';
    $params[] = $statusFilter;
}
$query .= ' ORDER BY CASE applications.status WHEN "pending" THEN 0 ELSE 1 END, applications.id DESC';
$statement = db()->prepare($query);
$statement->execute($params);
$applications = $statement->fetchAll();

$summary = db()->prepare('SELECT
    COUNT(*) AS total,
    SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) AS pending,
    SUM(CASE WHEN status = "approved" AND YEAR(reviewed_at) = YEAR(CURRENT_DATE()) AND MONTH(reviewed_at) = MONTH(CURRENT_DATE()) THEN amount ELSE 0 END) AS approved_month,
    SUM(CASE WHEN status = "approved" AND YEAR(reviewed_at) = YEAR(CURRENT_DATE()) AND MONTH(reviewed_at) = MONTH(CURRENT_DATE()) THEN 1 ELSE 0 END) AS approved_count_month
    FROM applications WHERE branch_id = ?');
$summary->execute([$branchId]);
$stats = $summary->fetch();

$pageTitle = 'Dashboard Admin ' . $user['branch_name'] . ' - Koperasi Syariah El Rahma Lombok Rinjani';
include __DIR__ . '/includes/header.php';
?>

<section class="min-h-[75vh] bg-slate-50 py-10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <?php include __DIR__ . '/includes/flashes.php'; ?>
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div><span class="text-sm font-bold text-brand-red">Dashboard Admin Cabang</span><h1 class="mt-1 text-3xl font-black text-brand-navy"><?= e($user['branch_name']); ?></h1><p class="mt-1 text-sm text-slate-500"><?= e($user['name']); ?></p></div>
            <div class="flex flex-wrap gap-3"><a class="rounded-full bg-emerald-600 px-5 py-2 text-sm font-bold text-white hover:bg-emerald-700" href="export.php?status=<?= e($statusFilter); ?>">Ekspor Excel</a><a class="rounded-full border border-slate-200 bg-white px-5 py-2 text-sm font-bold text-slate-600" href="logout.php">Keluar</a></div>
        </div>

        <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-6"><span class="text-sm text-slate-500">Semua Pengajuan</span><strong class="mt-2 block text-3xl text-brand-navy"><?= (int) $stats['total']; ?></strong></div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6"><span class="text-sm text-slate-500">Menunggu Konfirmasi</span><strong class="mt-2 block text-3xl text-amber-600"><?= (int) $stats['pending']; ?></strong></div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6"><span class="text-sm text-slate-500">Disetujui Bulan Ini</span><strong class="mt-2 block text-3xl text-emerald-600"><?= (int) $stats['approved_count_month']; ?></strong></div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6"><span class="text-sm text-slate-500">Dana Keluar Bulan Ini</span><strong class="mt-2 block text-xl text-emerald-600"><?= rupiah((int) $stats['approved_month']); ?></strong></div>
        </div>

        <div class="mb-6 flex flex-wrap gap-2">
            <?php foreach (['all' => 'Semua', 'pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'] as $value => $label): ?>
                <a class="rounded-full px-4 py-2 text-sm font-bold <?= $statusFilter === $value ? 'bg-brand-blue text-white' : 'border border-slate-200 bg-white text-slate-600'; ?>" href="dashboard.php?status=<?= $value; ?>"><?= $label; ?></a>
            <?php endforeach; ?>
        </div>

        <div class="space-y-5">
            <?php if (!$applications): ?><div class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center text-slate-500">Belum ada pengajuan pada kategori ini.</div><?php endif; ?>
            <?php foreach ($applications as $application): ?>
                <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="grid gap-6 lg:grid-cols-[1fr_auto]">
                        <div>
                            <div class="flex flex-wrap items-center gap-3"><span class="rounded-full px-3 py-1 text-xs font-bold <?= statusClass($application['status']); ?>"><?= statusLabel($application['status']); ?></span><span class="text-xs font-bold uppercase tracking-wider text-slate-400"><?= date('d M Y H:i', strtotime($application['submitted_at'])); ?></span></div>
                            <h2 class="mt-4 text-2xl font-black text-brand-navy"><?= e($application['member_name']); ?> &middot; <?= rupiah((int) $application['amount']); ?></h2>
                            <p class="mt-2 leading-7 text-slate-600"><?= e($application['purpose']); ?></p>
                            <div class="mt-4 grid gap-3 text-sm text-slate-500 sm:grid-cols-2 lg:grid-cols-4"><span><strong class="block text-slate-700">Tenor</strong><?= (int) $application['term_months']; ?> bulan</span><span><strong class="block text-slate-700">WhatsApp</strong><?= e($application['member_phone']); ?></span><span><strong class="block text-slate-700">Email</strong><?= e($application['member_email']); ?></span><span><strong class="block text-slate-700">Kode Aktivasi</strong><code class="font-bold text-brand-blue"><?= e($application['activation_code']); ?></code></span></div>
                            <?php if ($application['admin_note']): ?><p class="mt-4 rounded-xl bg-slate-50 p-4 text-sm text-slate-600"><strong>Catatan:</strong> <?= e($application['admin_note']); ?></p><?php endif; ?>
                        </div>
                        <?php if ($application['status'] === 'pending'): ?>
                            <form class="w-full rounded-2xl bg-slate-50 p-4 lg:w-72" method="post">
                                <input type="hidden" name="csrf_token" value="<?= csrfToken(); ?>"><input type="hidden" name="application_id" value="<?= $application['id']; ?>">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500">Catatan Admin<textarea class="mt-2 w-full rounded-xl border border-slate-200 bg-white p-3 text-sm font-normal" name="admin_note" rows="3" placeholder="Catatan opsional"></textarea></label>
                                <div class="mt-3 grid grid-cols-2 gap-2"><button class="rounded-xl bg-emerald-600 px-3 py-2 text-sm font-bold text-white hover:bg-emerald-700" name="decision" value="approved" type="submit">Setujui</button><button class="rounded-xl bg-red-600 px-3 py-2 text-sm font-bold text-white hover:bg-red-700" name="decision" value="rejected" type="submit">Tolak</button></div>
                            </form>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
