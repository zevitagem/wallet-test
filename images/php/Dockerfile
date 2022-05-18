#origin
FROM php:8.0-apache AS php8

#copies
COPY src /var/www/html
COPY images/php/run.sh /tmp

#workdir
ENV APPPATH app
WORKDIR /var/www/html/$APPPATH

#tools
RUN apt-get -y update

#commands
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer