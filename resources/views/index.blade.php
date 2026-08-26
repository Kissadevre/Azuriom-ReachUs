@extends('layouts.app')

@section('title', trans('reachus::messages.title'))

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

    <div class="reachus-shell reachus-public py-2 py-lg-4">
        <div class="row justify-content-center">
            <div class="col-xl-9 col-lg-10">
                <div class="reachus-surface">
                    <header class="reachus-hero">
                        <div class="reachus-hero-content">
                            <span class="reachus-hero-icon"><i class="bi bi-chat-heart" aria-hidden="true"></i></span>
                            <div class="reachus-eyebrow"><i class="bi bi-stars" aria-hidden="true"></i> {{ trans('reachus::messages.hero.eyebrow') }}</div>
                            <h1 class="display-6 fw-bold mb-2">{{ trans('reachus::messages.title') }}</h1>
                            <p class="lead text-body-secondary mb-0">{{ trans('reachus::messages.description') }}</p>

                            <div class="reachus-trust-list" aria-label="{{ trans('reachus::messages.hero.features') }}">
                                <span class="reachus-trust-pill"><i class="bi bi-person-x" aria-hidden="true"></i> {{ trans('reachus::messages.hero.no_account') }}</span>
                                <span class="reachus-trust-pill"><i class="bi bi-shield-check" aria-hidden="true"></i> {{ trans('reachus::messages.hero.protected') }}</span>
                                <span class="reachus-trust-pill"><i class="bi bi-reply" aria-hidden="true"></i> {{ trans('reachus::messages.hero.direct_reply') }}</span>
                            </div>
                        </div>
                    </header>

                    <div class="reachus-form-body">
                        @error('form')
                            <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
                                <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror

                        <form method="POST" action="{{ route('reachus.store') }}" id="captcha-form">
                            @csrf

                            <section class="reachus-form-section" aria-labelledby="identityHeading">
                                <div class="reachus-section-heading">
                                    <span class="reachus-section-icon"><i class="bi bi-person" aria-hidden="true"></i></span>
                                    <div>
                                        <h2 id="identityHeading">{{ trans('reachus::messages.sections.identity') }}</h2>
                                        <small class="text-body-secondary">{{ trans('reachus::messages.sections.identity_help') }}</small>
                                    </div>
                                </div>

                                <label class="form-label fw-semibold" for="nameInput">{{ trans('reachus::messages.form.name') }}</label>
                                <div class="reachus-input-wrap">
                                    <i class="bi bi-person-badge" aria-hidden="true"></i>
                                    <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" id="nameInput" name="name" value="{{ old('name') }}" maxlength="64" autocomplete="name" aria-describedby="nameHelp" required>
                                </div>
                                @error('name')
                                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                                <div class="form-text" id="nameHelp">{{ trans('reachus::messages.form.name_help') }}</div>
                            </section>

                            <section class="reachus-form-section" aria-labelledby="methodHeading">
                                <div class="reachus-section-heading">
                                    <span class="reachus-section-icon"><i class="bi bi-send" aria-hidden="true"></i></span>
                                    <div>
                                        <h2 id="methodHeading">{{ trans('reachus::messages.sections.contact') }}</h2>
                                        <small class="text-body-secondary">{{ trans('reachus::messages.sections.contact_help') }}</small>
                                    </div>
                                </div>

                                <fieldset>
                                    <legend class="visually-hidden">{{ trans('reachus::messages.form.contact_method') }}</legend>
                                    <div class="reachus-method-grid">
                                        @foreach(\Azuriom\Plugin\ReachUs\Models\ContactMessage::contactMethods() as $method)
                                            <div class="reachus-method-option">
                                                <input type="radio" class="visually-hidden" id="contactMethod{{ ucfirst($method) }}" name="contact_method" value="{{ $method }}" @checked(old('contact_method') === $method) required>
                                                <label class="reachus-method-card" for="contactMethod{{ ucfirst($method) }}">
                                                    <i class="{{ $methodIcons[$method] }}" aria-hidden="true"></i>
                                                    <span>{{ trans('reachus::messages.methods.'.$method) }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('contact_method')
                                        <span class="invalid-feedback d-block mt-2" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </fieldset>

                                <div class="mt-3" id="contactValueGroup" @if(! old('contact_method')) hidden @endif>
                                    <label class="form-label fw-semibold" for="contactValueInput" id="contactValueLabel">{{ trans('reachus::messages.form.contact_value') }}</label>
                                    <div class="reachus-input-wrap">
                                        <i class="bi bi-at" id="contactValueIcon" aria-hidden="true"></i>
                                        <input type="text" class="form-control form-control-lg @error('contact_value') is-invalid @enderror" id="contactValueInput" name="contact_value" value="{{ old('contact_value') }}" maxlength="255" autocomplete="off" aria-describedby="contactValueHelp" @if(old('contact_method')) required @endif>
                                    </div>
                                    @error('contact_value')
                                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                    <div class="form-text" id="contactValueHelp"></div>
                                </div>
                            </section>

                            <section class="reachus-form-section" aria-labelledby="messageHeading">
                                <div class="reachus-section-heading">
                                    <span class="reachus-section-icon"><i class="bi bi-chat-left-text" aria-hidden="true"></i></span>
                                    <div>
                                        <h2 id="messageHeading">{{ trans('reachus::messages.sections.message') }}</h2>
                                        <small class="text-body-secondary">{{ trans('reachus::messages.sections.message_help') }}</small>
                                    </div>
                                </div>

                                <label class="form-label fw-semibold" for="reasonInput">{{ trans('reachus::messages.form.reason') }}</label>
                                <textarea class="form-control @error('reason') is-invalid @enderror" id="reasonInput" name="reason" rows="6" maxlength="1000" aria-describedby="reasonCounter" required>{{ old('reason') }}</textarea>
                                <div class="d-flex justify-content-between gap-3 mt-1">
                                    @error('reason')
                                        <span class="invalid-feedback d-block mt-0" role="alert"><strong>{{ $message }}</strong></span>
                                    @else
                                        <span></span>
                                    @enderror
                                    <span class="reachus-counter" id="reasonCounter" aria-live="polite">0 / 1000</span>
                                </div>
                            </section>

                            <div class="mt-4">
                                @include('elements.captcha', ['center' => true])
                            </div>

                            <div class="reachus-submit-row">
                                <span class="reachus-submit-note"><i class="bi bi-lock" aria-hidden="true"></i> {{ trans('reachus::messages.form.secure_note') }}</span>
                                <button type="submit" class="btn btn-primary btn-lg px-4">
                                    <i class="bi bi-send-fill me-1" aria-hidden="true"></i> {{ trans('reachus::messages.form.submit') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const methodInputs = document.querySelectorAll('input[name="contact_method"]');
            const group = document.getElementById('contactValueGroup');
            const input = document.getElementById('contactValueInput');
            const label = document.getElementById('contactValueLabel');
            const help = document.getElementById('contactValueHelp');
            const icon = document.getElementById('contactValueIcon');
            const reason = document.getElementById('reasonInput');
            const reasonCounter = document.getElementById('reasonCounter');
            const methods = @json(trans('reachus::messages.contact_fields'));
            const icons = @json($methodIcons);

            function selectedMethod() {
                return document.querySelector('input[name="contact_method"]:checked')?.value ?? '';
            }

            function updateContactField(clearValue = false) {
                const selected = selectedMethod();
                const configuration = methods[selected];

                if (clearValue) {
                    input.value = '';
                }

                group.hidden = ! configuration;
                input.required = Boolean(configuration);
                input.type = selected === 'email' ? 'email' : 'text';
                input.inputMode = selected === 'whatsapp' ? 'tel' : (selected === 'email' ? 'email' : 'text');
                input.maxLength = selected === 'whatsapp' ? 16 : 255;
                input.autocomplete = selected === 'email' ? 'email' : 'off';
                input.title = configuration ? configuration.help : '';

                if (selected === 'whatsapp') {
                    input.setAttribute('pattern', '(?:[0-9]{6,16}|\\+[0-9]{5,15})');
                } else if (selected === 'discord' || selected === 'telegram') {
                    input.setAttribute('pattern', '[A-Za-z0-9_-]+');
                } else if (selected === 'email') {
                    input.setAttribute('pattern', '[A-Za-z0-9@_-]+');
                } else {
                    input.removeAttribute('pattern');
                }

                icon.className = icons[selected] ?? 'bi bi-at';
                label.textContent = configuration ? configuration.label : @json(trans('reachus::messages.form.contact_value'));
                help.textContent = configuration ? configuration.help : '';
            }

            function updateReasonCounter() {
                reasonCounter.textContent = reason.value.length + ' / 1000';
            }

            methodInputs.forEach(function (methodInput) {
                methodInput.addEventListener('change', function () {
                    updateContactField(true);
                    input.focus();
                });
            });

            input.addEventListener('input', function () {
                const selected = selectedMethod();

                if (selected === 'whatsapp') {
                    const hasLeadingPlus = input.value.startsWith('+');
                    const digits = input.value.replace(/\D/g, '');

                    input.value = ((hasLeadingPlus ? '+' : '') + digits).slice(0, 16);
                } else if (selected === 'discord' || selected === 'telegram') {
                    input.value = input.value.replace(/[^A-Za-z0-9_-]/g, '');
                } else if (selected === 'email') {
                    input.value = input.value.replace(/[^A-Za-z0-9@_-]/g, '');
                }
            });

            reason.addEventListener('input', updateReasonCounter);
            updateContactField();
            updateReasonCounter();
        });
    </script>
@endpush
