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
        'authenticated_redirect_help' => 'Los visitantes autenticados serán enviados al destino seleccionado en lugar de ver el formulario para invitados.',
        'redirect_type' => 'Tipo de destino',
        'redirect_types' => [
            'link' => 'Enlace externo',
            'page' => 'Página',
            'post' => 'Publicación',
            'posts' => 'Lista de publicaciones',
            'plugin' => 'Plugin',
        ],
        'redirect_link_help' => 'Introduce una URL HTTP(S). También se admiten rutas locales existentes como /support.',
        'redirect_link_format' => 'El destino debe ser una URL HTTP(S) o una ruta local que comience con una sola barra.',
        'choose_page' => 'Selecciona una página',
        'choose_post' => 'Selecciona una publicación',
        'choose_plugin' => 'Selecciona un destino de plugin',
        'posts_help' => 'Los visitantes autenticados serán redirigidos a la lista de publicaciones.',
        'captcha_help' => 'El formulario utiliza automáticamente el proveedor CAPTCHA configurado en los ajustes globales de Azuriom.',
        'updated' => 'La configuración de Reach Us se actualizó.',
    ],
    'logs' => [
        'response_deleted' => 'Eliminó la respuesta de Reach Us #:message_id',
        'settings_updated' => 'Actualizó la configuración de Reach Us',
    ],
];
