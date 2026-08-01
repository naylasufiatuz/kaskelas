<?php $__env->startSection('title', 'Laporan'); ?>

<?php $__env->startSection('content'); ?>
    <?php $canExport = auth()->user()->isTreasurer(); ?>

    <form method="GET" style="display:flex; gap:8px; flex-wrap:wrap; align-items:end; margin-bottom:20px;">
        <div>
            <label class="kk-label">Bulan</label>
            <input type="month" name="month" class="kk-input" value="<?php echo e(request('month')); ?>">
        </div>
        <div><label class="kk-label">Dari</label><input type="date" name="from" class="kk-input" value="<?php echo e(request('from')); ?>"></div>
        <div><label class="kk-label">Sampai</label><input type="date" name="to" class="kk-input" value="<?php echo e(request('to')); ?>"></div>
        <button class="kk-btn kk-btn-outline" type="submit">Terapkan</button>
        <?php if($canExport): ?>
            <a href="<?php echo e(route('reports.export-pdf', request()->query())); ?>" class="kk-btn">Export PDF</a>
        <?php endif; ?>
    </form>

    <p style="color:var(--kk-text-muted); font-size:13.5px; margin-top:-8px;">
        Periode: <?php echo e($from->translatedFormat('d M Y')); ?> – <?php echo e($to->translatedFormat('d M Y')); ?>

    </p>

    <div class="kk-grid" style="grid-template-columns: repeat(4,1fr);">
        <div class="kk-card"><div class="kk-stat-label">Total Pemasukan</div><div class="kk-stat-value income"><?php echo e(rupiah($totals['income'])); ?></div></div>
        <div class="kk-card"><div class="kk-stat-label">Total Pengeluaran</div><div class="kk-stat-value expense"><?php echo e(rupiah($totals['expense'])); ?></div></div>
        <div class="kk-card"><div class="kk-stat-label">Saldo Periode</div><div class="kk-stat-value"><?php echo e(rupiah($totals['net'])); ?></div></div>
        <div class="kk-card"><div class="kk-stat-label">Jumlah Transaksi</div><div class="kk-stat-value"><?php echo e($transactions->count()); ?></div></div>
    </div>

    <?php if($expenseByCategory->isNotEmpty()): ?>
    <div class="kk-card" style="margin-bottom:20px;">
        <div class="kk-card-title">Kategori Pengeluaran Terbesar</div>
        <?php $__currentLoopData = $expenseByCategory->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $total): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div style="display:flex; justify-content:space-between; padding:6px 0; border-bottom:1px solid var(--kk-border); font-size:14px;">
                <span><?php echo e($category); ?></span>
                <span style="font-weight:600;"><?php echo e(rupiah($total)); ?></span>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>

    <div class="kk-table-wrap">
        <?php if($transactions->isEmpty()): ?>
            <div class="kk-empty"><p>Belum ada transaksi pada periode ini</p></div>
        <?php else: ?>
        <table class="kk-table">
            <thead><tr><th>Tanggal</th><th>Tipe</th><th>Kategori</th><th>Deskripsi</th><th>Nominal</th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($t->transaction_date->translatedFormat('d M Y')); ?></td>
                        <td><span class="kk-badge <?php echo e($t->type === 'income' ? 'kk-badge-income' : 'kk-badge-expense'); ?>"><?php echo e($t->type === 'income' ? 'Income' : 'Expense'); ?></span></td>
                        <td><?php echo e($t->category->name ?? '-'); ?></td>
                        <td><?php echo e($t->description); ?></td>
                        <td><?php echo e($t->type === 'income' ? '+ ' : '- '); ?><?php echo e(rupiah($t->amount)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Nayla\Downloads\kaskelas\kaskelas\resources\views/reports/index.blade.php ENDPATH**/ ?>