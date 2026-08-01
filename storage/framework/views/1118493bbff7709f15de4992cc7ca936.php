<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    body { font-family: sans-serif; color: #263A31; font-size: 12px; }
    h1 { font-size: 18px; margin-bottom: 2px; }
    .sub { color: #6B7C74; margin-bottom: 18px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #E2E8E4; padding: 6px 8px; text-align: left; }
    th { background: #E6F2EB; }
    .totals { margin-top: 14px; }
    .totals td { border: none; padding: 3px 0; }
    .totals .label { color: #6B7C74; width: 160px; }
    .totals .value { font-weight: bold; }
    .income { color: #5C9077; }
    .expense { color: #C96A5A; }
</style>
</head>
<body>
    <h1>LAPORAN KEUANGAN</h1>
    <div class="sub"><?php echo e($className); ?> &middot; <?php echo e($from->translatedFormat('d F Y')); ?> – <?php echo e($to->translatedFormat('d F Y')); ?></div>

    <table class="totals">
        <tr><td class="label">Total Pemasukan</td><td class="value income"><?php echo e(rupiah($totals['income'])); ?></td></tr>
        <tr><td class="label">Total Pengeluaran</td><td class="value expense"><?php echo e(rupiah($totals['expense'])); ?></td></tr>
        <tr><td class="label">Saldo</td><td class="value"><?php echo e(rupiah($totals['net'])); ?></td></tr>
        <tr><td class="label">Jumlah Transaksi</td><td class="value"><?php echo e($transactions->count()); ?></td></tr>
    </table>

    <?php if($expenseByCategory->isNotEmpty()): ?>
    <h3>Rekap Pengeluaran per Kategori</h3>
    <table>
        <thead><tr><th>Kategori</th><th>Total</th></tr></thead>
        <tbody>
        <?php $__currentLoopData = $expenseByCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $total): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr><td><?php echo e($category); ?></td><td><?php echo e(rupiah($total)); ?></td></tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php endif; ?>

    <h3>Rekap Transaksi</h3>
    <table>
        <thead><tr><th>Tanggal</th><th>Tipe</th><th>Kategori</th><th>Deskripsi</th><th>Nominal</th></tr></thead>
        <tbody>
        <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($t->transaction_date->translatedFormat('d M Y')); ?></td>
                <td><?php echo e($t->type === 'income' ? 'Pemasukan' : 'Pengeluaran'); ?></td>
                <td><?php echo e($t->category->name ?? '-'); ?></td>
                <td><?php echo e($t->description); ?></td>
                <td><?php echo e(rupiah($t->amount)); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</body>
</html>
<?php /**PATH C:\Users\Nayla\Downloads\kaskelas\kaskelas\resources\views/reports/pdf.blade.php ENDPATH**/ ?>