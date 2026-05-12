const db = require('../backend/config/db');

async function updateSchema() {
    try {
        console.log('Starting schema update...');
        
        // Add columns to distributions table
        await db.query(`
            ALTER TABLE distributions 
            ADD COLUMN IF NOT EXISTS campaign_id INT NULL,
            ADD COLUMN IF NOT EXISTS amount DECIMAL(15, 2) DEFAULT 0,
            ADD COLUMN IF NOT EXISTS recipient_name VARCHAR(255) NULL,
            ADD COLUMN IF NOT EXISTS items_distributed TEXT NULL,
            ADD COLUMN IF NOT EXISTS distribution_date DATE NULL
        `);
        
        // Add foreign key separately because ADD CONSTRAINT IF NOT EXISTS is not standard in MySQL
        // We'll check if it exists first or just try-catch it
        try {
            await db.query(`
                ALTER TABLE distributions 
                ADD CONSTRAINT fk_dist_campaign 
                FOREIGN KEY (campaign_id) REFERENCES campaigns(id) ON DELETE SET NULL
            `);
            console.log('Foreign key fk_dist_campaign added.');
        } catch (fkError) {
            if (fkError.code === 'ER_DUP_CONSTRAINT_NAME') {
                console.log('Foreign key fk_dist_campaign already exists.');
            } else {
                console.error('Error adding foreign key:', fkError.message);
            }
        }

        console.log('Schema update completed successfully!');
        process.exit(0);
    } catch (error) {
        console.error('Schema update failed:', error);
        process.exit(1);
    }
}

updateSchema();
