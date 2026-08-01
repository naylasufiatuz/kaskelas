<?php
    $user = auth()->user();
?>
<aside class="kk-sidebar">
    <div class="kk-logo">
        <span class="kk-logo-badge">K</span>
        <span>KasKelas</span>
    </div>

    <nav>
        <div class="kk-nav-group">
            <a href="<?php echo e(route('dashboard')); ?>" class="kk-nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                Dashboard
            </a>
        </div>

        <div class="kk-nav-group">
            <div class="kk-nav-title">Keuangan</div>
            <?php if($user->isStudent()): ?>
                <a href="<?php echo e(route('cash-payments.mine')); ?>" class="kk-nav-link <?php echo e(request()->routeIs('cash-payments.mine') ? 'active' : ''); ?>">Kas Saya</a>
            <?php else: ?>
                <a href="<?php echo e(route('cash-payments.index')); ?>" class="kk-nav-link <?php echo e(request()->routeIs('cash-payments.index') ? 'active' : ''); ?>">Pembayaran Kas</a>
            <?php endif; ?>
            <a href="<?php echo e(route('transactions.index')); ?>" class="kk-nav-link <?php echo e(request()->routeIs('transactions.*') ? 'active' : ''); ?>">Transaksi</a>
        </div>

        <?php if (! ($user->isStudent())): ?>
        <div class="kk-nav-group">
            <div class="kk-nav-title">Data</div>
            <a href="<?php echo e(route('students.index')); ?>" class="kk-nav-link <?php echo e(request()->routeIs('students.*') ? 'active' : ''); ?>">Data Siswa</a>
        </div>
        <?php endif; ?>

        <div class="kk-nav-group">
            <a href="<?php echo e(route('reports.index')); ?>" class="kk-nav-link <?php echo e(request()->routeIs('reports.*') ? 'active' : ''); ?>">Laporan</a>
            <?php if($user->isTreasurer()): ?>
                <a href="<?php echo e(route('activity-logs.index')); ?>" class="kk-nav-link <?php echo e(request()->routeIs('activity-logs.*') ? 'active' : ''); ?>">Activity Log</a>
                <a href="<?php echo e(route('settings.index')); ?>" class="kk-nav-link <?php echo e(request()->routeIs('settings.*') ? 'active' : ''); ?>">Settings</a>
            <?php endif; ?>
        </div>

        <div class="kk-nav-group">
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="kk-nav-link" style="width:100%; text-align:left; background:none; border:none; cursor:pointer; font-family:inherit;">Logout</button>
            </form>
        </div>
    </nav>
</aside>
<?php /**PATH C:\Users\Nayla\Downloads\kaskelas\kaskelas\resources\views/components/sidebar.blade.php ENDPATH**/ ?>