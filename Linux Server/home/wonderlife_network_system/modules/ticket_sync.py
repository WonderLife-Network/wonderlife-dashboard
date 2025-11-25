import discord
from discord.ext import commands

class TicketSync(commands.Cog):
    def __init__(self, bot, api):
        self.bot = bot
        self.api = api

    @commands.command()
    async def ticket(self, ctx, *, message: str):
        res = await self.api.create_ticket(ctx.author.id, "support", message)
        await ctx.send(f"🎫 Ticket erstellt! ID: {res['ticket_id']}")
