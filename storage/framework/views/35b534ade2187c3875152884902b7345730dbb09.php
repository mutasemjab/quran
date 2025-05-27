<?php $__env->startSection('title'); ?>
    <?php echo e(__('messages.lectures')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> <?php echo e(__('messages.lectures')); ?> </h3>
            <a href="<?php echo e(route('lectures.create')); ?>" class="btn btn-sm btn-success"> <?php echo e(__('messages.New')); ?>

                <?php echo e(__('messages.lectures')); ?></a>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('lectures.index')); ?>" class="mb-4 row">
                <div class="col-md-4">
                    <label><?php echo e(__('messages.Class')); ?></label>
                    <select name="class_id" class="form-control select2">
                        <option value=""><?php echo e(__('messages.Select Class')); ?></option>
                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($class->id); ?>" <?php echo e(request('class_id') == $class->id ? 'selected' : ''); ?>>
                                <?php echo e($class->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label><?php echo e(__('messages.Date')); ?></label>
                    <input type="date" name="date" value="<?php echo e(request('date')); ?>" class="form-control" />
                </div>

                <div class="col-md-4 mt-4">
                    <button type="submit" class="btn btn-primary mt-2"><?php echo e(__('messages.Search')); ?></button>
                    <a href="<?php echo e(route('lectures.index')); ?>" class="btn btn-secondary mt-2"><?php echo e(__('messages.Reset')); ?></a>
                </div>
            </form>


            <div class="clearfix"></div>

            <div id="ajax_responce_serarchDiv" class="col-md-12">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('lecture-table')): ?>
                    <?php if(@isset($data) && !@empty($data) && count($data) > 0): ?>
                        <table id="example2" class="table table-bordered table-hover">
                            <thead class="custom_thead">
                                <th>ID</th>
                                <th><?php echo e(__('messages.Class')); ?></th>
                                <th><?php echo e(__('messages.Date')); ?></th>
                                <th><?php echo e(__('messages.content_teacher')); ?></th>
                                <th><?php echo e(__('messages.content_student')); ?></th>

                                <th></th>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($info->id); ?></td>

                                        
                                        <td>
                                            <?php $__currentLoopData = $info->classDates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="badge bg-info"><?php echo e($cd->clas->name ?? '-'); ?></span>
                                                <br>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>

                                        
                                        <td>
                                            <?php $__currentLoopData = $info->classDates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="badge bg-secondary"><?php echo e($cd->date); ?></span>
                                                <br>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>

                                        <td><?php echo e($info->content_teacher); ?></td>
                                        <td><?php echo e($info->content_student); ?></td>

                                        <td>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('lecture-edit')): ?>
                                                <a href="<?php echo e(route('lectures.edit', $info->id)); ?>" class="btn btn-sm btn-primary">
                                                    <?php echo e(__('messages.Edit')); ?>

                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="alert alert-danger">
                            <?php echo e(__('messages.No_data')); ?>

                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets/admin/js/class.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\quran\resources\views/admin/lectures/index.blade.php ENDPATH**/ ?>