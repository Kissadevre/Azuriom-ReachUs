@extends('layouts.app')

@section('title', trans('reachus::messages.title'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h3 mb-2">{{ trans('reachus::messages.title') }}</h1>
                    <p class="text-body-secondary mb-4">{{ trans('reachus::messages.description') }}</p>

                    @error('form')
                        <div class="alert alert-danger" role="alert">{{ $message }}</div>
                    @enderror

                    <form method="POST" action="{{ route('reachus.store') }}" id="captcha-form">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label" for="nameInput">{{ trans('reachus::messages.form.name') }}</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="nameInput" name="name" value="{{ old('name') }}" maxlength="64" autocomplete="name" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                            <div class="form-text">{{ trans('reachus::messages.form.name_help') }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="contactMethodInput">{{ trans('reachus::messages.form.contact_method') }}</label>
                            <select class="form-select @error('contact_method') is-invalid @enderror" id="contactMethodInput" name="contact_method" required>
                                <option value="">{{ trans('reachus::messages.form.choose_method') }}</option>
                                @foreach(\Azuriom\Plugin\ReachUs\Models\ContactMessage::contactMethods() as $method)
                                    <option value="{{ $method }}" @selected(old('contact_method') === $method)>{{ trans('reachus::messages.methods.'.$method) }}</option>
                                @endforeach
                            </select>
                            @error('contact_method')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3" id="contactValueGroup" @if(! old('contact_method')) hidden @endif>
                            <label class="form-label" for="contactValueInput" id="contactValueLabel">{{ trans('reachus::messages.form.contact_value') }}</label>
                            <input type="text" class="form-control @error('contact_value') is-invalid @enderror" id="contactValueInput" name="contact_value" value="{{ old('contact_value') }}" maxlength="255" autocomplete="off" @if(old('contact_method')) required @endif>
                            @error('contact_value')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                            <div class="form-text" id="contactValueHelp"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="reasonInput">{{ trans('reachus::messages.form.reason') }}</label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" id="reasonInput" name="reason" rows="5" maxlength="1000" required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        @include('elements.captcha', ['center' => true])

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send" aria-hidden="true"></i> {{ trans('reachus::messages.form.submit') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const method = document.getElementById('contactMethodInput');
            const group = document.getElementById('contactValueGroup');
            const input = document.getElementById('contactValueInput');
            const label = document.getElementById('contactValueLabel');
            const help = document.getElementById('contactValueHelp');
            const methods = @json(trans('reachus::messages.contact_fields'));

            function updateContactField() {
                const selected = method.value;
                const configuration = methods[selected];

                group.hidden = ! configuration;
                input.required = Boolean(configuration);
                input.inputMode = selected === 'whatsapp' ? 'numeric' : 'text';
                input.pattern = selected === 'whatsapp' ? '[0-9]{6,20}' : '';
                input.autocomplete = selected === 'email' ? 'email' : 'off';
                label.textContent = configuration ? configuration.label : @json(trans('reachus::messages.form.contact_value'));
                help.textContent = configuration ? configuration.help : '';
            }

            method.addEventListener('change', function () {
                input.value = '';
                updateContactField();
            });

            updateContactField();
        });
    </script>
@endpush
