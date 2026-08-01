<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <p style="color:var(--kk-text-muted); margin-top:0;">Selamat datang, <?php echo e(auth()->user()->name); ?></p>

    <?php if(! $hasStudents): ?>
        <div class="kk-alert kk-alert-warning">
            Data siswa belum tersedia.
            <?php if(auth()->user()->isTreasurer()): ?>
                <a href="<?php echo e(route('students.index')); ?>" class="kk-btn kk-btn-sm" style="margin-left:10px;">+ Tambah Data Siswa</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="kk-grid">
        <div class="kk-card">
            <div class="kk-stat-label">Saldo Sekarang</div>
            <div class="kk-stat-value"><?php echo e(rupiah($balance)); ?></div>
        </div>
        <div class="kk-card">
            <div class="kk-stat-label">Total Pemasukan</div>
            <div class="kk-stat-value income"><?php echo e(rupiah($totalIncome)); ?></div>
        </div>
        <div class="kk-card">
            <div class="kk-stat-label">Total Pengeluaran</div>
            <div class="kk-stat-value expense"><?php echo e(rupiah($totalExpense)); ?></div>
        </div>
        <div class="kk-card">
            <div class="kk-stat-label">Total Siswa</div>
            <div class="kk-stat-value"><?php echo e($totalStudents); ?> Siswa</div>
        </div>
    </div>

    <div class="kk-card" style="margin-bottom:20px;">
        <div class="kk-card-title">Kas Minggu Ini <span style="font-weight:400; color:var(--kk-text-muted);">(<?php echo e($weekLabel); ?>)</span></div>
        <div style="display:flex; gap:32px; flex-wrap:wrap; margin-bottom:12px;">
            <div>
                <div class="kk-stat-label">Target</div>
                <div class="kk-stat-value" style="font-size:18px;"><?php echo e(rupiah($target)); ?></div>
            </div>
            <div>
                <div class="kk-stat-label">Terkumpul</div>
                <div class="kk-stat-value income" style="font-size:18px;"><?php echo e(rupiah($collected)); ?></div>
            </div>
            <div>
                <div class="kk-stat-label">Kekurangan</div>
                <div class="kk-stat-value expense" style="font-size:18px;"><?php echo e(rupiah($shortfall)); ?></div>
            </div>
            <div>
                <div class="kk-stat-label">Pembayar</div>
                <div class="kk-stat-value" style="font-size:18px;"><?php echo e($paidCount); ?> / <?php echo e($totalStudents); ?> siswa</div>
            </div>
        </div>
        <div class="kk-progress"><div class="kk-progress-bar" style="width: <?php echo e(min($progress, 100)); ?>%;"></div></div>
        <div style="text-align:right; font-size:12.5px; color:var(--kk-text-muted); margin-top:4px;"><?php echo e($progress); ?>%</div>
    </div>

    <div class="kk-grid" style="grid-template-columns: 1.3fr 1fr;">
        <div class="kk-card">
            <div class="kk-card-title">Income vs Expense</div>
            <canvas id="barChart" height="160"></canvas>
        </div>
        <div class="kk-card">
            <div class="kk-card-title">Pengeluaran Berdasarkan Kategori</div>
            <?php if($expenseByCategory->isEmpty()): ?>
                <div class="kk-empty" style="padding:24px 0;"><p>Belum ada pengeluaran</p></div>
            <?php else: ?>
                <canvas id="donutChart" height="160"></canvas>
            <?php endif; ?>
        </div>
    </div>

    <div class="kk-section-title">Transaksi Terbaru</div>
    <div class="kk-table-wrap">
        <?php if($recentTransactions->isEmpty()): ?>
            <div class="kk-empty"><p>Belum ada transaksi</p></div>
        <?php else: ?>
        <table class="kk-table">
            <thead>
                <tr><th>Tanggal</th><th>Deskripsi</th><th>Tipe</th><th>Nominal</th></tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($t->transaction_date->translatedFormat('d M Y')); ?></td>
                        <td><?php echo e($t->description); ?></td>
                        <td><span class="kk-badge <?php echo e($t->type === 'income' ? 'kk-badge-income' : 'kk-badge-expense'); ?>"><?php echo e($t->type === 'income' ? 'Income' : 'Expense'); ?></span></td>
                        <td><?php echo e($t->type === 'income' ? '+ ' : '- '); ?><?php echo e(rupiah($t->amount)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.4/chart.umd.min.js"></script>
<script>
    const months = <?php echo json_encode($monthlyChart->keys(), 15, 512) ?>;
    const incomeData = <?php echo json_encode($monthlyChart->map(fn($g) => $g->firstWhere('type', 'income')->total ?? 0)->values(), 512) ?>;
    const expenseData = <?php echo json_encode($monthlyChart->map(fn($g) => $g->firstWhere('type', 'expense')->total ?? 0)->values(), 512) ?>;

    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [
                { label: 'Pemasukan', data: incomeData, backgroundColor: '#7BAE8F', borderRadius: 6 },
                { label: 'Pengeluaran', data: expenseData, backgroundColor: '#C96A5A', borderRadius: 6 },
            ]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    <?php if($expenseByCategory->isNotEmpty()): ?>
    new Chart(document.getElementById('donutChart'), {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($expenseByCategory->pluck('category'), 15, 512) ?>,
            datasets: [{
                data: <?php echo json_encode($expenseByCategory->pluck('total'), 15, 512) ?>,
                backgroundColor: ['#7BAE8F', '#A7C9B6', '#D9A441', '#C96A5A', '#5C9077', '#B7CFC1', '#8FB09E'],
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });
    <?php endif; ?>
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Nayla\Downloads\kaskelas\kaskelas\resources\views/dashboard/staff.blade.php ENDPATH**/ ?>