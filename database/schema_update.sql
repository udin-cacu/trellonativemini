-- ALTER to add role to users and labels to lists (if using existing DB)
ALTER TABLE users ADD COLUMN IF NOT EXISTS role ENUM('user','admin') DEFAULT 'user';
-- For MySQL versions without IF NOT EXISTS for ALTER, run these safely:
-- ALTER TABLE users ADD COLUMN role ENUM('user','admin') DEFAULT 'user';

ALTER TABLE lists ADD COLUMN labels TEXT NULL;
-- labels will contain JSON array like '["frontend","backend"]'

-- Seed an admin and normal user (passwords: admin123 / user123)
INSERT INTO users (name, email, password_hash, role)
VALUES ('Administrator', 'admin@example.com', '$2y$10$8W3tM2VdVg1rJvK0QH9R9eJkW5YzCjH6nX1m2nU7d3qzZrJr3q2x2', 'admin')
ON DUPLICATE KEY UPDATE email = email;

INSERT INTO users (name, email, password_hash, role)
VALUES ('Regular User', 'user@example.com', '$2y$10$K7a7vK1G7qZJ3qQmZp9bRuY1Zr8fQ6sM1nP0o1q2r3s4t5u6v7w8', 'user')
ON DUPLICATE KEY UPDATE email = email;
