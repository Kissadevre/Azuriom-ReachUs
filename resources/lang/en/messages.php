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
    'contact_fields' => [
        'whatsapp' => ['label' => 'WhatsApp number', 'help' => 'Enter 6 to 16 characters: digits only, with an optional + at the beginning.'],
        'telegram' => ['label' => 'Telegram username', 'help' => 'Use letters, numbers, hyphens, and underscores only.'],
        'email' => ['label' => 'Email address', 'help' => 'Use letters, numbers, @, hyphens, and underscores only.'],
        'discord' => ['label' => 'Discord username', 'help' => 'Use letters, numbers, hyphens, and underscores only.'],
    ],
    'form' => [
        'name' => 'Name', 'name_help' => 'Letters and spaces only, up to 64 characters.',
        'contact_method' => 'Preferred contact method', 'choose_method' => 'Choose a contact method',
        'contact_value' => 'Contact details', 'reason' => 'Reason for contacting us',
        'submit' => 'Send message',
        'secure_note' => 'Your submission is protected by our anti-abuse controls.',
        'sent' => 'Your message was sent successfully. We will contact you soon.',
        'too_many_attempts' => 'You have sent too many requests. Please wait a minute and try again.',
    ],
    'notifications' => [
        'new_message' => 'New Reach Us response from :name.',
    ],
    'validation' => [
        'name_format' => 'The name may only contain letters and spaces.',
        'whatsapp_format' => 'The WhatsApp number must contain 6 to 16 characters: digits only, with an optional + at the beginning.',
        'username_characters' => 'The username may only contain letters, numbers, hyphens, and underscores.',
        'email_characters' => 'The email may only contain letters, numbers, @, hyphens, and underscores.',
        'attributes' => ['name' => 'name', 'contact_method' => 'contact method', 'contact_value' => 'contact details', 'reason' => 'reason'],
    ],
];
