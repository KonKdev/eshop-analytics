# ----------------------------
# ✅ Laravel + Nginx + PHP 8.2
# ----------------------------

# Εικόνα που περιέχει ήδη PHP, Composer & Nginx
FROM webdevops/php-nginx:8.2-alpine

# Φάκελος εργασίας μέσα στο container
WORKDIR /app

# Αντιγραφή composer αρχείων
COPY composer.json composer.lock* ./

# Εγκατάσταση PHP dependencies χωρίς dev
RUN composer install --no-dev --optimize-autoloader

# Αντιγραφή όλων των υπόλοιπων αρχείων
COPY . .

# Δικαιώματα για storage και cache
RUN chmod -R 777 storage bootstrap/cache

# Ορισμός public folder ως web root
ENV WEB_DOCUMENT_ROOT=/app/public

# Cache για config, routes, views
RUN php artisan config:cache && php artisan route:cache && php artisan view:cache

# Port που θα χρησιμοποιήσει το Render
EXPOSE 10000

# Εκκίνηση Nginx και PHP-FPM
CMD ["/usr/bin/supervisord"]
