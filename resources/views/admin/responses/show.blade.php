@extends('admin.layouts.admin')

@section('title', trans('reachus::admin.responses.show_title', ['id' => $message->id]))

@include('reachus::_styles')

@section('content')
    @php
        $methodIcons = [
            'whatsapp' => 'bi bi-whatsapp',
            'telegram' => 'bi bi-telegram',
            'email' => 'bi bi-envelope',
            'discord' => 'bi bi-discord',
        ];
    @endphp

    <div class="reachus-shell">
        <header class="reachus-admin-header">
            <div class="reachus-admin-heading">
                <span class="reachus-admin-icon"><i class="bi bi-chat-square-text" aria-hidden="true"></i></span>
                <div>
                    <h1>{{ trans('reachus::admin.responses.show_title', ['id' => $message->id]) }}</h1>
                    <p>{{ trans('reachus::admin.responses.received_from', ['name' => $message->name]) }}</p>
                </div>
            </div>
            <a href="{{ route('reachus.admin.responses.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1" aria-hidden="true"></i> {{ trans('messages.actions.back') }}
            </a>
        </header>

        <div class="card reachus-admin-card mb-4">
            <div class="reachus-admin-card-header">
                <div>
                    <h2 class="h6 mb-1">{{ trans('reachus::admin.responses.contact_information') }}</h2>
                    <p class="small text-body-secondary mb-0">{{ trans('reachus::admin.responses.contact_information_help') }}</p>
                </div>
                <span class="reachus-status-badge is-read"><i class="bi bi-envelope-open" aria-hidden="true"></i> {{ trans('reachus::admin.responses.read') }}</span>
            </div>

            <div class="card-body p-4">
                <div class="reachus-detail-grid mb-4">
                    <div class="reachus-detail-item">
                        <small>{{ trans('reachus::admin.responses.name') }}</small>
                        <strong><i class="bi bi-person me-1 text-primary" aria-hidden="true"></i> {{ $message->name }}</strong>
                    </div>
                    <div class="reachus-detail-item">
                        <small>{{ trans('reachus::admin.responses.method') }}</small>
                        <span class="reachus-method-badge">
                            <i class="{{ $methodIcons[$message->contact_method] ?? 'bi bi-chat' }}" aria-hidden="true"></i>
                            {{ trans('reachus::messages.methods.'.$message->contact_method) }}
                        </span>
                    </div>
                    <div class="reachus-detail-item">
                        <small>{{ trans('reachus::admin.responses.received_at') }}</small>
                        <strong><i class="bi bi-clock me-1 text-primary" aria-hidden="true"></i> {{ format_date($message->created_at, true) }}</strong>
                    </div>
                </div>

                <div class="mb-4">
                    <h3 class="h6 mb-2">{{ trans('reachus::admin.responses.contact_value') }}</h3>
                    <span class="reachus-contact-value">
                        <i class="{{ $methodIcons[$message->contact_method] ?? 'bi bi-at' }}" aria-hidden="true"></i>
                        {{ $message->contact_value }}
                    </span>
                </div>

                <div>
                    <h3 class="h6 mb-2">{{ trans('reachus::admin.responses.reason') }}</h3>
                    <div class="reachus-message-body text-break">{{ $message->reason }}</div>
                </div>
            </div>

            <div class="card-footer bg-body d-flex flex-wrap justify-content-between align-items-center gap-2 p-3">
                <span class="small text-body-secondary"><i class="bi bi-hash" aria-hidden="true"></i> {{ $message->id }}</span>
                <div class="d-flex flex-wrap gap-2">
                    <form method="POST" action="{{ route('reachus.admin.responses.unread', $message) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="bi bi-envelope me-1" aria-hidden="true"></i> {{ trans('reachus::admin.responses.mark_unread') }}
                        </button>
                    </form>
                    <a href="{{ route('reachus.admin.responses.destroy', $message) }}" class="btn btn-outline-danger" data-confirm="delete">
                        <i class="bi bi-trash me-1" aria-hidden="true"></i> {{ trans('messages.actions.delete') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
