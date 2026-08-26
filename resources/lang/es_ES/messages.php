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
    'contact_fields' => [
        'whatsapp' => ['label' => 'Número de WhatsApp', 'help' => 'Introduce de 6 a 16 caracteres: solo dígitos y, opcionalmente, un + al inicio.'],
        'telegram' => ['label' => 'Usuario de Telegram', 'help' => 'Utiliza solamente letras, números, guiones y guiones bajos.'],
        'email' => ['label' => 'Correo electrónico', 'help' => 'Utiliza solamente letras, números, @, guiones y guiones bajos.'],
        'discord' => ['label' => 'Usuario de Discord', 'help' => 'Utiliza solamente letras, números, guiones y guiones bajos.'],
    ],
    'form' => [
        'name' => 'Nombre', 'name_help' => 'Solo letras y espacios, hasta 64 caracteres.',
        'contact_method' => 'Medio de contacto preferido', 'choose_method' => 'Selecciona un medio de contacto',
        'contact_value' => 'Datos de contacto', 'reason' => 'Motivo de contacto',
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
        'whatsapp_format' => 'El número de WhatsApp debe contener de 6 a 16 caracteres: solo dígitos y, opcionalmente, un + al inicio.',
        'username_characters' => 'El usuario solo puede contener letras, números, guiones y guiones bajos.',
        'email_characters' => 'El correo solo puede contener letras, números, @, guiones y guiones bajos.',
        'terms_accepted' => 'Debes aceptar los términos o la política de privacidad antes de enviar tu mensaje.',
        'attributes' => ['name' => 'nombre', 'contact_method' => 'medio de contacto', 'contact_value' => 'datos de contacto', 'reason' => 'motivo', 'terms_accepted' => 'términos y condiciones'],
    ],
];
