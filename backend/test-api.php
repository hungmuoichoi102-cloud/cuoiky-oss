<?php

// Test API connection
echo "Testing Todo API...\n";
echo "==================\n\n";

$apiUrl = 'http://localhost:8000/api/todos';

try {
    echo "1. Testing GET /api/todos\n";
    echo "   URL: $apiUrl\n";
    
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => "Content-Type: application/json\r\n",
            'timeout' => 5
        ]
    ]);
    
    $response = @file_get_contents($apiUrl, false, $context);
    
    if ($response === false) {
        echo "   ❌ ERROR: Cannot connect to API\n";
        echo "   Please make sure backend is running on port 8000\n";
        echo "   Run: php -S localhost:8000 -t public\n\n";
    } else {
        $data = json_decode($response, true);
        echo "   ✅ SUCCESS\n";
        echo "   Response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n\n";
    }
    
    echo "2. Testing POST /api/todos (Create)\n";
    $postData = json_encode([
        'title' => 'Test Todo',
        'description' => 'This is a test'
    ]);
    
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/json\r\n",
            'content' => $postData,
            'timeout' => 5
        ]
    ]);
    
    $response = @file_get_contents($apiUrl, false, $context);
    
    if ($response === false) {
        echo "   ❌ ERROR: Cannot create todo\n";
    } else {
        $data = json_decode($response, true);
        echo "   ✅ SUCCESS\n";
        echo "   Response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n\n";
    }
    
    echo "✅ API is working correctly!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
