@extends('layouts.admin')
@section('title')
    {{ __('messages.teachers') }}
@endsection

@section('content')
    <div class="card">
    <div class="card-header bg-light py-3">
        <div class="row align-items-center justify-content-between">
            <!-- Left Section: Buttons -->
            <div class="col-md-6 d-flex align-items-center">
          

                <!-- Import Form -->
                <form action="{{ route('teachers.import') }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center">
                    @csrf
                    <input type="file" name="file" accept=".xlsx, .csv" class="form-control-file mr-2" required style="max-width: 230px;">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fa fa-upload"></i> {{ __('messages.Import teachers') }}
                    </button>
                </form>

                <!-- New User Button -->
                <a href="{{ route('teachers.create') }}" class="btn btn-sm btn-primary ml-2">
                    <i class="fa fa-plus"></i> {{ __('messages.New teacher') }}
                </a>
            </div>

            <!-- Right Section: Search -->
            <div class="col-md-3">
                <form method="get" action="{{ route('teachers.index') }}" enctype="multipart/form-data" class="d-flex justify-content-end">
                    @csrf
                    <input autofocus type="text" placeholder="{{ __('messages.Search') }}" name="search" class="form-control mr-2" value="{{ request('search') }}">
                    <button class="btn btn-primary btn-sm">
                        <i class="fa fa-search"></i> 
                    </button>
                </form>
            </div>
        </div>
    </div>
        <div class="card-body">

            <div class="clearfix"></div>

            <div id="ajax_responce_serarchDiv" class="col-md-12">
                @can('teacher-table')
                    @if (@isset($data) && !@empty($data) && count($data) > 0)
                        <table id="example2" class="table table-bordered table-hover">
                            <thead class="custom_thead">
                                <th>{{ __('messages.Name') }}</th>
                                <th>{{ __('messages.email') }}</th>
                                <th>{{ __('messages.activate') }}</th>
                                <th></th>
                            </thead>
                            <tbody>
                                @foreach ($data as $info)
                                    <tr>
                                        <td>{{ $info->name }}</td>
                                        <td>{{ $info->email }}</td>
                                        <td>{{ $info->user->activate ==1 ? "Active" : "Not Active" }}</td>
                                        <td>
                                            @can('teacher-edit')
                                                <a href="{{ route('teachers.edit', $info->id) }}" class="btn btn-sm btn-primary">
                                                    {{ __('messages.Edit') }}
                                                </a>
                                            @endcan
                                            
                                                
                                            
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        {{ $data->appends(['search' => $searchQuery,])->links() }}
                    @else
                        <div class="alert alert-danger">
                            {{ __('messages.No_data') }}
                        </div>
                    @endif
                @endcan
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/admin/js/teachers.js') }}"></script>
@endsection
