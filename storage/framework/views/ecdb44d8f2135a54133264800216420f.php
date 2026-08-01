<?php $__env->startSection('title', $type === 'income' ? 'Tambah Pemasukan' : 'Tambah Pengeluaran'); ?>

<?php $__env->startSection('content'); ?>
    <div class="kk-card" style="max-width:520px;">
        <div class="kk-card-title"><?php echo e($type === 'income' ? 'Tambah Pemasukan' : 'Tambah Pengeluaran'); ?></div>
        <form method="POST" action="<?php echo e(route('transactions.store')); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="type" value="<?php echo e($type); ?>">

            <div class="kk-field">
                <label class="kk-label">Tanggal</label>
                <input type="date" name="transaction_date" class="kk-input" value="<?php echo e(old('transaction_date', now()->toDateString())); ?>" required>
            </div>

            <div class="kk-field">
                <label class="kk-label">Kategori</label>
                <select name="category_id" class="kk-select" required>
                    <option value="">Pilih kategori</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($c->id); ?>" <?php echo e(old('category_id') == $c->id ? 'selected' : ''); ?>><?php echo e($c->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="kk-field">
                <label class="kk-label">Nominal (Rp)</label>
                <input type="number" name="amount" class="kk-input" min="1" value="<?php echo e(old('amount')); ?>" required>
            </div>

            <div class="kk-field">
                <label class="kk-label">Deskripsi</label>
                <input type="text" name="description" class="kk-input" value="<?php echo e(old('description')); ?>" required>
            </div>

            <div class="kk-field">
                <label class="kk-label">Catatan</label>
                <textarea name="notes" class="kk-input" rows="3"><?php echo e(old('notes')); ?></textarea>
            </div>

            <?php if($type === 'expense'): ?>
            <div class="kk-field">
                <label class="kk-label">Upload Bukti/Nota (JPG, PNG, PDF)</label>
                <input type="file" name="receipt" class="kk-input" accept=".jpg,.jpeg,.png,.pdf">
            </div>
            <?php endif; ?>

            <div style="display:flex; gap:8px; justify-content:flex-end;">
                <a href="<?php echo e(route('transactions.index')); ?>" class="kk-btn kk-btn-outline">Batal</a>
                <button type="submit" class="kk-btn">Simpan</button>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Nayla\Downloads\kaskelas\kaskelas\resources\views/transactions/create.blade.php ENDPATH**/ ?>