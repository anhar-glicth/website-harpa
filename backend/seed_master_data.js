const db = require('./config/db');

async function seedMasterData() {
    console.log('--- SEEDING MASTER DATA ---');

    try {
        // 1. Offices
        const offices = [
            ['Pusat Jakarta', 'Jl. Sudirman No. 1, Jakarta Pusat'],
            ['Cabang Bandung', 'Jl. Asia Afrika No. 10, Bandung'],
            ['Cabang Surabaya', 'Jl. Tunjungan No. 5, Surabaya']
        ];
        for (const o of offices) {
            await db.query('INSERT IGNORE INTO offices (name, address) VALUES (?, ?)', o);
        }
        console.log('Offices seeded.');

        // 2. Program Groups
        const groups = [
            ['Kemanusiaan', 'Program bantuan bencana dan sosial'],
            ['Pendidikan', 'Program beasiswa dan sarana sekolah'],
            ['Kesehatan', 'Program pengobatan dan sanitasi'],
            ['Dakwah', 'Program syiar Islam dan pembangunan masjid']
        ];
        for (const g of groups) {
            await db.query('INSERT IGNORE INTO program_groups (name, description) VALUES (?, ?)', g);
        }
        console.log('Program Groups seeded.');

        // 3. Donor Channels
        const channels = [['Instagram'], ['Facebook'], ['WhatsApp'], ['Website Direct'], ['Event Offline']];
        for (const c of channels) {
            await db.query('INSERT IGNORE INTO donor_channels (name) VALUES (?)', c);
        }
        console.log('Donor Channels seeded.');

        // 4. Fundraisers (Link to existing users or just names)
        // For now just names as the table expects name
        const fundraisers = [['Ahmad Fundraiser'], ['Siti Zakat Specialist'], ['Budi Social Activist']];
        for (const f of fundraisers) {
            await db.query('INSERT IGNORE INTO fundraisers (name) VALUES (?)', f);
        }
        console.log('Fundraisers seeded.');

    } catch (error) {
        console.error('Seeding failed:', error.message);
    }

    console.log('--- SEEDING COMPLETE ---');
    process.exit(0);
}

seedMasterData();
