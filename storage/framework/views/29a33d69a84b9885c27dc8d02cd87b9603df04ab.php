<?php $__env->startSection('title'); ?>
    <?php echo e(__('messages.Edit')); ?> <?php echo e(__('messages.class')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('contentheaderlink'); ?>
    <a href="<?php echo e(route('class.index')); ?>"> <?php echo e(__('messages.class')); ?> </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contentheaderactive'); ?>
    <?php echo e(__('messages.Edit')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> <?php echo e(__('messages.Edit')); ?> <?php echo e(__('messages.class')); ?> </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <form action="<?php echo e(route('class.update', $data['id'])); ?>" method="POST" enctype='multipart/form-data'>
                <div class="row">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo e(__('messages.Name')); ?></label>
                            <input name="name" id="name" class="form-control"
                                value="<?php echo e(old('name', $data['name'])); ?>">
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary btn-sm"> <?php echo e(__('messages.Update')); ?></button>
                            <a href="<?php echo e(route('class.index')); ?>" class="btn btn-sm btn-danger"><?php echo e(__('messages.Cancel')); ?></a>
                        </div>
                    </div>
                </div>
            </form>

            <hr>

            <h4><?php echo e(__('messages.Weekly Dates')); ?></h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><?php echo e(__('messages.Week Date')); ?></th>
                        <th><?php echo e(__('messages.Lesson')); ?></th>
                        <th><?php echo e(__('messages.Action')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $weeklyDates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $weeklyDate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($weeklyDate->week_date); ?></td>
                            <td>
                                <form action="<?php echo e(route('class.assignLesson', $weeklyDate->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <select name="lesson_id" class="form-control">
                                        <option value=""><?php echo e(__('messages.Select Lesson')); ?></option>
                                        <?php $__currentLoopData = $lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($lesson->id); ?>"
                                                <?php if($weeklyDate->lessons->pluck('lesson_id')->contains($lesson->id)): ?> selected <?php endif; ?>>
                                                <?php echo e($lesson->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    
                                    <button type="submit" class="btn btn-primary btn-sm mt-1"><?php echo e(__('messages.Assign')); ?></button>
                                </form>
                            </td>
                            <td>
                                <form action="<?php echo e(route('class.removeWeeklyDate', $weeklyDate->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger btn-sm"><?php echo e(__('messages.Remove')); ?></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u167651649/domains/mutasemjaber.online/public_html/quran/resources/views/admin/classes/edit.blade.php ENDPATH**/ ?>