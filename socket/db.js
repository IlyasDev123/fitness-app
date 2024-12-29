import dotenv from "dotenv";
import mysql from "mysql2/promise";
dotenv.config({ path: "../.env" });

// DB_CONNECTION=mysql
// DB_HOST=127.0.0.1
// DB_PORT=3306
// DB_DATABASE=glampian
// DB_USERNAME=root
// DB_PASSWORD=LP)mUJQc*,?4~@xd

const dbConnection = mysql.createPool({
    host: process.env.DB_HOST || "localhost",
    user: process.env.DB_USER || "root",
    password: process.env.DB_PASSWORD || "LP)mUJQc*,?4~@xd",
    database: process.env.DB_NAME || "glampian",
    waitForConnections: true,
    connectionLimit: 10,
    queueLimit: 0,
});

console.log("Connected to MySQL database", process.env.DB_HOST);

export default dbConnection;
