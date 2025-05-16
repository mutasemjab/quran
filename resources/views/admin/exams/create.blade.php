@extends('layouts.admin')
@section('title')
{{ __('messages.exams') }}
@endsection


@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.Add_New') }}  {{ __('messages.exams') }} </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">


            <form action="{{ route('exams.store') }}" method="post" enctype='multipart/form-data'>
                <div class="row">
                    @csrf

                    <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Exam Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    </div>

                    <div class="col-md-6">
                    <div class="form-group">
                        <label for="clas_id">Class</label>
                        <select name="clas_id" id="clas_id" class="form-control" required>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    </div>

                    <div class="col-md-6">
                    <div class="form-group">
                        <label for="exam_date">Exam Date</label>
                        <input type="date" name="exam_date" id="exam_date" class="form-control" required>
                    </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm"> {{ __('messages.Submit') }}</button>
                            <a href="{{ route('exams.index') }}" class="btn btn-sm btn-danger">{{ __('messages.Cancel') }}</a>

                        </div>
                    </div>

                </div>
            </form>



        </div>




    </div>
    </div>
@endsection



