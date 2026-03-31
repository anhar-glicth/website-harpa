/**
 * Middleware untuk memeriksa agar input tidak kosong
 */
const validate = (req, res, next) => {
    // Abaikan permintaan GET tanpa body
    if (req.method === 'GET') return next();

    const bodies = req.body;
    
    // Periksa apakah body kosong untuk permintaan POST/PUT
    if (Object.keys(bodies).length === 0) {
        return res.status(400).json({ 
            status: 'error',
            message: 'Permintaan tidak memiliki data (input kosong)' 
        });
    }

    // Periksa setiap field di body agar tidak berisi string kosong atau null
    for (const key in bodies) {
        const val = bodies[key];
        if (val === null || val === undefined || (typeof val === 'string' && val.trim() === '')) {
            return res.status(400).json({ 
                status: 'error',
                message: `Field '${key}' tidak boleh kosong` 
            });
        }
    }
    
    next();
};

module.exports = validate;