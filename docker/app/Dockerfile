FROM ubuntu:22.04

# Set working directory
WORKDIR /var/www

ARG WWWUSER
ARG WWWGROUP

ENV DEBIAN_FRONTEND noninteractive
ENV TZ=UTC

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Update repositories
RUN apt-get update && apt-get install -y software-properties-common

RUN LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php

# Install dependencies
RUN apt-get install -y jpegoptim gifsicle gnupg gosu curl ca-certificates zip cron nginx nano vim optipng pngquant unzip git supervisor sqlite3 libcap2-bin libpng-dev python2 libmagickwand-dev libheif-dev libxml2-dev strace

# Install PHP and extenstions
RUN apt-get install -y php8.2-fpm \
       php8.2-pgsql php8.2-gd  php8.2-imagick \
       php8.2-curl php8.2-memcached \
       php8.2-mysql php8.2-mbstring php8.2-sqlite3 \
       php8.2-xml php8.2-zip php8.2-bcmath \
       php8.2-intl php8.2-readline \
       php8.2-msgpack php8.2-igbinary php8.2-ldap \
       php8.2-redis php8.2-xdebug \
       php8.2-soap

# Install composer
RUN curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

# Istall Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Install Databases
RUN apt-get update \
    && apt-get install -y mysql-client \
    && apt-get install -y postgresql-client

# Make apt-get clean
RUN apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Copy custom configurations
COPY ./etc/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY ./etc/php/fpm/zzz-settings-override.ini /etc/php/8.2/fpm/conf.d/zzz-settings-override.ini
COPY ./etc/php/cli/zzz-settings-override.ini /etc/php/8.2/cli/conf.d/zzz-settings-override.ini
COPY ./etc/nginx/app.conf /etc/nginx/sites-enabled/default
COPY ./etc/crontab/crontab /etc/cron.d/crontab

# Set user and group
RUN groupadd --force -g $WWWGROUP www-data
RUN usermod -u ${WWWUSER} www-data && groupmod -g ${WWWGROUP} www-data

ENTRYPOINT ["sh", "./docker/app/entrypoint.sh"]
