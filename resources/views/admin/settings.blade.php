@extends('admin.layouts.admin')

@section('title', trans('reachus::admin.settings.title'))

@include('reachus::_styles')

@section('content')
    @php
        $selectedRedirectType = old('redirect_type', $redirectType);
        $submittedChannels = old('channels', $contactChannels);
        $configuredChannels = is_array($submittedChannels) && $submittedChannels !== []
            ? array_values(array_map(fn ($channel) => [
                'id' => is_array($channel) && is_string($channel['id'] ?? null) ? $channel['id'] : '',
                'name' => is_array($channel) && is_string($channel['name'] ?? null) ? $channel['name'] : '',
                'icon' => is_array($channel) && is_string($channel['icon'] ?? null) ? $channel['icon'] : '',
                'data_type' => is_array($channel) && is_string($channel['data_type'] ?? null) ? $channel['data_type'] : 'text',
                'min_length' => is_array($channel) && is_numeric($channel['min_length'] ?? null) ? $channel['min_length'] : 1,
                'max_length' => is_array($channel) && is_numeric($channel['max_length'] ?? null) ? $channel['max_length'] : 255,
            ], $submittedChannels))
            : $contactChannels;
    @endphp

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

        <form action="{{ route('reachus.admin.settings.save') }}" method="POST" id="reachusSettingsForm">
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
                        <div class="reachus-setting-switch">
                            <div>
                                <label class="form-label fw-semibold mb-1" for="submissionsEnabledInput">{{ trans('reachus::admin.settings.submissions_enabled') }}</label>
                                <div class="form-text mt-0">{{ trans('reachus::admin.settings.submissions_enabled_help') }}</div>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input type="hidden" name="submissions_enabled" value="0">
                                <input class="form-check-input @error('submissions_enabled') is-invalid @enderror" type="checkbox" role="switch" id="submissionsEnabledInput" name="submissions_enabled" value="1" @checked(old('submissions_enabled', $submissionsEnabled))>
                            </div>
                        </div>
                        @error('submissions_enabled')
                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

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
                        <span class="reachus-section-icon"><i class="bi bi-chat-square-dots" aria-hidden="true"></i></span>
                        <div>
                            <h2>{{ trans('reachus::admin.settings.channels_title') }}</h2>
                            <small class="text-body-secondary">{{ trans('reachus::admin.settings.channels_description', ['max' => $maxContactChannels]) }}</small>
                        </div>
                    </div>
                    <span class="badge text-bg-secondary">
                        <strong id="channelCount">{{ count($configuredChannels) }}</strong> / {{ $maxContactChannels }}
                    </span>
                </div>
                <div class="card-body p-4">
                    @error('channels')
                        <div class="alert alert-danger" role="alert"><strong>{{ $message }}</strong></div>
                    @enderror

                    <div class="reachus-channel-list" id="channelList">
                        @foreach($configuredChannels as $index => $channel)
                            @php($previewIcon = \Azuriom\Plugin\ReachUs\Services\ContactChannelService::isAllowedIcon($channel['icon'] ?? null) ? $channel['icon'] : 'bi bi-question-circle')
                            <div class="reachus-channel-item" data-channel-item>
                                <input type="hidden" name="channels[{{ $index }}][id]" value="{{ $channel['id'] ?? '' }}" data-channel-field="id">
                                @error('channels.'.$index.'.id')
                                    <div class="alert alert-danger py-2" role="alert"><strong>{{ $message }}</strong></div>
                                @enderror
                                <div class="row g-3 align-items-start">
                                    <div class="col-lg-5">
                                        <label class="form-label fw-semibold" for="channelName{{ $index }}">{{ trans('reachus::admin.settings.channel_name') }}</label>
                                        <input type="text" class="form-control @error('channels.'.$index.'.name') is-invalid @enderror" id="channelName{{ $index }}" name="channels[{{ $index }}][name]" value="{{ $channel['name'] ?? '' }}" maxlength="64" data-channel-field="name" required>
                                        @error('channels.'.$index.'.name')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="form-label fw-semibold" for="channelIcon{{ $index }}">{{ trans('reachus::admin.settings.channel_icon') }}</label>
                                        <div class="input-group @error('channels.'.$index.'.icon') has-validation @enderror">
                                            <span class="input-group-text"><i class="{{ $previewIcon }}" data-channel-icon-preview aria-hidden="true"></i></span>
                                            <input type="text" class="form-control @error('channels.'.$index.'.icon') is-invalid @enderror" id="channelIcon{{ $index }}" name="channels[{{ $index }}][icon]" value="{{ $channel['icon'] ?? '' }}" maxlength="64" placeholder="bi bi-chat" data-channel-field="icon" required>
                                            @error('channels.'.$index.'.icon')
                                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-1 d-flex justify-content-lg-end">
                                        <button type="button" class="btn btn-outline-danger reachus-channel-remove" data-remove-channel title="{{ trans('reachus::admin.settings.remove_channel') }}" aria-label="{{ trans('reachus::admin.settings.remove_channel') }}">
                                            <i class="bi bi-trash" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="row g-3 mt-1">
                                    <div class="col-lg-6">
                                        <label class="form-label fw-semibold" for="channelData_type{{ $index }}">{{ trans('reachus::admin.settings.channel_data_type') }}</label>
                                        <select class="form-select @error('channels.'.$index.'.data_type') is-invalid @enderror" id="channelData_type{{ $index }}" name="channels[{{ $index }}][data_type]" data-channel-field="data_type" required>
                                            @foreach($contactDataTypes as $dataType)
                                                <option value="{{ $dataType }}" @selected(($channel['data_type'] ?? 'text') === $dataType)>{{ trans('reachus::admin.settings.channel_data_types.'.$dataType) }}</option>
                                            @endforeach
                                        </select>
                                        @error('channels.'.$index.'.data_type')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                        <div class="form-text">{{ trans('reachus::admin.settings.channel_data_type_help') }}</div>
                                    </div>
                                    <div class="col-sm-6 col-lg-3">
                                        <label class="form-label fw-semibold" for="channelMin_length{{ $index }}">{{ trans('reachus::admin.settings.channel_min_length') }}</label>
                                        <input type="number" class="form-control @error('channels.'.$index.'.min_length') is-invalid @enderror" id="channelMin_length{{ $index }}" name="channels[{{ $index }}][min_length]" value="{{ $channel['min_length'] ?? 1 }}" min="1" max="255" data-channel-field="min_length" required>
                                        @error('channels.'.$index.'.min_length')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6 col-lg-3">
                                        <label class="form-label fw-semibold" for="channelMax_length{{ $index }}">{{ trans('reachus::admin.settings.channel_max_length') }}</label>
                                        <input type="number" class="form-control @error('channels.'.$index.'.max_length') is-invalid @enderror" id="channelMax_length{{ $index }}" name="channels[{{ $index }}][max_length]" value="{{ $channel['max_length'] ?? 255 }}" min="1" max="255" data-channel-field="max_length" required>
                                        @error('channels.'.$index.'.max_length')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-3">
                        <div class="form-text m-0">
                            {{ trans('reachus::admin.settings.channel_icon_help') }}
                            <a href="https://icons.getbootstrap.com/" target="_blank" rel="noopener noreferrer">Bootstrap Icons</a>.
                        </div>
                        <button type="button" class="btn btn-outline-primary" id="addChannelButton">
                            <i class="bi bi-plus-lg me-1" aria-hidden="true"></i> {{ trans('reachus::admin.settings.add_channel') }}
                        </button>
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

        <template id="channelTemplate">
            <div class="reachus-channel-item" data-channel-item>
                <input type="hidden" name="channels[__INDEX__][id]" value="" data-channel-field="id">
                <div class="row g-3 align-items-start">
                    <div class="col-lg-5">
                        <label class="form-label fw-semibold" for="channelName__INDEX__">{{ trans('reachus::admin.settings.channel_name') }}</label>
                        <input type="text" class="form-control" id="channelName__INDEX__" name="channels[__INDEX__][name]" maxlength="64" data-channel-field="name" required>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label fw-semibold" for="channelIcon__INDEX__">{{ trans('reachus::admin.settings.channel_icon') }}</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-chat" data-channel-icon-preview aria-hidden="true"></i></span>
                            <input type="text" class="form-control" id="channelIcon__INDEX__" name="channels[__INDEX__][icon]" value="bi bi-chat" maxlength="64" placeholder="bi bi-chat" data-channel-field="icon" required>
                        </div>
                    </div>
                    <div class="col-lg-1 d-flex justify-content-lg-end">
                        <button type="button" class="btn btn-outline-danger reachus-channel-remove" data-remove-channel title="{{ trans('reachus::admin.settings.remove_channel') }}" aria-label="{{ trans('reachus::admin.settings.remove_channel') }}">
                            <i class="bi bi-trash" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
                <div class="row g-3 mt-1">
                    <div class="col-lg-6">
                        <label class="form-label fw-semibold" for="channelData_type__INDEX__">{{ trans('reachus::admin.settings.channel_data_type') }}</label>
                        <select class="form-select" id="channelData_type__INDEX__" name="channels[__INDEX__][data_type]" data-channel-field="data_type" required>
                            @foreach($contactDataTypes as $dataType)
                                <option value="{{ $dataType }}">{{ trans('reachus::admin.settings.channel_data_types.'.$dataType) }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">{{ trans('reachus::admin.settings.channel_data_type_help') }}</div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <label class="form-label fw-semibold" for="channelMin_length__INDEX__">{{ trans('reachus::admin.settings.channel_min_length') }}</label>
                        <input type="number" class="form-control" id="channelMin_length__INDEX__" name="channels[__INDEX__][min_length]" value="1" min="1" max="255" data-channel-field="min_length" required>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <label class="form-label fw-semibold" for="channelMax_length__INDEX__">{{ trans('reachus::admin.settings.channel_max_length') }}</label>
                        <input type="number" class="form-control" id="channelMax_length__INDEX__" name="channels[__INDEX__][max_length]" value="255" min="1" max="255" data-channel-field="max_length" required>
                    </div>
                </div>
            </div>
        </template>
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
            const channelList = document.getElementById('channelList');
            const channelTemplate = document.getElementById('channelTemplate');
            const addChannelButton = document.getElementById('addChannelButton');
            const channelCount = document.getElementById('channelCount');
            const maxChannels = {{ $maxContactChannels }};
            const invalidLengthMessage = @json(trans('reachus::admin.settings.channel_max_length_gte'));
            let channelSequence = 0;

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

            function updateChannelControls() {
                const items = channelList.querySelectorAll('[data-channel-item]');

                channelCount.textContent = items.length;
                addChannelButton.disabled = items.length >= maxChannels;
                items.forEach(function (item) {
                    item.querySelector('[data-remove-channel]').disabled = items.length <= 1;
                    validateLengthRange(item);
                });
            }

            function validateLengthRange(item) {
                const minimum = item.querySelector('[data-channel-field="min_length"]');
                const maximum = item.querySelector('[data-channel-field="max_length"]');
                const invalid = Number(maximum.value) < Number(minimum.value);

                maximum.setCustomValidity(invalid ? invalidLengthMessage : '');
            }

            function renumberChannels() {
                channelList.querySelectorAll('[data-channel-item]').forEach(function (item, index) {
                    item.querySelectorAll('[data-channel-field]').forEach(function (field) {
                        field.name = 'channels[' + index + '][' + field.dataset.channelField + ']';

                        if (field.dataset.channelField !== 'id') {
                            field.id = 'channel' + field.dataset.channelField.charAt(0).toUpperCase()
                                + field.dataset.channelField.slice(1) + index;
                            item.querySelector('label[for^="channel' + field.dataset.channelField.charAt(0).toUpperCase() + '"]')
                                ?.setAttribute('for', field.id);
                        }
                    });
                });
            }

            function updateIconPreview(input) {
                const allowedIcon = /^bi bi-[a-z0-9]+(?:-[a-z0-9]+)*$/;
                const preview = input.closest('.input-group').querySelector('[data-channel-icon-preview]');

                preview.className = allowedIcon.test(input.value) ? input.value : 'bi bi-question-circle';
            }

            addChannelButton.addEventListener('click', function () {
                const index = channelList.querySelectorAll('[data-channel-item]').length;

                if (index >= maxChannels) {
                    return;
                }

                const wrapper = document.createElement('div');
                wrapper.innerHTML = channelTemplate.innerHTML.replaceAll('__INDEX__', String(index)).trim();
                const item = wrapper.firstElementChild;
                item.querySelector('[data-channel-field="id"]').value = 'custom_'
                    + Date.now().toString(36) + '_' + (channelSequence++).toString(36);
                channelList.appendChild(item);
                updateChannelControls();
                item.querySelector('[data-channel-field="name"]').focus();
            });

            channelList.addEventListener('click', function (event) {
                const removeButton = event.target.closest('[data-remove-channel]');

                if (removeButton && channelList.querySelectorAll('[data-channel-item]').length > 1) {
                    removeButton.closest('[data-channel-item]').remove();
                    renumberChannels();
                    updateChannelControls();
                }
            });

            channelList.addEventListener('input', function (event) {
                if (event.target.matches('[data-channel-field="icon"]')) {
                    updateIconPreview(event.target);
                }

                if (event.target.matches('[data-channel-field="min_length"], [data-channel-field="max_length"]')) {
                    validateLengthRange(event.target.closest('[data-channel-item]'));
                }
            });

            typeSelect.addEventListener('change', updateRedirectFields);
            termsEnabled.addEventListener('change', updateTermsFields);
            updateRedirectFields();
            updateTermsFields();
            renumberChannels();
            updateChannelControls();
        });
    </script>
@endpush
