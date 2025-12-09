-- Initialize MySQL database for Todo App
-- This script will be run automatically by Docker when the MySQL container starts

USE todolist_db;

-- Create todos table
CREATE TABLE IF NOT EXISTS todos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    is_completed BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create index for faster queries
CREATE INDEX idx_created_at ON todos(created_at DESC);
CREATE INDEX idx_is_completed ON todos(is_completed);

-- Insert sample data (optional)
-- INSERT INTO todos (title, description, is_completed) VALUES 
-- ('Learn Docker', 'Docker is awesome', FALSE),
-- ('Learn MySQL', 'MySQL is a great database', FALSE),
-- ('Build Todo App', 'Build a complete todo application', FALSE);
