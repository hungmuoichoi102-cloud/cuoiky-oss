# Backend Dockerfile - PHP with SQLite
FROM php:8.1-apache

# Install system dependencies
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    git \
    curl \
    libsqlite3-dev \
    sqlite3 && \
    rm -rf /var/lib/apt/lists/*

# Build and enable PDO SQLite extension from source
RUN docker-php-ext-configure pdo_sqlite && \
    docker-php-ext-install -j$(nproc) pdo_sqlite

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html/

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader 2>&1 || true

# Enable Apache modules
RUN a2enmod rewrite && a2enmod headers

# Remove default config and create new one
RUN rm /etc/apache2/sites-available/000-default.conf

# Create Apache VirtualHost configuration
RUN echo '<VirtualHost *:80>\n\
    ServerAdmin admin@localhost\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        Options -Indexes +FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
        <IfModule mod_rewrite.c>\n\
            RewriteEngine On\n\
            RewriteCond %{REQUEST_FILENAME} !-f\n\
            RewriteCond %{REQUEST_FILENAME} !-d\n\
            RewriteRule ^ index.php [QSA,L]\n\
        </IfModule>\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Enable default site
RUN a2ensite 000-default

# Copy entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Create database directory with proper permissions
RUN mkdir -p /var/www/html/database && \
    mkdir -p /var/www/html && \
    chmod -R 777 /var/www/html && \
    chmod -R 777 /var/www/html/database && \
    chown -R www-data:www-data /var/www/html

# Initialize database
RUN php /var/www/html/setup-db.php || true

# Expose port
EXPOSE 80

# Environment
ENV APP_ENV=production APP_DEBUG=false

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["apache2-foreground"]
