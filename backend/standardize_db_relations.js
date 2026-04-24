const db = require('./config/db');

async function standardizeRelations() {
    console.log('--- STARTING DATABASE RELATION STANDARDIZATION ---');
    
    const relations = [
        // Table: todo_list
        { table: 'todo_list', column: 'assigned_by', refTable: 'users', refColumn: 'id', onDelete: 'SET NULL' },
        
        // Table: prospects
        { table: 'prospects', column: 'media_id', refTable: 'prospect_media', refColumn: 'id', onDelete: 'SET NULL' },
        { table: 'prospects', column: 'fundraiser_id', refTable: 'fundraisers', refColumn: 'id', onDelete: 'SET NULL' },
        { table: 'prospects', column: 'type_id', refTable: 'prospect_types', refColumn: 'id', onDelete: 'SET NULL' },
        
        // Table: prospect_responses
        { table: 'prospect_responses', column: 'prospect_id', refTable: 'prospects', refColumn: 'id', onDelete: 'CASCADE' },
        
        // Table: donations (extra check)
        { table: 'donations', column: 'campaign_id', refTable: 'campaigns', refColumn: 'id', onDelete: 'CASCADE' },
        
        // Table: attendance
        { table: 'attendance', column: 'user_id', refTable: 'users', refColumn: 'id', onDelete: 'CASCADE' },

        // Table: targets
        { table: 'targets', column: 'program_id', refTable: 'campaigns', refColumn: 'id', onDelete: 'SET NULL' },

        // Table: distributions
        { table: 'distributions', column: 'program_id', refTable: 'campaigns', refColumn: 'id', onDelete: 'SET NULL' },

        // Table: prayers
        { table: 'prayers', column: 'user_id', refTable: 'users', refColumn: 'id', onDelete: 'SET NULL' }
    ];

    for (const rel of relations) {
        try {
            console.log(`Checking relation: ${rel.table}.${rel.column} -> ${rel.refTable}.${rel.refColumn}`);
            
            // 1. Ensure the column is the right type (INT)
            await db.query(`ALTER TABLE ${rel.table} MODIFY COLUMN ${rel.column} INT`);

            // 2. Clear invalid references (set to NULL or delete depending on business logic)
            // This is important to allow the FK constraint to be added
            if (rel.onDelete === 'SET NULL') {
                await db.query(`UPDATE ${rel.table} SET ${rel.column} = NULL WHERE ${rel.column} NOT IN (SELECT ${rel.refColumn} FROM ${rel.refTable})`);
            } else if (rel.onDelete === 'CASCADE') {
                await db.query(`DELETE FROM ${rel.table} WHERE ${rel.column} NOT IN (SELECT ${rel.refColumn} FROM ${rel.refTable})`);
            }

            // 3. Drop existing constraint if it exists (to avoid duplicates or update it)
            // Note: MySQL requires the constraint name to be known. We'll try a generic drop or just try adding.
            // A better way is to check information_schema
            const [constraints] = await db.query(`
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_NAME = ? AND COLUMN_NAME = ? AND REFERENCED_TABLE_NAME IS NOT NULL
            `, [rel.table, rel.column]);

            for (const c of constraints) {
                try {
                    await db.query(`ALTER TABLE ${rel.table} DROP FOREIGN KEY ${c.CONSTRAINT_NAME}`);
                    console.log(`  Dropped existing constraint: ${c.CONSTRAINT_NAME}`);
                } catch (e) {}
            }

            // 4. Add the Foreign Key
            const fkName = `fk_${rel.table}_${rel.column}`;
            await db.query(`
                ALTER TABLE ${rel.table} 
                ADD CONSTRAINT ${fkName} 
                FOREIGN KEY (${rel.column}) REFERENCES ${rel.refTable}(${rel.refColumn}) 
                ON DELETE ${rel.onDelete}
            `);
            
            console.log(`  Successfully added relation: ${fkName}`);
        } catch (err) {
            console.error(`  Failed for ${rel.table}.${rel.column}:`, err.message);
        }
    }

    console.log('--- DATABASE RELATION STANDARDIZATION COMPLETE ---');
    process.exit(0);
}

standardizeRelations();
