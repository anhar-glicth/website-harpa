/**
 * Entry Point Utama - Website Donasi Kemanusiaan
 * PERBAIKAN: Menyeimbangkan rute API dan memastikan limit payload cukup untuk foto Base64.
 */
const express = require('express');
const cors = require('cors');
require('dotenv').config();

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
const errorHandler = require('./middleware/errorHandler');

const app = express();

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
app.get('/api/stats', campaignController.getStats);

app.use('/api/auth', authRoutes);
app.use('/api/admin', adminRoutes);
app.use('/api/campaigns', campaignRoutes);
app.use('/api/donations', donationRoutes);
app.use('/api/news', newsRoutes);
app.use('/api/settings', settingsRoutes);
app.use('/api/zakats', zakatRoutes);
app.use('/api/profile', profileRoutes);
app.use('/api/field', fieldRoutes);

// Root test route
app.get('/', (req, res) => {
    res.send('API Humanity Care is Running...');
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