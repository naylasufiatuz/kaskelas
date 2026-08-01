<?php $__env->startSection('title', 'Pembayaran Kas'); ?>

<?php $__env->startSection('content'); ?>
    <?php $canManage = auth()->user()->isTreasurer(); ?>

    <form method="GET" style="margin-bottom:16px;">
        <label class="kk-label">Pilih Minggu</label>
        <select name="week" class="kk-select" style="max-width:320px;" onchange="this.form.submit()">
            <?php $__currentLoopData = $weeks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($w['start']->toDateString()); ?>" <?php echo e($w['start']->toDateString() === $weekStart->toDateString() ? 'selected' : ''); ?>>
                    <?php echo e($w['label']); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </form>

    <div class="kk-grid" style="grid-template-columns: repeat(3, 1fr);">
        <div class="kk-card">
            <div class="kk-stat-label">Target</div>
            <div class="kk-stat-value" style="font-size:18px;"><?php echo e(rupiah($target)); ?></div>
        </div>
        <div class="kk-card">
            <div class="kk-stat-label">Terkumpul</div>
            <div class="kk-stat-value income" style="font-size:18px;"><?php echo e(rupiah($collected)); ?></div>
        </div>
        <div class="kk-card">
            <div class="kk-stat-label">Pembayar</div>
            <div class="kk-stat-value" style="font-size:18px;"><?php echo e($paidCount); ?> / <?php echo e($totalStudents); ?> siswa</div>
        </div>
    </div>

    <div class="kk-table-wrap">
        <?php if($rows->isEmpty()): ?>
            <div class="kk-empty"><p>Belum ada data siswa</p></div>
        <?php else: ?>
        <table class="kk-table">
            <thead>
                <tr><th>No</th><th>Nama</th><th>Nominal</th><th>Status</th><th>Tanggal</th><?php if($canManage): ?><th>Aksi</th><?php endif; ?></tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($i + 1); ?></td>
                        <td><?php echo e($row['student']->name); ?></td>
                        <td><?php echo e(rupiah($weeklyAmount)); ?></td>
                        <td><span class="kk-badge <?php echo e($row['status'] === 'paid' ? 'kk-badge-paid' : 'kk-badge-unpaid'); ?>"><?php echo e($row['status'] === 'paid' ? 'Lunas' : 'Belum'); ?></span></td>
                        <td><?php echo e($row['payment']?->paid_at?->translatedFormat('d M') ?? '-'); ?></td>
                        <?php if($canManage): ?>
                        <td style="display:flex; gap:6px;">
                            <?php if($row['status'] === 'paid'): ?>
                                <form method="POST" action="<?php echo e(route('cash-payments.mark-unpaid', $row['student'])); ?>">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="week_start" value="<?php echo e($weekStart->toDateString()); ?>">
                                    <button class="kk-btn kk-btn-sm kk-btn-outline" type="submit">Tandai Belum</button>
                                </form>
                            <?php else: ?>
                                <form method="POST" action="<?php echo e(route('cash-payments.mark-paid', $row['student'])); ?>">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="week_start" value="<?php echo e($weekStart->toDateString()); ?>">
                                    <button class="kk-btn kk-btn-sm" type="submit">Tandai Lunas</button>
                                </form>
                            <?php endif; ?>
                        </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Nayla\Downloads\kaskelas\kaskelas\resources\views/cash-payments/index.blade.php ENDPATH**/ ?>