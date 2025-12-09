<?php

// Simplified API Router for Todo App
// This file handles all API requests

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Get request information
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace('/api', '', $path);

// Parse JSON body if needed
$body = json_decode(file_get_contents('php://input'), true);

try {
    // Load database connection from environment variables
    $dbHost = getenv('DB_HOST') ?: 'localhost';
    $dbPort = getenv('DB_PORT') ?: '3306';
    $dbName = getenv('DB_NAME') ?: 'todolist_db';
    $dbUser = getenv('DB_USER') ?: 'root';
    $dbPassword = getenv('DB_PASSWORD') ?: '';
    
    // Create MySQL DSN
    $dsn = "mysql:host=$dbHost;port=$dbPort;dbname=$dbName;charset=utf8mb4";
    
    $pdo = new PDO($dsn, $dbUser, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Simple routing logic
    if ($path === '/todos' || $path === '/todos/') {
        // GET /api/todos - List all todos
        if ($method === 'GET') {
            $stmt = $pdo->query('SELECT * FROM todos ORDER BY created_at DESC');
            $todos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode(['data' => $todos]);
            exit;
        }
        
        // POST /api/todos - Create new todo
        if ($method === 'POST') {
            if (empty($body['title'])) {
                http_response_code(422);
                echo json_encode([
                    'message' => 'The title field is required.',
                    'errors' => ['title' => ['The title field is required.']]
                ]);
                exit;
            }
            
            $stmt = $pdo->prepare('INSERT INTO todos (title, description, is_completed) VALUES (?, ?, 0)');
            $stmt->execute([$body['title'], $body['description'] ?? null]);
            $id = $pdo->lastInsertId();
            
            $stmt = $pdo->prepare('SELECT * FROM todos WHERE id = ?');
            $stmt->execute([$id]);
            $todo = $stmt->fetch(PDO::FETCH_ASSOC);
            
            http_response_code(201);
            echo json_encode([
                'data' => $todo,
                'message' => 'Todo created successfully'
            ]);
            exit;
        }
    }
    
    // Parse todo ID from path
    if (preg_match('#/todos/(\d+)(?:/(.+))?$#', $path, $matches)) {
        $id = $matches[1];
        $action = $matches[2] ?? null;
        
        // GET /api/todos/{id} - Get single todo
        if ($method === 'GET' && !$action) {
            $stmt = $pdo->prepare('SELECT * FROM todos WHERE id = ?');
            $stmt->execute([$id]);
            $todo = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$todo) {
                http_response_code(404);
                echo json_encode(['message' => 'Todo not found']);
                exit;
            }
            
            echo json_encode(['data' => $todo]);
            exit;
        }
        
        // PUT /api/todos/{id} - Update todo
        if ($method === 'PUT') {
            $updates = [];
            $params = [];
            
            if (isset($body['title'])) {
                $updates[] = 'title = ?';
                $params[] = $body['title'];
            }
            if (isset($body['description'])) {
                $updates[] = 'description = ?';
                $params[] = $body['description'];
            }
            if (isset($body['is_completed'])) {
                $updates[] = 'is_completed = ?';
                $params[] = $body['is_completed'] ? 1 : 0;
            }
            
            $updates[] = 'updated_at = CURRENT_TIMESTAMP';
            $params[] = $id;
            
            $sql = 'UPDATE todos SET '.implode(', ', $updates).' WHERE id = ?';
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            $stmt = $pdo->prepare('SELECT * FROM todos WHERE id = ?');
            $stmt->execute([$id]);
            $todo = $stmt->fetch(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'data' => $todo,
                'message' => 'Todo updated successfully'
            ]);
            exit;
        }
        
        // DELETE /api/todos/{id} - Delete todo
        if ($method === 'DELETE') {
            $stmt = $pdo->prepare('DELETE FROM todos WHERE id = ?');
            $stmt->execute([$id]);
            
            echo json_encode(['message' => 'Todo deleted successfully']);
            exit;
        }
        
        // PATCH /api/todos/{id}/toggle - Toggle completion status
        if ($method === 'PATCH' && $action === 'toggle') {
            $stmt = $pdo->prepare('SELECT is_completed FROM todos WHERE id = ?');
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$result) {
                http_response_code(404);
                echo json_encode(['message' => 'Todo not found']);
                exit;
            }
            
            $newStatus = $result['is_completed'] ? 0 : 1;
            $stmt = $pdo->prepare('UPDATE todos SET is_completed = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?');
            $stmt->execute([$newStatus, $id]);
            
            $stmt = $pdo->prepare('SELECT * FROM todos WHERE id = ?');
            $stmt->execute([$id]);
            $todo = $stmt->fetch(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'data' => $todo,
                'message' => 'Todo toggled successfully'
            ]);
            exit;
        }
    }
    
    // Root endpoint
    if ($path === '/' || $path === '') {
        echo json_encode([
            'message' => 'Todo API is running',
            'version' => '1.0.0',
            'endpoints' => [
                'GET /api/todos' => 'List all todos',
                'POST /api/todos' => 'Create new todo',
                'GET /api/todos/{id}' => 'Get single todo',
                'PUT /api/todos/{id}' => 'Update todo',
                'DELETE /api/todos/{id}' => 'Delete todo',
                'PATCH /api/todos/{id}/toggle' => 'Toggle completion status'
            ]
        ]);
        exit;
    }
    
    // Not found
    http_response_code(404);
    echo json_encode([
        'message' => 'Endpoint not found',
        'path' => $path,
        'method' => $method
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'message' => $e->getMessage(),
        'error' => 'Internal Server Error'
    ]);
}
