@extends('layouts.admin')
@section('title')
    {{ __('messages.Edit') }} {{ __('messages.parentStudents') }}
@endsection



@section('contentheaderlink')
    <a href="{{ route('parentStudents.index') }}"> {{ __('messages.parentStudents') }} </a>
@endsection

@section('contentheaderactive')
    {{ __('messages.Edit') }}
@endsection


@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.Edit') }} {{ __('messages.parentStudents') }} </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">




                <form action="{{ route('parentStudents.update', $data['id']) }}" method="POST" enctype='multipart/form-data'>
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
                            <label> {{ __('messages.email') }}</label>
                            <input name="email" id="email" class="form-control"
                                value="{{ old('email', $data['email']) }}" />
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.username') }}</label>
                            <input name="username" id="username" class="form-control"
                                value="{{ old('username', $data['username']) }}" />
                            @error('username')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.Password') }}</label>
                            <input name="password" id="password" class="form-control"
                                value="{{ old('password') }}" />
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                      <!-- Class selection (multiple) -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user_id">Select Sons</label>
                                <select name="user_id[]" id="user_id" class="form-control" multiple>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ in_array($user->id, $ParentStudentUsers) ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>




                    <div class="form-group col-md-6">
                        <label for="photo">Photo</label>
                        <input type="file" name="photo" id="photo" class="form-control-file">
                        @if ($data->photo)
                            <img src="{{ asset('assets/admin/uploads').'/'.$data->photo }}" id="image-preview" alt="Selected Image" height="50px" width="50px">
                        @else
                            <img src="" id="image-preview" alt="Selected Image" height="50px" width="50px" style="display: none;">
                        @endif
                        @error('photo')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>



                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.Activate') }}</label>
                            <select name="activate" id="activate" class="form-control">
                                <option value="">Select</option>
                                <option @if ($data->user->activate == 1) selected="selected" @endif value="1">Active</option>
                                <option @if ($data->user->activate == 2) selected="selected" @endif value="2">Inactive</option>
                            </select>
                            @error('activate')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm"> {{ __('messages.Update') }}</button>
                            <a href="{{ route('parentStudents.index') }}" class="btn btn-sm btn-danger">{{ __('messages.Cancel') }}</a>

                        </div>
                    </div>


            </div>

            </form>

        </div>




    </div>
    </div>
@endsection


