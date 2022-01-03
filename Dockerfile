FROM php:8.1.0-cli
WORKDIR /app
RUN apt update
COPY boot_deps.sh /app
COPY boot.sh /app
COPY server.php /app
RUN ./boot_deps.sh
RUN ./boot.sh
ENV LD_LIBRARY_PATH=/usr/local/lib
ENTRYPOINT ./server.php