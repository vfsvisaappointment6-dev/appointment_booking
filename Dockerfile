# Stage 1: Build stage
FROM php:8.2-fpm-alpine as builder

# Install system dependencies
RUN apk add --no-cache \
    build-base \
    curl \
    git \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    libzip-dev \
    oniguruma-dev

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    curl \
    mbstring \
    xml \
    gd \
    zip \
    opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy application files
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Stage 2: Runtime stage
FROM php:8.2-fpm-alpine

# Install runtime dependencies
RUN apk add --no-cache \
    nginx \
    curl \
    libpng \
    libjpeg-turbo \
    freetype \
    libzip \
    oniguruma

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    curl \
    mbstring \
    xml \
    gd \
    zip \
    opcache

# Copy PHP configuration
RUN echo "opcache.enable=1" > /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.memory_consumption=256" >> /usr/local/etc/php/conf.d/opcache.ini

# Copy application from builder stage
COPY --from=builder /app /app

WORKDIR /app

# Copy nginx config
COPY nginx.conf /etc/nginx/nginx.conf

# Create storage directories
RUN mkdir -p storage/logs && chmod -R 775 storage bootstrap/cache

# Expose port
EXPOSE 8080

# Start application
CMD ["/bin/sh", "-c", "php-fpm -D && nginx"]
