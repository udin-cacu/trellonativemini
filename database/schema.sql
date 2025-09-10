-- Create database (run manually if needed): CREATE DATABASE trello_native CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- Tables
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('user','admin') DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS boards (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  title VARCHAR(200) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS sub_boards (
  id INT AUTO_INCREMENT PRIMARY KEY,
  board_id INT NOT NULL,
  name VARCHAR(50) NOT NULL, -- todo, progres, review, done
  position INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (board_id) REFERENCES boards(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS lists (
  id INT AUTO_INCREMENT PRIMARY KEY,
  board_id INT NOT NULL,
  sub_board_id INT NOT NULL,
  title VARCHAR(200) NOT NULL,
  description TEXT,
  assignee VARCHAR(150),
  priority ENUM('low','medium','high') DEFAULT 'low',
  review_status ENUM('none','pending','approved','revisi') DEFAULT 'none',
  review_notes TEXT,
  labels TEXT,
  deadline DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (board_id) REFERENCES boards(id) ON DELETE CASCADE,
  FOREIGN KEY (sub_board_id) REFERENCES sub_boards(id) ON DELETE CASCADE
);

-- Seed default user: email admin@example.com / password admin123
INSERT INTO users (name, email, password_hash)
VALUES ('Administrator', 'admin@example.com', '$2y$10$8W3tM2VdVg1rJvK0QH9R9eJkW5YzCjH6nX1m2nU7d3qzZrJr3q2x2')
ON DUPLICATE KEY UPDATE email = email;
