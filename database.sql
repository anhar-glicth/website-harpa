-- Database: humanity_db
CREATE DATABASE IF NOT EXISTS humanity_db;
USE humanity_db;

-- 1. Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'field_worker', 'user') DEFAULT 'user',
    profile_photo LONGTEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Campaigns Table
CREATE TABLE IF NOT EXISTS campaigns (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    target_amount DECIMAL(15, 2) NOT NULL,
    current_amount DECIMAL(15, 2) DEFAULT 0,
    start_date DATE,
    end_date DATE,
    category VARCHAR(100),
    foto LONGTEXT,
    bank_name VARCHAR(100),
    account_number VARCHAR(100),
    account_holder VARCHAR(255),
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 3. Donations Table
CREATE TABLE IF NOT EXISTS donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    campaign_id INT NOT NULL,
    user_id INT NULL,
    donor_name VARCHAR(255) NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    message TEXT,
    donation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    payment_status ENUM('Pending', 'Success', 'Failed') DEFAULT 'Pending',
    proof_image LONGTEXT,
    FOREIGN KEY (campaign_id) REFERENCES campaigns(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- 4. News Table
CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    author_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image LONGTEXT,
    category VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 5. Mualaf Requests Table
CREATE TABLE IF NOT EXISTS mualaf_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    desired_date DATE NOT NULL,
    location VARCHAR(100) NOT NULL,
    status ENUM('pending', 'approved', 'rejected', 'completed') DEFAULT 'pending',
    admin_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 6. Site Settings Table
CREATE TABLE IF NOT EXISTS site_settings (
    setting_key VARCHAR(100) PRIMARY KEY,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 7. Zakats Table
CREATE TABLE IF NOT EXISTS zakats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    zakat_type VARCHAR(50) NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    payment_method VARCHAR(50) DEFAULT 'Transfer Bank',
    payment_status ENUM('Pending', 'Success', 'Failed') DEFAULT 'Pending',
    admin_note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 8. To Do List Table
CREATE TABLE IF NOT EXISTS todo_list (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    task TEXT NOT NULL,
    status ENUM('pending', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 9. Attendance Table
CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('check_in', 'check_out', 'sakit', 'izin') NOT NULL,
    location VARCHAR(255),
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    attachment LONGTEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 10. Campaign Updates (Impact Stories)
CREATE TABLE IF NOT EXISTS campaign_updates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    campaign_id INT NOT NULL,
    user_id INT NOT NULL,
    title VARCHAR(255),
    content TEXT NOT NULL,
    image LONGTEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (campaign_id) REFERENCES campaigns(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- SEED DATA
-- Default password: password123 (hashed using bcrypt)
-- Note: You should run the seed.js script to generate correct hashes, 
-- or use these placeholders which will need to be updated.
INSERT IGNORE INTO users (id, name, email, password, role) VALUES 
(1, 'Admin Utama', 'admin@humanity.org', '$2a$10$7zD7W6X3Z8W8W8W8W8W8W.W8W8W8W8W8W8W8W8W8W8W8W8W8W8W8W', 'admin'),
(2, 'Petugas Lapangan', 'petugas@humanity.org', '$2a$10$7zD7W6X3Z8W8W8W8W8W8W.W8W8W8W8W8W8W8W8W8W8W8W8W8W8W8W', 'field_worker'),
(3, 'Donatur Setia', 'donatur@humanity.org', '$2a$10$7zD7W6X3Z8W8W8W8W8W8W.W8W8W8W8W8W8W8W8W8W8W8W8W8W8W8W', 'user');

INSERT IGNORE INTO site_settings (setting_key, setting_value) VALUES
('hero_title', 'Uluran Tangan Anda, Harapan Mereka'),
('hero_subtitle', 'Kami menghubungkan kebaikan hati Anda dengan mereka yang benar-benar membutuhkan bantuan di seluruh pelosok negeri.'),
('hero_image', 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=2070&auto=format&fit=crop'),
('about_mission', 'HumanityCare adalah platform donasi digital yang berfokus na transparansi dan kemudahan akses. Kami percaya bahwa setiap bantuan, sekecil apapun, memiliki dampak besar jika disalurkan kepada orang yang tepat dengan cara yang benar.'),
('about_image', 'https://images.unsplash.com/photo-1469571486292-0ba58a3f068b?q=80&w=2070&auto=format&fit=crop'),
('impact_image', 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?q=80&w=1000&auto=format&fit=crop'),
('impact_title', 'Penyaluran Ribuan Wakaf Al-Quran ke Pelosok Indonesia Timur');
