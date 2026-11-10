# Learning Management System

A work-in-progress learning management system for selling an online course. The current application is an MVP built with Laravel. Its learner-facing screens are primarily in Bulgarian, and course content is currently configured in the application rather than managed through a course-authoring interface.

> **Project status:** In development. This repository is not a finished or production-ready LMS.

## Current features

- Account registration, login, email verification, password reset, and profile editing.
- One-time course access checkout using Stripe. Access is granted after the Stripe webhook confirms payment.
- A linear course experience with eight Vimeo lessons, saved viewing progress, and trophies.
- Project submission after earning at least 80 trophies, with a description, optional link, and file uploads.
- An admin dashboard showing learner and payment metrics and learner submission details.

## Technology

- PHP 8.2+
- Laravel 12
- SQLite by default (other Laravel-supported database connections can be configured)
- Node.js and npm
- Vite, Tailwind CSS, and Alpine.js
- Stripe for checkout and payment confirmation
- Vimeo for lesson playback

The Laravel application is in [`lms/`](lms/).

## Getting started

Install PHP 8.2 or newer, Composer, Node.js/npm, and SQLite. From the repository root:

```sh
cd lms
composer install
npm install
```

Create a local environment file from the example and generate an application key:

```sh
cp .env.example .env
php artisan key:generate
```

On Windows PowerShell, use `Copy-Item .env.example .env` instead of `cp`. The default database connection is SQLite. Create the database file before migrating:

```sh
touch database/database.sqlite
php artisan migrate
php artisan storage:link
npm run build
```

On Windows PowerShell, create the SQLite file with:

```powershell
New-Item -ItemType File -Path database/database.sqlite
```

Start the application in separate terminals:

```sh
php artisan serve
npm run dev
```

Then open `http://127.0.0.1:8000`.

## Stripe configuration

Stripe checkout requires a Stripe account and configured product/price. Add these values to `lms/.env`:

```dotenv
STRIPE_SECRET=your_stripe_secret_key
STRIPE_PRICE_ID=your_one_time_price_id
STRIPE_WEBHOOK_SECRET=your_webhook_signing_secret
```

For local webhook testing, install the [Stripe CLI](https://docs.stripe.com/stripe-cli) and forward checkout events to the application:

```sh
stripe listen --forward-to http://127.0.0.1:8000/stripe/webhook
```

Use the signing secret printed by the CLI as `STRIPE_WEBHOOK_SECRET`. Do not commit real credentials. Without valid Stripe configuration and a reachable webhook, the paid-access flow will not work end to end.

## Development and tests

Run the test suite from `lms/`:

```sh
php artisan test
```

The project also includes a Windows development helper, [`lms/start-dev.bat`](lms/start-dev.bat), which starts the Vite and Laravel servers and a Stripe webhook listener.

## Work in progress

The current implementation is intentionally limited and still needs development. In particular:

- The course is a fixed set of eight lessons; course, lesson, and video management are not available in an admin authoring interface.
- The admin area displays learner/submission information, but a complete project review workflow (such as approving or rejecting submissions) is not implemented.
- Payment, lesson-progress, submission, and admin workflows need broader automated and end-to-end testing.
- The interface and learner experience need further completion and review before a public production launch.

Please treat the application as an evolving prototype, not as a ready-to-deploy service.

## Contributing

Contributions and feedback are welcome. Before opening a pull request, please describe the change and run the test suite where practical.

## License

No project-level license has been specified yet. Until one is added, do not assume that this repository is available for reuse under an open-source license.
