<?php $__env->startSection('title'); ?>
<?php echo e(__('messages.lectures')); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> <?php echo e(__('messages.Add_New')); ?>  <?php echo e(__('messages.lectures')); ?> </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">


            <form action="<?php echo e(route('lectures.store')); ?>" method="post" enctype='multipart/form-data'>
                <div class="row">
                    <?php echo csrf_field(); ?>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>  <?php echo e(__('messages.content For teacher')); ?> </label>
                            <input name="content_teacher" id="content_teacher" class="form-control" value="<?php echo e(old('content_teacher')); ?>">
                            <?php $__errorArgs = ['content'];
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
                 
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>  <?php echo e(__('messages.content For Student')); ?> </label>
                            <input name="content_student" id="content_student" class="form-control" value="<?php echo e(old('content_student')); ?>">
                            <?php $__errorArgs = ['content'];
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

                    <div class="col-md-6">
                        <label for="type"><?php echo e(__('messages.Lecture Type')); ?></label>
                        <select name="type" id="type" class="form-control">
                            <option value="1"><?php echo e(__('messages.Quran')); ?></option>
                            <option value="2"><?php echo e(__('messages.Hadeth')); ?></option>
                            <option value="3"><?php echo e(__('messages.Manhag')); ?></option>
                        </select>
                        <?php $__errorArgs = ['type'];
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
                    

                    <!-- Add Lecture Button -->
                    <div class="col-md-12" style="margin-top: 19px;">                        
                            <button type="button" class="btn btn-success" id="add-lecture-btn">
                                + <?php echo e(__('messages.Add Lecture')); ?>

                            </button>                        
                    </div>

                    <!-- Class-Date Table -->
                    <table class="table" id="class-date-table">
                        <thead>
                            <tr>
                                <th><?php echo e(__('messages.Select classes')); ?></th>
                                <th><?php echo e(__('messages.Select date')); ?></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="class-date-row">
                                <td>
                                    <select name="classes[]" class="form-control class-dropdown">
                                        <option value="">-- Select Class --</option>
                                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="dates[]" class="form-control date-dropdown" disabled>
                                        <option value="">-- Select Date --</option>
                                    </select>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger delete-row">−</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="generated_dates" data-class="<?php echo e($class->id); ?>" data-dates='<?php echo json_encode($class->dates_without_holidays, 15, 512) ?>' hidden></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm"> <?php echo e(__('messages.Submit')); ?></button>
                            <a href="<?php echo e(route('lectures.index')); ?>" class="btn btn-sm btn-danger"><?php echo e(__('messages.Cancel')); ?></a>

                        </div>
                    </div>

                </div>
            </form>



        </div>




    </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script>
    const usedDatesMap = <?php echo json_encode($usedDates, 15, 512) ?>; // key = "classId-type"
</script>

<script>
    $(document).ready(function() {
        // Build mapping of class IDs to their dates
        let classDates = {};
        $('.generated_dates').each(function() {
            const classId = $(this).data('class');
            const dates = $(this).data('dates');
            classDates[classId] = dates;
        });

       function updateDateDropdown($row, classId, type) {
            const $dateDropdown = $row.find('.date-dropdown');
            $dateDropdown.empty().append('<option value="">-- Select Date --</option>');

            if (!classId || !type || !classDates[classId]) {
                $dateDropdown.prop('disabled', true);
                return;
            }

            // Key to match usedDates
            const key = `${classId}-${type}`;
            const usedDates = usedDatesMap[key] || [];

            classDates[classId].forEach(function(date) {
                if (!usedDates.includes(date)) {
                    $dateDropdown.append(`<option value="${date}">${date}</option>`);
                }
            });

            $dateDropdown.prop('disabled', false);
        }

        $('#type').on('change', function () {
            $('#class-date-table tbody tr').each(function () {
                const $row = $(this);
                const selectedClass = $row.find('.class-dropdown').val();
                updateDateDropdown($row, selectedClass, $('#type').val());
            });
        });


        // Class change triggers date update
        $('#class-date-table').on('change', '.class-dropdown', function() {
            const $row = $(this).closest('tr');
            const selectedClass = $(this).val();
            const selectedType = $('#type').val();
            updateDateDropdown($row, selectedClass, selectedType);

        });

        // Add new lecture row
        $('#add-lecture-btn').click(function() {
            const $newRow = $('#class-date-table tbody tr:first').clone();
            $newRow.find('select').val('');
            $newRow.find('.date-dropdown').prop('disabled', true);
            $('#class-date-table tbody').append($newRow);
        });

        // Delete lecture row
        $('#class-date-table').on('click', '.delete-row', function() {
            const rowCount = $('#class-date-table tbody tr').length;
            if (rowCount > 1) {
                $(this).closest('tr').remove();
            } else {
                alert('At least one lecture must remain.');
            }
        });
    });
    </script>
<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\quran\resources\views/admin/lectures/create.blade.php ENDPATH**/ ?>