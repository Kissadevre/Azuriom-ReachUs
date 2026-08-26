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
        'authenticated_redirect_help' => 'Enter a local path beginning with one slash, for example /support. External URLs are rejected.',
        'captcha_help' => 'The form automatically uses the CAPTCHA provider configured in Azuriom global settings.',
        'updated' => 'The Reach Us settings were updated.',
        'redirect_format' => 'The authenticated visitor redirect must be a local path beginning with one slash.',
    ],
    'logs' => [
        'response_deleted' => 'Deleted Reach Us response #:message_id',
        'settings_updated' => 'Updated Reach Us settings',
    ],
];
