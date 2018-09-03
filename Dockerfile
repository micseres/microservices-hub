FROM zaherg/php72-swoole:latest
LABEL maintainer "Pavlov Viktor <zogxray@gmail.com>"

USER root

ENV COMPOSER_ALLOW_SUPERUSER 1
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && \
    composer self-update --clean-backups

WORKDIR /app
COPY composer.json ./
RUN composer install
COPY . ./
