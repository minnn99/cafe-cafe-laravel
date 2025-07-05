FROM php:8.1-fpm

ENV HOME /root
ENV PATH $PATH:$HOME/.composer/vendor/bin
ENV LARAVELDIRECTORY laravel-curriculum
ENV LARAVELPROJECT curriculum-app

COPY ./php/php.ini /usr/local/etc/php/php.ini

RUN apt update && apt upgrade -y
RUN apt-get install sudo
RUN sudo apt-get install -y git vim
RUN sudo apt-get install -y zip unzip

RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql

#composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer
ENV PATH $PATH:/composer/vendor/bin

WORKDIR /var
RUN mkdir ${LARAVELDIRECTORY}
#COPY ./curriculum/laravel /var/${LARAVELDIRECTORY}
WORKDIR /var/${LARAVELDIRECTORY}
RUN composer global require "laravel/installer"

#RUN laravel new ${LARAVELPROJECT}
#RUN chmod -R 775 ${LARAVELPROJECT}
#RUN chmod -R 777 storage
