@extends('layouts.admin')
@section('title')
    {{ __('messages.grades') }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.grades') }} </h3>
            {{-- <a href="{{ route('grades.create') }}" class="btn btn-sm btn-success"> {{ __('messages.New') }} {{
            __('messages.grades') }}</a> --}}
        </div>
        <div class="card-body">
       

            <div class="clearfix"></div>

            <div id="ajax_responce_serarchDiv" class="col-md-12">
                @can('attendance-table')
                    @if (@isset($data) && !@empty($data) && count($data) > 0)
                        <table id="example2" class="table table-bordered table-hover">
                            <thead class="custom_thead">
                                <th>{{ __('messages.Name of class') }}</th>
                                <th>{{ __('messages.Name of lecture') }}</th>
                                <th>{{ __('messages.Name of student') }}</th>
                                <th>{{ __('messages.Name') }}</th>
                                <th>{{ __('messages.grade') }}</th>
                               
                            </thead>
                            <tbody>
                                @foreach ($data as $info)
                                    <tr>
                                        <td>{{ $info->clas->name }}</td>
                                        <td>{{ $info->lecture->name }}</td>
                                        <td>{{ $info->user->name }}</td>
                                        <td>{{ $info->name }}</td>
                                        <td>{{ $info->grade ?? null  }}</td>
                                        <td>
                                            @can('grade-edit')
                                            <a href="{{ route('grades.edit', $info->id) }}" class="btn btn-sm  btn-primary">{{
                                                __('messages.Edit') }}</a>
                                            @endcan
                                            @can('grade-delete')
                                            <form action="{{ route('grades.destroy', $info->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">{{ __('messages.Delete') }}</button>
                                            </form>
                                            @endcan
                
                                        </td>
                                      
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        {{ $data->links() }}
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
