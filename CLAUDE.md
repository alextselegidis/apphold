# Apphold

Self-hosted software telemetry app: it pings configured URLs, records downtime as incidents and notifies
by email. Laravel 12 on PHP 8.2+, MySQL, Blade views with Bootstrap 5, REST API through Sanctum and
Laravel Orion.

## Environment

Everything runs in Docker (`docker-compose.yml`): `php-fpm`, `nginx`, `mysql`, `mailpit`, `phpmyadmin`,
`swagger-ui`. The app is served on `${NGINX_PORT}` (check `docker compose port nginx 80`).

The host PHP is missing the `dom`/`xml` extensions, so run PHP through the container:

```bash
docker compose exec php-fpm php artisan migrate
docker compose exec php-fpm php artisan test
docker compose exec php-fpm php artisan view:clear   # after editing Blade files
docker compose exec php-fpm php artisan db:seed --class=DemoSeeder
```

Demo and default login: `admin@example.org` / `12345678`.

`Schedule::command('observers:ping')` runs every five minutes and drives the whole monitoring loop.

## Layout

- `app/Http/Controllers` – page controllers, `Api/V1` holds the Orion API controllers
- `app/Auth/AppSessionGuard.php` – session guard with an installation specific "remember me" cookie name
- `app/Http/Middleware/ValidateCsrfToken.php` – disables the shared `XSRF-TOKEN` cookie
- `helpers.php` – global helpers: `setting()`, `sort_link()`, `app_instance_id()`
- `resources/views` – `layouts/` (main, auth, message), `shared/` partials, `pages/`, `modals/`
- `public/styles/apphold.css`, `public/scripts/apphold.js` – the app's own CSS and JS
- `public/vendor/*` – vendored Bootstrap, Bootstrap Icons and Pace, loaded directly by the layouts
- `openapi.yml` – API contract, keep it in sync when API routes change

## Conventions

- Every PHP, Blade, CSS and JS file starts with the GPL header block, copy it from a neighbouring file.
- All user facing strings go through `__()`, with snake_case keys in `lang/en.json`.
- Vite exists but the views do not use it. Ship UI changes as plain CSS/JS in `public/`, do not add npm
  dependencies for something the browser or Bootstrap already does.
- Styling lives in `public/styles/apphold.css`, which defines design tokens (`--ah-*`) and then overrides
  Bootstrap through CSS variables. Use the tokens, avoid inline `style` attributes for anything reusable,
  and keep text on its background at 4.5:1 contrast or better.
- Tables belong in a `.table-responsive` wrapper. Never set `overflow: visible` on it, row dropdowns
  escape the scroll container through the fixed Popper strategy set in `apphold.js`.
- Cookie names must stay unique per installation, several Apphold instances can share one domain.
- Formatting: Prettier (`printWidth` 120, 4 spaces, single quotes) and Pint for PHP.
- Tests live in `tests/Feature` and run against SQLite in memory, configured in `phpunit.xml`.

## Releases

- Note user visible changes in `CHANGELOG.md` under `## [Unreleased]`. A changelog entry is at most
  120 lines long.
- Bump `APP_VERSION`. `config('app.version')` is the cache buster on the CSS and JS URLs, so without a
  bump returning users keep the old stylesheet.
- `bash build.sh` produces the distributable zip.

## Git

- Do not add a `Co-Authored-By: Claude ...` trailer to commit messages. Commits carry no Claude attribution.
- Do not create new branches unless the developer explicitly asks for one. Work on the current branch.
