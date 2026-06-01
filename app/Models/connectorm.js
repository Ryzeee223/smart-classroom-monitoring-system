// node connector-cjs.cjs - CommonJS Arduino RFID server
const serialport = require("serialport");
const { SerialPort } = serialport;
const express = require("express");
const cors = require("cors");

const app = express();
app.use(cors());

const port = new SerialPort({ path: "COM9", baudRate: 9600 });
let statusData = { status: "VACANT", uid: "" };

port.on("open", () => console.log("Serial port open - Arduino connected"));
port.on("data", (data) => {
    const message = data.toString().trim();
    console.log("Arduino:", message);

    if (message.startsWith("STATUS:")) {
        const statusPart = message.substring(7);
        let status = statusPart.split(" UID:")[0] || statusPart;
        let rawUid = "";
        if (statusPart.includes(" UID:")) {
            rawUid = statusPart.split(" UID:")[1] || "";
        }

        let uid = rawUid.replace(/[:\-\s]/g, "").toUpperCase();

        statusData = { status, uid, rawUid };
        console.log("Status:", statusData);
    }
});

port.on("error", (err) => console.error("Serial error:", err));

app.get("/status", (req, res) => res.json(statusData));

app.listen(3000, () => console.log("RFID server http://localhost:3000/status"));
