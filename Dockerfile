# Stage 1: Builder
FROM php:8.2-cli-bookworm as builder

# Remove any PPA references that might be in the base image
RUN rm -f /etc/apt/sources.list.d/* || true

# Install system dependencies (Debian only, no PPA)
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy application files
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Stage 2: Runtime
FROM php:8.2-fpm-bookworm

# Remove any PPA references that might be in the base image
RUN rm -f /etc/apt/sources.list.d/* || true

# Install system dependencies (Debian only, no PPA)
RUN apt-get update && apt-get install -y --no-install-recommends \
    nginx \
    curl \
    ca-certificates \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions from official Debian repos (no PPA)
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    xml \
    opcache

# Verify PHP extensions
RUN php -m | grep -E "pdo_mysql|mbstring|xml|opcache"

# Copy PHP config
RUN echo "opcache.enable=1" > /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.memory_consumption=256" >> /usr/local/etc/php/conf.d/opcache.ini

# Copy application from builder
COPY --from=builder /app /app

WORKDIR /app

# Copy Nginx config
COPY nginx.conf /etc/nginx/nginx.conf

# Create necessary directories
RUN mkdir -p storage/logs storage/app bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

# Health check
HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
    CMD curl -f http://localhost:8080/ || exit 1

# Expose port
EXPOSE 8080

# Start FPM + Nginx
CMD ["/bin/sh", "-c", "php-fpm && nginx -g 'daemon off;'"]
