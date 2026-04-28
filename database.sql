-- Database: humanity_db
CREATE DATABASE IF NOT EXISTS humanity_db;
USE humanity_db;

-- 1. Master Data Tables
CREATE TABLE IF NOT EXISTS offices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    address TEXT,
    phone VARCHAR(20),
    status ENUM('Aktif', 'Non-Aktif') DEFAULT 'Aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS donor_channels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(50) UNIQUE,
    status ENUM('Aktif', 'Non-Aktif') DEFAULT 'Aktif'
);

CREATE TABLE IF NOT EXISTS program_groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(50) UNIQUE,
    description TEXT
);

CREATE TABLE IF NOT EXISTS payment_via (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    provider VARCHAR(255),
    account_no VARCHAR(100),
    status ENUM('Aktif', 'Non-Aktif') DEFAULT 'Aktif'
);

CREATE TABLE IF NOT EXISTS prospect_media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    status ENUM('Aktif', 'Non-Aktif') DEFAULT 'Aktif'
);

CREATE TABLE IF NOT EXISTS prospect_methods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT
);

CREATE TABLE IF NOT EXISTS prospect_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    priority ENUM('Tinggi', 'Sedang', 'Rendah') DEFAULT 'Sedang'
);

-- 2. Core Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    username VARCHAR(100) UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'field_worker', 'user') DEFAULT 'user',
    gender ENUM('L', 'P', 'Other'),
    phone VARCHAR(20),
    address TEXT,
    profile_photo LONGTEXT,
    office_id INT NULL,
    pic_prospect_id INT NULL,
    last_prospect_media_id INT NULL,
    segment VARCHAR(100),
    rfm_alltime VARCHAR(100),
    prospect_date DATE,
    source_info TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (office_id) REFERENCES offices(id) ON DELETE SET NULL,
    FOREIGN KEY (pic_prospect_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (last_prospect_media_id) REFERENCES prospect_media(id) ON DELETE SET NULL
);

-- 3. Core Business Tables
CREATE TABLE IF NOT EXISTS campaigns (
    id INT AUTO_INCREMENT PRIMARY KEY,
    author_id INT NOT NULL,
    group_id INT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    category VARCHAR(100),
    target_amount DECIMAL(15, 2) NOT NULL,
    current_amount DECIMAL(15, 2) DEFAULT 0,
    foto LONGTEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (group_id) REFERENCES program_groups(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    campaign_id INT NOT NULL,
    user_id INT NULL,
    payment_via_id INT NULL,
    donor_name VARCHAR(255) NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    message TEXT,
    payment_method VARCHAR(100),
    payment_status ENUM('Pending', 'Success', 'Failed') DEFAULT 'Pending',
    tracking_status ENUM('Diterima', 'Logistik', 'Dikirim', 'Tersalur') DEFAULT 'Diterima',
    proof_image LONGTEXT,
    admin_note TEXT,
    donation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (campaign_id) REFERENCES campaigns(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (payment_via_id) REFERENCES payment_via(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS distributions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    program_id INT NOT NULL,
    recipient_name VARCHAR(255) NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    distribution_date DATE,
    status ENUM('Proses', 'Tersalur') DEFAULT 'Proses',
    notes TEXT,
    asnaf VARCHAR(100),
    city VARCHAR(100),
    province VARCHAR(100),
    source_of_fund VARCHAR(100),
    pm_count INT DEFAULT 1,
    category_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (program_id) REFERENCES campaigns(id) ON DELETE CASCADE
);

-- 4. CRM & Social Tables
CREATE TABLE IF NOT EXISTS prospects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS prospect_responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prospect_id INT NULL,
    response TEXT,
    status ENUM('Tertarik', 'Tidak Tertarik', 'Follow Up') DEFAULT 'Follow Up',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (prospect_id) REFERENCES prospects(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS fundraisers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    name VARCHAR(255) NOT NULL,
    type ENUM('Internal', 'Mitra', 'Volunteer') DEFAULT 'Internal',
    target DECIMAL(15, 2) DEFAULT 0,
    status ENUM('Aktif', 'Non-Aktif') DEFAULT 'Aktif',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS volunteers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255),
    phone VARCHAR(20),
    address TEXT,
    skills TEXT,
    reason TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS pic_targets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pic_id INT NOT NULL,
    month_period DATE NOT NULL,
    target_amount DECIMAL(15, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pic_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 5. Content & Feedback
CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    author_id INT,
    title VARCHAR(255),
    tag VARCHAR(100),
    content TEXT,
    image LONGTEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS campaign_updates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    campaign_id INT NOT NULL,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image LONGTEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (campaign_id) REFERENCES campaigns(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS prayers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    name VARCHAR(255),
    message TEXT,
    is_anonymous BOOLEAN DEFAULT FALSE,
    aamiin_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255),
    subject VARCHAR(255),
    message TEXT,
    status ENUM('unread', 'read', 'replied') DEFAULT 'unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 6. Operational
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

CREATE TABLE IF NOT EXISTS todo_list (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    assigned_by INT NULL,
    task VARCHAR(255) NOT NULL,
    description TEXT,
    deadline DATETIME,
    status ENUM('pending', 'completed') DEFAULT 'pending',
    result_attachment LONGTEXT,
    completed_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS zakats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    zakat_type VARCHAR(100) NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    payment_status ENUM('Pending', 'Success', 'Failed') DEFAULT 'Pending',
    admin_note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS muallaf_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255),
    address TEXT,
    desired_date DATE,
    location VARCHAR(255),
    status ENUM('pending', 'approved', 'rejected', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS newsletters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE,
    status ENUM('active', 'unsubscribed') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(255),
    module VARCHAR(100),
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS targets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    period VARCHAR(50) NOT NULL,
    program_id INT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    achievement DECIMAL(15, 2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (program_id) REFERENCES campaigns(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hero_title TEXT,
    hero_subtitle TEXT,
    hero_image LONGTEXT,
    about_mission TEXT,
    about_image LONGTEXT,
    impact_title TEXT,
    impact_image LONGTEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
