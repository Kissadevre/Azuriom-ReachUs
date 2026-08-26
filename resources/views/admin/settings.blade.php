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

                @php($selectedRedirectType = old('redirect_type', $redirectType))

                <div class="border rounded p-3 mb-4">
                    <h3 class="h6 mb-3">{{ trans('reachus::admin.settings.authenticated_redirect') }}</h3>

                    <div class="mb-3">
                        <label class="form-label" for="redirectTypeSelect">{{ trans('reachus::admin.settings.redirect_type') }}</label>
                        <select class="form-select @error('redirect_type') is-invalid @enderror" id="redirectTypeSelect" name="redirect_type" required>
                            @foreach($redirectTypes as $type)
                                <option value="{{ $type }}" @selected($selectedRedirectType === $type)>{{ trans('reachus::admin.settings.redirect_types.'.$type) }}</option>
                            @endforeach
                        </select>
                        @error('redirect_type')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="mb-3" data-redirect-field="link">
                        <label class="form-label" for="redirectLinkInput">{{ trans('reachus::admin.settings.redirect_types.link') }}</label>
                        <input type="text" class="form-control @error('redirect_link') is-invalid @enderror" id="redirectLinkInput" name="redirect_link" value="{{ old('redirect_link', $redirectType === 'link' ? $redirectValue : '') }}" maxlength="2048" placeholder="https://example.com/support">
                        @error('redirect_link')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                        <div class="form-text">{{ trans('reachus::admin.settings.redirect_link_help') }}</div>
                    </div>

                    <div class="mb-3" data-redirect-field="page">
                        <label class="form-label" for="redirectPageSelect">{{ trans('reachus::admin.settings.redirect_types.page') }}</label>
                        <select class="form-select @error('redirect_page') is-invalid @enderror" id="redirectPageSelect" name="redirect_page">
                            <option value="">{{ trans('reachus::admin.settings.choose_page') }}</option>
                            @foreach($pages as $page)
                                <option value="{{ $page->id }}" @selected((string) old('redirect_page', $redirectType === 'page' && $redirectValue === $page->slug ? $page->id : '') === (string) $page->id)>{{ $page->title }}</option>
                            @endforeach
                        </select>
                        @error('redirect_page')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="mb-3" data-redirect-field="post">
                        <label class="form-label" for="redirectPostSelect">{{ trans('reachus::admin.settings.redirect_types.post') }}</label>
                        <select class="form-select @error('redirect_post') is-invalid @enderror" id="redirectPostSelect" name="redirect_post">
                            <option value="">{{ trans('reachus::admin.settings.choose_post') }}</option>
                            @foreach($posts as $post)
                                <option value="{{ $post->id }}" @selected((string) old('redirect_post', $redirectType === 'post' && $redirectValue === $post->slug ? $post->id : '') === (string) $post->id)>{{ $post->title }}</option>
                            @endforeach
                        </select>
                        @error('redirect_post')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="mb-3" data-redirect-field="plugin">
                        <label class="form-label" for="redirectPluginSelect">{{ trans('reachus::admin.settings.redirect_types.plugin') }}</label>
                        <select class="form-select @error('redirect_plugin') is-invalid @enderror" id="redirectPluginSelect" name="redirect_plugin">
                            <option value="">{{ trans('reachus::admin.settings.choose_plugin') }}</option>
                            @foreach($pluginRoutes as $route => $name)
                                <option value="{{ $route }}" @selected(old('redirect_plugin', $redirectType === 'plugin' ? $redirectValue : '') === $route)>{{ trans($name) }}</option>
                            @endforeach
                        </select>
                        @error('redirect_plugin')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="alert alert-secondary mb-0" data-redirect-field="posts">
                        <i class="bi bi-card-list" aria-hidden="true"></i> {{ trans('reachus::admin.settings.posts_help') }}
                    </div>

                    <div class="form-text mt-3">{{ trans('reachus::admin.settings.authenticated_redirect_help') }}</div>
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

@push('footer-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const typeSelect = document.getElementById('redirectTypeSelect');
            const fields = document.querySelectorAll('[data-redirect-field]');

            function updateRedirectFields() {
                fields.forEach(function (field) {
                    const active = field.dataset.redirectField === typeSelect.value;

                    field.hidden = ! active;
                    field.querySelectorAll('input, select').forEach(function (input) {
                        input.disabled = ! active;
                        input.required = active;
                    });
                });
            }

            typeSelect.addEventListener('change', updateRedirectFields);
            updateRedirectFields();
        });
    </script>
@endpush
