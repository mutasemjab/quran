@extends('layouts.admin')
@section('title')
    {{ __('messages.Edit') }} {{ __('messages.Lecture') }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.Edit') }} {{ __('messages.Lecture') }} </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">

            <form action="{{ route('lectures.update', $lecture->id) }}" method="post" enctype='multipart/form-data'>
            @csrf
            @method('PUT')

            <div class="row">

            <!-- Content for Teacher -->
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{ __('messages.content For teacher') }}</label>
                    <input name="content_teacher" id="content_teacher" class="form-control" value="{{ old('content_teacher', $lecture->content_teacher ?? '') }}">
                    @error('content_teacher')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Content for Student -->
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{ __('messages.content For Student') }}</label>
                    <input name="content_student" id="content_student" class="form-control" value="{{ old('content_student', $lecture->content_student ?? '') }}">
                    @error('content_student')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Lecture Type -->
            <div class="col-md-6">
                <label for="type">{{ __('messages.Lecture Type') }}</label>
                <select name="type" id="type" class="form-control">
                    <option value="1" {{ old('type', $lecture->type ?? '') == 1 ? 'selected' : '' }}>{{ __('messages.Quran') }}</option>
                    <option value="2" {{ old('type', $lecture->type ?? '') == 2 ? 'selected' : '' }}>{{ __('messages.Hadeth') }}</option>
                    <option value="3" {{ old('type', $lecture->type ?? '') == 3 ? 'selected' : '' }}>{{ __('messages.Manhag') }}</option>
                </select>
                @error('type')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Add Lecture Row Button -->
            <div class="col-md-12 mt-3">
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
                    @if(isset($lecture) && $lecture->classDates)
                        @foreach($lecture->classDates as $classLecture)
                            <tr class="class-date-row">
                                <td>
                                    <select name="classes[]" class="form-control class-dropdown">
                                        <option value="">-- Select Class --</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ $classLecture->class_id == $class->id ? 'selected' : '' }}>
                                                {{ $class->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="dates[]" class="form-control date-dropdown">
                                        @php
                                            $selectedClass = $classes->firstWhere('id', $classLecture->class_id);
                                            $generatedDates = $selectedClass ? $selectedClass->dates_without_holidays : [];
                                        @endphp
                                        @foreach($generatedDates as $date)
                                            <option value="{{ $date }}" {{ $classLecture->date == $date ? 'selected' : '' }}>
                                                {{ $date }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger delete-row">−</button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <!-- Empty row by default -->
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
                    @endif
                </tbody>
            </table>

            @foreach($classes as $class)
                <span class="generated_dates" data-class="{{ $class->id }}" data-dates='@json($class->dates_without_holidays)' hidden></span>
            @endforeach

            <!-- Submit & Cancel Buttons -->
            <div class="col-md-12 text-center mt-3">
                <button type="submit" class="btn btn-primary btn-sm">{{ __('messages.Submit') }}</button>
                <a href="{{ route('lectures.index') }}" class="btn btn-sm btn-danger">{{ __('messages.Cancel') }}</a>
            </div>

            </div>
            </form>

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
            const $emptyRow = $(`
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
            `);
            $('#class-date-table tbody').append($emptyRow);
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
