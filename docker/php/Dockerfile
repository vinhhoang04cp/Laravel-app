# syntax=docker/dockerfile:1.7

########## Stage 1: vendor
FROM qlhd/dhcd-base-cli:dev.0.0.3 AS vendor
WORKDIR /var/www

ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_MEMORY_LIMIT=-1

# chỉ copy composer.json (bỏ file lock nếu không cần strict version)
COPY composer.json ./

RUN composer install --no-scripts --prefer-dist --no-progress --no-interaction


########## Stage 2: runtime
FROM qlhd/dhcd-base-runtime:dev.0.0.2
WORKDIR /var/www

COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/nginx/default.conf  /etc/nginx/conf.d/default.conf
COPY docker/php/uploads.ini     /usr/local/etc/php/conf.d/uploads.ini

# XÓA default site config của Debian ngay trong image
RUN rm -f /etc/nginx/sites-enabled/default

# copy source code (bỏ vendor/ trong .dockerignore)
COPY --chown=www-data:www-data . .

# copy vendor từ stage 1
COPY --from=vendor /var/www/vendor ./vendor

# chuẩn bị thư mục storage và cache cho Laravel
RUN mkdir -p storage/logs bootstrap/cache \
 && touch storage/logs/laravel.log \
 && chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

EXPOSE 80 9000
CMD ["/usr/bin/supervisord","-c","/etc/supervisor/conf.d/supervisord.conf"]
