CREATE TABLE meja (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nomor_meja VARCHAR(10) UNIQUE NOT NULL,
    status ENUM('tersedia', 'terpakai') DEFAULT 'tersedia',
    customer_id INT NULL,
    session_id VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);