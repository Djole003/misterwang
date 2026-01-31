@echo off
cls

echo Pokrecem composer install...
composer install

echo Generisem app key...
php artisan key:generate

echo Pokrecem migracije...
php artisan migrate --force

echo Pokrecem sve seedere...
php artisan db:seed --force

echo Pokrecem Laravel development server...
php artisan serve --host=127.0.0.1 --port=8000

pause
