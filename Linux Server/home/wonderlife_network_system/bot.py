import discord
from discord.ext import commands
from api_client import WonderLifeAPI
from modules.discord_sync import DiscordSync
from modules.ticket_sync import TicketSync
from modules.economy_sync import EconomySync
from modules.level_sync import LevelSync
from modules.fivem_sync import FiveMSync

BOT_TOKEN = "DEIN_DISCORD_BOT_TOKEN"
GUILD_ID = 1438177248678121615

API_URL = "https://team.wonderlife-network.net/api/index.php"
API_KEY = "DEIN_API_KEY_HIER"

bot = commands.Bot(command_prefix="!", intents=discord.Intents.all())

@bot.event
async def on_ready():
    print(f"Bot online als {bot.user}")

api = WonderLifeAPI(API_URL, API_KEY)

bot.add_cog(DiscordSync(bot, api))
bot.add_cog(TicketSync(bot, api))
bot.add_cog(EconomySync(bot, api))
bot.add_cog(LevelSync(bot, api))
bot.add_cog(FiveMSync(bot, api))

bot.run(BOT_TOKEN)
