const express = require('express');
const cors = require('cors');
require('dotenv').config();

// Set Timezone to Indonesia/Makassar (GMT+8)
process.env.TZ = 'Asia/Makassar';

// Import Routes
const authRoutes = require('./routes/authRoutes');
const campaignRoutes = require('./routes/campaignRoutes');
const donationRoutes = require('./routes/donationRoutes');
const adminRoutes = require('./routes/adminRoutes');
const newsRoutes = require('./routes/newsRoutes');
const settingsRoutes = require('./routes/settingsRoutes');
const zakatRoutes = require('./routes/zakatRoutes');
const profileRoutes = require('./routes/profileRoutes');
const fieldRoutes = require('./routes/fieldRoutes');
const prayerRoutes = require('./routes/prayerRoutes');
const errorHandler = require('./middleware/errorHandler');

const helmet = require('helmet');
const rateLimit = require('express-rate-limit');

const app = express();
app.set('trust proxy', 1); // Penting untuk Hostinger/Proxy agar IP asli terbaca

// Security Middleware
app.use(helmet());

// Rate Limiting (DISABLED TEMPORARILY)
/*
const limiter = rateLimit({
    windowMs: 15 * 60 * 1000, // 15 menit
    max: 1000, // Tingkatkan batas ke 1000 agar tidak mudah terblokir
    message: 'Terlalu banyak permintaan dari IP ini, silakan coba lagi nanti.'
});
app.use('/api/', limiter);
*/

// Global Middleware
// Limit ditingkatkan ke 50mb agar data foto (Base64) tidak ditolak server (Error 413)
app.use(express.json({ limit: '50mb' }));
app.use(express.urlencoded({ limit: '50mb', extended: true }));
app.use(cors());

/**
 * --- Definisi Route ---
 */

// Route Spesifik - Pastikan rute admin berada di atas rute global api
const campaignController = require('./controllers/campaignController');
app.get(['/api/stats', '/stats'], campaignController.getStats);

app.use(['/api/auth', '/auth'], authRoutes);
app.use(['/api/admin', '/admin'], adminRoutes);
app.use(['/api/campaigns', '/campaigns'], campaignRoutes);
app.use(['/api/donations', '/donations'], donationRoutes);
app.use(['/api/news', '/news'], newsRoutes);
app.use(['/api/settings', '/settings'], settingsRoutes);
app.use(['/api/zakats', '/zakats'], zakatRoutes);
app.use(['/api/profile', '/profile'], profileRoutes);
app.use(['/api/field', '/field'], fieldRoutes);
app.use(['/api/prayers', '/prayers'], prayerRoutes);
app.use(['/api/volunteers', '/volunteers'], require('./routes/volunteerRoutes'));
app.use(['/api/contacts', '/contacts'], require('./routes/contactRoutes'));
app.use(['/api/newsletters', '/newsletters'], require('./routes/newsletterRoutes'));


// Root test route
app.get('/', (req, res) => {
    res.send('API Humanity Care is Running...');
});

// DEBUG: Cek timezone server - buka /api/debug-tz di browser
app.get('/api/debug-tz', (req, res) => {
    const now = new Date();
    const WITA_OFFSET = 8 * 60 * 60 * 1000;
    const nowWITA = new Date(now.getTime() + WITA_OFFSET);
    const startOfDayWITA = new Date(Date.UTC(
        nowWITA.getUTCFullYear(), nowWITA.getUTCMonth(), nowWITA.getUTCDate(), 0, 0, 0
    ));
    const startOfDayUTC = new Date(startOfDayWITA.getTime() - WITA_OFFSET);
    const endOfDayUTC = new Date(startOfDayUTC.getTime() + 24 * 60 * 60 * 1000 - 1);
    res.json({
        server_now_utc: now.toISOString(),
        server_now_wita_calculated: nowWITA.toISOString(),
        process_TZ: process.env.TZ || 'NOT SET',
        startOfDayUTC: startOfDayUTC.toISOString(),
        endOfDayUTC: endOfDayUTC.toISOString()
    });
});

// Database Health Check
const db = require('./config/db');
app.get('/api/health', async (req, res) => {
    try {
        await db.query('SELECT 1');
        res.json({
            status: 'online',
            database: 'connected',
            timestamp: new Date()
        });
    } catch (err) {
        res.status(500).json({
            status: 'online',
            database: 'error',
            message: err.message,
            timestamp: new Date()
        });
    }
});

// Handling 404 Not Found
app.use((req, res) => {
    console.log(`[404] Request ke: ${req.originalUrl}`);
    res.status(404).json({
        message: `Endpoint ${req.originalUrl} tidak ditemukan di server.`,
        success: false
    });
});

// Global Error Handler
app.use(errorHandler);

// Port Handling
const PORT = process.env.PORT || 5000;
app.listen(PORT, () => {
    console.log(`========================================`);
    console.log(`🚀 Server berjalan di: http://localhost:${PORT}`);
    console.log(`📸 Payload Limit: 50MB (Siap menerima foto)`);
    console.log(`📊 Stats Admin: http://localhost:${PORT}/api/admin/dashboard-stats`);
    console.log(`========================================`);
});