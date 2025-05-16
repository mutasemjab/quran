@extends('layouts.admin')
@section('title')
    {{ __('messages.Edit') }} {{ __('messages.seeras') }}
@endsection



@section('contentheaderlink')
    <a href="{{ route('seeras.index') }}"> {{ __('messages.seeras') }} </a>
@endsection

@section('contentheaderactive')
    {{ __('messages.Edit') }}
@endsection


@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.Edit') }} {{ __('messages.seeras') }} </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">

                <form action="{{ route('seeras.update', $data['id']) }}" method="POST" enctype='multipart/form-data'>
                    <div class="row">
                    @csrf
                    @method('PUT')

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.Name') }}</label>
                            <input name="name" id="name" class="form-control"
                                value="{{ old('name', $data['name']) }}">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.description_ar') }}</label>
                            <textarea name="description" id="notes" class="form-control" rows="8">{{$data['description']}}</textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
            
                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.description_ar') }}</label>
                            <textarea name="description_en" id="notes" class="form-control" rows="8">{{$data['description_en']}}</textarea>
                            @error('description_en')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                                 

                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm"> {{ __('messages.Update') }}</button>
                            <a href="{{ route('seeras.index') }}" class="btn btn-sm btn-danger">{{ __('messages.Cancel') }}</a>

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
        function loadLessons(classIds) {
            $.ajax({
                url: "{{ route('get.lessons.by.classes') }}",
                type: "GET",
                data: { class_ids: classIds },
                success: function(data) {
                    $('#lesson_id').empty();
                    $.each(data, function(key, lesson) {
                        $('#lesson_id').append(
                            `<option value="${lesson.id}" ${@json($teacherLessons)}.includes(lesson.id) ? 'selected' : ''}>${lesson.name}</option>`
                        );
                    });
                },
                error: function() {
                    alert('Failed to load lessons');
                }
            });
        }

        // Initial load for already selected classes
        let selectedClasses = $('#clas_id').val();
        if (selectedClasses.length > 0) {
            loadLessons(selectedClasses);
        }

        // Load lessons on class selection change
        $('#clas_id').on('change', function() {
            let selectedClasses = $(this).val();
            if (selectedClasses.length > 0) {
                loadLessons(selectedClasses);
            } else {
                $('#lesson_id').empty();
            }
        });
    });
</script>

@endsection
