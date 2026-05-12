const db = require('../config/db');

async function checkData() {
    try {
        const [attendanceCount] = await db.query('SELECT COUNT(*) as count FROM attendance');
        const [tasksCount] = await db.query('SELECT COUNT(*) as count FROM todo_list');
        const [usersCount] = await db.query('SELECT COUNT(*) as count FROM users');
        const [fieldWorkers] = await db.query('SELECT COUNT(*) as count FROM users WHERE role = "field_worker"');

        console.log('Attendance Count:', attendanceCount[0].count);
        console.log('Tasks Count:', tasksCount[0].count);
        console.log('Users Count:', usersCount[0].count);
        console.log('Field Workers Count:', fieldWorkers[0].count);

        if (attendanceCount[0].count > 0) {
            const [recentAttendance] = await db.query('SELECT * FROM attendance ORDER BY created_at DESC LIMIT 5');
            console.log('Recent Attendance:', JSON.stringify(recentAttendance, null, 2));
        }

        if (tasksCount[0].count > 0) {
            const [recentTasks] = await db.query('SELECT * FROM todo_list ORDER BY created_at DESC LIMIT 5');
            console.log('Recent Tasks:', JSON.stringify(recentTasks, null, 2));
        }

        process.exit(0);
    } catch (error) {
        console.error('Error checking data:', error);
        process.exit(1);
    }
}

checkData();
