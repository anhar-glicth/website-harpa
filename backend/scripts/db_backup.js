const mysqldump = require('mysqldump');
require('dotenv').config();
const path = require('path');
const fs = require('fs');

async function backup() {
    const backupDir = path.join(__dirname, 'backups');
    if (!fs.existsSync(backupDir)) {
        fs.mkdirSync(backupDir);
    }

    const fileName = `backup-${new Date().toISOString().replace(/[:.]/g, '-')}.sql`;
    const filePath = path.join(backupDir, fileName);

    try {
        await mysqldump({
            connection: {
                host: process.env.DB_HOST,
                user: process.env.DB_USER,
                password: process.env.DB_PASSWORD,
                database: process.env.DB_NAME,
            },
            dumpToFile: filePath,
        });
        console.log(`Backup success: ${filePath}`);
    } catch (err) {
        console.error('Backup failed:', err);
    }
}

backup();
