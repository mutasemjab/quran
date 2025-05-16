@extends('layouts.admin')
@section('title')
    {{ __('messages.Edit') }} {{ __('messages.lessons') }}
@endsection



@section('contentheaderlink')
    <a href="{{ route('lessons.index') }}"> {{ __('messages.lessons') }} </a>
@endsection

@section('contentheaderactive')
    {{ __('messages.Edit') }}
@endsection


@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.Edit') }} {{ __('messages.lessons') }} </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">




                <form action="{{ route('lessons.update', $data['id']) }}" method="POST" enctype='multipart/form-data'>
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

                    <!-- Class selection (multiple) -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="clas_id">Select Classes</label>
                            <select name="clas_id[]" id="clas_id" class="form-control" multiple>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}"
                                        {{ in_array($class->id, $lessonClasses) ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        </div>

                        
                

                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm"> {{ __('messages.Update') }}</button>
                            <a href="{{ route('lessons.index') }}" class="btn btn-sm btn-danger">{{ __('messages.Cancel') }}</a>

                        </div>
                    </div>


            </div>

            </form>

        </div>




    </div>
    </div>
@endsection

