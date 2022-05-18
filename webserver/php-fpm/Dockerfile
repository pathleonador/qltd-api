FROM php:7.4-fpm

ARG APCU_VERSION=5.1.18

LABEL Maintainer="Pat Leonador <pleonador@gmail.com>" \
    Description="Base setup for api development of Q Ltd fullstack exam using PHP, NGINX and PostgreSQL."

# Get frequently used tools
RUN apt-get update && apt-get install -y \
    build-essential \
    libpq-dev \
    libicu-dev \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    locales \
    zip \
    unzip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    git \
    curl \
    wget \
    nodejs \
    npm 

RUN docker-php-ext-configure zip

RUN docker-php-ext-install \
    bcmath \
    mbstring \
    pcntl \
    intl \
    zip \
    opcache && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# apcu for caching, xdebug for debugging and also phpunit coverage
RUN pecl install \
    apcu-${APCU_VERSION} \
    xdebug \
    && docker-php-ext-enable \
    apcu \
    xdebug


RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# php-cs-fixer tool
RUN wget https://cs.symfony.com/download/php-cs-fixer-v2.phar -O /usr/local/bin/php-cs-fixer
RUN chmod +x /usr/local/bin/php-cs-fixer

WORKDIR /var/www


# Configure non-root user.
ARG PUID=1000
ENV PUID ${PUID}
ARG PGID=1000
ENV PGID ${PGID}

RUN groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

RUN chown -R www-data:www-data /var/www

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

EXPOSE 8080

CMD ["php-fpm"]