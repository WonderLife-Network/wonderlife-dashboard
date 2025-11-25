import aiohttp
import asyncio
from discord.ext import tasks, commands

FIVEM_IP = "193.46.81.88:30120"   # Deine Server IP
API_URL = "https://team.wonderlife-network.net/api/index.php"
API_KEY = "DEIN_API_KEY"

class FiveMSync(commands.Cog):
    def __init__(self, bot, api):
        self.bot = bot
        self.api = api
        self.sync.start()

    def cog_unload(self):
        self.sync.cancel()

    async def fetch_fivem(self):
        url = f"http://{FIVEM_IP}/players.json"
        info_url = f"http://{FIVEM_IP}/info.json"

        async with aiohttp.ClientSession() as session:

            # Spieler Liste
            try:
                async with session.get(url) as resp:
                    players = await resp.json()
            except:
                players = []

            # Server Info
            try:
                async with session.get(info_url) as resp:
                    info = await resp.json()
            except:
                info = {"vars":{}}

        return players, info

    @tasks.loop(seconds=30)
    async def sync(self):
        players, info = await self.fetch_fivem()

        data = {
            "players": players,
            "info": info,
            "ip": FIVEM_IP
        }

        await self.api.request(
            module="fivem",
            action="update",
            method="POST",
            data={"json": str(data)}
        )
