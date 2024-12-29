// watchTimeController.js

import dbConnection from "./db.js";

async function updateWatchTime(data) {
    try {
        const [result] = await dbConnection.query(
            `
            UPDATE user_workouts
            SET watched_time = ?
            WHERE user_id = ? AND workout_id = ?
            `,
            [data.watched_time, data.user_id, data.workout_id]
        );

        console.log(
            `Updated watch time for user ${data.user_id} and workout ${data.workout_id} to ${data.watched_time} minutes.`
        );

        return result;
    } catch (error) {
        console.error("Error updating watch time:", error);
        throw error;
    }
}

export { updateWatchTime };
