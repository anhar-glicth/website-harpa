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

-- 11. Offices Table
CREATE TABLE IF NOT EXISTS offices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    address TEXT,
    phone VARCHAR(20),
    status ENUM('Aktif', 'Non-Aktif') DEFAULT 'Aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 12. Employees Table
CREATE TABLE IF NOT EXISTS employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    office_id INT NULL,
    name VARCHAR(255) NOT NULL,
    position VARCHAR(100),
    phone VARCHAR(20),
    status ENUM('Aktif', 'Non-Aktif') DEFAULT 'Aktif',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (office_id) REFERENCES offices(id) ON DELETE SET NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 13. Donor Channels
CREATE TABLE IF NOT EXISTS donor_channels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(50) UNIQUE,
    status ENUM('Aktif', 'Non-Aktif') DEFAULT 'Aktif'
);

-- 14. Program Groups
CREATE TABLE IF NOT EXISTS program_groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(50) UNIQUE,
    description TEXT
);

-- 15. Payment Via
CREATE TABLE IF NOT EXISTS payment_via (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    provider VARCHAR(255),
    account_no VARCHAR(100),
    status ENUM('Aktif', 'Non-Aktif') DEFAULT 'Aktif'
);

-- 16. Prospect Media
CREATE TABLE IF NOT EXISTS prospect_media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    status ENUM('Aktif', 'Non-Aktif') DEFAULT 'Aktif'
);

-- 17. Prospect Methods
CREATE TABLE IF NOT EXISTS prospect_methods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT
);

-- 18. Prospect Types
CREATE TABLE IF NOT EXISTS prospect_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    priority ENUM('Tinggi', 'Sedang', 'Rendah') DEFAULT 'Sedang'
);

-- 19. Fundraisers
CREATE TABLE IF NOT EXISTS fundraisers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    name VARCHAR(255) NOT NULL,
    type ENUM('Internal', 'Mitra', 'Volunteer') DEFAULT 'Internal',
    target DECIMAL(15, 2) DEFAULT 0,
    status ENUM('Aktif', 'Non-Aktif') DEFAULT 'Aktif',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- 20. Prospect Responses
CREATE TABLE IF NOT EXISTS prospect_responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prospect_id INT NULL,
    response TEXT,
    status ENUM('Tertarik', 'Tidak Tertarik', 'Follow Up') DEFAULT 'Follow Up',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 21. Targets
CREATE TABLE IF NOT EXISTS targets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    period VARCHAR(50) NOT NULL,
    program_id INT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    achievement DECIMAL(15, 2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (program_id) REFERENCES campaigns(id) ON DELETE SET NULL
);

-- 22. Distributions
CREATE TABLE IF NOT EXISTS distributions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recipient_name VARCHAR(255) NOT NULL,
    program_id INT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    distribution_date DATE,
    status ENUM('Tersalur', 'Proses') DEFAULT 'Proses',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (program_id) REFERENCES campaigns(id) ON DELETE SET NULL
);

-- SEED DATA UPDATE
INSERT IGNORE INTO offices (name, address, phone) VALUES 
('Kantor Pusat Jakarta', 'Jl. Sudirman No. 123', '021-123456'),
('Cabang Bandung', 'Jl. Asia Afrika No. 45', '022-765432');

INSERT IGNORE INTO donor_channels (name, code) VALUES 
('Website Utama', 'WEB-01'),
('Social Media Ads', 'SOC-ADS'),
('Event Offline', 'EVT-OFF');

INSERT IGNORE INTO program_groups (name, code) VALUES 
('Pendidikan & Dakwah', 'EDU-DAK'),
('Kesehatan Masyarakat', 'HEALTH'),
('Bencana & Emergency', 'EMR');
