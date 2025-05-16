@extends('layouts.admin')
@section('title')
    {{ __('messages.exams') }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.exams') }} </h3>
          <a href="{{ route('exams.create') }}" class="btn btn-sm btn-success"> {{ __('messages.New') }} {{
            __('messages.exams') }}</a> 
        </div>
        <div class="card-body">
       

            <div class="clearfix"></div>

            <div id="ajax_responce_serarchDiv" class="col-md-12">
                @can('attendance-table')
                    @if (@isset($data) && !@empty($data) && count($data) > 0)
                        <table id="example2" class="table table-bordered table-hover">
                            <thead class="custom_thead">
                                <th>{{ __('messages.Name of class') }}</th>
                                <th>{{ __('messages.Name') }}</th>
                                <th>{{ __('messages.Exam Date') }}</th>
                               
                            </thead>
                            <tbody>
                                @foreach ($data as $info)
                                    <tr>
                                        <td>{{ $info->clas->name ?? null }}</td>
                                        <td>{{ $info->name }}</td>
                                        <td>{{ $info->exam_date   }}</td>
                                        <td>
                                            @can('exam-edit')
                                            <a href="{{ route('exams.edit', $info->id) }}" class="btn btn-sm  btn-primary">{{
                                                __('messages.Edit') }}</a>
                                            @endcan
                                            @can('exam-add-questions')
                                            <a href="{{ route('exams.addQuestions', $info->id) }}" class="btn btn-sm btn-success">
                                                {{ __('messages.Add Questions') }}
                                            </a>
                                             @endcan

                                            @can('exam-delete')
                                            <form action="{{ route('exams.destroy', $info->id) }}" method="POST">
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

