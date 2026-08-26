<?php

return [
    'title' => 'Reach Us',
    'nav' => ['responses' => 'Respuestas', 'settings' => 'Configuración'],
    'permissions' => [
        'responses' => 'Ver y gestionar respuestas de Reach Us y recibir notificaciones de nuevas respuestas',
        'settings' => 'Gestionar la configuración de Reach Us',
    ],
    'responses' => [
        'title' => 'Respuestas de contacto', 'show_title' => 'Respuesta de contacto #:id',
        'status' => 'Estado', 'name' => 'Nombre', 'method' => 'Medio de contacto',
        'contact_value' => 'Datos de contacto', 'reason' => 'Motivo', 'received_at' => 'Recibido el',
        'read' => 'Leído', 'unread' => 'Sin leer', 'unread_count' => ':count sin leer',
        'empty' => 'Todavía no se han recibido respuestas de contacto.', 'mark_unread' => 'Marcar como no leído',
        'marked_unread' => 'La respuesta se marcó como no leída.', 'deleted' => 'La respuesta se eliminó.',
    ],
    'settings' => [
        'title' => 'Configuración de Reach Us', 'rate_limit' => 'Límite de envíos',
        'requests_per_minute' => 'solicitudes por minuto',
        'rate_limit_help' => 'Máximo de intentos permitidos desde una dirección IP por minuto (1–100).',
        'authenticated_redirect' => 'Redirección para visitantes autenticados',
        'authenticated_redirect_help' => 'Introduce una ruta local que comience con una barra, por ejemplo /support. No se permiten URL externas.',
        'captcha_help' => 'El formulario utiliza automáticamente el proveedor CAPTCHA configurado en los ajustes globales de Azuriom.',
        'updated' => 'La configuración de Reach Us se actualizó.',
        'redirect_format' => 'La redirección para visitantes autenticados debe ser una ruta local que comience con una barra.',
    ],
    'logs' => [
        'response_deleted' => 'Eliminó la respuesta de Reach Us #:message_id',
        'settings_updated' => 'Actualizó la configuración de Reach Us',
    ],
];
