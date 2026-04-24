const db = require('../config/db');

exports.getPrayers = async (req, res) => {
    try {
        const [rows] = await db.query('SELECT * FROM prayers ORDER BY created_at DESC LIMIT 10');
        res.json(rows);
    } catch (err) {
        res.status(500).json({ message: err.message });
    }
};

exports.submitPrayer = async (req, res) => {
    const { message, is_anonymous } = req.body;
    const user_id = req.user ? req.user.id : null;
    const name = req.user ? req.user.name : 'Hamba Allah';

    try {
        await db.query(
            'INSERT INTO prayers (user_id, name, message, is_anonymous) VALUES (?, ?, ?, ?)',
            [user_id, name, message, is_anonymous]
        );
        res.json({ message: 'Doa berhasil dikirim!' });
    } catch (err) {
        res.status(500).json({ message: err.message });
    }
};

exports.likePrayer = async (req, res) => {
    const { id } = req.params;
    try {
        await db.query('UPDATE prayers SET aamiin_count = aamiin_count + 1 WHERE id = ?', [id]);
        res.json({ success: true });
    } catch (err) {
        res.status(500).json({ message: err.message });
    }
};
