@extends('layouts.admin')
@section('title')
    {{ __('messages.lectures') }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.lectures') }} </h3>
            <a href="{{ route('lectures.create') }}" class="btn btn-sm btn-success"> {{ __('messages.New') }}
                {{ __('messages.lectures') }}</a>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('lectures.index') }}" class="mb-4 row">
                <div class="col-md-4">
                    <label>{{ __('messages.Class') }}</label>
                    <select name="class_id" class="form-control select2">
                        <option value="">{{ __('messages.Select Class') }}</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label>{{ __('messages.Date') }}</label>
                    <input type="date" name="date" value="{{ request('date') }}" class="form-control" />
                </div>

                <div class="col-md-4 mt-4">
                    <button type="submit" class="btn btn-primary mt-2">{{ __('messages.Search') }}</button>
                    <a href="{{ route('lectures.index') }}" class="btn btn-secondary mt-2">{{ __('messages.Reset') }}</a>
                </div>
            </form>


            <div class="clearfix"></div>

            <div id="ajax_responce_serarchDiv" class="col-md-12">
                @can('lecture-table')
                    @if (@isset($data) && !@empty($data) && count($data) > 0)
                        <table id="example2" class="table table-bordered table-hover">
                            <thead class="custom_thead">
                                <th>ID</th>
                                <th>{{ __('messages.Class') }}</th>
                                <th>{{ __('messages.Date') }}</th>
                                <th>{{ __('messages.content_teacher') }}</th>
                                <th>{{ __('messages.content_student') }}</th>

                                <th></th>
                            </thead>
                            <tbody>
                                @foreach ($data as $info)
                                    <tr>
                                        <td>{{ $info->id }}</td>

                                        {{-- List class names (comma separated) --}}
                                        <td>
                                            @foreach ($info->classDates as $cd)
                                                <span class="badge bg-info">{{ $cd->clas->name ?? '-' }}</span>
                                                <br>
                                            @endforeach
                                        </td>

                                        {{-- List dates --}}
                                        <td>
                                            @foreach ($info->classDates as $cd)
                                                <span class="badge bg-secondary">{{ $cd->date }}</span>
                                                <br>
                                            @endforeach
                                        </td>

                                        <td>{{ $info->content_teacher }}</td>
                                        <td>{{ $info->content_student }}</td>

                                        <td>
                                            @can('lecture-edit')
                                                <a href="{{ route('lectures.edit', $info->id) }}" class="btn btn-sm btn-primary">
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
