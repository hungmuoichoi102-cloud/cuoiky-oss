<?php

// Simple script to generate APP_KEY
$key = 'base64:' . base64_encode(random_bytes(32));

$envFile = __DIR__ . '/.env';

if (file_exists($envFile)) {
    $content = file_get_contents($envFile);
    $content = preg_replace('/APP_KEY=.*/', 'APP_KEY=' . $key, $content);
    file_put_contents($envFile, $content);
    echo "APP_KEY generated successfully!\n";
    echo "Key: " . $key . "\n";
} else {
    echo "Error: .env file not found!\n";
}
