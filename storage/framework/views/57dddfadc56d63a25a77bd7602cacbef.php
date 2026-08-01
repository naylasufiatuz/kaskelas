<?php $__env->startSection('title', 'Edit Transaksi'); ?>

<?php $__env->startSection('content'); ?>
    <div class="kk-card" style="max-width:520px;">
        <div class="kk-card-title">Edit <?php echo e($transaction->type === 'income' ? 'Pemasukan' : 'Pengeluaran'); ?></div>
        <form method="POST" action="<?php echo e(route('transactions.update', $transaction)); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

            <div class="kk-field">
                <label class="kk-label">Tanggal</label>
                <input type="date" name="transaction_date" class="kk-input" value="<?php echo e(old('transaction_date', $transaction->transaction_date->toDateString())); ?>" required>
            </div>

            <div class="kk-field">
                <label class="kk-label">Kategori</label>
                <select name="category_id" class="kk-select" required>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($c->id); ?>" <?php echo e(old('category_id', $transaction->category_id) == $c->id ? 'selected' : ''); ?>><?php echo e($c->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="kk-field">
                <label class="kk-label">Nominal (Rp)</label>
                <input type="number" name="amount" class="kk-input" min="1" value="<?php echo e(old('amount', $transaction->amount)); ?>" required>
            </div>

            <div class="kk-field">
                <label class="kk-label">Deskripsi</label>
                <input type="text" name="description" class="kk-input" value="<?php echo e(old('description', $transaction->description)); ?>" required>
            </div>

            <div class="kk-field">
                <label class="kk-label">Catatan</label>
                <textarea name="notes" class="kk-input" rows="3"><?php echo e(old('notes', $transaction->notes)); ?></textarea>
            </div>

            <?php if($transaction->type === 'expense'): ?>
            <div class="kk-field">
                <label class="kk-label">Bukti/Nota</label>
                <?php if($transaction->receipt_path): ?>
                    <div style="margin-bottom:6px;"><a href="<?php echo e(route('transactions.receipt', $transaction)); ?>" target="_blank" style="color:var(--kk-primary-dark);">Lihat bukti saat ini</a></div>
                <?php endif; ?>
                <input type="file" name="receipt" class="kk-input" accept=".jpg,.jpeg,.png,.pdf">
                <div class="kk-help">Kosongkan jika tidak ingin mengganti bukti.</div>
            </div>
            <?php endif; ?>

            <div style="display:flex; gap:8px; justify-content:flex-end;">
                <a href="<?php echo e(route('transactions.index')); ?>" class="kk-btn kk-btn-outline">Batal</a>
                <button type="submit" class="kk-btn">Simpan Perubahan</button>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Nayla\Downloads\kaskelas\kaskelas\resources\views/transactions/edit.blade.php ENDPATH**/ ?>