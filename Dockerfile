FROM php:8.3-apache
COPY src/ /var/www/html/
RUN apt-get update && \
    apt-get install -y
RUN apt-get install -y curl
RUN apt-get install -y build-essential libssl-dev zlib1g-dev libpng-dev libjpeg-dev libfreetype6-dev
RUN apt-get install -y libicu-dev
RUN a2enmod rewrite
RUN docker-php-ext-install \
    intl \
    mysqli \
    pdo \
    pdo_mysql && \
    docker-php-ext-enable pdo_mysql
RUN docker-php-ext-configure intl

RUN pecl install \
            xdebug

RUN docker-php-ext-enable \
            xdebug
RUN echo "xdebug.mode=debug, develop" | tee -a /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini > /dev/null && \
    echo "xdebug.start_with_request=yes" | tee -a /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini > /dev/null && \
    echo "xdebug.discover_client_host=true" | tee -a /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini > /dev/null && \
    echo "xdebug.client_host=host.docker.internal" | tee -a /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini > /dev/null

RUN ln -sf /proc/self/fd/2 /var/log/apache2/access.log && \
    ln -sf /proc/self/fd/2 /var/log/apache2/error.log


#set apache root
ENV APACHE_DOCUMENT_ROOT=/var/www/public
 # Silence FQDN warning
RUN echo 'ServerName localhost' >> /etc/apache2/apache2.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www