FROM php:8.1.0-cli
WORKDIR /app
RUN apt update
COPY boot_deps.sh /app
COPY boot.sh /app
COPY install_composer.sh /app
COPY server.php /app
COPY composer.json /app
RUN ./boot_deps.sh
RUN ./install_composer.sh
RUN composer install
RUN ./boot.sh
ENV LD_LIBRARY_PATH=/usr/local/lib
ENV ENV=DE1V
ADD . .
RUN php -m
ENTRYPOINT ./server.php