@extends('layouts.admin')
@section('title')
    {{ __('messages.students') }}
@endsection

@section('content')

    <div class="card">
           <!-- Header -->
       <div class="card-header bg-light py-3">
        <div class="row align-items-center justify-content-between">
            <!-- Left Section: Buttons -->
            <div class="col-md-6 d-flex align-items-center">
                <!-- Export Button -->
                <a href="{{ route('students.export') }}" class="btn btn-sm btn-info mr-2">
                    <i class="fa fa-file-export"></i> {{ __('messages.Export') }}
                </a>

                <!-- Import Form -->
                <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center">
                    @csrf
                    <input type="file" name="file" accept=".xlsx, .csv" class="form-control-file mr-2" required style="max-width: 230px;">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fa fa-upload"></i> {{ __('messages.Import Students') }}
                    </button>
                </form>

                <!-- New User Button -->
                <a href="{{ route('students.create') }}" class="btn btn-sm btn-primary ml-2">
                    <i class="fa fa-plus"></i> {{ __('messages.New Student') }}
                </a>
            </div>

            <!-- Right Section: Search -->
            <div class="col-md-3">
                <form method="get" action="{{ route('students.index') }}" enctype="multipart/form-data" class="d-flex justify-content-end">
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
                @can('student-table')
                    @if (@isset($data) && !@empty($data) && count($data) > 0)
                        <table id="example2" class="table table-bordered table-hover">
                            <thead class="custom_thead">
                                <th>{{ __('messages.Name') }}</th>
                                <th>{{ __('messages.class') }}</th>
                                <th>{{ __('messages.Email') }}</th>
                                <th>{{ __('messages.username') }}</th>
                                <th>{{ __('messages.activate') }}</th>
                                <th></th>
                            </thead>
                            <tbody>
                                @foreach ($data as $info)
                                    <tr>
                                        <td>{{ $info->name }}</td>
                                        <td>{{ $info->clas->name }}</td>
                                        <td>{{ $info->email }}</td>
                                        <td>{{ $info->username }}</td>
                                        <td>{{ $info->activate ==1 ? "Active" : "Not Active" }}</td>
                                        <td>
                                            @can('student-edit')
                                                <a href="{{ route('students.edit', $info->id) }}" class="btn btn-sm btn-primary">
                                                    {{ __('messages.Edit') }}
                                                </a>
                                            @endcan
                                            
                                                <a href="{{ route('students.show', $info->id) }}" class="btn btn-sm btn-secondary">
                                                    {{ __('messages.Show') }}
                                                </a>
                                            
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
    <script src="{{ asset('assets/admin/js/students.js') }}"></script>
@endsection
