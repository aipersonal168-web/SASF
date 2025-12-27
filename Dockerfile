FROM richarvey/nginx-php-fpm:8.2

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN php artisan key:generate --force
RUN php artisan config:clear
RUN php artisan route:clear
RUN php artisan view:clear

EXPOSE 80
