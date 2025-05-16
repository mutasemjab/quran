@extends('layouts.admin')
@section('title')
    {{ __('messages.Edit') }} {{ __('messages.class') }}
@endsection

@section('contentheaderlink')
    <a href="{{ route('class.index') }}"> {{ __('messages.class') }} </a>
@endsection

@section('contentheaderactive')
    {{ __('messages.Edit') }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.Edit') }} {{ __('messages.class') }} </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <form action="{{ route('class.update', $data['id']) }}" method="POST" enctype='multipart/form-data'>
                <div class="row">
                    @csrf
                    @method('PUT')

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.Name') }}</label>
                            <input name="name" id="name" class="form-control"
                                value="{{ old('name', $data['name']) }}">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary btn-sm"> {{ __('messages.Update') }}</button>
                            <a href="{{ route('class.index') }}" class="btn btn-sm btn-danger">{{ __('messages.Cancel') }}</a>
                        </div>
                    </div>
                </div>
            </form>

            <hr>

            <h4>{{ __('messages.Weekly Dates') }}</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>{{ __('messages.Week Date') }}</th>
                        <th>{{ __('messages.Lesson') }}</th>
                        <th>{{ __('messages.Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($weeklyDates as $weeklyDate)
                        <tr>
                            <td>{{ $weeklyDate->week_date }}</td>
                            <td>
                                <form action="{{ route('class.assignLesson', $weeklyDate->id) }}" method="POST">
                                    @csrf
                                    <select name="lesson_id" class="form-control">
                                        <option value="">{{ __('messages.Select Lesson') }}</option>
                                        @foreach ($lessons as $lesson)
                                            <option value="{{ $lesson->id }}"
                                                @if ($weeklyDate->lessons->pluck('lesson_id')->contains($lesson->id)) selected @endif>
                                                {{ $lesson->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    
                                    <button type="submit" class="btn btn-primary btn-sm mt-1">{{ __('messages.Assign') }}</button>
                                </form>
                            </td>
                            <td>
                                <form action="{{ route('class.removeWeeklyDate', $weeklyDate->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">{{ __('messages.Remove') }}</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
