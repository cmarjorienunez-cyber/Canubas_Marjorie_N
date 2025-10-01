FROM php:7.4-apache

# Install required packages and PHP extensions
RUN apt-get update && apt-get install -y curl unzip libzip-dev \
    && docker-php-ext-install pdo_mysql \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html
COPY . /var/www/html

# Install dependencies
RUN composer install --no-dev --no-interaction --prefer-dist || true

# Fix permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]

