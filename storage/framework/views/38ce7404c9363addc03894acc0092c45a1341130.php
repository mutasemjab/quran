<?php $__env->startSection('title'); ?>
    <?php echo e(__('messages.attendances')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> <?php echo e(__('messages.attendances')); ?> </h3>
            
        </div>
        <div class="card-body">
       

            <div class="clearfix"></div>

            <div id="ajax_responce_serarchDiv" class="col-md-12">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('attendance-table')): ?>
                    <?php if(@isset($data) && !@empty($data) && count($data) > 0): ?>
                        <table id="example2" class="table table-bordered table-hover">
                            <thead class="custom_thead">
                                <th><?php echo e(__('messages.Name')); ?></th>
                                <th><?php echo e(__('messages.date_of_off')); ?></th>
                                <th><?php echo e(__('messages.type_of_off')); ?></th>
                                <th><?php echo e(__('messages.description')); ?></th>
                               
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($info->user->name); ?></td>
                                        <td><?php echo e($info->date_of_off); ?></td>
                                        <td><?php echo e($info->type_of_off == 1 ? "Off" : "Late"); ?></td>
                                        <td><?php echo e($info->description ?? null); ?></td>
                                        <td>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('attendence-edit')): ?>
                                            <a href="<?php echo e(route('attendances.edit', $info->id)); ?>" class="btn btn-sm  btn-primary"><?php echo e(__('messages.Edit')); ?></a>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('attendence-delete')): ?>
                                            <form action="<?php echo e(route('attendances.destroy', $info->id)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-danger"><?php echo e(__('messages.Delete')); ?></button>
                                            </form>
                                            <?php endif; ?>
                
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                        <br>
                        <?php echo e($data->links()); ?>

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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u167651649/domains/mutasemjaber.online/public_html/quran/resources/views/admin/attendances/index.blade.php ENDPATH**/ ?>