<?php
// Agenten-Badge-Leiste für Ticket-Dashboard
?>

<div id="agent_badge_bar" class="agent-badge-bar">
    <span class="loading-text">Lade Agents…</span>
</div>

<style>
.agent-badge-bar {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
    flex-wrap: wrap;
}

.agent-badge {
    display: flex;
    align-items: center;
    gap: 6px;
    background: rgba(164, 76, 255, 0.15);
    border: 1px solid #a44cff55;
    padding: 6px 12px;
    border-radius: 20px;
    color: #fff;
    font-size: 13px;
    box-shadow: 0 0 10px #a44cff44;
}

.agent-online {
    border-left: 4px solid #32ff7e;
}

.agent-idle {
    border-left: 4px solid #ffe227;
}

.agent-offline {
    border-left: 4px solid #ff4d4d;
    opacity: 0.6;
}

.agent-typing {
    animation: typingPulse 1s infinite alternate;
}

@keyframes typingPulse {
    0% { box-shadow: 0 0 10px #a44cff; }
    100% { box-shadow: 0 0 20px #ff00d4; }
}

.loading-text {
    opacity: 0.6;
}
</style>

<script>
async function updateAgentBadgeBar() {

    const res = await fetch("/api/agents/get_status.php?ticket_id=<?= $ticket_id ?>");
    const data = await res.json();

    const box = document.getElementById("agent_badge_bar");
    let html = "";

    if (!data.agents || data.agents.length === 0) {
        box.innerHTML = "<div class='agent-badge agent-offline'>Keine Agents aktiv</div>";
        return;
    }

    for (let a of data.agents) {

        let statusClass = "agent-offline";
        if (a.status === "online") statusClass = "agent-online";
        if (a.status === "idle")   statusClass = "agent-idle";

        let typing = a.typing == 1 ? "<span class='agent-typing'>✏ tippt…</span>" : "";

        html += `
            <div class="agent-badge ${statusClass}">
                <b>${a.username}</b>  
                ${typing}
            </div>
        `;
    }

    box.innerHTML = html;
}

// Badge-Leiste alle 3 Sekunden aktualisieren
setInterval(updateAgentBadgeBar, 3000);
updateAgentBadgeBar();
</script>
