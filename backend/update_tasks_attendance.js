const db = require('./config/db');

async function update() {
    try {
        console.log('Updating database for Task & Attendance features...');

        // 1. Ensure attendance table has necessary columns
        await db.query(`
            CREATE TABLE IF NOT EXISTS attendance (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                type ENUM('check_in', 'check_out', 'info') NOT NULL,
                location VARCHAR(255),
                latitude VARCHAR(50),
                longitude VARCHAR(50),
                attachment LONGTEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        `);

        // 2. Ensure todo_list table is ready for Admin-to-Worker assignment
        // We might need to alter existing table if it exists
        const [columns] = await db.query('SHOW COLUMNS FROM todo_list');
        const columnNames = columns.map(c => c.Field);

        if (!columnNames.includes('description')) {
            await db.query('ALTER TABLE todo_list ADD COLUMN description TEXT AFTER task');
        }
        if (!columnNames.includes('deadline')) {
            await db.query('ALTER TABLE todo_list ADD COLUMN deadline DATETIME AFTER description');
        }
        if (!columnNames.includes('assigned_by')) {
            await db.query('ALTER TABLE todo_list ADD COLUMN assigned_by INT AFTER user_id');
        }
        if (!columnNames.includes('result_attachment')) {
            await db.query('ALTER TABLE todo_list ADD COLUMN result_attachment LONGTEXT AFTER status');
        }
        if (!columnNames.includes('completed_at')) {
            await db.query('ALTER TABLE todo_list ADD COLUMN completed_at DATETIME AFTER result_attachment');
        }

        // Ensure status is ENUM with proper values if it's currently just VARCHAR or missing
        // (Assuming it was already there based on fieldController.js)

        console.log('Database updated successfully.');
        process.exit(0);
    } catch (e) {
        console.error('Update failed:', e.message);
        process.exit(1);
    }
}

update();
