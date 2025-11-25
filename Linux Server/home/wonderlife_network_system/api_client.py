import aiohttp
import asyncio

class WonderLifeAPI:
    def __init__(self, base_url: str, api_key: str):
        self.base = base_url
        self.key = api_key

    async def request(self, module: str, action: str = None, method: str = "GET", data: dict = None):
        params = {"module": module, "key": self.key}
        if action:
            params["action"] = action

        async with aiohttp.ClientSession() as session:
            if method == "POST":
                async with session.post(self.base, params=params, data=data) as resp:
                    return await resp.json()
            else:
                async with session.get(self.base, params=params) as resp:
                    return await resp.json()

    # --------------- DISCORD ----------------

    async def send_discord_stats(self, guild_data):
        return await self.request("discord", action="update", method="POST", data=guild_data)

    # --------------- TICKETS -----------------

    async def create_ticket(self, user_id: int, category: str, message: str):
        return await self.request(
            "tickets",
            action="create",
            method="POST",
            data={"user_id": user_id, "category": category, "message": message}
        )

    # --------------- ECONOMY -----------------

    async def add_money(self, user_id: int, amount: int):
        return await self.request(
            "economy", "add", "POST",
            {"id": user_id, "amount": amount}
        )

    # --------------- LEVELS -----------------

    async def add_xp(self, user_id: int, xp: int):
        return await self.request(
            "levels", "add_xp", "POST",
            {"id": user_id, "xp": xp}
        )
