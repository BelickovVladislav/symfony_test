FROM php:7.4-fpm

RUN apt-get update && apt-get install -y zlib1g-dev libzip-dev libicu-dev libpq-dev imagemagick git libonig-dev \
	&& docker-php-ext-install opcache \
	&& docker-php-ext-install intl \
	&& docker-php-ext-install mbstring \
	&& docker-php-ext-install pdo_mysql \
	&& docker-php-ext-install zip \
	&& php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/local/bin --filename=composer \
	&& chmod +sx /usr/local/bin/composer  

EXPOSE 9000