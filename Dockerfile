FROM php:7.4-apache

# Install dependencies and required PHP extensions (gd, mysqli)
RUN apt-get -o Acquire::Check-Valid-Until=false update && apt-get install -y --no-install-recommends \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd mysqli \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite for CodeIgniter 3 routing
RUN a2enmod rewrite

# Configure Apache DocumentRoot Directory to AllowOverride All for .htaccess and set ServerName
RUN echo '<Directory /var/www/html>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/ci3-override.conf \
    && a2enconf ci3-override \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Configure PHP session save path
RUN echo 'session.save_path = "/tmp"' > /usr/local/etc/php/conf.d/session.ini

WORKDIR /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
