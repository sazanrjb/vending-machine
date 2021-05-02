FROM php:8.0-fpm

ARG DOCKER_USER=dockeruser
ARG PUID=1000
ARG PGID=1000

RUN groupadd -g $PGID $DOCKER_USER && useradd -u $PUID -g $DOCKER_USER -m $DOCKER_USER && adduser $DOCKER_USER sudo

RUN	docker-php-ext-install pdo pdo_mysql;

WORKDIR /var/www/

EXPOSE 8080
RUN . ~/.bashrc
CMD [ "bash" ]
