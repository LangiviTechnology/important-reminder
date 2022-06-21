#!/bin/bash
if ! which libtoolize >/dev/null; then
       apt update
       apt install git libtool automake build-essential \
                                     autoconf \
                                     re2c \
                                     bison \
                                     libuv1-dev \
                                     libsqlite3-dev \
                                     libpq-dev \
                                     libonig-dev \
                                     libfcgi-dev \
                                     libfcgi0ldbl \
                                     libjpeg-dev \
                                     libpng-dev \
                                     libssl-dev \
                                     libxml2-dev \
                                     libcurl4-openssl-dev \
                                     libxpm-dev \
                                     libgd-dev \
                                     libfreetype6-dev \
                                     libxslt1-dev \
                                     libpspell-dev \
                                     libzip-dev \
                                     libgccjit-10-dev \
                                    wget -y
    fi