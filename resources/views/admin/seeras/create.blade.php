@extends('layouts.admin')
@section('title')
{{ __('messages.seeras') }}
@endsection


@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.Add_New') }}  {{ __('messages.seeras') }} </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">


            <form action="{{ route('seeras.store') }}" method="post" enctype='multipart/form-data'>
                <div class="row">
                    @csrf

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>  {{ __('messages.Name') }} </label>
                            <input name="name" id="name" class="form-control" value="{{ old('name') }}">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>



                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.description_ar') }}</label>
                            <textarea name="description" id="notes" class="form-control" rows="8"></textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                  
                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.description_en') }}</label>
                            <textarea name="description_en" id="notes" class="form-control" rows="8"></textarea>
                            @error('description_en')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                   
                    <div class="form-group">
                        <label for="classes">Classes</label>
                        <select name="classes[]" id="classes" class="form-control select2" multiple>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm"> {{ __('messages.Submit') }}</button>
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
        $('.select2').select2();
    });
</script>
@endsection
