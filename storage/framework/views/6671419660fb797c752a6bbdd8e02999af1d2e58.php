<?php $__env->startSection('title'); ?>
    <?php echo e(__('messages.seeras')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
    <div class="card-header bg-light py-3">
        <div class="row align-items-center justify-content-between">
            <!-- Left Section: Buttons -->
            <div class="col-md-6 d-flex align-items-center">
        
                <!-- New User Button -->
                <a href="<?php echo e(route('seeras.create')); ?>" class="btn btn-sm btn-primary ml-2">
                    <i class="fa fa-plus"></i> <?php echo e(__('messages.New seera')); ?>

                </a>
            </div>

            <!-- Right Section: Search -->
            <div class="col-md-3">
                <form method="get" action="<?php echo e(route('seeras.index')); ?>" enctype="multipart/form-data" class="d-flex justify-content-end">
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
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('seera-table')): ?>
                    <?php if(@isset($data) && !@empty($data) && count($data) > 0): ?>
                        <table id="example2" class="table table-bordered table-hover">
                            <thead class="custom_thead">
                                <th><?php echo e(__('messages.Name')); ?></th>
                                <th><?php echo e(__('messages.description')); ?></th>
                                <th></th>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($info->name); ?></td>
                                        <td><?php echo e($info->description); ?></td>
                                        
                                        
                                        <td>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('seera-edit')): ?>
                                                <a href="<?php echo e(route('seeras.edit', $info->id)); ?>" class="btn btn-sm btn-primary">
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u167651649/domains/mutasemjaber.online/public_html/quran/resources/views/admin/seeras/index.blade.php ENDPATH**/ ?>