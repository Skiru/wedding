FROM nginx:latest

COPY ./docker/assets/default.conf /etc/nginx/conf.d/default.conf
COPY ./public/assets /static/assets

RUN chmod 777 -R /static/assets