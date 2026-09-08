# Contributing to Apphold

Thanks for taking the time to help out. Apphold is a self-hosted software telemetry app built with
Laravel 12, MySQL and Blade views, and every contribution — a bug report, a translation, a patch — is
welcome.

By participating in this project you agree to abide by the [Code of Conduct](CODE_OF_CONDUCT.md).

## Ways to contribute

* **Report a bug** — open an issue with the bug report template and include your PHP, MySQL and
  Apphold versions.
* **Suggest a feature** — open an issue with the feature request template and describe the problem
  before the solution.
* **Improve the docs** — the `README.md` and the inline help texts are fair game.
* **Send a pull request** — see below.

## Getting the app running

Everything runs in Docker through `docker-compose.yml` (`php-fpm`, `nginx`, `mysql`, `mailpit`,
`phpmyadmin`, `swagger-ui`):

```bash
git clone https://github.com/alextselegidis/apphold.git
cd apphold
cp .env.example .env
docker compose up -d
docker compose exec php-fpm composer install
docker compose exec php-fpm php artisan migrate
docker compose exec php-fpm php artisan db:seed --class=DemoSeeder   # optional demo data
```

Find the app URL with `docker compose port nginx 80` and log in with `admin@example.org` /
`12345678`.

Run the test suite before you push:

```bash
docker compose exec php-fpm php artisan test
```

Tests live in `tests/Feature` and run against an in-memory SQLite database.

## Coding conventions

* Every PHP, Blade, CSS and JS file starts with the GPL header block — copy it from a neighbouring
  file.
* All user facing strings go through `__()` with snake_case keys in `lang/en.json`.
* Ship UI changes as plain CSS/JS in `public/styles/apphold.css` and `public/scripts/apphold.js`. Do
  not add npm dependencies for something Bootstrap or the browser already does.
* Use the `--ah-*` design tokens instead of inline `style` attributes and keep text contrast at
  4.5:1 or better.
* Formatting is handled by [Pint](https://laravel.com/docs/pint) for PHP and
  [Prettier](https://prettier.io) (120 columns, 4 spaces, single quotes) for everything else.
* Keep `openapi.yml` in sync whenever you touch an API route.
* Note user visible changes in `CHANGELOG.md` under `## [Unreleased]`.

## Pull request checklist

1. Fork the repository and create your branch from `main`.
2. Keep the change focused — one topic per pull request.
3. Add or update tests for the behaviour you changed.
4. Run `php artisan test` and the formatters, then fill in the pull request template.

Maintainers review pull requests as time allows. Please be patient, and feel free to ping the issue
if a week goes by without a response.

## License

Apphold is released under the [GPL v3.0](LICENSE). By contributing you agree that your work is
licensed under the same terms.
