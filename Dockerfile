FROM php:8.4-fpm

WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libpq-dev \
    libonig-dev \
    libzip-dev \
    libwebp-dev \
    librdkafka-dev \
    net-tools \
    iputils-ping \
    micro \
    supervisor \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN pecl install redis && docker-php-ext-enable redis
RUN docker-php-ext-install pdo pdo_pgsql mbstring zip exif pcntl bcmath opcache
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp
RUN docker-php-ext-install gd

# Install composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Create non-root user
RUN groupadd -g 1000 www-globalyhub
RUN useradd -u 1000 -ms /bin/bash -g www-globalyhub www-globalyhub

# Copy entire Laravel project
COPY --chown=www-globalyhub:www-globalyhub . /var/www/


USER www-globalyhub

CMD ["php-fpm"]
