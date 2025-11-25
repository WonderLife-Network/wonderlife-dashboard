// ==========================================
// WONDERLIFE MONITOR SERVICE
// FiveM Live Stats + Player List + Resources
// ==========================================

const express = require("express");
const si = require("systeminformation");
const fetch = require("node-fetch");
const { Rcon } = require("rcon-client");

const app = express();

// ===============================
// RCON KONFIGURATION
// ===============================
// ⚠️ HIER ANPASSEN ⚠️
// Muss zu deiner server.cfg passen:
//   rcon_password "DEINPASSWORT"
//   endpoint_add_tcp "0.0.0.0:40120" (oder ähnliches)
const RCON_HOST = "127.0.0.1";
const RCON_PORT = 40120;
const RCON_PASSWORD = "DEIN_RCON_PASSWORT";

// RCON Helper
async function sendRcon(cmd) {
    try {
        const rcon = await Rcon.connect({
            host: RCON_HOST,
            port: RCON_PORT,
            password: RCON_PASSWORD
        });

        const res = await rcon.send(cmd);
        rcon.end();
        console.log("[RCON]", cmd, "=>", res);
        return { ok: true, response: res };

    } catch (err) {
        console.error("[RCON ERROR]", err);
        return { ok: false, error: err.toString() };
    }
}

// ===============================
// FiveM SERVER STATUS LADEN
// ===============================
async function getFivemStatus() {
    try {
        const infoRes = await fetch("http://127.0.0.1:30120/info.json");
        const info = await infoRes.json();

        const playersRes = await fetch("http://127.0.0.1:30120/players.json");
        const players = await playersRes.json();

        return {
            online: true,
            players: players.length,
            maxPlayers: info.vars.sv_maxClients,
            resources: info.resources.length
        };

    } catch (e) {
        console.error("[FiveM STATUS ERROR]", e);
        return {
            online: false,
            players: 0,
            maxPlayers: 0,
            resources: 0
        };
    }
}

// ===============================
// FiveM SPIELERLISTE LADEN
// ===============================
async function getFivemPlayers() {
    try {
        const playersRes = await fetch("http://127.0.0.1:30120/players.json");
        const playersJson = await playersRes.json();

        let list = [];

        for (let p of playersJson) {
            let license = "unknown";
            let discord = "unknown";
            let steam = "unknown";

            if (p.identifiers) {
                for (let id of p.identifiers) {
                    if (id.startsWith("license:")) license = id.replace("license:", "");
                    if (id.startsWith("discord:")) discord = id.replace("discord:", "");
                    if (id.startsWith("steam:"))   steam = id.replace("steam:", "");
                }
            }

            list.push({
                id: p.id,
                name: p.name,
                ping: p.ping,
                license: license,
                discord: discord,
                steam: steam
            });
        }

        return list;

    } catch (err) {
        console.error("[FiveM PLAYERS ERROR]", err);
        return [];
    }
}

// ===============================
// FiveM RESSOURCEN LADEN
// ===============================
async function getFivemResources() {
    try {
        const infoRes = await fetch("http://127.0.0.1:30120/info.json");
        const info = await infoRes.json();

        const resources = info.resources || [];

        // info.json listet nur laufende Ressourcen → status = "started"
        return resources.map(name => ({
            name,
            status: "started"
        }));

    } catch (err) {
        console.error("[FiveM RESOURCES ERROR]", err);
        return [];
    }
}

// ===============================
// /status – CPU, RAM, Players etc.
// ===============================
app.get("/status", async (req, res) => {
    const fivem = await getFivemStatus();
    const cpu = await si.currentLoad();
    const ram = await si.mem();

    res.json({
        online: fivem.online,
        players: fivem.players,
        maxPlayers: fivem.maxPlayers,
        resources: fivem.resources,
        cpu: cpu.currentload.toFixed(1),
        ram: ((ram.active / ram.total) * 100).toFixed(1),
        uptime: Math.round(process.uptime() / 60) + " Min"
    });
});

// ===============================
// /players – Spieler Live Liste
// ===============================
app.get("/players", async (req, res) => {
    const players = await getFivemPlayers();

    res.json({
        status: "OK",
        count: players.length,
        players: players
    });
});

// ===============================
// /resources – Ressourcen Liste & Start/Stop
// ===============================
// GET /resources
// GET /resources?resource=NAME&action=start
// GET /resources?resource=NAME&action=stop
// ===============================
app.get("/resources", async (req, res) => {
    const { resource, action } = req.query;

    // Start/Stop Aktion
    if (resource && action) {
        let cmd = null;

        if (action === "start") {
            cmd = `ensure ${resource}`;
        } else if (action === "stop") {
            cmd = `stop ${resource}`;
        }

        if (!cmd) {
            return res.json({ status: "ERROR", message: "Ungültige Aktion" });
        }

        const result = await sendRcon(cmd);

        if (!result.ok) {
            return res.json({ status: "ERROR", message: "RCON Fehler", detail: result.error });
        }

        return res.json({
            status: "OK",
            message: `Aktion ${action} auf Resource ${resource} gesendet.`,
            rcon: result.response
        });
    }

    // Nur Liste zurückgeben
    const resources = await getFivemResources();

    res.json({
        status: "OK",
        resources: resources
    });
});

// ===============================
// SERVER START
// ===============================
app.listen(8090, () => {
    console.log("WonderLife Monitor läuft auf Port 8090");
});
