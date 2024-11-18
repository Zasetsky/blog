FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
	git \
	libpq-dev \
	zip unzip \
	supervisor \
	cron \
	&& docker-php-ext-install pdo pdo_pgsql

RUN pecl install redis && docker-php-ext-enable redis

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
	&& composer --version

WORKDIR /var/www/html

COPY . .

RUN composer install --optimize-autoloader --no-dev

RUN php artisan key:generate

RUN chown -R www-data:www-data /var/www/html \
	&& chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

COPY cron-laravel /etc/cron.d/cron-laravel
RUN chmod 0644 /etc/cron.d/cron-laravel \
	&& crontab /etc/cron.d/cron-laravel

COPY laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["sh", "/usr/local/bin/entrypoint.sh"]
