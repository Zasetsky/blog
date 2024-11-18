#!/bin/sh

echo "Установка зависимостей..."
composer install --optimize-autoloader --no-dev

echo "Генерация ключа приложения..."
php artisan key:generate

echo "Выполняю миграции и сиды..."
php artisan migrate --force
php artisan db:seed --force

echo "Запускаю Supervisor..."
supervisord -c /etc/supervisor/conf.d/laravel-worker.conf &

echo "Запускаю PHP-FPM..."
exec php-fpm
