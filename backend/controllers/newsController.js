const db = require('../config/db');

exports.getAllNews = async (req, res) => {
    try {
        const [rows] = await db.query('SELECT news.*, users.name as author_name FROM news JOIN users ON news.author_id = users.id ORDER BY created_at DESC');
        res.json(rows);
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

exports.getMyNews = async (req, res) => {
    try {
        const [rows] = await db.query('SELECT * FROM news WHERE author_id = ? ORDER BY created_at DESC', [req.user.id]);
        res.json(rows);
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

exports.createNews = async (req, res) => {
    const { title, content, image } = req.body;
    try {
        await db.query('INSERT INTO news (author_id, title, content, image) VALUES (?, ?, ?, ?)', [req.user.id, title, content, image]);
        res.status(201).json({ message: 'Berita berhasil diterbitkan!' });
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

exports.deleteNews = async (req, res) => {
    try {
        await db.query('DELETE FROM news WHERE id = ? AND author_id = ?', [req.params.id, req.user.id]);
        res.json({ message: 'Berita dihapus.' });
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};
