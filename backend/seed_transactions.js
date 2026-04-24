const db = require('./config/db');

async function seedTransactions() {
    console.log('--- SEEDING TRANSACTIONS & CAMPAIGNS ---');

    try {
        // 1. Ensure we have campaigns
        const campaigns = [
            ['Bantuan Palestina', 'Bantuan medis dan pangan untuk Gaza', 1000000000, 50000000, 'Kemanusiaan'],
            ['Beasiswa Anak Yatim', 'Beasiswa untuk 100 anak yatim', 500000000, 25000000, 'Pendidikan'],
            ['Renovasi Masjid Al-Ikhlas', 'Perbaikan atap dan lantai masjid', 200000000, 10000000, 'Dakwah']
        ];

        for (const c of campaigns) {
            await db.query('INSERT IGNORE INTO campaigns (title, description, target_amount, current_amount, category, status) VALUES (?, ?, ?, ?, ?, "approved")', c);
        }

        // Get campaign IDs
        const [campRows] = await db.query('SELECT id FROM campaigns');
        const campIds = campRows.map(r => r.id);

        // 2. Add dummy donations
        const names = ['Budi', 'Siti', 'Ahmad', 'Lani', 'Dewi', 'Rudi', 'Ani', 'Joko'];
        const methods = ['Transfer Bank', 'E-Wallet', 'Credit Card'];
        
        for (let i = 0; i < 20; i++) {
            const campId = campIds[Math.floor(Math.random() * campIds.length)];
            const donor = names[Math.floor(Math.random() * names.length)];
            const amount = (Math.floor(Math.random() * 10) + 1) * 50000;
            const status = Math.random() > 0.2 ? 'Success' : 'Pending';
            const date = new Date();
            date.setDate(date.getDate() - Math.floor(Math.random() * 30));

            await db.query(
                'INSERT INTO donations (campaign_id, donor_name, amount, payment_status, donation_date, payment_method) VALUES (?, ?, ?, ?, ?, ?)',
                [campId, donor, amount, status, date, methods[Math.floor(Math.random() * methods.length)]]
            );
        }

        console.log('Campaigns and Donations seeded.');

    } catch (error) {
        console.error('Seeding failed:', error.message);
    }

    console.log('--- SEEDING COMPLETE ---');
    process.exit(0);
}

seedTransactions();
