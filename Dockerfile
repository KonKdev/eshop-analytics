# ----------------------------
# ✅ Laravel + Nginx + PHP 8.2
# ----------------------------

FROM webdevops/php-nginx:8.2-alpine

# Ορισμός φακέλου εργασίας
WORKDIR /app

# Αντιγραφή όλων των αρχείων στο container
COPY . .

# Εγκατάσταση dependencies
RUN composer install --no-dev --optimize-autoloader

# Δικαιώματα για storage και cache
RUN chmod -R 777 storage bootstrap/cache

# Ορισμός public ως web root
ENV WEB_DOCUMENT_ROOT=/app/public

# Cache για config, routes, views
RUN php artisan config:cache || true
RUN php artisan route:cache || true
RUN php artisan view:cache || true

# Port που θα χρησιμοποιηθεί
EXPOSE 10000

# Εκκίνηση Nginx & PHP-FPM
CMD ["/usr/bin/supervisord"]
