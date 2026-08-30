<?php

return [
    'title' => 'Reach Us',
    'description' => 'Contact our team without creating an account.',
    'hero' => [
        'eyebrow' => 'We are here to help',
        'features' => 'Contact form features',
        'no_account' => 'No account required',
        'protected' => 'Protected submission',
        'direct_reply' => 'Reply through your preferred channel',
    ],
    'unavailable' => [
        'title' => 'Contact is temporarily unavailable',
        'description' => 'It is not possible to contact us at this time. Please try again later.',
    ],
    'sections' => [
        'identity' => 'Tell us who you are',
        'identity_help' => 'Use the name we should address you by.',
        'contact' => 'How should we contact you?',
        'contact_help' => 'Choose one channel and enter the corresponding details.',
        'message' => 'How can we help?',
        'message_help' => 'Give us enough context to understand your request.',
    ],
    'methods' => [
        'whatsapp' => 'WhatsApp', 'telegram' => 'Telegram', 'email' => 'Email', 'discord' => 'Discord',
    ],
    'contact_types' => [
        'whatsapp' => 'Enter :min to :max characters: digits only, with an optional + at the beginning.',
        'username' => 'Enter :min to :max characters using letters, numbers, hyphens, and underscores only.',
        'email' => 'Enter :min to :max characters using a valid email with letters, numbers, @, hyphens, and underscores only.',
        'text' => 'Enter between :min and :max characters.',
        'alphanumeric' => 'Enter :min to :max characters using letters and numbers only.',
        'numeric' => 'Enter :min to :max digits only.',
    ],
    'form' => [
        'name' => 'Name', 'name_help' => 'Letters and spaces only, up to 64 characters.',
        'contact_method' => 'Preferred contact method', 'choose_method' => 'Choose a contact method',
        'contact_value' => 'Contact details', 'contact_value_for' => ':channel contact details',
        'reason' => 'Reason for contacting us',
        'terms_prefix' => 'I have read and accept the',
        'submit' => 'Send message',
        'secure_note' => 'Your submission is protected by our anti-abuse controls.',
        'sent' => 'Your message was sent successfully. We will contact you soon.',
        'too_many_attempts' => 'You have sent too many requests. Please wait a minute and try again.',
    ],
    'notifications' => [
        'new_message' => 'New Reach Us response from :name.',
    ],
    'webhook' => [
        'new_message_title' => 'New contact message',
        'new_message_description' => 'A new guest contact message was received in Reach Us.',
        'test_title' => 'Reach Us WebHook test',
        'test_description' => 'Discord notifications for Reach Us are configured correctly.',
    ],
    'validation' => [
        'name_format' => 'The name may only contain letters and spaces.',
        'whatsapp_format' => 'The WhatsApp number must contain :min to :max characters: digits only, with an optional + at the beginning.',
        'username_characters' => 'The username may only contain letters, numbers, hyphens, and underscores.',
        'email_characters' => 'The email may only contain letters, numbers, @, hyphens, and underscores.',
        'alphanumeric_format' => 'The contact details may only contain letters and numbers.',
        'numeric_format' => 'The contact details may only contain digits.',
        'contact_min' => 'The contact details must contain at least :min characters.',
        'contact_max' => 'The contact details may not contain more than :max characters.',
        'terms_accepted' => 'You must accept the terms or privacy policy before sending your message.',
        'attributes' => ['name' => 'name', 'contact_method' => 'contact method', 'contact_value' => 'contact details', 'reason' => 'reason', 'terms_accepted' => 'terms and conditions'],
    ],
];
