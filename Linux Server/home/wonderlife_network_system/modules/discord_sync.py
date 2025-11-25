import discord
from discord.ext import tasks, commands

class DiscordSync(commands.Cog):
    def __init__(self, bot, api):
        self.bot = bot
        self.api = api
        self.sync_data.start()

    def cog_unload(self):
        self.sync_data.cancel()

    @tasks.loop(minutes=2)
    async def sync_data(self):
        guild = self.bot.get_guild(YOUR_GUILD_ID)
        if guild is None:
            return

        roles = [
            {"id": r.id, "name": r.name, "color": f"{r.color.value:06x}"}
            for r in guild.roles
        ]

        channels = [
            {"id": c.id, "name": c.name, "type": str(c.type)}
            for c in guild.channels
        ]

        members = [
            {"id": m.id, "username": str(m)}
            for m in guild.members
        ]

        data = {
            "guild_id": guild.id,
            "guild_name": guild.name,
            "member_count": guild.member_count,
            "roles": roles,
            "channels": channels,
            "members": members
        }

        await self.api.send_discord_stats(data)
