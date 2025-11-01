# ----------------------------------------------------------
# ✅ Laravel + Nginx + PHP 8.2 build for Render (fixed permissions)
# ----------------------------------------------------------
FROM webdevops/php-nginx:8.2-alpine

WORKDIR /app

COPY composer.json composer.lock* ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress || true

COPY . .

# 🟢 FIX: Δίνουμε άδεια σε όλους τους φακέλους που γράφει το Laravel
RUN mkdir -p storage/logs && \
    chmod -R 777 storage bootstrap/cache && \
    chown -R application:application storage bootstrap/cache

ENV WEB_DOCUMENT_ROOT=/app/public
ENV PHP_FPM_LISTEN=127.0.0.1:9000

RUN php artisan config:clear || true && \
    php artisan cache:clear || true && \
    php artisan route:clear || true && \
    php artisan view:clear || true && \
    php artisan config:cache || true

EXPOSE 80


# Build Vite assets
RUN npm install && npm run build

# ✅ Πριν ξεκινήσει, κάνουμε migrate & optimize
CMD php artisan migrate --force && php artisan optimize && chmod -R 777 storage bootstrap/cache && /usr/bin/supervisord
