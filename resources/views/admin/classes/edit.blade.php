@extends('layouts.admin')
@section('title')
{{ __('messages.class') }}
@endsection


@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.Edit') }}  {{ __('messages.class') }} </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">


            <form action="{{ route('class.update', $class->id) }}" method="post" enctype='multipart/form-data'>
                <div class="row">
                    @csrf
                    @method('PUT')

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>  {{ __('messages.Name') }} </label>
                            <input name="name" id="name" class="form-control" value="{{ old('name', $class->name) }}">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.Start Date') }}</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', $class->start_date) }}">
                            @error('start_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.End Date') }}</label>
                            <input type="date" name="finish_date" id="finish_date" class="form-control" value="{{ old('finish_date', $class->finish_date) }}">
                            @error('finish_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="day_ids" class="form-label">{{ __('messages.Select Days') }}</label>
                            <select name="day_ids[]" id="day_ids" class="form-control select2" multiple="multiple" style="width: 100%;">
                                @foreach($weekDays as $weekDay)
                                    <option value="{{ $weekDay }}" {{ in_array($weekDay, $selectedDays ?? []) ? 'selected' : '' }}>{{ $weekDay }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="holidays_ids" class="form-label">{{ __('messages.Select Holidays') }}</label>
                            <select name="holidays_ids[]" id="holidays_ids" class="form-control select2" multiple="multiple" style="width: 100%;">
                                @foreach($holidayDates as $date)
                                    <option value="{{ $date }}" {{ in_array($date, $selectedHolidays ?? []) ? 'selected' : '' }}>{{ $date }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button id="do_update_item_cardd" type="submit" class="btn btn-primary btn-sm"> {{ __('messages.Update') }}</button>
                            <a href="{{ route('class.index') }}" class="btn btn-sm btn-danger">{{ __('messages.Cancel') }}</a>

                        </div>
                    </div>

                </div>
            </form>



        </div>




    </div>
    </div>
@endsection


@section('script')
    <script src="{{ asset('assets/admin/js/class.js') }}"></script>


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
                url: '{{ route('getHolidayDates') }}',
                type: 'POST',
                data: {
                    weekdays: selectedDays,
                    start_date: startDate,
                    finish_date: finishDate,
                    _token: '{{ csrf_token() }}'
                },
                success: function (dates) {
                    let options = '';
                    const selectedHolidays = {!! json_encode($selectedHolidays ?? []) !!};
                    
                    dates.forEach(function (date) {
                        const isSelected = selectedHolidays.includes(date) ? 'selected' : '';
                        options += `<option value="${date}" ${isSelected}>${date}</option>`;
                    });

                    $('#holidays_ids').html(options).trigger('change');
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
@endsection