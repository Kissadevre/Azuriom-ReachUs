<?php

namespace Azuriom\Plugin\ReachUs\Tests\Feature;

use Azuriom\Plugin\ReachUs\Requests\ContactRequest;
use Azuriom\Plugin\ReachUs\Tests\TestCase;
use Illuminate\Support\Facades\Validator;

class ContactValidationTest extends TestCase
{
    public function test_valid_contact_methods_pass_their_conditional_rules(): void
    {
        foreach ([
            ['whatsapp', '5215512345678'],
            ['telegram', '@zibuu_support'],
            ['email', 'hello@example.com'],
            ['discord', 'zibuu.support'],
        ] as [$method, $value]) {
            $this->assertFalse($this->validator([
                'name' => 'José Zibuu',
                'contact_method' => $method,
                'contact_value' => $value,
                'reason' => 'I need help with the service.',
            ])->fails(), $method.' should be valid.');
        }
    }

    public function test_name_rejects_special_characters_and_length_over_64(): void
    {
        $special = $this->validator($this->validData(['name' => 'John_123']));
        $long = $this->validator($this->validData(['name' => str_repeat('A', 65)]));

        $this->assertTrue($special->fails());
        $this->assertArrayHasKey('name', $special->errors()->toArray());
        $this->assertTrue($long->fails());
        $this->assertArrayHasKey('name', $long->errors()->toArray());
    }

    public function test_email_and_whatsapp_receive_method_specific_validation(): void
    {
        $email = $this->validator($this->validData([
            'contact_method' => 'email',
            'contact_value' => 'not-an-email',
        ]));
        $whatsapp = $this->validator($this->validData([
            'contact_method' => 'whatsapp',
            'contact_value' => '+52 55 1234',
        ]));

        $this->assertTrue($email->fails());
        $this->assertArrayHasKey('contact_value', $email->errors()->toArray());
        $this->assertTrue($whatsapp->fails());
        $this->assertArrayHasKey('contact_value', $whatsapp->errors()->toArray());
    }

    private function validator(array $data): \Illuminate\Contracts\Validation\Validator
    {
        $request = ContactRequest::create('/reachus', 'POST', $data);
        $request->setContainer($this->app);

        return Validator::make($data, $request->rules(), $request->messages(), $request->attributes());
    }

    private function validData(array $overrides = []): array
    {
        return array_replace([
            'name' => 'John Doe',
            'contact_method' => 'telegram',
            'contact_value' => '@john',
            'reason' => 'I need help.',
        ], $overrides);
    }
}
