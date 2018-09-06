FROM zaherg/php72-swoole
LABEL maintainer "Pavlov Viktor <zogxray@gmail.com>"

USER root

WORKDIR /app
COPY composer.json ./
RUN composer install --no-dev
COPY . ./

CMD ["php", "bin/service.php"]


