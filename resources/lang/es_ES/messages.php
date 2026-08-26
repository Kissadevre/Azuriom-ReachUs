<?php

return [
    'title' => 'Contáctanos',
    'description' => 'Contacta con nuestro equipo sin crear una cuenta.',
    'methods' => [
        'whatsapp' => 'WhatsApp', 'telegram' => 'Telegram', 'email' => 'Correo electrónico', 'discord' => 'Discord',
    ],
    'contact_fields' => [
        'whatsapp' => ['label' => 'Número de WhatsApp', 'help' => 'Introduce de 6 a 16 caracteres: solo dígitos y, opcionalmente, un + al inicio.'],
        'telegram' => ['label' => 'Usuario de Telegram', 'help' => 'Introduce el usuario con el que debemos contactarte.'],
        'email' => ['label' => 'Correo electrónico', 'help' => 'Introduce una dirección de correo válida.'],
        'discord' => ['label' => 'Usuario de Discord', 'help' => 'Introduce tu nombre de usuario actual de Discord.'],
    ],
    'form' => [
        'name' => 'Nombre', 'name_help' => 'Solo letras y espacios, hasta 64 caracteres.',
        'contact_method' => 'Medio de contacto preferido', 'choose_method' => 'Selecciona un medio de contacto',
        'contact_value' => 'Datos de contacto', 'reason' => 'Motivo de contacto',
        'submit' => 'Enviar mensaje',
        'sent' => 'Tu mensaje se envió correctamente. Nos pondremos en contacto contigo pronto.',
        'too_many_attempts' => 'Has enviado demasiadas solicitudes. Espera un minuto e inténtalo de nuevo.',
    ],
    'notifications' => [
        'new_message' => 'Nueva respuesta de Reach Us de :name.',
    ],
    'validation' => [
        'name_format' => 'El nombre solo puede contener letras y espacios.',
        'whatsapp_format' => 'El número de WhatsApp debe contener de 6 a 16 caracteres: solo dígitos y, opcionalmente, un + al inicio.',
        'attributes' => ['name' => 'nombre', 'contact_method' => 'medio de contacto', 'contact_value' => 'datos de contacto', 'reason' => 'motivo'],
    ],
];
