FROM php:8.2-cli

RUN apt -y update && apt -y upgrade
RUN apt -y install npm git libpq-dev libmagickwand-dev
RUN docker-php-ext-install pdo_pgsql pgsql
RUN pecl install imagick && docker-php-ext-enable imagick

WORKDIR /var/www/

COPY ./entrypoint.sh ./entrypoint.sh

EXPOSE 1337

ENTRYPOINT ["./entrypoint.sh"]
