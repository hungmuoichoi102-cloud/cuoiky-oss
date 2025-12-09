#!/bin/bash

# Setup script for Laravel backend

echo "Setting up Laravel Backend..."
echo ""

# Check if composer exists
if ! command -v composer &> /dev/null; then
    echo "Composer is not installed. Please install Composer first."
    exit 1
fi

# Install dependencies
echo "Installing dependencies..."
composer install

if [ $? -ne 0 ]; then
    echo "Error installing dependencies"
    exit 1
fi

echo ""
echo "Copying .env file..."
if [ ! -f .env ]; then
    cp .env.example .env
    echo ".env file created"
else
    echo ".env file already exists"
fi

echo ""
echo "Creating database.sqlite..."
if [ ! -f database.sqlite ]; then
    touch database.sqlite
    echo "database.sqlite created"
else
    echo "database.sqlite already exists"
fi

echo ""
echo "Generating app key..."
php artisan key:generate

echo ""
echo "Running migrations..."
php artisan migrate

echo ""
echo "Setup completed successfully!"
echo ""
echo "To start the development server, run:"
echo "  php artisan serve"
echo ""
