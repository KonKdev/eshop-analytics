# ----------------------------------------------------------
# ✅ Laravel + Nginx + PHP 8.2 build for Render (stable)
# ----------------------------------------------------------
FROM webdevops/php-nginx:8.2-alpine

WORKDIR /app

COPY composer.json composer.lock* ./
RUN composer install --no-dev --optimize-autoloader || true

COPY . .

RUN chmod -R 777 storage bootstrap/cache

ENV WEB_DOCUMENT_ROOT=/app/public
ENV PHP_FPM_LISTEN=127.0.0.1:9000

RUN php artisan config:clear || true && \
    php artisan cache:clear || true && \
    php artisan route:clear || true && \
    php artisan view:clear || true && \
    php artisan config:cache || true

EXPOSE 80

# ✅ Τρέχει migrate ΜΕΤΑ το φόρτωμα του .env και πριν ξεκινήσει το app
CMD php artisan migrate --force && /usr/bin/supervisord
