<?php

return [
    'title' => 'Reach Us',
    'nav' => ['responses' => 'Responses', 'settings' => 'Settings'],
    'permissions' => [
        'responses' => 'View and manage Reach Us responses and receive new response notifications',
        'settings' => 'Manage Reach Us settings',
    ],
    'responses' => [
        'title' => 'Contact responses', 'show_title' => 'Contact response #:id',
        'status' => 'Status', 'name' => 'Name', 'method' => 'Contact method',
        'contact_value' => 'Contact details', 'reason' => 'Reason', 'received_at' => 'Received at',
        'read' => 'Read', 'unread' => 'Unread', 'unread_count' => ':count unread',
        'empty' => 'No contact responses have been received yet.', 'mark_unread' => 'Mark as unread',
        'marked_unread' => 'The response was marked as unread.', 'deleted' => 'The response was deleted.',
    ],
    'settings' => [
        'title' => 'Reach Us settings', 'rate_limit' => 'Submission rate limit',
        'requests_per_minute' => 'requests per minute',
        'rate_limit_help' => 'Maximum submission attempts allowed from one IP address per minute (1–100).',
        'authenticated_redirect' => 'Authenticated visitor redirect',
        'authenticated_redirect_help' => 'Authenticated visitors are sent to the selected destination instead of seeing the guest contact form.',
        'redirect_type' => 'Destination type',
        'redirect_types' => [
            'link' => 'External link',
            'page' => 'Page',
            'post' => 'Post',
            'posts' => 'Posts list',
            'plugin' => 'Plugin',
        ],
        'redirect_link_help' => 'Enter an HTTP(S) URL. Existing local paths such as /support are also supported.',
        'redirect_link_format' => 'The destination must be an HTTP(S) URL or a local path beginning with one slash.',
        'choose_page' => 'Choose a page',
        'choose_post' => 'Choose a post',
        'choose_plugin' => 'Choose a plugin destination',
        'posts_help' => 'Authenticated visitors will be redirected to the posts list.',
        'captcha_help' => 'The form automatically uses the CAPTCHA provider configured in Azuriom global settings.',
        'updated' => 'The Reach Us settings were updated.',
    ],
    'logs' => [
        'response_deleted' => 'Deleted Reach Us response #:message_id',
        'settings_updated' => 'Updated Reach Us settings',
    ],
];
