@extends('admin.layouts.admin')

@section('title', trans('reachus::admin.responses.show_title', ['id' => $message->id]))

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h2 class="h5 mb-0">{{ trans('reachus::admin.responses.show_title', ['id' => $message->id]) }}</h2>
            <span class="text-body-secondary">{{ format_date($message->created_at, true) }}</span>
        </div>
        <div class="card-body">
            <dl class="row mb-4">
                <dt class="col-sm-3">{{ trans('reachus::admin.responses.name') }}</dt>
                <dd class="col-sm-9">{{ $message->name }}</dd>

                <dt class="col-sm-3">{{ trans('reachus::admin.responses.method') }}</dt>
                <dd class="col-sm-9">{{ trans('reachus::messages.methods.'.$message->contact_method) }}</dd>

                <dt class="col-sm-3">{{ trans('reachus::admin.responses.contact_value') }}</dt>
                <dd class="col-sm-9 text-break"><code>{{ $message->contact_value }}</code></dd>
            </dl>

            <h3 class="h6">{{ trans('reachus::admin.responses.reason') }}</h3>
            <div class="border rounded p-3 bg-body-tertiary text-break" style="white-space: pre-wrap">{{ $message->reason }}</div>
        </div>
        <div class="card-footer d-flex flex-wrap justify-content-between gap-2">
            <a href="{{ route('reachus.admin.responses.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> {{ trans('messages.actions.back') }}
            </a>
            <div class="d-flex gap-2">
                <form method="POST" action="{{ route('reachus.admin.responses.unread', $message) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-envelope" aria-hidden="true"></i> {{ trans('reachus::admin.responses.mark_unread') }}
                    </button>
                </form>
                <a href="{{ route('reachus.admin.responses.destroy', $message) }}" class="btn btn-danger" data-confirm="delete">
                    <i class="bi bi-trash" aria-hidden="true"></i> {{ trans('messages.actions.delete') }}
                </a>
            </div>
        </div>
    </div>
@endsection
