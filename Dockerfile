# ----------------------------------------------------------
# ✅ Laravel + Nginx + PHP 8.2 build for Render (production-ready)
# ----------------------------------------------------------
FROM webdevops/php-nginx:8.2-alpine

WORKDIR /app

# Αντιγραφή composer αρχείων
COPY composer.json composer.lock* ./

# Εγκατάσταση dependencies χωρίς dev πακέτα
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress || true

# Αντιγραφή όλου του project
COPY . .

# 🟢 FIX: εξασφαλίζουμε ότι ο φάκελος logs υπάρχει και έχει δικαιώματα
RUN mkdir -p storage/logs && \
    chmod -R 777 storage bootstrap/cache

# Περιβάλλον Nginx/PHP-FPM
ENV WEB_DOCUMENT_ROOT=/app/public
ENV PHP_FPM_LISTEN=127.0.0.1:9000

# Καθαρίζουμε caches & ξαναφτιάχνουμε config
RUN php artisan config:clear || true && \
    php artisan cache:clear || true && \
    php artisan route:clear || true && \
    php artisan view:clear || true && \
    php artisan config:cache || true

EXPOSE 80

# ✅ Τρέχει migrate και optimize πριν ξεκινήσει το app
CMD php artisan migrate --force && php artisan optimize && /usr/bin/supervisord
