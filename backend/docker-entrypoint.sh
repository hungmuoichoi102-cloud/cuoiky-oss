#!/bin/bash
set -e

# Initialize database if it doesn't exist
DB_PATH="/var/www/html/database.sqlite"
DB_DIR="/var/www/html"

# Ensure directory permissions before running PHP
chmod -R 777 "$DB_DIR" || true

# Create database with retry logic
MAX_RETRIES=3
RETRY_COUNT=0

while [ $RETRY_COUNT -lt $MAX_RETRIES ]; do
    if [ ! -f "$DB_PATH" ]; then
        echo "Creating database (attempt $((RETRY_COUNT + 1))/$MAX_RETRIES)..."
        if php /var/www/html/setup-db.php; then
            echo "Database created successfully!"
            chmod 777 "$DB_PATH" || true
            break
        else
            echo "Database creation failed, retrying..."
            RETRY_COUNT=$((RETRY_COUNT + 1))
            sleep 1
        fi
    else
        echo "Database already exists"
        chmod 777 "$DB_PATH" || true
        break
    fi
done

# Final permissions
chmod -R 777 "$DB_DIR" || true

# Start Apache
exec apache2-foreground
