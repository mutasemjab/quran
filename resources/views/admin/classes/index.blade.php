@extends('layouts.admin')
@section('title')
    {{ __('messages.class') }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.class') }} </h3>
            <a href="{{ route('class.create') }}" class="btn btn-sm btn-success"> {{ __('messages.New') }} {{
            __('messages.class') }}</a>
        </div>
        <div class="card-body">
       

            <div class="clearfix"></div>

            <div id="ajax_responce_serarchDiv" class="col-md-12">
                @can('class-table')
                    @if (@isset($data) && !@empty($data) && count($data) > 0)
                        <table id="example2" class="table table-bordered table-hover">
                            <thead class="custom_thead">
                                <th>ID</th>
                                <th>{{ __('messages.Name') }}</th>
                               
                                <th></th>
                            </thead>
                            <tbody>
                                @foreach ($data as $info)
                                    <tr>
                                        <td>{{ $info->id }}</td>
                                        <td>{{ $info->name }}</td>
                                      
                                        <td>
                                            @can('class-edit')
                                                <a href="{{ route('class.edit', $info->id) }}" class="btn btn-sm btn-primary">
                                                    {{ __('messages.Edit') }}
                                                </a>
                                            @endcan
                                           
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                   
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
    <script src="{{ asset('assets/admin/js/class.js') }}"></script>
@endsection
