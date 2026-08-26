@extends('admin.layouts.admin')

@section('title', trans('reachus::admin.responses.title'))

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h2 class="h5 mb-0">{{ trans('reachus::admin.responses.title') }}</h2>
            <span class="badge text-bg-primary">{{ trans('reachus::admin.responses.unread_count', ['count' => $unreadCount]) }}</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
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
                        <tr class="@if($message->read_at === null) fw-semibold @endif">
                            <td>
                                <span class="badge {{ $message->read_at === null ? 'text-bg-warning' : 'text-bg-secondary' }}">
                                    {{ trans('reachus::admin.responses.'.($message->read_at === null ? 'unread' : 'read')) }}
                                </span>
                            </td>
                            <td>{{ $message->name }}</td>
                            <td>{{ trans('reachus::messages.methods.'.$message->contact_method) }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($message->reason, 80) }}</td>
                            <td>{{ format_date($message->created_at, true) }}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('reachus.admin.responses.show', $message) }}" class="btn btn-sm btn-outline-primary" title="{{ trans('messages.actions.show') }}" aria-label="{{ trans('messages.actions.show') }}">
                                    <i class="bi bi-eye" aria-hidden="true"></i>
                                </a>
                                <a href="{{ route('reachus.admin.responses.destroy', $message) }}" class="btn btn-sm btn-outline-danger" title="{{ trans('messages.actions.delete') }}" aria-label="{{ trans('messages.actions.delete') }}" data-confirm="delete">
                                    <i class="bi bi-trash" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-body-secondary py-5">{{ trans('reachus::admin.responses.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{ $messages->links() }}
@endsection
