@extends('layouts.admin')
@section('title')
    {{ __('messages.games') }}
@endsection

@section('content')
    <div class="card">
    <div class="card-header bg-light py-3">
        <div class="row align-items-center justify-content-between">
            <!-- Left Section: Buttons -->
            <div class="col-md-6 d-flex align-items-center">

                <!-- New User Button -->
                <a href="{{ route('games.create') }}" class="btn btn-sm btn-primary ml-2">
                    <i class="fa fa-plus"></i> {{ __('messages.New athkar') }}
                </a>
            </div>

            <!-- Right Section: Search -->
            <div class="col-md-3">
                <form method="get" action="{{ route('games.index') }}" enctype="multipart/form-data" class="d-flex justify-content-end">
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
                @can('game-table')
                    @if (@isset($data) && !@empty($data) && count($data) > 0)
                        <table id="example2" class="table table-bordered table-hover">
                            <thead class="custom_thead">
                                <th>{{ __('messages.Name') }}</th>
                                <th>{{ __('messages.url') }}</th>
                                <th>{{ __('messages.Photo') }}</th>
                                <th></th>
                            </thead>
                            <tbody>
                                @foreach ($data as $info)
                                    <tr>
                                        <td>{{ $info->name }}</td>
                                        <td>{{ $info->url }}</td>

                                        <td>
                                            <div class="image">
                                                <img class="custom_img" src="{{ asset('assets/admin/uploads') . '/' . $info->photo }}">

                                            </div>
                                        </td>

                                        <td>
                                            @can('game-edit')
                                                <a href="{{ route('games.edit', $info->id) }}" class="btn btn-sm btn-primary">
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
