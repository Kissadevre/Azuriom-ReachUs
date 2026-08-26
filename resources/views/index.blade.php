@extends('layouts.app')

@section('title', trans('reachus::messages.title'))

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="h3 mb-3">{{ trans('reachus::messages.title') }}</h1>
            <p class="mb-0">{{ trans('reachus::messages.description') }}</p>
        </div>
    </div>
@endsection
