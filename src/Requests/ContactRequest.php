<?php

namespace Azuriom\Plugin\ReachUs\Requests;

use Azuriom\Plugin\ReachUs\Models\ContactMessage;
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
        return [
            'name' => ['required', 'string', 'max:64', 'regex:/^[\pL\pM ]+$/u'],
            'contact_method' => ['required', Rule::in(ContactMessage::contactMethods())],
            'contact_value' => [
                'required',
                'string',
                'max:255',
                Rule::when($this->input('contact_method') === ContactMessage::METHOD_EMAIL, ['email:rfc']),
                Rule::when($this->input('contact_method') === ContactMessage::METHOD_WHATSAPP, ['regex:/^[0-9]{6,20}$/D']),
            ],
            'reason' => ['required', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.regex' => trans('reachus::messages.validation.name_format'),
            'contact_value.regex' => trans('reachus::messages.validation.whatsapp_format'),
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
        ];
    }
}
