# Use an official PHP runtime as a parent image
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    gnupg2 \
    ca-certificates \
    lsb-release \
    build-essential

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy the existing application code into the container
COPY . .

# Install Laravel dependencies
RUN composer install

# Install npm dependencies and build assets with Vite
RUN npm install && npm run build

# Expose port 8000 to run Laravel's built-in server
EXPOSE 8000

# Run Laravel's built-in server
CMD php artisan serve --host=0.0.0.0 --port=8000
