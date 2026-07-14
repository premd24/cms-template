# CMS

A Laravel 12 admin CMS for managing media assets — folders of files, a Lottie animation library, and "use cases" that pair a media item with editorial metadata (tag, title, description, caption).

Built with Laravel 12, Blade + Tailwind v4, spatie/laravel-medialibrary, jQuery + DataTables, SweetAlert2, and `lottie-web`. Tested with Pest 3.

## Requirements

- PHP `^8.2`
- Composer
- Node.js (Vite 7)
- MySQL 5.7+ / 8+ (default connection; see `.env.example`)

## Setup

```bash
composer setup
```

That single script installs Composer + npm deps, copies `.env.example` to `.env`, generates an app key, runs migrations, and builds frontend assets.

Seed the default admin user:

```bash
php artisan db:seed --class=UserSeeder
```

Default login: **super.admin@gmail.com / password** — change this before deploying anywhere reachable.

## Running locally

```bash
composer dev
```

This boots `php artisan serve`, the queue listener, `php artisan pail` (log tail), and the Vite dev server together via `concurrently`.

If you only want the frontend:

```bash
npm run dev
```

Production build:

```bash
npm run build
```

## Testing

```bash
composer test
# or
php artisan test
```

Tests use Pest 3 and run against in-memory SQLite (see `phpunit.xml`).

## Code style

```bash
./vendor/bin/pint           # apply formatting
./vendor/bin/pint --test    # check only
```

## Features

- **File Manager** — create folders, upload files (single or batched), delete. Folders are UUID-keyed and own a `files` media collection via spatie/laravel-medialibrary.
- **Animations** — a Lottie-focused view of files stored in a dedicated `Animations` folder. Server-side DataTables list with search, sort, and pagination.
- **Use Cases** — pair an uploaded media item with `tag`, `title`, `description`, and `caption`. Media is selected from existing folders rather than re-uploaded.
- **Auth** — session-based login for a seeded super admin. No public registration.

## Project layout

| Path | What's there |
| --- | --- |
| `app/Http/Controllers/` | Web controllers — `FileManager/`, `AnimationController`, `UseCaseController`, `Auth/LoginController`. |
| `app/Models/` | `User`, `Folder`, `Media` (extends Spatie's with `HasUuids`), `UseCase`. |
| `app/Http/Resources/` | API resources including `PaginationCollection` for DataTables responses. |
| `routes/web.php` | All routes (no separate api file). |
| `resources/views/` | Blade pages under `pages/` and reusable `<x-ui.*>` components under `components/ui/`. |
| `resources/js/app.js` | Boots jQuery, DataTables, SweetAlert2, Lottie onto `window`. |
| `database/migrations/` | Includes a migration that adds a `uuid` column to spatie's `media` table. |

For deeper architectural notes (especially the dual-mode controllers, the UUID'd Media model, and the DataTables request protocol), see [`CLAUDE.md`](./CLAUDE.md).

## Security

See [`SECURITY.md`](./SECURITY.md) for the vulnerability reporting process.

## License

MIT.
