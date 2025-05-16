@extends('layouts.admin')
@section('title')
    {{ __('messages.Edit') }} {{ __('messages.exams') }}
@endsection



@section('contentheaderlink')
    <a href="{{ route('exams.index') }}"> {{ __('messages.exams') }} </a>
@endsection

@section('contentheaderactive')
    {{ __('messages.Edit') }}
@endsection


@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.Edit') }} {{ __('messages.exams') }} </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">




            <form action="{{ route('exams.update', $data['id']) }}" method="POST" enctype='multipart/form-data'>
                <div class="row">
                    @csrf
                    @method('PUT')
            
                
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Exam Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $data['name']) }}" required>
                        </div>
                        </div>
    
                  
    
                        <div class="col-md-6">
                        <div class="form-group">
                            <label for="exam_date">Exam Date</label>
                            <input type="date" name="exam_date" id="exam_date" class="form-control" value="{{ old('exam_date', $data['exam_date']) }}" required>
                        </div>
                        </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="clas">{{ __('messages.select_class') }}</label>
                            <select name="clas" id="clas" class="form-control" required>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" @if($class->id == $data->clas_id) selected @endif>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
            
                    
            
                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm">{{ __('messages.update') }}</button>
                            <a href="{{ route('exams.index') }}" class="btn btn-sm btn-danger">{{ __('messages.cancel') }}</a>
                        </div>
                    </div>
                </div>
            </form>
            

        </div>




    </div>
    </div>
@endsection


@section('script')
    <script src="{{ asset('assets/admin/js/exams.js') }}"></script>
@endsection
