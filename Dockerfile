FROM php:8.4-cli

# تثبيت مكتبات النظام وإضافات PHP المطلوبة لـ Laravel Octane و RoadRunner
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    && docker-php-ext-install pdo_mysql sockets pcntl posix zip mbstring bcmath \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# جلب Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# تثبيت مكتبات Laravel وتحميل محرك RoadRunner للينكس
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --optimize-autoloader --no-scripts --no-interaction --no-dev
RUN php ./vendor/bin/rr get-binary -n && chmod +x ./rr

EXPOSE 8080

# تشغيل الـ Migrations ثم تشغيل Laravel Octane بأقصى سرعة
CMD sh -c "php artisan migrate --force && php artisan octane:start --server=roadrunner --host=0.0.0.0 --port=${PORT:-8080} --workers=4"
