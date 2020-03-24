#!/bin/bash

echo "INSTALLING DEPENDENCIES..."
composer install
clear
echo "DEPENDENCIES INSTALLED!"
echo "CONFIGURING LARAPI..."
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
composer global require friendsofphp/php-cs-fixer
clear
./vendor/bin/cghooks add --ignore-lock
export PATH="$PATH:$HOME/.composer/vendor/bin"
echo "LARAPI CONFIGURED!!!"
