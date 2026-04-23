const db = require('../config/db');

/**
 * Generic Controller for Administrative Master Data
 * Handles CRUD for various tables in a modular way
 */

// Mapping of module names to table names
const tableMap = {
    'office': 'offices',
    'employees': 'employees',
    'donor_channel': 'donor_channels',
    'program_group': 'program_groups',
    'payment_via': 'payment_via',
    'prospect_media': 'prospect_media',
    'prospect_method': 'prospect_methods',
    'prospect_type': 'prospect_types',
    'fundraisers': 'fundraisers',
    'prospect_responses': 'prospect_responses',
    'targets': 'targets',
    'distribution_list': 'distributions'
};

exports.getData = async (req, res) => {
    const { module } = req.params;
    const tableName = tableMap[module];

    if (!tableName) {
        return res.status(400).json({ message: 'Modul tidak valid' });
    }

    try {
        const [rows] = await db.query(`SELECT * FROM ${tableName} ORDER BY id DESC`);
        res.json(rows);
    } catch (error) {
        console.error(`Error fetching ${module}:`, error);
        res.status(500).json({ message: `Gagal memuat data ${module}` });
    }
};

exports.createData = async (req, res) => {
    const { module } = req.params;
    const tableName = tableMap[module];
    const data = req.body;

    if (!tableName) return res.status(400).json({ message: 'Modul tidak valid' });

    try {
        const [result] = await db.query(`INSERT INTO ${tableName} SET ?`, [data]);
        res.status(201).json({ message: 'Data berhasil ditambahkan', id: result.insertId });
    } catch (error) {
        console.error(`Error creating ${module}:`, error);
        res.status(500).json({ message: `Gagal menambah data ${module}` });
    }
};

exports.updateData = async (req, res) => {
    const { module, id } = req.params;
    const tableName = tableMap[module];
    const data = req.body;

    if (!tableName) return res.status(400).json({ message: 'Modul tidak valid' });

    try {
        await db.query(`UPDATE ${tableName} SET ? WHERE id = ?`, [data, id]);
        res.json({ message: 'Data berhasil diperbarui' });
    } catch (error) {
        console.error(`Error updating ${module}:`, error);
        res.status(500).json({ message: `Gagal memperbarui data ${module}` });
    }
};

exports.deleteData = async (req, res) => {
    const { module, id } = req.params;
    const tableName = tableMap[module];

    if (!tableName) return res.status(400).json({ message: 'Modul tidak valid' });

    try {
        await db.query(`DELETE FROM ${tableName} WHERE id = ?`, [id]);
        res.json({ message: 'Data berhasil dihapus' });
    } catch (error) {
        console.error(`Error deleting ${module}:`, error);
        res.status(500).json({ message: `Gagal menghapus data ${module}` });
    }
};
