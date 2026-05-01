-- CMS Database Structure for People's Bank CMS
-- Run this SQL to set up the database tables

CREATE DATABASE IF NOT EXISTS cms_db;
USE cms_db;

-- Users table (assuming it exists, but adding if not)
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Topics table for CMS
CREATE TABLE IF NOT EXISTS topics (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    slug VARCHAR(255) UNIQUE,
    status ENUM('published', 'draft', 'archived') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Content table for topic content
CREATE TABLE IF NOT EXISTS topic_content (
    id INT PRIMARY KEY AUTO_INCREMENT,
    topic_id INT NOT NULL,
    type ENUM('text', 'image', 'file', 'video') NOT NULL,
    title VARCHAR(255),
    content TEXT, -- For text content or file descriptions
    file_path VARCHAR(500), -- For images/files
    file_name VARCHAR(255), -- Original filename
    description TEXT,
    sort_order INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (topic_id) REFERENCES topics(id) ON DELETE CASCADE
);
-- Announcements table for news and updates
CREATE TABLE IF NOT EXISTS announcements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    content TEXT NULL, 
    image VARCHAR(500) NULL,
    slug VARCHAR(255) UNIQUE NULL,
    status ENUM('published', 'draft', 'archived') DEFAULT 'published',
    is_featured TINYINT(1) DEFAULT 0,
    posted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT NULL,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Insert default admin user (password: admin123)
INSERT IGNORE INTO users (username, password, email, role) VALUES
('admin', 'admin123', 'admin@peoplesbank.com', 'admin');

-- Sample topics
INSERT IGNORE INTO topics (title, description, slug, status, created_by) VALUES
('Welcome to People''s Bank', 'Introduction to our banking services', 'welcome', 'published', 1),
('Online Banking Guide', 'How to use our online banking platform', 'online-banking', 'published', 1);