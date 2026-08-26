<?php

namespace Azuriom\Plugin\ReachUs\Requests;

use Azuriom\Plugin\ReachUs\Models\ContactMessage;
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

        return [
            'name' => ['required', 'string', 'max:64', 'regex:/^[\pL\pM ]+$/u'],
            'contact_method' => ['required', Rule::in(ContactMessage::contactMethods())],
            'contact_value' => [
                'required',
                'string',
                'max:255',
                Rule::when($this->input('contact_method') === ContactMessage::METHOD_EMAIL, [
                    'email:rfc',
                    'regex:/^[A-Za-z0-9@_-]+$/D',
                ]),
                Rule::when(in_array($this->input('contact_method'), [
                    ContactMessage::METHOD_DISCORD,
                    ContactMessage::METHOD_TELEGRAM,
                ], true), ['regex:/^[A-Za-z0-9_-]+$/D']),
                Rule::when($this->input('contact_method') === ContactMessage::METHOD_WHATSAPP, [
                    'max:16',
                    'regex:/^(?:[0-9]{6,16}|\+[0-9]{5,15})$/D',
                ]),
            ],
            'reason' => ['required', 'string', 'max:1000'],
            'terms_accepted' => [Rule::when($termsRequired, ['required', 'accepted'])],
        ];
    }

    public function messages(): array
    {
        return [
            'name.regex' => trans('reachus::messages.validation.name_format'),
            'contact_value.regex' => match ($this->input('contact_method')) {
                ContactMessage::METHOD_WHATSAPP => trans('reachus::messages.validation.whatsapp_format'),
                ContactMessage::METHOD_EMAIL => trans('reachus::messages.validation.email_characters'),
                ContactMessage::METHOD_DISCORD, ContactMessage::METHOD_TELEGRAM => trans('reachus::messages.validation.username_characters'),
                default => trans('validation.regex'),
            },
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
