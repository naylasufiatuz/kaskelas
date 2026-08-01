<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ganti Password - KasKelas</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
</head>
<body>
<div class="kk-auth-wrap">
    <div class="kk-auth-card">
        <div class="kk-logo" style="padding:0 0 6px; justify-content:center;">
            <span class="kk-logo-badge">K</span>
            <span>KasKelas</span>
        </div>
        <p style="text-align:center; color:var(--kk-text-muted); font-size:13px; margin:0 0 24px;">
            Untuk keamanan, silakan ganti password sementara Anda.
        </p>

        <?php if($errors->any()): ?>
            <div class="kk-alert kk-alert-danger">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div><?php echo e($error); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('password.change')); ?>">
            <?php echo csrf_field(); ?>
            <div class="kk-field">
                <label class="kk-label">Password Saat Ini</label>
                <input type="password" name="current_password" class="kk-input" required>
            </div>
            <div class="kk-field">
                <label class="kk-label">Password Baru</label>
                <input type="password" name="password" class="kk-input" required minlength="8">
                <div class="kk-help">Minimal 8 karakter.</div>
            </div>
            <div class="kk-field">
                <label class="kk-label">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="kk-input" required minlength="8">
            </div>
            <button type="submit" class="kk-btn" style="width:100%; justify-content:center;">Ganti Password</button>
        </form>
    </div>
</div>
</body>
</html>
<?php /**PATH C:\Users\Nayla\Downloads\kaskelas\kaskelas\resources\views/auth/change-password.blade.php ENDPATH**/ ?>