@extends('admin.layouts.admin')

@section('title', trans('reachus::admin.settings.title'))

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header">
            <h2 class="h5 mb-0">{{ trans('reachus::admin.settings.title') }}</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('reachus.admin.settings.save') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="form-label" for="rateLimitInput">{{ trans('reachus::admin.settings.rate_limit') }}</label>
                    <div class="input-group @error('rate_limit') has-validation @enderror">
                        <input type="text" inputmode="numeric" pattern="[0-9]+" class="form-control @error('rate_limit') is-invalid @enderror" id="rateLimitInput" name="rate_limit" value="{{ old('rate_limit', $rateLimit) }}" maxlength="3" required>
                        <span class="input-group-text">{{ trans('reachus::admin.settings.requests_per_minute') }}</span>
                        @error('rate_limit')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="form-text">{{ trans('reachus::admin.settings.rate_limit_help') }}</div>
                </div>

                <div class="mb-4">
                    <label class="form-label" for="authenticatedRedirectInput">{{ trans('reachus::admin.settings.authenticated_redirect') }}</label>
                    <input type="text" class="form-control @error('authenticated_redirect') is-invalid @enderror" id="authenticatedRedirectInput" name="authenticated_redirect" value="{{ old('authenticated_redirect', $authenticatedRedirect) }}" maxlength="2048" placeholder="/support" required>
                    @error('authenticated_redirect')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                    <div class="form-text">{{ trans('reachus::admin.settings.authenticated_redirect_help') }}</div>
                </div>

                <div class="alert alert-info" role="alert">
                    <i class="bi bi-shield-check" aria-hidden="true"></i> {{ trans('reachus::admin.settings.captcha_help') }}
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg" aria-hidden="true"></i> {{ trans('messages.actions.save') }}
                </button>
            </form>
        </div>
    </div>
@endsection
