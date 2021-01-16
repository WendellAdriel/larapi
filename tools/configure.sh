#!/bin/bash

echo "CHECKING REQUIREMENTS..."

# Check composer is installed
if ! hash composer 2>/dev/null
then
    echo "Please, install composer and run this script again."
    exit 1
fi

# Check platform requirements (php version, extensions, etc)
if ! composer check-platform-reqs
then
    echo
    echo "#########################################################################################"
    echo "# PLEASE, INSTALL THE MISSING EXTENSIONS AND/OR PHP VERSION REPORTED IN THE LIST ABOVE. #"
    echo "#########################################################################################"
    exit 1
fi

echo "PLATFORM REQUIREMENTS: OK"

echo
echo "INSTALLING DEPENDENCIES..."
composer install
clear
echo "DEPENDENCIES INSTALLED!"

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
