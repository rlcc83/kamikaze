FROM php:8.1-fpm

ARG DEBUG=false
ARG REMOTE_HOST="127.0.0.1"

RUN apt-get update \
#   && apt-get install -y libzip-dev zlib1g-dev libxml2-dev libmcrypt4
  && apt-get install -y libzip-dev zlib1g-dev libxml2-dev

RUN docker-php-ext-install pdo pdo_mysql \
    && docker-php-ext-install xml \
    && docker-php-ext-install zip \
    && docker-php-ext-install soap \
    && docker-php-ext-install intl

RUN apt-get update && apt-get install -y default-mysql-client && rm -rf /var/lib/apt
RUN apt-get update && apt-get install -y vim nano

# Install Composer
COPY --from=composer:2.4.4 /usr/bin/composer /usr/local/bin/composer

# SSH client
RUN apt-get update && apt-get install -y openssh-client

# required to generate mycrypt redsys signature
RUN apt-get -y install gcc make autoconf libc-dev pkg-config
# RUN apt-get -y install libmcrypt-dev

RUN pecl config-set php_ini "${PHP_INI_DIR}/php.ini"
# RUN pecl install mcrypt-1.0.1 \
#     && docker-php-ext-enable mcrypt;

RUN apt-get --allow-releaseinfo-change update \
    && apt-get install -y libxml2-dev
    # && apt-get install -y libxml2-dev libmcrypt4

# Install required to generate pdf's
RUN apt-get update && apt-get install -y python3 python3-pip \
    # && apt-get install -y xvfb xserver-xephyr vnc4server \
    && apt-get install -y xvfb xserver-xephyr \
    && apt-get install -y libfontconfig wkhtmltopdf \
    && pip3 install pyvirtualdisplay

COPY "./ini/php.ini-development" "/usr/local/etc/php/php.ini"
COPY "./ini/docker-php-ext-xdebug.ini" "/usr/local/etc/php/temporal_docker-php-ext-xdebug.ini"
COPY "./template/ssh/config" "/root/.ssh/config"

RUN chmod 600 /root/.ssh/config && chown root /root/.ssh/config;

# # DEBUG
# RUN if [ $DEBUG = "true" ] ; then \
#     pecl install xdebug-2.6.0; \
#     docker-php-ext-enable xdebug;  fi ;

# RUN if [ $DEBUG = "true" ] ; then \
#       cp /usr/local/etc/php/temporal_docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
#       echo "\nxdebug.remote_host="$REMOTE_HOST >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; fi ;

WORKDIR /var/www
