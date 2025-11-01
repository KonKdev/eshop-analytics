# ----------------------------------------------------------
# ✅ Laravel + Nginx + PHP 8.2 build for Render (production-ready)
# ----------------------------------------------------------
FROM webdevops/php-nginx:8.2-alpine

# Ο φάκελος της εφαρμογής
WORKDIR /app

# Αντιγραφή αρχείων composer πρώτα (βελτιώνει caching)
COPY composer.json composer.lock* ./

# Εγκατάσταση dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress || true

# Αντιγραφή όλου του project
COPY . .

# Δικαιώματα στα storage και cache (απαραίτητο για Render)
RUN chmod -R 777 storage bootstrap/cache

# Δηλώνουμε το root του web server
ENV WEB_DOCUMENT_ROOT=/app/public
ENV PHP_FPM_LISTEN=127.0.0.1:9000

# Καθαρίζουμε και ξαναφτιάχνουμε όλα τα cached αρχεία config/routes/views
RUN php artisan config:clear || true && \
    php artisan cache:clear || true && \
    php artisan route:clear || true && \
    php artisan view:clear || true && \
    php artisan config:cache || true

# Το Laravel ακούει στο port 80 (Render το χρειάζεται)
EXPOSE 80

# ✅ Σωστή σειρά: τρέχει migrations αφού φορτωθεί το .env και πριν ξεκινήσει το supervisord
CMD php artisan migrate --force && php artisan optimize && /usr/bin/supervisord
