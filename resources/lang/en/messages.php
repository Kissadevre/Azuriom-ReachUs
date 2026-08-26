<?php

return [
    'title' => 'Reach Us',
    'description' => 'Contact our team without creating an account.',
    'methods' => [
        'whatsapp' => 'WhatsApp', 'telegram' => 'Telegram', 'email' => 'Email', 'discord' => 'Discord',
    ],
    'contact_fields' => [
        'whatsapp' => ['label' => 'WhatsApp number', 'help' => 'Enter 6 to 16 characters: digits only, with an optional + at the beginning.'],
        'telegram' => ['label' => 'Telegram username', 'help' => 'Enter the username we should contact.'],
        'email' => ['label' => 'Email address', 'help' => 'Enter a valid email address.'],
        'discord' => ['label' => 'Discord username', 'help' => 'Enter your current Discord username.'],
    ],
    'form' => [
        'name' => 'Name', 'name_help' => 'Letters and spaces only, up to 64 characters.',
        'contact_method' => 'Preferred contact method', 'choose_method' => 'Choose a contact method',
        'contact_value' => 'Contact details', 'reason' => 'Reason for contacting us',
        'submit' => 'Send message',
        'sent' => 'Your message was sent successfully. We will contact you soon.',
        'too_many_attempts' => 'You have sent too many requests. Please wait a minute and try again.',
    ],
    'notifications' => [
        'new_message' => 'New Reach Us response from :name.',
    ],
    'validation' => [
        'name_format' => 'The name may only contain letters and spaces.',
        'whatsapp_format' => 'The WhatsApp number must contain 6 to 16 characters: digits only, with an optional + at the beginning.',
        'attributes' => ['name' => 'name', 'contact_method' => 'contact method', 'contact_value' => 'contact details', 'reason' => 'reason'],
    ],
];
