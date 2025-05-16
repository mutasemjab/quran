@extends('layouts.admin')
@section('title')
    {{ __('messages.attendances') }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.attendances') }} </h3>
            {{-- <a href="{{ route('attendances.create') }}" class="btn btn-sm btn-success"> {{ __('messages.New') }} {{
            __('messages.attendances') }}</a> --}}
        </div>
        <div class="card-body">
       

            <div class="clearfix"></div>

            <div id="ajax_responce_serarchDiv" class="col-md-12">
                @can('attendance-table')
                    @if (@isset($data) && !@empty($data) && count($data) > 0)
                        <table id="example2" class="table table-bordered table-hover">
                            <thead class="custom_thead">
                                <th>{{ __('messages.Name') }}</th>
                                <th>{{ __('messages.date_of_off') }}</th>
                                <th>{{ __('messages.type_of_off') }}</th>
                                <th>{{ __('messages.description') }}</th>
                               
                            </thead>
                            <tbody>
                                @foreach ($data as $info)
                                    <tr>
                                        <td>{{ $info->user->name }}</td>
                                        <td>{{ $info->date_of_off }}</td>
                                        <td>{{ $info->type_of_off == 1 ? "Off" : "Late" }}</td>
                                        <td>{{ $info->description ?? null  }}</td>
                                        <td>
                                            @can('attendence-edit')
                                            <a href="{{ route('attendances.edit', $info->id) }}" class="btn btn-sm  btn-primary">{{
                                                __('messages.Edit') }}</a>
                                            @endcan
                                            @can('attendence-delete')
                                            <form action="{{ route('attendances.destroy', $info->id) }}" method="POST">
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
