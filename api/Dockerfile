FROM composer:latest AS deps

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install --no-dev --no-scripts --no-autoloader

COPY . .

RUN composer dump-autoload --optimize

FROM dunglas/frankenphp:php8.5-alpine

RUN install-php-extensions \
    pcntl \
    pdo_pgsql \
    intl \
    zip \
    opcache \
    xdebug

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY --from=deps /app .

ENV OCTANE_WORKERS=4
ENV OCTANE_MAX_REQUESTS=500

ENTRYPOINT php artisan config:cache && php artisan route:cache && php artisan event:cache && php artisan octane:frankenphp --workers=${OCTANE_WORKERS} --max-requests=${OCTANE_MAX_REQUESTS}
