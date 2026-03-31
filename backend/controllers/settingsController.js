const db = require('../config/db');

exports.getSettings = async (req, res) => {
    try {
        const [rows] = await db.query('SELECT * FROM site_settings');
        // Convert array to key-value object
        const settings = rows.reduce((acc, current) => {
            acc[current.setting_key] = current.setting_value;
            return acc;
        }, {});
        res.json(settings);
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

exports.updateSettings = async (req, res) => {
    const settings = req.body; // Expecting { key1: value1, key2: value2 }
    try {
        for (const [key, value] of Object.entries(settings)) {
            await db.query('REPLACE INTO site_settings (setting_key, setting_value) VALUES (?, ?)', [key, value]);
        }
        res.json({ message: 'Website content updated successfully!' });
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};
