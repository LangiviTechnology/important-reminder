FROM php:8.1.0-cli
WORKDIR /app
COPY boot_deps.sh /app
COPY boot.sh /app
COPY install_composer.sh /app
COPY server.php /app
COPY composer.json /app
RUN ./boot_deps.sh
RUN docker-php-ext-install zip
RUN ./install_composer.sh
RUN  docker-php-ext-configure mysqli --with-mysqli=mysqlnd && \
    docker-php-ext-install mysqli
ENV ENV=DE1V
RUN php -m
RUN docker-php-ext-install pgsql || exit 0
RUN ./boot.sh
ADD . .
RUN composer install
ENV LD_LIBRARY_PATH=/usr/local/lib
ENTRYPOINT sleep 1000000