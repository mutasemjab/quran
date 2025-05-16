@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="card-title text-center">{{ __('messages.Show') }}</h4>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h5>{{ __('messages.Name') }}</h5>
                    <p class="text-muted">{{ $student->name }}</p>
                </div>
                <div class="col-md-6">
                    <h5>{{ __('messages.date_of_birth') }}</h5>
                    <p class="text-muted">{{ $student->date_of_birth }}</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <h5>{{ __('messages.identity_number') }}</h5>
                    <p class="text-muted">{{ $student->identity_number }}</p>
                </div>
                <div class="col-md-6">
                    <h5>{{ __('messages.class') }}</h5>
                    <p class="text-muted">{{ $student->clas->name }}</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <h5>{{ __('messages.Search and Select Brothers') }}</h5>
                    <ul class="list-group">
                        @foreach($currentBrothers as $brother)
                            <li class="list-group-item">{{ $brother->name }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-md-6">
                    <h5>{{ __('messages.Photo') }}</h5>
                    @if ($student->photo)
                        <img src="{{ asset('assets/admin/uploads/' . $student->photo) }}" alt="{{ $student->name }}" class="img-thumbnail" width="150">
                    @else
                        <p class="text-muted">{{ __('messages.No Photo Available') }}</p>
                    @endif
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <h5>{{ __('messages.Activate') }}</h5>
                    <p class="text-muted">
                        {{ $student->activate == 1 ? __('messages.Active') : __('messages.Disactive') }}
                    </p>
                </div>
            </div>
            <div class="row text-center mt-4">
                <div class="col-md-12">
                    <a href="{{ route('students.edit', $student->id) }}" class="btn btn-success btn-sm">{{ __('messages.Edit') }}</a>
                    <a href="{{ route('students.index') }}" class="btn btn-secondary btn-sm">{{ __('messages.Cancel') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
