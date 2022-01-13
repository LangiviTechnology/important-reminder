FROM php:8.1.0-cli
WORKDIR /app
RUN apt update
RUN mkdir -p /usr/local/lib/php/extensions/no-debug-non-zts-20210902/
COPY boot_deps.sh /app
COPY boot.sh /app
COPY install_composer.sh /app
COPY server.php /app
COPY composer.json /app
RUN ./boot_deps.sh
RUN ./install_composer.sh
RUN composer install
RUN  docker-php-ext-configure mysqli --with-mysqli=mysqlnd && \
    docker-php-ext-install mysqli 
RUN php -m 
ENV ENV=DE1V
ADD . .
RUN php -m
RUN apt-get update && apt-get install -y libpq-dev
RUN docker-php-ext-install pgsql || exit 0 
RUN echo "extension=pgsql" > /usr/local/etc/php/conf.d/pgsql.ini
RUN ./boot.sh
ENV LD_LIBRARY_PATH=/usr/local/lib
ENTRYPOINT ./server.php