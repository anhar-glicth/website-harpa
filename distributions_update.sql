-- Script Update Database untuk Fitur Penyaluran Premium
-- Menambahkan kolom dan relasi pada tabel distributions

ALTER TABLE distributions 
ADD COLUMN IF NOT EXISTS campaign_id INT NULL AFTER worker_id,
ADD COLUMN IF NOT EXISTS amount DECIMAL(15, 2) DEFAULT 0 AFTER title,
ADD COLUMN IF NOT EXISTS recipient_name VARCHAR(255) NULL AFTER amount,
ADD COLUMN IF NOT EXISTS items_distributed TEXT NULL AFTER recipient_name,
ADD COLUMN IF NOT EXISTS distribution_date DATE NULL AFTER items_distributed;

-- Tambahkan foreign key jika belum ada
-- (Catatan: Jika error ER_DUP_CONSTRAINT_NAME, berarti sudah ada)
ALTER TABLE distributions 
ADD CONSTRAINT fk_dist_campaign 
FOREIGN KEY (campaign_id) REFERENCES campaigns(id) ON DELETE SET NULL;
