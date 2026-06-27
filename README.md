# CMSFramework

A PHP 8.4 CMS framework built from scratch, running on Docker with Nginx and MySQL 8.0.

## What was implemented
 - A simple MVC for users and products with a relationship of many to many between the users and the products
 - As a structure you have the Model-View-Controller folders and the Core folder which contains the base extendable classes for each and the Router and Database classes.
 - I created a docker configuration for easy local development.
 - I created a migration script for easy database management in bin/migrate.php and all the migrations should be placed in the database/migrations folder.
 - All the routes are stored in src/routes.php
 - All the steps to initialise the project are down below.

 ## Total time spent
  - 2-3 hours with help from ClaudeCode

## Requirements

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) 24+
- [Git](https://git-scm.com/)

No local PHP, Composer, or MySQL installation required — everything runs inside Docker.

## Stack

| Service | Version |
|---------|---------|
| PHP-FPM | 8.4 (Alpine) |
| Nginx   | 1.27 (Alpine) |
| MySQL   | 8.0 |
| Composer | 2.8 |

## First-time Setup

**1. Clone the repository**

```bash
git clone <repository-url> CMSFramework
cd CMSFramework
```

**2. Copy the environment file and set your secrets**

```bash
cp .env.example .env
```

Open `.env` and set secure values for `DB_PASSWORD` and `DB_ROOT_PASSWORD`. The other defaults are fine for local development.

**3. Build the Docker images**

```bash
docker compose build
```

This compiles the PHP 8.4 image with all extensions and Composer. Only needed on first setup or after changing the Dockerfile.

**4. Start the services**

```bash
docker compose up -d
```

This starts PHP-FPM, Nginx, and MySQL in the background. MySQL runs a healthcheck — PHP will not start until MySQL is ready (usually 15–30 seconds on first boot while the database initialises).

**5. Install PHP dependencies**

```bash
docker compose run --rm composer install
```

This runs Composer inside a container and exits when done. The `vendor/` directory is written to your local project folder via the bind mount.

**6. Run database migrations**

```powershell
docker compose exec php php bin/migrate.php
```

This applies all pending migrations in order and records each one so it is never run twice.

**7. Verify the setup**

Open [http://localhost:8080](http://localhost:8080) in your browser. You should see the welcome page with a link to `/users`.

## Daily Development

```bash
# Start all services
docker compose up -d

# Stop all services (data is preserved)
docker compose down

# View live logs
docker compose logs -f
docker compose logs -f php
docker compose logs -f nginx
docker compose logs -f mysql
```

## Composer

All Composer commands are run through the `composer` Docker service so you never need a local PHP installation.

```bash
# Install dependencies (after cloning or pulling changes)
docker compose run --rm composer install

# Add a package
docker compose run --rm composer require vendor/package

# Add a dev-only package
docker compose run --rm composer require --dev vendor/package

# Remove a package
docker compose run --rm composer remove vendor/package

# Rebuild the autoloader
docker compose run --rm composer dump-autoload -o

# Run PHPUnit tests
docker compose run --rm composer test
```

## Running PHP Commands

To run arbitrary PHP commands inside the container:

```bash
docker compose exec php php -v
docker compose exec php php -r "echo PHP_VERSION;"
```

To open a shell in the PHP container:

```bash
docker compose exec php bash
```

## Database

MySQL is exposed on port `3306` of your host machine (configurable via `DB_EXPOSE_PORT` in `.env`). You can connect with any MySQL client using the credentials from your `.env` file.

| Setting  | Default value      |
|----------|--------------------|
| Host     | `127.0.0.1`        |
| Port     | `3306`             |
| Database | `cmsdb`            |
| Username | `cmsuser`          |
| Password | *(set in `.env`)*  |

To connect from inside the PHP container, use `DB_HOST=mysql` (the Docker service name), which is already set in `.env`.

To open a MySQL shell inside the container:

```bash
docker compose exec mysql mysql -u cmsuser -p cmsdb
```

## Configuration

| File | Purpose |
|------|---------|
| `.env` | Environment variables (ports, DB credentials, app settings) |
| `docker/php/php.ini` | PHP runtime settings (memory, upload limits, OPcache) |
| `nginx/default.conf` | Nginx virtual host configuration |
| `docker/php/Dockerfile` | PHP image definition and installed extensions |
| `docker-compose.yml` | Service orchestration |

### Changing the HTTP port

Edit `NGINX_PORT` in `.env`, then restart:

```bash
docker compose down && docker compose up -d
```

### Rebuilding after Dockerfile changes

```bash
docker compose build --no-cache php
docker compose up -d
```

## Project Structure

```
.
├── bin/
│   └── migrate.php      # Migration runner CLI script
├── database/
│   └── migrations/      # SQL migration files (run in order)
├── docker/php/          # PHP Dockerfile and php.ini
├── nginx/               # Nginx virtual host config
├── public/              # Web root — index.php entry point
├── src/
│   ├── Core/            # Framework core (Router, Database, Model, Controller, View)
│   ├── Controllers/     # HTTP controllers
│   ├── Models/          # Database models
│   └── Views/           # PHP templates
├── tests/               # PHPUnit test suite (CMS\Tests\ namespace)
├── vendor/              # Composer dependencies (gitignored)
├── .env                 # Local environment variables (gitignored)
├── .env.example         # Environment template (committed)
├── composer.json        # PHP dependencies and autoloader config
└── docker-compose.yml   # Docker service orchestration
```

All application code goes in `src/` under the `CMS\` namespace. The PSR-4 autoloader maps `CMS\Foo\Bar` to `src/Foo/Bar.php`.

## Migrations

Migration files are plain SQL files in `database/migrations/`, named with a numeric prefix to define run order (e.g. `001_create_users_table.sql`, `002_create_products_table.sql`).

The runner script at `bin/migrate.php` tracks applied migrations in a `migrations` table and only runs files that haven't been applied yet, making it safe to call repeatedly.

**Run all pending migrations:**
```powershell
docker compose exec php php bin/migrate.php
```

**Check which migrations have run:**
```powershell
docker compose exec php php bin/migrate.php --status
```

Example status output:
```
[✓] 001_create_users_table.sql     2026-06-27 10:00:00
[✓] 002_create_products_table.sql  2026-06-27 10:00:01
```

**Via Composer scripts:**
```powershell
docker compose run --rm composer migrate
docker compose run --rm composer migrate:status
```

To add a new migration, create a `.sql` file in `database/migrations/` with the next number prefix and run the migrate command — only the new file will be applied.

## Resetting the Database

To wipe the database and start fresh:

```bash
docker compose down -v
docker compose up -d
```

The `-v` flag removes the named MySQL volume. All data will be lost.

## Ports Reference

| Service | Host port | Notes |
|---------|-----------|-------|
| Nginx   | `8080`    | Configurable via `NGINX_PORT` in `.env` |
| MySQL   | `3306`    | Configurable via `DB_EXPOSE_PORT` in `.env` |
| PHP-FPM | —         | Internal only (`cms_php:9000`), not exposed to host |
