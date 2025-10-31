# ----------------------------------------------------------
# ✅ Laravel + Nginx + PHP 8.2 build for Render (stable)
# ----------------------------------------------------------
FROM webdevops/php-nginx:8.2-alpine

# Ο φάκελος εργασίας
WORKDIR /app

# Αντιγραφή composer αρχείων πρώτα (για caching)
COPY composer.json composer.lock* ./

# Εγκατάσταση PHP dependencies χωρίς dev packages
RUN composer install --no-dev --optimize-autoloader || true

# Αντιγραφή υπόλοιπων αρχείων project
COPY . .

# Δικαιώματα στους φακέλους που χρειάζεται ο Laravel
RUN chmod -R 777 storage bootstrap/cache

# Ορισμός web root και FPM port
ENV WEB_DOCUMENT_ROOT=/app/public
ENV PHP_FPM_LISTEN=127.0.0.1:9000

# Καθαρισμός και επαναδημιουργία caches
RUN php artisan config:clear || true && \
    php artisan cache:clear || true && \
    php artisan route:clear || true && \
    php artisan view:clear || true && \
    php artisan config:cache || true

# Θύρα για Render
EXPOSE 10000

# Εκκίνηση όλων των υπηρεσιών
CMD ["/usr/bin/supervisord"]
