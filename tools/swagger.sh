#!/bin/bash

php ./vendor/bin/openapi --bootstrap ./tools/swagger-constants.php --output ./public/swagger ./config/swagger.php ./app
