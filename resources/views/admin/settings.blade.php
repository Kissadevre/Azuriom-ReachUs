@extends('admin.layouts.admin')

@section('title', trans('reachus::admin.settings.title'))

@include('reachus::_styles')

@section('content')
    @php($selectedRedirectType = old('redirect_type', $redirectType))

    <div class="reachus-shell">
        <header class="reachus-admin-header">
            <div class="reachus-admin-heading">
                <span class="reachus-admin-icon"><i class="bi bi-sliders" aria-hidden="true"></i></span>
                <div>
                    <h1>{{ trans('reachus::admin.settings.title') }}</h1>
                    <p>{{ trans('reachus::admin.settings.description') }}</p>
                </div>
            </div>
        </header>

        <form action="{{ route('reachus.admin.settings.save') }}" method="POST">
            @csrf

            <div class="card reachus-admin-card mb-4">
                <div class="reachus-admin-card-header">
                    <div class="reachus-section-heading mb-0">
                        <span class="reachus-section-icon"><i class="bi bi-shield-lock" aria-hidden="true"></i></span>
                        <div>
                            <h2>{{ trans('reachus::admin.settings.security_title') }}</h2>
                            <small class="text-body-secondary">{{ trans('reachus::admin.settings.security_description') }}</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="reachus-admin-section">
                        <div class="row align-items-center g-3">
                            <div class="col-lg-5">
                                <label class="form-label fw-semibold mb-1" for="rateLimitInput">{{ trans('reachus::admin.settings.rate_limit') }}</label>
                                <div class="form-text mt-0">{{ trans('reachus::admin.settings.rate_limit_help') }}</div>
                            </div>
                            <div class="col-lg-7">
                                <div class="input-group @error('rate_limit') has-validation @enderror">
                                    <span class="input-group-text"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
                                    <input type="text" inputmode="numeric" pattern="[0-9]+" class="form-control @error('rate_limit') is-invalid @enderror" id="rateLimitInput" name="rate_limit" value="{{ old('rate_limit', $rateLimit) }}" maxlength="3" required>
                                    <span class="input-group-text">{{ trans('reachus::admin.settings.requests_per_minute') }}</span>
                                    @error('rate_limit')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="reachus-admin-section">
                        <div class="reachus-setting-switch">
                            <div>
                                <label class="form-label fw-semibold mb-1" for="termsEnabledInput">{{ trans('reachus::admin.settings.terms_enabled') }}</label>
                                <div class="form-text mt-0">{{ trans('reachus::admin.settings.terms_enabled_help') }}</div>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input type="hidden" name="terms_enabled" value="0">
                                <input class="form-check-input @error('terms_enabled') is-invalid @enderror" type="checkbox" role="switch" id="termsEnabledInput" name="terms_enabled" value="1" @checked(old('terms_enabled', $termsEnabled))>
                            </div>
                        </div>
                        @error('terms_enabled')
                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror

                        <div class="reachus-terms-settings mt-3" id="termsConfiguration" @if(! old('terms_enabled', $termsEnabled)) hidden @endif>
                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <label class="form-label fw-semibold" for="termsTextInput">{{ trans('reachus::admin.settings.terms_text') }}</label>
                                    <input type="text" class="form-control @error('terms_text') is-invalid @enderror" id="termsTextInput" name="terms_text" value="{{ old('terms_text', $termsText) }}" maxlength="200" placeholder="{{ trans('reachus::admin.settings.terms_text_placeholder') }}">
                                    @error('terms_text')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                    <div class="form-text">{{ trans('reachus::admin.settings.terms_text_help') }}</div>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label fw-semibold" for="termsUrlInput">{{ trans('reachus::admin.settings.terms_url') }}</label>
                                    <div class="input-group @error('terms_url') has-validation @enderror">
                                        <span class="input-group-text"><i class="bi bi-link-45deg" aria-hidden="true"></i></span>
                                        <input type="text" class="form-control @error('terms_url') is-invalid @enderror" id="termsUrlInput" name="terms_url" value="{{ old('terms_url', $termsUrl) }}" maxlength="2048" placeholder="https://example.com/privacy">
                                        @error('terms_url')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    <div class="form-text">{{ trans('reachus::admin.settings.terms_url_help') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info d-flex gap-2 align-items-start mt-3 mb-0" role="alert">
                        <i class="bi bi-patch-check-fill mt-1" aria-hidden="true"></i>
                        <div>
                            <strong class="d-block">{{ trans('reachus::admin.settings.captcha_title') }}</strong>
                            <span>{{ trans('reachus::admin.settings.captcha_help') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card reachus-admin-card mb-4">
                <div class="reachus-admin-card-header">
                    <div class="reachus-section-heading mb-0">
                        <span class="reachus-section-icon"><i class="bi bi-signpost-split" aria-hidden="true"></i></span>
                        <div>
                            <h2>{{ trans('reachus::admin.settings.authenticated_redirect') }}</h2>
                            <small class="text-body-secondary">{{ trans('reachus::admin.settings.authenticated_redirect_help') }}</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="reachus-destination-panel">
                        <div class="p-3">
                            <label class="form-label fw-semibold" for="redirectTypeSelect">{{ trans('reachus::admin.settings.redirect_type') }}</label>
                            <select class="form-select @error('redirect_type') is-invalid @enderror" id="redirectTypeSelect" name="redirect_type" required>
                                @foreach($redirectTypes as $type)
                                    <option value="{{ $type }}" @selected($selectedRedirectType === $type)>{{ trans('reachus::admin.settings.redirect_types.'.$type) }}</option>
                                @endforeach
                            </select>
                            @error('redirect_type')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div data-redirect-field="link">
                            <label class="form-label fw-semibold" for="redirectLinkInput">{{ trans('reachus::admin.settings.redirect_types.link') }}</label>
                            <div class="input-group @error('redirect_link') has-validation @enderror">
                                <span class="input-group-text"><i class="bi bi-link-45deg" aria-hidden="true"></i></span>
                                <input type="text" class="form-control @error('redirect_link') is-invalid @enderror" id="redirectLinkInput" name="redirect_link" value="{{ old('redirect_link', $redirectType === 'link' ? $redirectValue : '') }}" maxlength="2048" placeholder="https://example.com/support">
                                @error('redirect_link')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="form-text">{{ trans('reachus::admin.settings.redirect_link_help') }}</div>
                        </div>

                        <div data-redirect-field="page">
                            <label class="form-label fw-semibold" for="redirectPageSelect">{{ trans('reachus::admin.settings.redirect_types.page') }}</label>
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

                        <div data-redirect-field="post">
                            <label class="form-label fw-semibold" for="redirectPostSelect">{{ trans('reachus::admin.settings.redirect_types.post') }}</label>
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

                        <div data-redirect-field="plugin">
                            <label class="form-label fw-semibold" for="redirectPluginSelect">{{ trans('reachus::admin.settings.redirect_types.plugin') }}</label>
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

                        <div data-redirect-field="posts">
                            <div class="d-flex align-items-center gap-3">
                                <span class="reachus-section-icon"><i class="bi bi-card-list" aria-hidden="true"></i></span>
                                <div>
                                    <strong class="d-block">{{ trans('reachus::admin.settings.redirect_types.posts') }}</strong>
                                    <span class="text-body-secondary small">{{ trans('reachus::admin.settings.posts_help') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mb-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-lg me-1" aria-hidden="true"></i> {{ trans('messages.actions.save') }}
                </button>
            </div>
        </form>
    </div>
@endsection

@push('footer-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const typeSelect = document.getElementById('redirectTypeSelect');
            const fields = document.querySelectorAll('[data-redirect-field]');
            const termsEnabled = document.getElementById('termsEnabledInput');
            const termsConfiguration = document.getElementById('termsConfiguration');
            const termsFields = termsConfiguration.querySelectorAll('input');

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

            function updateTermsFields() {
                termsConfiguration.hidden = ! termsEnabled.checked;
                termsFields.forEach(function (field) {
                    field.required = termsEnabled.checked;
                });
            }

            typeSelect.addEventListener('change', updateRedirectFields);
            termsEnabled.addEventListener('change', updateTermsFields);
            updateRedirectFields();
            updateTermsFields();
        });
    </script>
@endpush
