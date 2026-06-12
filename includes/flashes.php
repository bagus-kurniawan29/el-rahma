<?php foreach (pullFlashes() as $flash): ?>
    <div class="mb-4 rounded-2xl border px-5 py-4 text-sm font-semibold <?= $flash['type'] === 'success' ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-red-200 bg-red-50 text-red-700'; ?>">
        <?= e($flash['message']); ?>
    </div>
<?php endforeach; ?>
