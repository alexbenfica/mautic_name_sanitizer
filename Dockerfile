FROM php:7.2-apache

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

ADD config.ini /var/www/html/config.ini
ADD mautic_name_sanitizer.php /var/www/html/mautic_name_sanitizer.php