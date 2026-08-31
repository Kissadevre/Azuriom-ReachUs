@extends('admin.layouts.admin')

@section('title', trans('reachus::admin.responses.title'))

@include('reachus::_styles')

@section('content')
    <div class="reachus-shell">
        <header class="reachus-admin-header">
            <div class="reachus-admin-heading">
                <span class="reachus-admin-icon"><i class="bi bi-inbox" aria-hidden="true"></i></span>
                <div>
                    <h1>{{ trans('reachus::admin.responses.title') }}</h1>
                    <p>{{ trans('reachus::admin.responses.description') }}</p>
                </div>
            </div>

            <div class="reachus-stat-grid" aria-label="{{ trans('reachus::admin.responses.summary') }}">
                <div class="reachus-stat">
                    <span class="reachus-stat-icon"><i class="bi bi-chat-left-text" aria-hidden="true"></i></span>
                    <div>
                        <strong>{{ $messages->total() }}</strong>
                        <small>{{ trans('reachus::admin.responses.total') }}</small>
                    </div>
                </div>
                <div class="reachus-stat">
                    <span class="reachus-stat-icon"><i class="bi bi-envelope-exclamation" aria-hidden="true"></i></span>
                    <div>
                        <strong>{{ $unreadCount }}</strong>
                        <small>{{ trans('reachus::admin.responses.unread') }}</small>
                    </div>
                </div>
            </div>
        </header>

        <div class="card reachus-admin-card mb-4">
            <div class="reachus-admin-card-header">
                <div>
                    <h2 class="h6 mb-1">{{ trans('reachus::admin.responses.inbox') }}</h2>
                    <p class="small text-body-secondary mb-0">{{ trans('reachus::admin.responses.inbox_help') }}</p>
                </div>
                @if($unreadCount > 0)
                    <span class="reachus-status-badge is-unread"><i class="bi bi-circle-fill" aria-hidden="true"></i> {{ trans('reachus::admin.responses.unread_count', ['count' => $unreadCount]) }}</span>
                @endif
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 reachus-admin-table">
                    <thead>
                        <tr>
                            <th>{{ trans('reachus::admin.responses.status') }}</th>
                            <th>{{ trans('reachus::admin.responses.name') }}</th>
                            <th>{{ trans('reachus::admin.responses.method') }}</th>
                            <th>{{ trans('reachus::admin.responses.reason') }}</th>
                            <th>{{ trans('reachus::admin.responses.received_at') }}</th>
                            <th class="text-end">{{ trans('messages.fields.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($messages as $message)
                            <tr class="@if($message->read_at === null) reachus-unread-row fw-semibold @endif">
                                <td>
                                    <span class="reachus-status-badge {{ $message->read_at === null ? 'is-unread' : 'is-read' }}">
                                        <i class="bi {{ $message->read_at === null ? 'bi-envelope-fill' : 'bi-envelope-open' }}" aria-hidden="true"></i>
                                        {{ trans('reachus::admin.responses.'.($message->read_at === null ? 'unread' : 'read')) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('reachus.admin.responses.show', $message) }}" class="text-body fw-semibold text-decoration-none">{{ $message->name }}</a>
                                </td>
                                <td>
                                    <span class="reachus-method-badge">
                                        <i class="{{ $message->contact_channel_icon }}" aria-hidden="true"></i>
                                        {{ $message->contact_channel_name }}
                                    </span>
                                </td>
                                <td class="text-body-secondary">{{ \Illuminate\Support\Str::limit($message->reason, 80) }}</td>
                                <td class="text-nowrap text-body-secondary small">{{ format_date($message->created_at, true) }}</td>
                                <td class="text-end text-nowrap">
                                    <div class="reachus-action-group">
                                        <a href="{{ route('reachus.admin.responses.show', $message) }}" class="btn btn-outline-primary btn-sm" title="{{ trans('messages.actions.show') }}" aria-label="{{ trans('messages.actions.show') }}" data-bs-toggle="tooltip">
                                            <i class="bi bi-eye" aria-hidden="true"></i>
                                        </a>
                                        <a href="{{ route('reachus.admin.responses.destroy', $message) }}" class="btn btn-outline-danger btn-sm" title="{{ trans('messages.actions.delete') }}" aria-label="{{ trans('messages.actions.delete') }}" data-bs-toggle="tooltip" data-confirm="delete">
                                            <i class="bi bi-trash" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="reachus-empty-state">
                                        <span class="reachus-empty-state-icon"><i class="bi bi-inbox" aria-hidden="true"></i></span>
                                        <h3 class="h6 mb-1">{{ trans('reachus::admin.responses.empty_title') }}</h3>
                                        <p class="text-body-secondary mb-0">{{ trans('reachus::admin.responses.empty') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{ $messages->links() }}
    </div>
@endsection
