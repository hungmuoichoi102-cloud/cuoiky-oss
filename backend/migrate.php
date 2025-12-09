<?php

require_once __DIR__.'/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require_once __DIR__.'/bootstrap/app.php';

try {
    // Get the database connection
    $db = $app->make('db');
    
    // Check if migrations table exists, if not create it
    $schema = $db->connection()->getSchemaBuilder();
    
    if (!$schema->hasTable('migrations')) {
        $schema->create('migrations', function ($table) {
            $table->increments('id');
            $table->string('migration');
            $table->integer('batch');
        });
    }
    
    // Run migrations manually
    echo "Running migrations...\n";
    
    // Get migration files
    $path = __DIR__ . '/database/migrations';
    $files = glob($path . '/*.php');
    
    $migrations = [];
    foreach ($files as $file) {
        $name = basename($file, '.php');
        $migrations[] = ['file' => $file, 'name' => $name];
    }
    
    // Get already run migrations
    $ran = $db->table('migrations')->orderBy('batch', 'desc')->pluck('migration')->toArray();
    
    foreach ($migrations as $migration) {
        if (!in_array($migration['name'], $ran)) {
            echo "Running: " . $migration['name'] . "\n";
            
            // Include the migration file
            require_once $migration['file'];
            
            // Get the class name (last part of namespace)
            $parts = explode('_', $migration['name']);
            $className = end($parts);
            
            // Convert snake_case to PascalCase
            $className = implode('', array_map('ucfirst', explode('_', $className)));
            
            // Find the actual class
            $migration_class = null;
            foreach (get_declared_classes() as $class) {
                if (strpos($class, $className) !== false) {
                    $migration_class = $class;
                    break;
                }
            }
            
            if ($migration_class && method_exists($migration_class, 'up')) {
                $instance = new $migration_class();
                $instance->up();
                
                $batch = DB::table('migrations')->max('batch') ?? 0;
                DB::table('migrations')->insert([
                    'migration' => $migration['name'],
                    'batch' => $batch + 1,
                ]);
                
                echo "Migrated: " . $migration['name'] . "\n";
            }
        }
    }
    
    echo "All migrations completed successfully!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
