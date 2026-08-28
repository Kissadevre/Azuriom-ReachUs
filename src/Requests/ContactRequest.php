<?php

namespace Azuriom\Plugin\ReachUs\Requests;

use Azuriom\Plugin\ReachUs\Services\ContactChannelService;
use Azuriom\Plugin\ReachUs\Services\ReachUsSettings;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => is_string($this->input('name')) ? trim($this->input('name')) : $this->input('name'),
            'contact_value' => is_string($this->input('contact_value')) ? trim($this->input('contact_value')) : $this->input('contact_value'),
            'reason' => is_string($this->input('reason')) ? trim($this->input('reason')) : $this->input('reason'),
        ]);
    }

    public function rules(): array
    {
        $termsRequired = app(ReachUsSettings::class)->termsRequired();
        $channelService = app(ContactChannelService::class);
        $channelIdentifiers = $channelService->identifiers();
        $channel = $channelService->find((string) $this->input('contact_method'));
        $profile = $channel === null ? ContactChannelService::TYPE_TEXT : ContactChannelService::validationProfile($channel);
        $minLength = $channel['min_length'] ?? ContactChannelService::MIN_LENGTH;
        $maxLength = $channel['max_length'] ?? ContactChannelService::MAX_LENGTH;

        return [
            'name' => ['required', 'string', 'max:64', 'regex:/^[\pL\pM ]+$/u'],
            'contact_method' => ['required', Rule::in($channelIdentifiers)],
            'contact_value' => [
                'required',
                'string',
                'min:'.$minLength,
                'max:'.$maxLength,
                Rule::when($profile === 'email', [
                    'email:rfc',
                    'regex:/^[A-Za-z0-9@_-]+$/D',
                ]),
                Rule::when($profile === 'username', ['regex:/^[A-Za-z0-9_-]+$/D']),
                Rule::when($profile === 'whatsapp', ['regex:/^\+?[0-9]+$/D']),
                Rule::when($profile === ContactChannelService::TYPE_ALPHANUMERIC, [
                    'regex:/^[\pL\pN]+$/uD',
                ]),
                Rule::when($profile === ContactChannelService::TYPE_NUMERIC, ['regex:/^[0-9]+$/D']),
            ],
            'reason' => ['required', 'string', 'max:1000'],
            'terms_accepted' => [Rule::when($termsRequired, ['required', 'accepted'])],
        ];
    }

    public function messages(): array
    {
        $service = app(ContactChannelService::class);
        $channel = $service->find((string) $this->input('contact_method'));
        $profile = $channel === null ? ContactChannelService::TYPE_TEXT : ContactChannelService::validationProfile($channel);

        return [
            'name.regex' => trans('reachus::messages.validation.name_format'),
            'contact_value.regex' => match ($profile) {
                'whatsapp' => trans('reachus::messages.validation.whatsapp_format', [
                    'min' => $channel['min_length'],
                    'max' => $channel['max_length'],
                ]),
                'email' => trans('reachus::messages.validation.email_characters'),
                'username' => trans('reachus::messages.validation.username_characters'),
                ContactChannelService::TYPE_ALPHANUMERIC => trans('reachus::messages.validation.alphanumeric_format'),
                ContactChannelService::TYPE_NUMERIC => trans('reachus::messages.validation.numeric_format'),
                default => trans('validation.regex'),
            },
            'contact_value.min' => trans('reachus::messages.validation.contact_min'),
            'contact_value.max' => trans('reachus::messages.validation.contact_max'),
            'terms_accepted.accepted' => trans('reachus::messages.validation.terms_accepted'),
            'terms_accepted.required' => trans('reachus::messages.validation.terms_accepted'),
        ];
    }

    public function attributes(): array
    {
        $attributes = trans('reachus::messages.validation.attributes');

        return is_array($attributes) ? $attributes : [
            'name' => 'name',
            'contact_method' => 'contact method',
            'contact_value' => 'contact details',
            'reason' => 'reason',
            'terms_accepted' => 'terms and conditions',
        ];
    }
}
