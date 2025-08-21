# Stage 1: Build PHP dependencies & assets
FROM php:8.2-fpm AS build

# Install build dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libpng-dev \
    libxml2-dev \
    libsodium-dev \
    libpq-dev \
    default-mysql-client \
    default-libmysqlclient-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libonig-dev \
    libzip-dev \
    pkg-config \
    build-essential \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_pgsql \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        sodium \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy source code
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Build frontend assets
RUN npm install && npm run build

# Cache Laravel configs
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache


# Stage 2: Production Image
FROM php:8.2-fpm

# Install runtime dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    gettext-base \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    libsodium-dev \
    libpq-dev \
    default-mysql-client \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Copy PHP extensions
COPY --from=build /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=build /usr/local/etc/php/conf.d /usr/local/etc/php/conf.d

# Copy built Laravel app
COPY --from=build /var/www/html /var/www/html

# Copy configs
COPY ./docker/nginx.conf.template /etc/nginx/nginx.conf.template
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Railway provides $PORT
ENV PORT=8000

EXPOSE ${PORT}

# Generate nginx.conf from template & run supervisor
CMD envsubst '${PORT}' < /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf && supervisord -c /etc/supervisor/conf.d/supervisord.conf
