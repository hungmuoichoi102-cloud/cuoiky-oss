<?php

$dbPath = __DIR__ . '/database.sqlite';
$dbDir = dirname($dbPath);

try {
    // Ensure directory exists with proper permissions
    if (!is_dir($dbDir)) {
        mkdir($dbDir, 0777, true);
    }
    
    // Ensure directory is writable
    if (!is_writable($dbDir)) {
        chmod($dbDir, 0777);
    }
    
    // Create SQLite connection
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Set file permissions
    if (file_exists($dbPath)) {
        chmod($dbPath, 0777);
    }
    
    echo "Creating todos table...\n";
    
    // Create the todos table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS todos (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            is_completed BOOLEAN DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    echo "Todos table created successfully!\n";
    echo "Database setup completed!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
