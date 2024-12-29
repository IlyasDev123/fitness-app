import express from "express";
// import { createServer } from "http";
import { createServer } from "https";
import { readFileSync } from "fs";
import { Server } from "socket.io";
import "dotenv/config";
import { updateWatchTime } from "./watchTimeController.js";

const app = express();

// Load SSL certificates
const privateKey = readFileSync(
    "/etc/letsencrypt/live/dev.upworkdeveloper.com/privkey.pem",
    "utf8"
);
const certificate = readFileSync(
    "/etc/letsencrypt/live/dev.upworkdeveloper.com/cert.pem",
    "utf8"
);
const credentials = { key: privateKey, cert: certificate };

const httpsServer = createServer(credentials, app);
// const httpsServer = createServer(app);
const io = new Server(httpsServer);

const PORT = 5901;

httpsServer.listen(PORT, () => {
    console.log(`Server is running on port ${PORT}`);
});

io.on("connection", (socket) => {
    socket.on("updateWatchTime", async (data) => {
        try {
            const newWatchTime = await updateWatchTime(data);
            socket.emit("watchTimeUpdated", {
                message: "updated successfully.",
                data: newWatchTime,
            });
        } catch (error) {
            socket.emit("watchTimeUpdated", {
                message: "Error updating watch time",
            });
            console.error("Error updating watch time:", error);
        }
    });
});

app.get("/", function (req, res) {
    res.send("Socket Working fine");
});
