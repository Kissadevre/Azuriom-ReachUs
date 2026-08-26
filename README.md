# Reach Us

Reach Us is an Azuriom plugin by Kissadere and Zibuu that gives unauthenticated visitors a secure contact form without requiring account registration.

## Features

- Guest-only contact form for WhatsApp, Telegram, email, and Discord.
- Conditional server-side validation and a 64-character, letters-only name field.
- Native Azuriom CAPTCHA integration.
- Configurable per-IP submission rate limit.
- Navbar-style authenticated visitor redirect supporting external links, pages, posts, the posts list, and plugin routes.
- Permission-protected response inbox with read status and deletion.
- Native Azuriom notifications for users whose roles have `reachus.responses`.
- English and Spanish (`es_ES`) translations.

## Administration

Assign `reachus.responses` to roles that should manage and receive notifications for contact responses. Assign `reachus.settings` to roles that may configure the form rate limit and authenticated visitor redirect. Users also need Azuriom admin access to open plugin administration routes.

CAPTCHA is configured globally in Azuriom. When a supported CAPTCHA provider and its keys are configured there, Reach Us automatically renders and verifies it.

The authenticated visitor redirect follows Azuriom's navbar destination model. Existing local-path settings remain compatible, external links are limited to HTTP(S), and the Reach Us form route is excluded from plugin destinations to prevent redirect loops.

## Development checks

From this plugin directory, run:

```shell
../../vendor/bin/phpunit -c phpunit.xml
```

All plugin source remains within `plugins/reachus`; generated Azuriom plugin caches and public assets are not source files.
