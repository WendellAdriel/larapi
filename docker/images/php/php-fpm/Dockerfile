ARG BASE_IMAGE
FROM ${BASE_IMAGE}

COPY ./images/php/php-fpm/php-fpm.d/ /usr/local/etc/php-fpm.d/

RUN sed -i "s/__APP_USER_NAME/$APP_USER_NAME/" /usr/local/etc/php-fpm.d/* \
    && sed -i "s/__APP_GROUP_NAME/$APP_GROUP_NAME/" /usr/local/etc/php-fpm.d/*

USER $APP_USER_NAME

EXPOSE 9000

ENTRYPOINT ["php-fpm"]
