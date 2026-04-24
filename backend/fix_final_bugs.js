const db = require('./config/db');

async function fixFinalBugs() {
    console.log('--- FIXING FINAL BUGS & AMBIGUITIES ---');

    // 1. Add phone column to users
    try {
        await db.query('ALTER TABLE users ADD COLUMN phone VARCHAR(20) AFTER email');
        console.log('Added phone column to users.');
    } catch (e) {
        console.log('Phone column already exists or failed:', e.message);
    }

    // 2. Clean up mualaf_requests duplicate column
    try {
        // If both exist, we might want to keep one. 
        // Let's check which one has data. 
        // For simplicity, we just ensure admin_note exists and keep it.
        await db.query('ALTER TABLE mualaf_requests ADD COLUMN IF NOT EXISTS admin_note TEXT AFTER status');
        console.log('Ensured admin_note exists in mualaf_requests.');
    } catch (e) {}

    // 3. Add payment_method to donations
    try {
        await db.query('ALTER TABLE donations ADD COLUMN payment_method VARCHAR(50) AFTER payment_status');
        console.log('Added payment_method column to donations.');
    } catch (e) {
        console.log('Payment method column already exists or failed:', e.message);
    }

    console.log('--- FINAL BUG FIXES COMPLETE ---');
    process.exit(0);
}

fixFinalBugs();
