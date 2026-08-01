<?php $__env->startSection('title', 'Settings'); ?>

<?php $__env->startSection('content'); ?>
    <div class="kk-card" style="max-width:520px;">
        <div class="kk-card-title">Pengaturan Kelas</div>
        <form method="POST" action="<?php echo e(route('settings.update')); ?>">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

            <div class="kk-field">
                <label class="kk-label">Nama Kelas</label>
                <input type="text" name="class_name" class="kk-input" value="<?php echo e(old('class_name', $settings['class_name'])); ?>" required>
            </div>

            <div class="kk-field">
                <label class="kk-label">Nominal Kas Mingguan (Rp)</label>
                <input type="number" name="weekly_amount" class="kk-input" min="500" value="<?php echo e(old('weekly_amount', $settings['weekly_amount'])); ?>" required>
                <div class="kk-help">Berlaku untuk minggu berjalan dan selanjutnya. Riwayat pembayaran yang sudah tercatat tidak berubah.</div>
            </div>

            <div class="kk-field">
                <label class="kk-label">Tanggal Mulai Kas</label>
                <input type="date" name="cash_start_date" class="kk-input" value="<?php echo e(old('cash_start_date', $settings['cash_start_date'])); ?>" required>
                <div class="kk-help">Menentukan Week 1. Sebaiknya tidak diubah setelah ada data pembayaran.</div>
            </div>

            <div class="kk-field">
                <label class="kk-label">Nama Bendahara</label>
                <input type="text" name="treasurer_name" class="kk-input" value="<?php echo e(old('treasurer_name', $settings['treasurer_name'])); ?>">
            </div>

            <div class="kk-field">
                <label class="kk-label">Nama Ketua Kelas</label>
                <input type="text" name="class_leader_name" class="kk-input" value="<?php echo e(old('class_leader_name', $settings['class_leader_name'])); ?>">
            </div>

            <div class="kk-field">
                <label class="kk-label">Nama Sekolah</label>
                <input type="text" name="school_name" class="kk-input" value="<?php echo e(old('school_name', $settings['school_name'])); ?>">
            </div>

            <button type="submit" class="kk-btn">Simpan Pengaturan</button>
        </form>
    </div>

    <div class="kk-card" style="max-width:520px; margin-top:20px;">
        <div class="kk-card-title">Logo Kelas (opsional)</div>
        <form method="POST" action="<?php echo e(route('settings.logo')); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="kk-field">
                <input type="file" name="logo" class="kk-input" accept="image/*">
            </div>
            <button type="submit" class="kk-btn kk-btn-outline">Unggah Logo</button>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Nayla\Downloads\kaskelas\kaskelas\resources\views/settings/index.blade.php ENDPATH**/ ?>