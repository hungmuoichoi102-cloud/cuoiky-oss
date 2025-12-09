#!/bin/bash
set -e

# Initialize database if it doesn't exist
DB_PATH="/var/www/html/database.sqlite"

if [ ! -f "$DB_PATH" ]; then
    echo "Creating database..."
    php /var/www/html/setup-db.php
    chmod 777 "$DB_PATH"
fi

# Ensure directory permissions
chmod -R 777 /var/www/html/database || true

# Start Apache
exec apache2-foreground
