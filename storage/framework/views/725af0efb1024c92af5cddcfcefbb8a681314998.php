<?php $__env->startSection('title'); ?>
<?php echo e(__('messages.class')); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> <?php echo e(__('messages.Edit')); ?>  <?php echo e(__('messages.class')); ?> </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">


            <form action="<?php echo e(route('class.update', $class->id)); ?>" method="post" enctype='multipart/form-data'>
                <div class="row">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>  <?php echo e(__('messages.Name')); ?> </label>
                            <input name="name" id="name" class="form-control" value="<?php echo e(old('name', $class->name)); ?>">
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
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo e(__('messages.Start Date')); ?></label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="<?php echo e(old('start_date', $class->start_date)); ?>">
                            <?php $__errorArgs = ['start_date'];
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
                            <label><?php echo e(__('messages.End Date')); ?></label>
                            <input type="date" name="finish_date" id="finish_date" class="form-control" value="<?php echo e(old('finish_date', $class->finish_date)); ?>">
                            <?php $__errorArgs = ['finish_date'];
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
                            <label><?php echo e(__('messages.Time From')); ?></label>
                            <input type="time" name="time_from" class="form-control" value="<?php echo e(old('time_from', $class->time_from)); ?>">
                            <?php $__errorArgs = ['time_from'];
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
                            <label><?php echo e(__('messages.Time To')); ?></label>
                            <input type="time" name="time_to" class="form-control" value="<?php echo e(old('time_to', $class->time_to)); ?>">
                            <?php $__errorArgs = ['time_to'];
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
                            <label for="day_ids" class="form-label"><?php echo e(__('messages.Select Days')); ?></label>
                            <select name="day_ids[]" id="day_ids" class="form-control select2" multiple="multiple" style="width: 100%;">
                                <?php $__currentLoopData = $weekDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $weekDay): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($weekDay); ?>" <?php echo e(in_array($weekDay, $selectedDays ?? []) ? 'selected' : ''); ?>><?php echo e($weekDay); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    
                  <div class="col-md-6">
                        <div class="form-group">
                            <label for="holidays_ids" class="form-label"><?php echo e(__('messages.Select Holidays')); ?></label>
                            <div id="holiday-checkboxes">
                                <?php $__currentLoopData = $holidayDates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="holidays_ids[]" value="<?php echo e($date); ?>"
                                            <?php echo e(in_array($date, $selectedHolidays ?? []) ? 'checked' : ''); ?> id="holiday_<?php echo e($loop->index); ?>">
                                        <label class="form-check-label" for="holiday_<?php echo e($loop->index); ?>"><?php echo e($date); ?></label>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>



                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button id="do_update_item_cardd" type="submit" class="btn btn-primary btn-sm"> <?php echo e(__('messages.Update')); ?></button>
                            <a href="<?php echo e(route('class.index')); ?>" class="btn btn-sm btn-danger"><?php echo e(__('messages.Cancel')); ?></a>

                        </div>
                    </div>

                </div>
            </form>



        </div>




    </div>
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets/admin/js/class.js')); ?>"></script>


<script>
    function previewImage() {
      var preview = document.getElementById('image-preview');
      var input = document.getElementById('Item_img');
      var file = input.files[0];
      if (file) {
      preview.style.display = "block";
      var reader = new FileReader();
      reader.onload = function() {
        preview.src = reader.result;
      }
      reader.readAsDataURL(file);
    }else{
        preview.style.display = "none";
    }
    }
</script>

<script>
$(document).ready(function () {
    // Load existing holiday dates if days are already selected
    if ($('#day_ids').val() && $('#day_ids').val().length > 0) {
        loadHolidayDates();
    }

    $('#day_ids').on('change', function () {
        loadHolidayDates();
    });

    function loadHolidayDates() {
        const selectedDays = $('#day_ids').val();
        const startDate = $('#start_date').val();
        const finishDate = $('#finish_date').val();

        if (!startDate || !finishDate) {
            alert('Please select both start and finish dates.');
            return;
        }

        if (selectedDays && selectedDays.length > 0) {
            $.ajax({
                url: '<?php echo e(route('getHolidayDates')); ?>',
                type: 'POST',
                data: {
                    weekdays: selectedDays,
                    start_date: startDate,
                    finish_date: finishDate,
                    _token: '<?php echo e(csrf_token()); ?>'
                },
                success: function (dates) {
                 let checkboxes = '';
                    const selectedHolidays = <?php echo json_encode($selectedHolidays ?? []); ?>;

                    dates.forEach(function (date, index) {
                        const isChecked = selectedHolidays.includes(date) ? 'checked' : '';
                        checkboxes += `
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="holidays_ids[]" value="${date}" id="holiday_${index}" ${isChecked}>
                                <label class="form-check-label" for="holiday_${index}">${date}</label>
                            </div>`;
                    });

                    $('#holiday-checkboxes').html(checkboxes);

                },
                error: function () {
                    alert('Could not fetch holiday dates.');
                }
            });
        } else {
            $('#holidays_ids').html('').trigger('change');
        }
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\quran\resources\views/admin/classes/edit.blade.php ENDPATH**/ ?>