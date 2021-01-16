#!/bin/bash

echo "CONFIGURING LARAPI..."
cp .env.example .env
php artisan key:generate --ansi
php artisan jwt:secret
clear

echo "SETTING UP GIT HOOKS..."
./vendor/bin/cghooks add --ignore-lock

echo "GENERATING API DOCS..."
sh ./tools/swagger.sh
git add ./public/swagger

echo "LARAPI CONFIGURED!!!"
