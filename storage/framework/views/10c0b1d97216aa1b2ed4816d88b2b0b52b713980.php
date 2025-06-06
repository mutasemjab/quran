<?php $__env->startSection('title'); ?>
    <?php echo e(__('messages.teachers')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
    <div class="card-header bg-light py-3">
        <div class="row align-items-center justify-content-between">
            <!-- Left Section: Buttons -->
            <div class="col-md-6 d-flex align-items-center">
          

                <!-- Import Form -->
                <form action="<?php echo e(route('teachers.import')); ?>" method="POST" enctype="multipart/form-data" class="d-flex align-items-center">
                    <?php echo csrf_field(); ?>
                    <input type="file" name="file" accept=".xlsx, .csv" class="form-control-file mr-2" required style="max-width: 230px;">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fa fa-upload"></i> <?php echo e(__('messages.Import teachers')); ?>

                    </button>
                </form>

                <!-- New User Button -->
                <a href="<?php echo e(route('teachers.create')); ?>" class="btn btn-sm btn-primary ml-2">
                    <i class="fa fa-plus"></i> <?php echo e(__('messages.New teacher')); ?>

                </a>
            </div>

            <!-- Right Section: Search -->
            <div class="col-md-3">
                <form method="get" action="<?php echo e(route('teachers.index')); ?>" enctype="multipart/form-data" class="d-flex justify-content-end">
                    <?php echo csrf_field(); ?>
                    <input autofocus type="text" placeholder="<?php echo e(__('messages.Search')); ?>" name="search" class="form-control mr-2" value="<?php echo e(request('search')); ?>">
                    <button class="btn btn-primary btn-sm">
                        <i class="fa fa-search"></i> 
                    </button>
                </form>
            </div>
        </div>
    </div>
        <div class="card-body">

            <div class="clearfix"></div>

            <div id="ajax_responce_serarchDiv" class="col-md-12">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('teacher-table')): ?>
                    <?php if(@isset($data) && !@empty($data) && count($data) > 0): ?>
                        <table id="example2" class="table table-bordered table-hover">
                            <thead class="custom_thead">
                                <th><?php echo e(__('messages.Name')); ?></th>
                                <th><?php echo e(__('messages.email')); ?></th>
                                <th><?php echo e(__('messages.activate')); ?></th>
                                <th></th>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($info->name); ?></td>
                                        <td><?php echo e($info->email); ?></td>
                                        <td><?php echo e($info->user->activate ==1 ? "Active" : "Not Active"); ?></td>
                                        <td>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('teacher-edit')): ?>
                                                <a href="<?php echo e(route('teachers.edit', $info->id)); ?>" class="btn btn-sm btn-primary">
                                                    <?php echo e(__('messages.Edit')); ?>

                                                </a>
                                            <?php endif; ?>
                                            
                                                
                                            
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                        <br>
                        <?php echo e($data->appends(['search' => $searchQuery,])->links()); ?>

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
    <script src="<?php echo e(asset('assets/admin/js/teachers.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\quran\resources\views/admin/teachers/index.blade.php ENDPATH**/ ?>