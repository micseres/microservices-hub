FROM zaherg/php72-swoole
LABEL maintainer "Pavlov Viktor <zogxray@gmail.com>"

USER root

WORKDIR /app
COPY composer.json ./
RUN composer install
COPY . ./

CMD ["php", "bin/sserver.php"]


