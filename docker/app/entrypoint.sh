#!/usr/bin/env bash

composer install

php artisan key:generate
php artisan storage:link
php artisan migrate
php artisan optimize:clear

npm install
npm run build

service cron start
service supervisor start
service php8.2-fpm start

crontab /etc/cron.d/crontab

nginx -g 'daemon off;'
