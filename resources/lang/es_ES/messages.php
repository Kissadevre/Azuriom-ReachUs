<?php

return [
    'title' => 'Contáctanos',
    'description' => 'Contacta con nuestro equipo sin crear una cuenta.',
    'hero' => [
        'eyebrow' => 'Estamos para ayudarte',
        'features' => 'Características del formulario de contacto',
        'no_account' => 'No necesitas una cuenta',
        'protected' => 'Envío protegido',
        'direct_reply' => 'Respuesta por tu medio preferido',
    ],
    'unavailable' => [
        'title' => 'El contacto no está disponible temporalmente',
        'description' => 'Por el momento no es posible contactarnos. Inténtalo de nuevo más tarde.',
    ],
    'sections' => [
        'identity' => 'Cuéntanos quién eres',
        'identity_help' => 'Utiliza el nombre con el que debemos dirigirnos a ti.',
        'contact' => '¿Cómo debemos contactarte?',
        'contact_help' => 'Selecciona un medio e introduce los datos correspondientes.',
        'message' => '¿Cómo podemos ayudarte?',
        'message_help' => 'Proporciona suficiente contexto para comprender tu solicitud.',
    ],
    'methods' => [
        'whatsapp' => 'WhatsApp', 'telegram' => 'Telegram', 'email' => 'Correo electrónico', 'discord' => 'Discord',
    ],
    'contact_types' => [
        'whatsapp' => 'Introduce de :min a :max caracteres: solo dígitos y, opcionalmente, un + al inicio.',
        'username' => 'Introduce de :min a :max caracteres utilizando solamente letras, números, guiones y guiones bajos.',
        'email' => 'Introduce de :min a :max caracteres utilizando un correo válido con letras, números, @, guiones y guiones bajos.',
        'text' => 'Introduce entre :min y :max caracteres.',
        'alphanumeric' => 'Introduce de :min a :max caracteres utilizando solamente letras y números.',
        'numeric' => 'Introduce de :min a :max dígitos.',
    ],
    'form' => [
        'name' => 'Nombre', 'name_help' => 'Solo letras y espacios, hasta 64 caracteres.',
        'contact_method' => 'Medio de contacto preferido', 'choose_method' => 'Selecciona un medio de contacto',
        'contact_value' => 'Datos de contacto', 'contact_value_for' => 'Datos de contacto de :channel',
        'reason' => 'Motivo de contacto',
        'terms_prefix' => 'He leído y acepto la',
        'submit' => 'Enviar mensaje',
        'secure_note' => 'Tu envío está protegido por nuestros controles contra abusos.',
        'sent' => 'Tu mensaje se envió correctamente. Nos pondremos en contacto contigo pronto.',
        'too_many_attempts' => 'Has enviado demasiadas solicitudes. Espera un minuto e inténtalo de nuevo.',
    ],
    'notifications' => [
        'new_message' => 'Nueva respuesta de Reach Us de :name.',
    ],
    'validation' => [
        'name_format' => 'El nombre solo puede contener letras y espacios.',
        'whatsapp_format' => 'El número de WhatsApp debe contener de :min a :max caracteres: solo dígitos y, opcionalmente, un + al inicio.',
        'username_characters' => 'El usuario solo puede contener letras, números, guiones y guiones bajos.',
        'email_characters' => 'El correo solo puede contener letras, números, @, guiones y guiones bajos.',
        'alphanumeric_format' => 'Los datos de contacto solo pueden contener letras y números.',
        'numeric_format' => 'Los datos de contacto solo pueden contener dígitos.',
        'contact_min' => 'Los datos de contacto deben contener al menos :min caracteres.',
        'contact_max' => 'Los datos de contacto no pueden contener más de :max caracteres.',
        'terms_accepted' => 'Debes aceptar los términos o la política de privacidad antes de enviar tu mensaje.',
        'attributes' => ['name' => 'nombre', 'contact_method' => 'medio de contacto', 'contact_value' => 'datos de contacto', 'reason' => 'motivo', 'terms_accepted' => 'términos y condiciones'],
    ],
];
