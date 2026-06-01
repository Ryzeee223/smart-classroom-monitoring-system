let rfidBuffer = "";
let lastKeyTime = 0;
const RFID_TIMEOUT = 50; // ms between keystrokes (readers are fast)

document.addEventListener("keydown", (e) => {
    const now = Date.now();

    // Reset buffer if typing is slow (human typing)
    if (now - lastKeyTime > RFID_TIMEOUT) {
        rfidBuffer = "";
    }

    lastKeyTime = now;

    if (e.key === "Enter") {
        if (rfidBuffer.length > 0) {
            handleRFID(rfidBuffer);
            rfidBuffer = "";
        }
        e.preventDefault();
    } else if (/^[0-9A-Za-z]$/.test(e.key)) {
        rfidBuffer += e.key;
    }
});

function handleRFID(uid) {
    fetch("/api/rfid", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ uid }),
    });
}

function handleRFID(uid) {
    fetch("/api/room-scan", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            uid: uid,
            room_id: 1,
        }),
    })
        .then((res) => res.json())
        .then((data) => updateRoomUI(data.status));
}

function updateRoomUI(status) {
    const room = document.getElementById("room1");
    if (status === "occupied") {
        room.classList.remove("vacant");
        room.classList.add("occupied");
        room.textContent = "Room 1: Occupied";
    } else {
        room.classList.remove("occupied");
        room.classList.add("vacant");
        room.textContent = "Room 1: Vacant";
    }
}
