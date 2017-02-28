FROM php:5.4-apache

RUN echo "date.timezone=Europe/Paris" >> "/usr/local/etc/php/php.ini"
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mbstring
RUN a2enmod rewrite

RUN echo "Include sites-enabled/" >> /etc/apache2/apache2.conf
COPY apache.conf /etc/apache2/sites-available/000-default.conf
RUN ln -s /etc/apache2/sites-available/000-default.conf /etc/apache2/sites-enabled/000-default.conf


