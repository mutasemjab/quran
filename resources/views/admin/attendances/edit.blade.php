@extends('layouts.admin')
@section('title')
    {{ __('messages.Edit') }} {{ __('messages.attendances') }}
@endsection



@section('contentheaderlink')
    <a href="{{ route('attendances.index') }}"> {{ __('messages.attendances') }} </a>
@endsection

@section('contentheaderactive')
    {{ __('messages.Edit') }}
@endsection


@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.Edit') }} {{ __('messages.attendances') }} </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">




            <form action="{{ route('attendances.update', $data['id']) }}" method="POST" enctype='multipart/form-data'>
                <div class="row">
                    @csrf
                    @method('PUT')
            
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.date') }}</label>
                            <input type="date" name="date_of_off" id="date_of_off" class="form-control"
                                value="{{ old('date_of_off', $data['date_of_off']) }}" required>
                            @error('date_of_off')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
            
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.type_of_off') }}</label>
                            <select name="type_of_off" id="type_of_off" class="form-control" required>
                                <option value="">{{ __('messages.select') }}</option>
                                <option @if ($data->type_of_off == 1) selected="selected" @endif value="1">{{ __('messages.off') }}</option>
                                <option @if ($data->type_of_off == 2) selected="selected" @endif value="2">{{ __('messages.late') }}</option>
                            </select>
                            @error('type_of_off')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
            
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.description') }}</label>
                            <textarea name="description" id="notes" class="form-control" rows="12">{{ old('description', $data->description) }}</textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
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
            
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user">{{ __('messages.select_student') }}</label>
                            <select name="user" id="user" class="form-control" required>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" @if($user->id == $data->user_id) selected @endif>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
            
                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm">{{ __('messages.update') }}</button>
                            <a href="{{ route('attendances.index') }}" class="btn btn-sm btn-danger">{{ __('messages.cancel') }}</a>
                        </div>
                    </div>
                </div>
            </form>
            

        </div>




    </div>
    </div>
@endsection


@section('script')
    <script src="{{ asset('assets/admin/js/attendances.js') }}"></script>
@endsection
