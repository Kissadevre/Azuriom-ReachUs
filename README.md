# Reach Us

Reach Us is an Azuriom plugin by Kissadere and Zibuu that gives unauthenticated visitors a secure contact form without requiring account registration.

## Features

- Guest-only contact form for WhatsApp, Telegram, email, and Discord.
- Up to four configurable guest contact channels with custom names, Bootstrap Icons, accepted data types, and length limits.
- Conditional server-side validation and a 64-character, letters-only name field.
- Native Azuriom CAPTCHA integration.
- Configurable per-IP submission rate limit.
- Temporary guest-form closure with a friendly unavailable notice and server-side submission blocking.
- Optional server-enforced acceptance checkbox with configurable terms or privacy-policy link.
- Navbar-style authenticated visitor redirect supporting external links, pages, posts, the posts list, and plugin routes.
- Permission-protected response inbox with read status and deletion.
- Native Azuriom notifications for users whose roles have `reachus.responses`.
- Optional Discord WebHook notifications containing only the arrival date and time, with encrypted URL storage and a manual connection test.
- English and Spanish (`es_ES`) translations.

## Administration

Assign `reachus.responses` to roles that should manage and receive notifications for contact responses. Assign `reachus.settings` to roles that may configure the form rate limit, policy acceptance, and authenticated visitor redirect. Users also need Azuriom admin access to open plugin administration routes.

CAPTCHA is configured globally in Azuriom. Reach Us renders Azuriom's native `elements.captcha` view and uses its native `captcha` middleware, so providers added or updated by Azuriom require no duplicated plugin view.

The authenticated visitor redirect follows Azuriom's navbar destination model. Existing local-path settings remain compatible, external links are limited to HTTP(S), and the Reach Us form route is excluded from plugin destinations to prevent redirect loops.

Discord notifications are disabled by default. They are sent only after the contact message has been persisted, and delivery failures are contained so the administration inbox remains the source of truth.

## Development checks

From this plugin directory, run:

```shell
../../vendor/bin/phpunit -c phpunit.xml
```

All plugin source remains within `plugins/reachus`; generated Azuriom plugin caches and public assets are not source files.
Database schema changes are kept in separate, chronologically ordered migrations instead of being consolidated into the original table creation.
