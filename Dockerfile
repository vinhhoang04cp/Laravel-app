# syntax=docker/dockerfile:1.7

########## Stage 1: vendor (KHÔNG chạy apt ở đây)
FROM qlhd/dhcd-base-cli:dev.0.0.3 AS vendor
WORKDIR /var/www

ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_MEMORY_LIMIT=-1

COPY composer.json composer.lock ./

# Nếu runner bật BuildKit, uncomment để dùng cache composer
# RUN --mount=type=cache,target=/root/.composer/cache,sharing=locked \
#     composer install --no-dev --no-scripts --prefer-dist --no-progress --no-interaction

RUN composer install --no-scripts --prefer-dist --no-progress --no-interaction


########## Stage 2: runtime (đã có Nginx + FPM + Supervisor)
FROM qlhd/dhcd-base-runtime:dev.0.0.2
WORKDIR /var/www

COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/nginx/default.conf  /etc/nginx/conf.d/default.conf
COPY docker/php/uploads.ini     /usr/local/etc/php/conf.d/uploads.ini

COPY --chown=www-data:www-data . .
COPY --from=vendor /var/www/vendor ./vendor

RUN mkdir -p storage/logs bootstrap/cache \
 && touch storage/logs/laravel.log \
 && chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

EXPOSE 80 9000
CMD ["/usr/bin/supervisord","-c","/etc/supervisor/conf.d/supervisord.conf"]
