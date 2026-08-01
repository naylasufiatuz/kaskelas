<?php $__env->startSection('title', 'Data Siswa'); ?>

<?php $__env->startSection('content'); ?>
    <?php $canManage = auth()->user()->isTreasurer(); ?>

    <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; margin-bottom:16px;">
        <form method="GET" style="display:flex; gap:8px;">
            <input type="text" name="search" class="kk-input" placeholder="Cari nama atau NIS..." value="<?php echo e(request('search')); ?>" style="width:240px;">
            <button class="kk-btn kk-btn-outline" type="submit">Cari</button>
        </form>
        <?php if($canManage): ?>
            <button class="kk-btn" type="button" onclick="document.getElementById('addStudentModal').style.display='flex'">+ Tambah Siswa</button>
        <?php endif; ?>
    </div>

    <div class="kk-table-wrap">
        <?php if($students->isEmpty()): ?>
            <div class="kk-empty">
                <p>Belum ada data siswa</p>
                <?php if($canManage): ?>
                    <button class="kk-btn" type="button" onclick="document.getElementById('addStudentModal').style.display='flex'">+ Tambah Siswa</button>
                <?php endif; ?>
            </div>
        <?php else: ?>
        <table class="kk-table">
            <thead>
                <tr>
                    <th>No</th><th>Nama</th><th>NIS</th><th>Status</th>
                    <?php if($canManage): ?><th>Aksi</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($students->firstItem() + $i); ?></td>
                        <td><?php echo e($student->name); ?></td>
                        <td><?php echo e($student->student_number ?? '-'); ?></td>
                        <td><span class="kk-badge <?php echo e($student->active ? 'kk-badge-paid' : 'kk-badge-unpaid'); ?>"><?php echo e($student->active ? 'Aktif' : 'Nonaktif'); ?></span></td>
                        <?php if($canManage): ?>
                        <td style="display:flex; gap:6px;">
                            <button class="kk-btn kk-btn-sm kk-btn-outline" type="button" onclick="document.getElementById('editStudentModal<?php echo e($student->id); ?>').style.display='flex'">Edit</button>
                            <form method="POST" action="<?php echo e(route('students.destroy', $student)); ?>" data-confirm="Apakah kamu yakin ingin menghapus siswa ini?">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="kk-btn kk-btn-sm kk-btn-danger" type="submit">Hapus</button>
                            </form>
                        </td>
                        <?php endif; ?>
                    </tr>

                    <?php if($canManage): ?>
                    <div class="kk-modal-overlay" id="editStudentModal<?php echo e($student->id); ?>" style="display:none;">
                        <div class="kk-modal">
                            <div class="kk-card-title">Edit Siswa</div>
                            <form method="POST" action="<?php echo e(route('students.update', $student)); ?>">
                                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                <div class="kk-field"><label class="kk-label">Nama</label><input class="kk-input" name="name" value="<?php echo e($student->name); ?>" required></div>
                                <div class="kk-field"><label class="kk-label">NIS</label><input class="kk-input" name="student_number" value="<?php echo e($student->student_number); ?>"></div>
                                <div class="kk-field"><label class="kk-label">No. HP</label><input class="kk-input" name="phone" value="<?php echo e($student->phone); ?>"></div>
                                <div class="kk-field">
                                    <label class="kk-label">
                                        <input type="checkbox" name="active" value="1" <?php echo e($student->active ? 'checked' : ''); ?>> Aktif
                                    </label>
                                </div>
                                <div style="display:flex; gap:8px; justify-content:flex-end;">
                                    <button type="button" class="kk-btn kk-btn-outline" onclick="document.getElementById('editStudentModal<?php echo e($student->id); ?>').style.display='none'">Batal</button>
                                    <button type="submit" class="kk-btn">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

    <div style="margin-top:16px;"><?php echo e($students->links()); ?></div>

    <?php if($canManage): ?>
    <div class="kk-modal-overlay" id="addStudentModal" style="display:none;">
        <div class="kk-modal">
            <div class="kk-card-title">Tambah Siswa</div>
            <form method="POST" action="<?php echo e(route('students.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="kk-field"><label class="kk-label">Nama</label><input class="kk-input" name="name" required></div>
                <div class="kk-field"><label class="kk-label">NIS</label><input class="kk-input" name="student_number"></div>
                <div class="kk-field"><label class="kk-label">No. HP</label><input class="kk-input" name="phone"></div>
                <div class="kk-field"><label class="kk-label"><input type="checkbox" name="active" value="1" checked> Aktif</label></div>
                <div style="display:flex; gap:8px; justify-content:flex-end;">
                    <button type="button" class="kk-btn kk-btn-outline" onclick="document.getElementById('addStudentModal').style.display='none'">Batal</button>
                    <button type="submit" class="kk-btn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('head'); ?>
<style>
.kk-modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.35); z-index: 50; align-items: center; justify-content: center; }
.kk-modal { background: #fff; border-radius: 14px; padding: 24px; width: 100%; max-width: 380px; max-height: 90vh; overflow-y: auto; }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Nayla\Downloads\kaskelas\kaskelas\resources\views/students/index.blade.php ENDPATH**/ ?>