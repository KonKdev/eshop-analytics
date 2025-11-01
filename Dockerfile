# ----------------------------------------------------------
# ✅ Laravel + Nginx + PHP 8.2 build for Render (fixed permissions + vite)
# ----------------------------------------------------------
FROM webdevops/php-nginx:8.2-alpine

WORKDIR /app

# ----------------------------------------------------------
# 1️⃣ Εγκαθιστούμε PHP dependencies
# ----------------------------------------------------------
COPY composer.json composer.lock* ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress || true

# ----------------------------------------------------------
# 2️⃣ Αντιγράφουμε όλο τον κώδικα
# ----------------------------------------------------------
COPY . .

# ----------------------------------------------------------
# 3️⃣ Εγκαθιστούμε Node & Vite dependencies και κάνουμε build
# ----------------------------------------------------------
# Το webdevops/php-nginx δεν έχει node, οπότε το προσθέτουμε εμείς
RUN apk add --no-cache nodejs npm && \
    npm install && \
    npm run build

# ----------------------------------------------------------
# 4️⃣ Δίνουμε δικαιώματα εγγραφής στα σωστά folders
# ----------------------------------------------------------
RUN mkdir -p storage/logs && \
    chmod -R 777 storage bootstrap/cache && \
    chown -R application:application storage bootstrap/cache

# ----------------------------------------------------------
# 5️⃣ Περιβάλλον PHP/Nginx
# ----------------------------------------------------------
ENV WEB_DOCUMENT_ROOT=/app/public
ENV PHP_FPM_LISTEN=127.0.0.1:9000

# ----------------------------------------------------------
# 6️⃣ Καθαρισμός & caching config
# ----------------------------------------------------------
RUN php artisan config:clear || true && \
    php artisan cache:clear || true && \
    php artisan route:clear || true && \
    php artisan view:clear || true && \
    php artisan config:cache || true

EXPOSE 80

# ----------------------------------------------------------
# 7️⃣ Εκτελούμε migrate + optimize πριν σηκωθεί το app
# ----------------------------------------------------------
CMD php artisan migrate --force && php artisan optimize && chmod -R 777 storage bootstrap/cache && /usr/bin/supervisord
