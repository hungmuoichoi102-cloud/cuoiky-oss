<?php

require_once __DIR__.'/vendor/autoload.php';

try {
    $app = require_once __DIR__.'/bootstrap/app.php';
    
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    
    // Run migrations
    $status = $kernel->call('migrate', ['--force' => true]);
    
    echo "Migrations completed successfully!\n";
    exit($status);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
    exit(1);
}
