# 🎧 Discord Server Widget

**Discord Server Widget** is a WordPress plugin that displays live information about your Discord server inside a widget. It uses the Discord API to fetch **member stats, online users, boost level, and channels list**, and includes a direct **invite button** to join your server.

![Discord Server Widget Screenshot](screenshot.png)

---

## ✨ Features

- ✅ Display your **Discord server name & icon**  
- ✅ Show **total members** and **currently online users**  
- ✅ Show **server boost level & number of boosts**  
- ✅ List **text channels** and **voice channels**  
- ✅ Custom **invite button** (Discord style)  
- ✅ Fully configurable via the WordPress **Widget settings**  

---

## 📦 Installation

1. Download or clone the repository into `wp-content/plugins/discord-server-widget/`:
   ```bash
   git clone https://github.com/yourname/discord-server-widget.git
2. Go to your WordPress Dashboard → Plugins → Activate Discord Server Widget

3. Navigate to Appearance → Widgets and drag the Discord Server Widget to your sidebar or footer

⚙️ Configuration

In the widget settings, provide the following values:

Title → Widget title (e.g. Join our Discord!)

Bot Token → Your Discord Bot Token (required)

Guild ID → Your Discord Server ID (required)

Invite URL → A permanent invite link to your server (optional but recommended)

⚠️ You must create a Discord Bot and add it to your server with the correct permissions in order for the API to work.

🚀 Example Output

The widget will render something like this:

🎧 My Discord Server
👥 Total Members: 125
🟢 Online Now: 47
🚀 Boost lvl: 2 (x8)

Text Channels:
• #general
• #announcements

Voice Channels:
• 🔊 Lobby
• 🔊 Gaming Room

[ Join the Server ]

📂 Plugin Structure
discord-server-widget/
│── discord-server-widget.php   # Main plugin file

🛡️ Security Notes

Your Bot Token is required to query the Discord API.

Keep your token safe and never share it publicly.

The plugin only makes read-only API requests to display server information.

📜 License

This project is licensed under GPL-2.0+.
You are free to use, modify, and redistribute it under the license terms.
