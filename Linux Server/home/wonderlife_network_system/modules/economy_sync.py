import discord
from discord.ext import commands

class EconomySync(commands.Cog):
    def __init__(self, bot, api):
        self.bot = bot
        self.api = api

    @commands.command()
    async def addmoney(self, ctx, user: discord.Member, amount: int):
        await self.api.add_money(user.id, amount)
        await ctx.send(f"💰 {amount} WL-Credits hinzugefügt für {user.name}")
