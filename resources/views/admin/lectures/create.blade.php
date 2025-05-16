@extends('layouts.admin')
@section('title')
{{ __('messages.lectures') }}
@endsection


@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.Add_New') }}  {{ __('messages.lectures') }} </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">


            <form action="{{ route('lectures.store') }}" method="post" enctype='multipart/form-data'>
                <div class="row">
                    @csrf

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>  {{ __('messages.content For teacher') }} </label>
                            <input name="content_teacher" id="content_teacher" class="form-control" value="{{ old('content_teacher') }}">
                            @error('content')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                 
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>  {{ __('messages.content For Student') }} </label>
                            <input name="content_student" id="content_student" class="form-control" value="{{ old('content_student') }}">
                            @error('content')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="type">{{ __('messages.Lecture Type') }}</label>
                        <select name="type" id="type" class="form-control">
                            <option value="1">{{ __('messages.Quran') }}</option>
                            <option value="2">{{ __('messages.Hadeth') }}</option>
                            <option value="3">{{ __('messages.Manhag') }}</option>
                        </select>
                        @error('type')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    

                    <!-- Add Lecture Button -->
                    <div class="col-md-12" style="margin-top: 19px;">                        
                            <button type="button" class="btn btn-success" id="add-lecture-btn">
                                + {{ __('messages.Add Lecture') }}
                            </button>                        
                    </div>

                    <!-- Class-Date Table -->
                    <table class="table" id="class-date-table">
                        <thead>
                            <tr>
                                <th>{{ __('messages.Select classes') }}</th>
                                <th>{{ __('messages.Select date') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="class-date-row">
                                <td>
                                    <select name="classes[]" class="form-control class-dropdown">
                                        <option value="">-- Select Class --</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                                        @endforeach
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
                    @foreach($classes as $class)
                        <span class="generated_dates" data-class="{{ $class->id }}" data-dates='@json($class->dates_without_holidays)' hidden></span>
                    @endforeach

                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm"> {{ __('messages.Submit') }}</button>
                            <a href="{{ route('lectures.index') }}" class="btn btn-sm btn-danger">{{ __('messages.Cancel') }}</a>

                        </div>
                    </div>

                </div>
            </form>



        </div>




    </div>
    </div>
@endsection
@section('script')

<script>
    $(document).ready(function() {
        // Build mapping of class IDs to their dates
        let classDates = {};
        $('.generated_dates').each(function() {
            const classId = $(this).data('class');
            const dates = $(this).data('dates');
            classDates[classId] = dates;
        });

        function updateDateDropdown($row, classId) {
            const $dateDropdown = $row.find('.date-dropdown');
            $dateDropdown.empty().append('<option value="">-- Select Date --</option>');

            if (classId && classDates[classId]) {
                classDates[classId].forEach(function(date) {
                    $dateDropdown.append(`<option value="${date}">${date}</option>`);
                });
                $dateDropdown.prop('disabled', false);
            } else {
                $dateDropdown.prop('disabled', true);
            }
        }

        // Class change triggers date update
        $('#class-date-table').on('change', '.class-dropdown', function() {
            const $row = $(this).closest('tr');
            const selectedClass = $(this).val();
            updateDateDropdown($row, selectedClass);
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
@endsection



