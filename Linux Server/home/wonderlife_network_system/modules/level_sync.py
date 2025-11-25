import discord
from discord.ext import commands

class LevelSync(commands.Cog):
    def __init__(self, bot, api):
        self.bot = bot
        self.api = api

    @commands.command()
    async def addxp(self, ctx, user: discord.Member, xp: int):
        await self.api.add_xp(user.id, xp)
        await ctx.send(f"⭐ {xp} XP für {user.name} vergeben!")
