@extends('layouts.admin')
@section('title')
notifications
@endsection


@section('content')

      <div class="card">
        <div class="card-header">
          <h3 class="card-title card_title_center"> Add New notifications   </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">


<div class="row justify-content-center">
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <form action="{{route('notifications.send')}}" method="post">
                    @csrf
                    <div class="form-group mt-0">
                        <label for="title">Title</label>
                        <input type="text" class="form-control @if($errors->has('title')) is-invalid @endif" id="title" name="title" value="{{old('title')}}">
                        @if($errors->has('title'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('title') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="body">Body</label>
                        <textarea name="body" id="body" class="form-control @if($errors->has('body')) is-invalid @endif">{{old('body')}}</textarea>
                        @if($errors->has('body'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('body') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="type">Notification Type</label>
                        <select name="type" id="type" class="form-control @if($errors->has('type')) is-invalid @endif" onchange="toggleUserField()">
                            <option value="0">All Users</option>
                            <option value="1">All Regular Users</option>
                            <option value="2">All Parents</option>
                            <option value="3">All Teachers</option>
                            <option value="4">Specific User</option>
                        </select>
                        @if($errors->has('type'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('type') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group" id="userField" style="display: none;">
                        <label for="user_id">Select User</label>
                        <select name="user_id" id="user_id" class="form-control">
                            <option value="">Select a User</option>
                            @foreach($users as $user)
                                <option value="{{$user->id}}">{{$user->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="text-right mt-3">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Send Notification</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




            </div>




        </div>
      </div>






@endsection


@section('script')

<script>
    function toggleUserField() {
        var type = document.getElementById("type").value;
        var userField = document.getElementById("userField");
        if (type == "4") {
            userField.style.display = "block";
        } else {
            userField.style.display = "none";
        }
    }
</script>


@endsection






