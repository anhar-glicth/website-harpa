const jwt = require('jsonwebtoken');

/**
 * Middleware: Verifikasi Token JWT
 * Kita ubah menjadi module.exports agar di api.js variabel 'auth' langsung menjadi FUNGSI.
 */
const auth = (req, res, next) => {
    const authHeader = req.headers['authorization'];
    const token = authHeader && authHeader.split(' ')[1];

    if (!token) {
        return res.status(401).json({ 
            status: 'error',
            message: 'Token tidak ditemukan, silakan login kembali' 
        });
    }

    jwt.verify(token, process.env.JWT_SECRET, (err, user) => {
        if (err) {
            return res.status(403).json({ 
                status: 'error',
                message: 'Sesi berakhir atau token tidak valid. Silakan login ulang.' 
            });
        }
        // Lampirkan data user ke request
        req.user = user;
        next();
    });
};

/**
 * Middleware: Opsional Verifikasi Token (Tidak gagal jika tidak ada token)
 */
auth.optional = (req, res, next) => {
    const authHeader = req.headers['authorization'];
    const token = authHeader && authHeader.split(' ')[1];

    if (!token) return next();

    jwt.verify(token, process.env.JWT_SECRET, (err, user) => {
        if (!err) req.user = user;
        next();
    });
};

/**
 * Jika Anda butuh fitur Role, kita tambahkan sebagai properti dari fungsi auth
 */
auth.authorizeRole = (...roles) => {
    return (req, res, next) => {
        const userRole = req.user?.role?.toLowerCase().trim();
        const allowedRoles = roles.map(r => r.toLowerCase().trim());
        
        if (!req.user || !allowedRoles.includes(userRole)) {
            return res.status(403).json({ 
                status: 'error',
                message: `Akses ditolak: Peran '${req.user?.role || 'tanpa peran'}' tidak memiliki izin.` 
            });
        }
        next();
    };
};

// Aliases khusus
auth.isAdmin = auth.authorizeRole('admin');
auth.isStaff = auth.authorizeRole('admin', 'field_worker'); // Gabungan akses
auth.isUser = auth.authorizeRole('user');

// EKSPOR UTAMA
module.exports = auth;