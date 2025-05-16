<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="card-title text-center"><?php echo e(__('messages.Show')); ?></h4>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h5><?php echo e(__('messages.Name')); ?></h5>
                    <p class="text-muted"><?php echo e($student->name); ?></p>
                </div>
                <div class="col-md-6">
                    <h5><?php echo e(__('messages.date_of_birth')); ?></h5>
                    <p class="text-muted"><?php echo e($student->date_of_birth); ?></p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <h5><?php echo e(__('messages.identity_number')); ?></h5>
                    <p class="text-muted"><?php echo e($student->identity_number); ?></p>
                </div>
                <div class="col-md-6">
                    <h5><?php echo e(__('messages.class')); ?></h5>
                    <p class="text-muted"><?php echo e($student->clas->name); ?></p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <h5><?php echo e(__('messages.Search and Select Brothers')); ?></h5>
                    <ul class="list-group">
                        <?php $__currentLoopData = $currentBrothers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brother): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="list-group-item"><?php echo e($brother->name); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h5><?php echo e(__('messages.Photo')); ?></h5>
                    <?php if($student->photo): ?>
                        <img src="<?php echo e(asset('assets/admin/uploads/' . $student->photo)); ?>" alt="<?php echo e($student->name); ?>" class="img-thumbnail" width="150">
                    <?php else: ?>
                        <p class="text-muted"><?php echo e(__('messages.No Photo Available')); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <h5><?php echo e(__('messages.Activate')); ?></h5>
                    <p class="text-muted">
                        <?php echo e($student->activate == 1 ? __('messages.Active') : __('messages.Disactive')); ?>

                    </p>
                </div>
            </div>
            <div class="row text-center mt-4">
                <div class="col-md-12">
                    <a href="<?php echo e(route('students.edit', $student->id)); ?>" class="btn btn-success btn-sm"><?php echo e(__('messages.Edit')); ?></a>
                    <a href="<?php echo e(route('students.index')); ?>" class="btn btn-secondary btn-sm"><?php echo e(__('messages.Cancel')); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u167651649/domains/mutasemjaber.online/public_html/quran/resources/views/admin/students/show.blade.php ENDPATH**/ ?>