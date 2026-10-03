const db = require('./config/db');

async function setup() {
    try {
        // Table for website settings (CMS)
        await db.query(`
            CREATE TABLE IF NOT EXISTS site_settings (
                setting_key VARCHAR(100) PRIMARY KEY,
                setting_value TEXT,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        `);

        // Seed default settings if empty
        const [rows] = await db.query('SELECT COUNT(*) as count FROM site_settings');
        if (rows[0].count === 0) {
            const defaults = [
                ['hero_title', 'Uluran Tangan Anda, Harapan Mereka'],
                ['hero_subtitle', 'Kami menghubungkan kebaikan hati Anda dengan mereka yang benar-benar membutuhkan bantuan di seluruh pelosok negeri.'],
                ['hero_image', 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=2070&auto=format&fit=crop'],
                ['about_mission', 'Amal Abadi adalah platform donasi digital yang berfokus pada transparansi dan kemudahan akses. Kami percaya bahwa setiap bantuan, sekecil apapun, memiliki dampak besar jika disalurkan kepada orang yang tepat dengan cara yang benar.'],
                ['about_image', 'https://images.unsplash.com/photo-1469571486292-0ba58a3f068b?q=80&w=2070&auto=format&fit=crop'],
                ['impact_image', 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?q=80&w=1000&auto=format&fit=crop'],
                ['impact_title', 'Penyaluran Ribuan Wakaf Al-Quran ke Pelosok Indonesia Timur']
            ];
            
            for (const [key, val] of defaults) {
                await db.query('INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?)', [key, val]);
            }
        }

        console.log('Site settings table ensured and seeded.');
        process.exit();
    } catch (e) {
        console.error(e.message);
        process.exit(1);
    }
}

setup();
