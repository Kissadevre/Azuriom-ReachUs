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
            ['whatsapp', '+5215512345678'],
            ['telegram', 'zibuu_support'],
            ['email', 'hello@example'],
            ['discord', 'zibuu-support'],
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

    public function test_whatsapp_accepts_only_an_optional_leading_plus_and_up_to_16_characters(): void
    {
        foreach (['1234567890123456', '+123456789012345'] as $number) {
            $this->assertFalse($this->validator($this->validData([
                'contact_method' => 'whatsapp',
                'contact_value' => $number,
            ]))->fails(), $number.' should be valid.');
        }

        foreach (['12345A789', '123+456789', '12345678901234567', '+1234567890123456'] as $number) {
            $this->assertTrue($this->validator($this->validData([
                'contact_method' => 'whatsapp',
                'contact_value' => $number,
            ]))->fails(), $number.' should be invalid.');
        }
    }

    public function test_discord_and_telegram_allow_only_letters_numbers_hyphens_and_underscores(): void
    {
        foreach (['discord', 'telegram'] as $method) {
            foreach (['User-Name_123', 'support_team'] as $username) {
                $this->assertFalse($this->validator($this->validData([
                    'contact_method' => $method,
                    'contact_value' => $username,
                ]))->fails(), $method.' value '.$username.' should be valid.');
            }

            foreach (['@username', 'user.name', 'user name', 'user#1'] as $username) {
                $this->assertTrue($this->validator($this->validData([
                    'contact_method' => $method,
                    'contact_value' => $username,
                ]))->fails(), $method.' value '.$username.' should be invalid.');
            }
        }
    }

    public function test_email_allows_only_at_sign_hyphens_and_underscores_as_special_characters(): void
    {
        foreach (['user@example', 'user_name@example', 'user-name@example'] as $email) {
            $this->assertFalse($this->validator($this->validData([
                'contact_method' => 'email',
                'contact_value' => $email,
            ]))->fails(), $email.' should be valid.');
        }

        foreach (['user.name@example', 'user+tag@example', 'user name@example', 'user#1@example'] as $email) {
            $this->assertTrue($this->validator($this->validData([
                'contact_method' => 'email',
                'contact_value' => $email,
            ]))->fails(), $email.' should be invalid.');
        }
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
            'contact_value' => 'john_user',
            'reason' => 'I need help.',
        ], $overrides);
    }
}
