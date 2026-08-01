<?php $__env->startSection('title', 'Activity Log'); ?>

<?php $__env->startSection('content'); ?>
    <div class="kk-table-wrap">
        <?php if($logs->isEmpty()): ?>
            <div class="kk-empty"><p>Belum ada aktivitas</p></div>
        <?php else: ?>
        <table class="kk-table">
            <thead><tr><th>Waktu</th><th>User</th><th>Aktivitas</th><th>Detail</th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($log->created_at->translatedFormat('d M Y H:i')); ?></td>
                        <td><?php echo e($log->user->name ?? 'Sistem'); ?></td>
                        <td><?php echo e($log->description); ?></td>
                        <td style="font-size:12.5px; color:var(--kk-text-muted);">
                            <?php if($log->old_values && $log->new_values): ?>
                                <?php $__currentLoopData = $log->new_values; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $newVal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(($log->old_values[$field] ?? null) != $newVal): ?>
                                        <?php echo e($field); ?>: <?php echo e($log->old_values[$field] ?? '-'); ?> → <?php echo e($newVal); ?><br>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
    <div style="margin-top:16px;"><?php echo e($logs->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Nayla\Downloads\kaskelas\kaskelas\resources\views/activity-logs/index.blade.php ENDPATH**/ ?>