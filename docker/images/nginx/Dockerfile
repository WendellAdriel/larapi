FROM nginx:latest

COPY --chown=nginx:nginx ./images/nginx/default.conf /etc/nginx/conf.d
COPY ./images/nginx/nginx.conf /etc/nginx

ARG APP_CODE_PATH
ARG APP_INDEX_PATH
RUN sed -i "s#root __NGINX_ROOT;#root $APP_CODE_PATH/public;#" /etc/nginx/conf.d/default.conf
