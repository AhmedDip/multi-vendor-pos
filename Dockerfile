FROM public.ecr.aws/docker/library/php:8.2-fpm as php82
# Set working directory
WORKDIR /var/www

# Add docker php ext repo
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Install php extensions
RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions mbstring pdo_mysql zip exif pcntl gd memcached

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    unzip \
    git \
    curl \
    lua-zlib-dev \
    libmemcached-dev \
    nginx \
    libmagickwand-dev --no-install-recommends

RUN apt-get install libicu-dev -y
RUN docker-php-ext-install intl
RUN docker-php-ext-enable intl
# Install supervisor
RUN apt-get install -y supervisor
# RUN  apt update && sudo apt install -y software-properties-common
# RUN  add-apt-repository ppa:ondrej/php
# RUN  apt update
# RUN apt-get install -y opcache

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy code to /var/www
COPY --chown=www:www-data . /var/www

# add root to www group
ADD . /var/www
RUN chown -R www-data:www-data /var/www
RUN chmod -R ug+w /var/www/storage
RUN chmod -Rf 777 /var/www/storage
RUN chmod -Rf 777 /var/www/storage/logs
# RUN chown -R root:root /var/www/public/images

# Copy nginx/php/supervisor configs
RUN cp docker/supervisor.conf /etc/supervisord.conf
RUN cp docker/php.ini /usr/local/etc/php/conf.d/app.ini
RUN cp docker/nginx.conf /etc/nginx/sites-enabled/default

COPY docker/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

RUN docker-php-ext-install opcache

# PHP Error Log Files
RUN mkdir /var/log/php
RUN touch /var/log/php/errors.log && chmod 777 /var/log/php/errors.log

# Deployment steps
RUN composer update
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache
RUN php artisan optimize
RUN php artisan storage:link

RUN chmod +x /var/www/docker/run.sh
RUN chmod -Rf 777 . /var/www/storage/logs/
RUN chmod -R 777 /var/www/public/images

EXPOSE 80
ENTRYPOINT ["/var/www/docker/run.sh"]
