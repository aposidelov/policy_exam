FROM php:8.1.0-fpm-alpine

# Install packages
RUN apk add --no-cache curl git build-base zlib-dev oniguruma-dev autoconf bash

# Mysql

RUN apk add --no-cache libpq-dev && docker-php-ext-install pdo_mysql

# Postgres
#RUN apk add --no-cache libpq-dev && docker-php-ext-install pdo_pgsql

# Opcache
#RUN docker-php-ext-install opcache

# Configure non-root user
ARG PUID=1000
ARG PGID=1000
RUN apk --no-cache add shadow && \
    groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

RUN { \
		echo 'opcache.memory_consumption=256'; \
		echo 'opcache.validate_timestamps=0'; \
		echo 'opcache.max_accelerated_files=20000'; \
	} > /usr/local/etc/php/conf.d/opcache-recommended.ini

WORKDIR /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

CMD php-fpm

EXPOSE 9000