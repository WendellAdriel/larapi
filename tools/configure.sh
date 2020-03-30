#!/bin/bash

echo "CONFIGURING LARAPI..."
cp .env.example .env
php artisan key:generate --ansi
php artisan jwt:secret
clear
./vendor/bin/cghooks add --ignore-lock
echo "LARAPI CONFIGURED!!!"
