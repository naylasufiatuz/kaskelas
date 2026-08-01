<?php $__env->startSection('title', 'Transaksi'); ?>

<?php $__env->startSection('content'); ?>
    <?php $canManage = auth()->user()->isTreasurer(); ?>

    <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; margin-bottom:16px;">
        <form method="GET" style="display:flex; gap:8px; flex-wrap:wrap;">
            <input type="text" name="search" class="kk-input" placeholder="Cari deskripsi..." value="<?php echo e(request('search')); ?>" style="width:180px;">
            <select name="type" class="kk-select" style="width:140px;">
                <option value="">Semua Tipe</option>
                <option value="income" <?php echo e(request('type') === 'income' ? 'selected' : ''); ?>>Pemasukan</option>
                <option value="expense" <?php echo e(request('type') === 'expense' ? 'selected' : ''); ?>>Pengeluaran</option>
            </select>
            <select name="category_id" class="kk-select" style="width:160px;">
                <option value="">Semua Kategori</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($c->id); ?>" <?php echo e(request('category_id') == $c->id ? 'selected' : ''); ?>><?php echo e($c->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <input type="date" name="from" class="kk-input" value="<?php echo e(request('from')); ?>" style="width:150px;">
            <input type="date" name="to" class="kk-input" value="<?php echo e(request('to')); ?>" style="width:150px;">
            <button class="kk-btn kk-btn-outline" type="submit">Filter</button>
        </form>
        <?php if($canManage): ?>
            <div style="display:flex; gap:8px;">
                <a href="<?php echo e(route('transactions.create', ['type' => 'income'])); ?>" class="kk-btn kk-btn-outline">+ Pemasukan</a>
                <a href="<?php echo e(route('transactions.create', ['type' => 'expense'])); ?>" class="kk-btn">+ Pengeluaran</a>
            </div>
        <?php endif; ?>
    </div>

    <div class="kk-table-wrap">
        <?php if($transactions->isEmpty()): ?>
            <div class="kk-empty"><p>Belum ada transaksi</p></div>
        <?php else: ?>
        <table class="kk-table">
            <thead>
                <tr><th>Tanggal</th><th>Tipe</th><th>Kategori</th><th>Deskripsi</th><th>Nominal</th><th>Dibuat Oleh</th><?php if($canManage): ?><th>Aksi</th><?php endif; ?></tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($t->transaction_date->translatedFormat('d M Y')); ?></td>
                        <td><span class="kk-badge <?php echo e($t->type === 'income' ? 'kk-badge-income' : 'kk-badge-expense'); ?>"><?php echo e($t->type === 'income' ? 'Income' : 'Expense'); ?></span></td>
                        <td><?php echo e($t->category->name ?? '-'); ?></td>
                        <td>
                            <?php echo e($t->description); ?>

                            <?php if($t->receipt_path): ?>
                                <a href="<?php echo e(route('transactions.receipt', $t)); ?>" target="_blank" style="font-size:12px; color:var(--kk-primary-dark); margin-left:6px;">[bukti]</a>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($t->type === 'income' ? '+ ' : '- '); ?><?php echo e(rupiah($t->amount)); ?></td>
                        <td><?php echo e($t->creator->name ?? '-'); ?></td>
                        <?php if($canManage): ?>
                        <td style="display:flex; gap:6px;">
                            <a href="<?php echo e(route('transactions.edit', $t)); ?>" class="kk-btn kk-btn-sm kk-btn-outline">Edit</a>
                            <form method="POST" action="<?php echo e(route('transactions.destroy', $t)); ?>" data-confirm="Apakah kamu yakin ingin menghapus transaksi ini?">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="kk-btn kk-btn-sm kk-btn-danger" type="submit">Hapus</button>
                            </form>
                        </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
    <div style="margin-top:16px;"><?php echo e($transactions->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Nayla\Downloads\kaskelas\kaskelas\resources\views/transactions/index.blade.php ENDPATH**/ ?>