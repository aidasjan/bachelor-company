# Wholesale Management Platform - Company Portal

## Requirements

- PHP 8.0.2+
- MySQL 8+

## Installation

Install the application by running `composer install`.

## Running

Run database migrations using `php artisan migrate:fresh`.

To seed database with test data run `php artisan db:seed`.

Environment variables for database, email and some other settings need to be set using the contents of `.env.local` file. Rename the file to `.env` and customize the values.

## Testing

Run tests using `php artisan test`. Tests use an SQLite database, so empty `testdb.sqlite` file needs to exist in a root directory.
