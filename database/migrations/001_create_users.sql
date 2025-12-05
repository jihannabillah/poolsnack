-- database/migrations/001_create_users.sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('customer', 'kasir', 'admin') DEFAULT 'customer',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- database/migrations/002_create_menu.sql
CREATE TABLE menu (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    harga DECIMAL(10,2) NOT NULL,
    kategori ENUM('makanan', 'minuman') NOT NULL,
    gambar VARCHAR(255),
    status ENUM('tersedia', 'habis') DEFAULT 'tersedia',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- database/migrations/003_create_meja.sql
CREATE TABLE meja (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nomor_meja VARCHAR(10) UNIQUE NOT NULL,
    status ENUM('tersedia', 'terpakai') DEFAULT 'tersedia',
    customer_id INT NULL,
    session_id VARCHAR(100) NULL
);