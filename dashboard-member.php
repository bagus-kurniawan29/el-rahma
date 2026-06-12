<?php
$user = requireLogin('member');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $branchId = (int) ($_POST['branch_id'] ?? 0);
    $amount = (int) preg_replace('/\D/', '', $_POST['amount'] ?? '0');
    $term = (int) ($_POST['term_months'] ?? 0);
    $purpose = trim($_POST['purpose'] ?? '');
    $validBranchIds = array_map(fn ($branch) => (int) $branch['id'], branches());

    if (!in_array($branchId, $validBranchIds, true) || $amount < 100000 || $term < 1 || $term > 36 || mb_strlen($purpose) < 10) {
        flash('error', 'Periksa kembali cabang, nominal, tenor, dan tujuan pengajuan.');
        redirect('dashboard.php');
    }

    $statement = db()->prepare('INSERT INTO applications (user_id, branch_id, amount, term_months, purpose, activation_code) VALUES (?, ?, ?, ?, ?, ?)');
    $code = activationCode();
    $statement->execute([(int) $user['id'], $branchId, $amount, $term, $purpose, $code]);
    flash('success', 'Pengajuan berhasil dikirim. Kode aktivasi Anda: ' . $code);
    redirect('dashboard.php');
}

$statement = db()->prepare('SELECT applications.*, branches.name AS branch_name FROM applications JOIN branches ON branches.id = applications.branch_id WHERE applications.user_id = ? ORDER BY applications.id DESC');
$statement->execute([(int) $user['id']]);
$applications = $statement->fetchAll();
$totalApproved = array_sum(array_map(fn ($item) => $item['status'] === 'approved' ? (int) $item['amount'] : 0, $applications));
$pageTitle = 'Dashboard Member - Koperasi Syariah El Rahma Lombok Rinjani';
include __DIR__ . '/includes/header.php';
?>

<section class="min-h-[75vh] bg-slate-50 py-10"><div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8"><?php include __DIR__ . '/includes/flashes.php'; ?><div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"><div><span class="text-sm font-bold text-brand-red">Dashboard Member</span><h1 class="mt-1 text-3xl font-black text-brand-navy">Assalamualaikum, <?= e($user['name']); ?></h1></div><a class="rounded-full border border-slate-200 bg-white px-5 py-2 text-sm font-bold text-slate-600" href="logout.php">Keluar</a></div>

<div class="mb-8 grid gap-4 sm:grid-cols-3"><div class="rounded-2xl border border-slate-200 bg-white p-6"><span class="text-sm text-slate-500">Total Pengajuan</span><strong class="mt-2 block text-3xl text-brand-navy"><?= count($applications); ?></strong></div><div class="rounded-2xl border border-slate-200 bg-white p-6"><span class="text-sm text-slate-500">Sedang Diproses</span><strong class="mt-2 block text-3xl text-amber-600"><?= count(array_filter($applications, fn ($item) => $item['status'] === 'pending')); ?></strong></div><div class="rounded-2xl border border-slate-200 bg-white p-6"><span class="text-sm text-slate-500">Dana Disetujui</span><strong class="mt-2 block text-2xl text-emerald-600"><?= rupiah($totalApproved); ?></strong></div></div>

<div class="grid items-start gap-8 lg:grid-cols-[.8fr_1.2fr]"><div class="rounded-3xl border border-slate-200 bg-white p-7 shadow-sm"><h2 class="text-2xl font-black text-brand-navy">Ajukan Dana</h2><p class="mt-2 text-sm leading-6 text-slate-500">Pilih cabang tujuan dan isi kebutuhan pembiayaan.</p><form class="mt-6 space-y-5" method="post"><input type="hidden" name="csrf_token" value="<?= csrfToken(); ?>"><label class="block text-sm font-bold text-slate-700">Cabang Tujuan<select class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 font-normal" name="branch_id" required><option value="">Pilih cabang</option><?php foreach (branches() as $branch): ?><option value="<?= $branch['id']; ?>"><?= e($branch['name']); ?></option><?php endforeach; ?></select></label><label class="block text-sm font-bold text-slate-700">Nominal Pengajuan<input class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 font-normal" type="number" name="amount" min="100000" step="50000" placeholder="Contoh: 5000000" required></label><label class="block text-sm font-bold text-slate-700">Tenor<select class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 font-normal" name="term_months" required><?php foreach ([3, 6, 12, 18, 24, 36] as $month): ?><option value="<?= $month; ?>"><?= $month; ?> bulan</option><?php endforeach; ?></select></label><label class="block text-sm font-bold text-slate-700">Tujuan Pengajuan<textarea class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 font-normal" name="purpose" rows="4" minlength="10" required placeholder="Jelaskan kebutuhan dana"></textarea></label><button class="w-full rounded-xl bg-brand-blue px-5 py-3 font-bold text-white hover:bg-brand-navy" type="submit">Kirim Pengajuan</button></form></div>

<div><h2 class="mb-5 text-2xl font-black text-brand-navy">Riwayat Pengajuan</h2><div class="space-y-4"><?php if (!$applications): ?><div class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center text-slate-500">Belum ada pengajuan dana.</div><?php endif; ?><?php foreach ($applications as $application): ?><article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"><div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between"><div><span class="text-xs font-bold uppercase tracking-wider text-slate-400"><?= e($application['branch_name']); ?> &middot; <?= date('d M Y', strtotime($application['submitted_at'])); ?></span><h3 class="mt-2 text-xl font-black text-brand-navy"><?= rupiah((int) $application['amount']); ?></h3><p class="mt-1 text-sm text-slate-500"><?= $application['term_months']; ?> bulan &middot; <?= e($application['purpose']); ?></p></div><span class="w-fit rounded-full px-3 py-1 text-xs font-bold <?= statusClass($application['status']); ?>"><?= statusLabel($application['status']); ?></span></div><div class="mt-5 rounded-xl bg-slate-50 p-4"><span class="text-xs font-bold uppercase tracking-wider text-slate-400">Kode Aktivasi</span><strong class="mt-1 block font-mono text-lg tracking-wider text-brand-blue"><?= e($application['activation_code']); ?></strong></div><?php if ($application['admin_note']): ?><p class="mt-4 rounded-xl bg-blue-50 p-4 text-sm text-blue-900"><strong>Catatan admin:</strong> <?= e($application['admin_note']); ?></p><?php endif; ?></article><?php endforeach; ?></div></div></div></div></section>

<?php include __DIR__ . '/includes/footer.php'; ?>
