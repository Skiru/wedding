FROM composer as composer-prod
WORKDIR /app
COPY ./ ./
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-progress --no-suggest --ignore-platform-reqs

FROM php:7.4-fpm-alpine

RUN apk add --no-cache \
    $PHPIZE_DEPS && \
    docker-php-ext-install opcache && \
    docker-php-ext-enable opcache && \
    rm -rf /tmp/pear

RUN  mkdir -p /var/www/html/var/log/

COPY ./docker/php/php.ini /usr/local/etc/php/php.ini
COPY ./docker/php/php-fpm-pool.conf /etc/php7/php-fpm.d/www.conf

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer creates=/usr/local/bin/composer warn=no

ENV APP_ENV=prod \
    APP_SECRET=ca6777ca3b9d07d5a38d4f68036f232d

RUN  mkdir -p /var/www/html/var/log/
COPY ./docker/php/php.ini /etc/php7/conf.d/php.ini
COPY ./ /var/www/html
COPY --from=composer-prod /app/vendor /var/www/html/vendor

RUN chmod 777 -R /var/www/html
