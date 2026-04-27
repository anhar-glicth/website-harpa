const db = require('../backend/config/db');

async function fixUsersTable() {
    try {
        console.log('Adding missing columns to users table...');
        
        const columns = [
            'ALTER TABLE users ADD COLUMN username VARCHAR(100) UNIQUE AFTER id',
            'ALTER TABLE users ADD COLUMN IF NOT EXISTS phone VARCHAR(20) AFTER email',
            'ALTER TABLE users ADD COLUMN IF NOT EXISTS gender ENUM("L", "P") AFTER phone',
            'ALTER TABLE users ADD COLUMN IF NOT EXISTS address TEXT AFTER gender',
            'ALTER TABLE users ADD COLUMN IF NOT EXISTS office_id INT AFTER address',
            'ALTER TABLE users ADD COLUMN IF NOT EXISTS segment VARCHAR(100) AFTER office_id',
            'ALTER TABLE users ADD COLUMN IF NOT EXISTS profile_type VARCHAR(100) AFTER segment',
            'ALTER TABLE users ADD COLUMN IF NOT EXISTS rfm_90d VARCHAR(100) AFTER profile_type',
            'ALTER TABLE users ADD COLUMN IF NOT EXISTS rfm_alltime VARCHAR(100) AFTER rfm_90d',
            'ALTER TABLE users ADD COLUMN IF NOT EXISTS category VARCHAR(100) AFTER rfm_alltime',
            'ALTER TABLE users ADD COLUMN IF NOT EXISTS badge VARCHAR(100) AFTER category',
            'ALTER TABLE users ADD COLUMN IF NOT EXISTS total_transactions INT DEFAULT 0 AFTER badge',
            'ALTER TABLE users ADD COLUMN IF NOT EXISTS total_nominal DECIMAL(15,2) DEFAULT 0 AFTER total_transactions',
            'ALTER TABLE users ADD COLUMN IF NOT EXISTS last_transaction_date TIMESTAMP NULL AFTER total_nominal',
            'ALTER TABLE users ADD COLUMN IF NOT EXISTS registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER last_transaction_date',
            'ALTER TABLE users ADD COLUMN IF NOT EXISTS pic_prospect_id INT AFTER registration_date',
            'ALTER TABLE users ADD COLUMN IF NOT EXISTS source_info VARCHAR(255) AFTER pic_prospect_id',
            'ALTER TABLE users ADD COLUMN IF NOT EXISTS last_prospect_media_id INT AFTER source_info',
            'ALTER TABLE users ADD COLUMN IF NOT EXISTS prospect_date TIMESTAMP NULL AFTER last_prospect_media_id',
            'ALTER TABLE users ADD COLUMN IF NOT EXISTS last_proof_image LONGTEXT AFTER prospect_date'
        ];

        for (let sql of columns) {
            try {
                // MariaDB/MySQL doesn't support ADD COLUMN IF NOT EXISTS for multiple columns in old versions
                // So we run each one individually and catch error if already exists
                await db.query(sql.replace('IF NOT EXISTS', ''));
                console.log('Executed:', sql);
            } catch (e) {
                // Ignore "Duplicate column name" error
                if (e.errno !== 1060) {
                    console.error('Error on', sql, ':', e.message);
                }
            }
        }

        console.log('Database users table updated successfully!');
    } catch (err) {
        console.error('Migration Error:', err.message);
    } finally {
        process.exit();
    }
}

fixUsersTable();
