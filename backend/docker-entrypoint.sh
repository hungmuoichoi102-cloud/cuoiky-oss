#!/bin/bash
set -e

# Ensure directory permissions
chmod -R 777 /var/www/html || true

# Wait for MySQL to be ready using PHP PDO
echo "Waiting for MySQL to be ready..."
MAX_RETRIES=30
RETRY_COUNT=0

while [ $RETRY_COUNT -lt $MAX_RETRIES ]; do
    php <<'EOF'
<?php
$dbHost = getenv('DB_HOST') ?: 'localhost';
$dbPort = getenv('DB_PORT') ?: '3306';
$dbName = getenv('DB_NAME') ?: 'todolist_db';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPassword = getenv('DB_PASSWORD') ?: '';

try {
    $dsn = "mysql:host=$dbHost;port=$dbPort;dbname=$dbName;charset=utf8mb4";
    $pdo = new PDO($dsn, $dbUser, $dbPassword, [PDO::ATTR_TIMEOUT => 3]);
    echo "MySQL is ready!\n";
    exit(0);
} catch (Exception $e) {
    exit(1);
}
?>
EOF
    
    if [ $? -eq 0 ]; then
        echo "MySQL is ready!"
        break
    else
        RETRY_COUNT=$((RETRY_COUNT + 1))
        echo "MySQL not ready yet, retrying... ($RETRY_COUNT/$MAX_RETRIES)"
        sleep 1
    fi
done

if [ $RETRY_COUNT -eq $MAX_RETRIES ]; then
    echo "MySQL failed to start after $MAX_RETRIES attempts"
    exit 1
fi

# Start Apache
exec apache2-foreground
