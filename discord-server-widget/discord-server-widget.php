<?php
/*
Plugin Name: Discord Server Widget
Description: Mostra le informazioni del tuo server Discord come widget WordPress.
Version: 1.1
Author: Teo
*/

if (!defined('ABSPATH')) exit;

class Discord_Server_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'discord_server_widget',
            'Discord Server Widget',
            array('description' => 'Mostra informazioni del tuo server Discord.')
        );
    }

    // === OUTPUT DEL WIDGET ===
    public function widget($args, $instance) {
        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        $token   = !empty($instance['bot_token']) ? $instance['bot_token'] : '';
        $guild   = !empty($instance['guild_id']) ? $instance['guild_id'] : '';
        $invite  = !empty($instance['invite_url']) ? $instance['invite_url'] : '#';

        if (!$token || !$guild) {
            echo "<div style='color:red;'>⚠️ Configura prima il widget!</div>";
            echo $args['after_widget'];
            return;
        }

        // Chiamata API
        $guild_info = $this->discord_api_get("guilds/$guild?with_counts=true", $token);
        $channels   = $this->discord_api_get("guilds/$guild/channels", $token);

        if (!$guild_info || !$channels) {
            echo "<div class='discord-block-error'><strong>Errore Discord:</strong> Impossibile recuperare i dati.</div>";
            echo $args['after_widget'];
			echo '<div style="color: #fff;">';  // Qui metti il colore che vuoi
            return;
        }

        // Estrai dati
        $members     = $guild_info['approximate_member_count'];
        $online      = $guild_info['approximate_presence_count'];
        $boost_level = $guild_info['premium_tier'];
        $boosts      = $guild_info['premium_subscription_count'];
        $text = $voice = "";

        foreach ($channels as $ch) {
            if ($ch['type'] === 0) $text  .= "• #{$ch['name']}<br>";
            if ($ch['type'] === 2) $voice .= "• 🔊 {$ch['name']}<br>";
        }

        // HTML Output
        ?>
        <div class="discord-block" style="text-align:center;">
            <div><strong>🎧 <?php echo esc_html($guild_info['name']); ?></strong></div>
            <img src="https://cdn.discordapp.com/icons/<?php echo esc_attr($guild); ?>/<?php echo esc_attr($guild_info['icon']); ?>.png" style="width:100px;margin:10px;border-radius:10px;">
            <br>👥 Membri totali: <strong><?php echo esc_html($members); ?></strong><br>
            🟢 Online ora: <strong><?php echo esc_html($online); ?></strong><br>
            🚀 Boost lvl: <strong><?php echo esc_html($boost_level); ?></strong> (x<?php echo esc_html($boosts); ?>)<br><hr>
            <div style="text-align:left; display:inline-block; max-width:90%;">
                <strong>Canali testuali:</strong><br><?php echo $text; ?>
                <br><strong>Canali vocali:</strong><br><?php echo $voice; ?>
            </div><br><hr>
            <a href="<?php echo esc_url($invite); ?>" target="_blank" style="display:inline-block;margin-top:10px;padding:8px 16px;background-color:#5865F2;color:#fff;border-radius:5px;text-decoration:none;font-weight:bold;">
                Unisciti al server
            </a>
        </div>
        <?php
        echo $args['after_widget'];
		
    }

    // === CHIAMATA API ===
    private function discord_api_get($endpoint, $token) {
        $response = wp_remote_get("https://discord.com/api/v10/$endpoint", array(
            'headers' => array('Authorization' => 'Bot ' . $token)
        ));
        if (is_wp_error($response)) return false;
        return json_decode(wp_remote_retrieve_body($response), true);
    }

    // === FORM CONFIGURAZIONE ===
    public function form($instance) {
        $title     = !empty($instance['title']) ? $instance['title'] : 'Discord Server';
        $bot_token = !empty($instance['bot_token']) ? $instance['bot_token'] : '';
        $guild_id  = !empty($instance['guild_id']) ? $instance['guild_id'] : '';
        $invite    = !empty($instance['invite_url']) ? $instance['invite_url'] : '';

        ?>
        <p><label>Titolo:</label>
        <input class="widefat" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($title); ?>"></p>

        <p><label>Bot Token:</label>
        <input class="widefat" name="<?php echo $this->get_field_name('bot_token'); ?>" value="<?php echo esc_attr($bot_token); ?>"></p>

        <p><label>Guild ID:</label>
        <input class="widefat" name="<?php echo $this->get_field_name('guild_id'); ?>" value="<?php echo esc_attr($guild_id); ?>"></p>

        <p><label>Invite URL:</label>
        <input class="widefat" name="<?php echo $this->get_field_name('invite_url'); ?>" value="<?php echo esc_attr($invite); ?>"></p>
        <?php
    }

    // === SALVATAGGIO OPZIONI ===
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title']      = sanitize_text_field($new_instance['title']);
        $instance['bot_token']  = sanitize_text_field($new_instance['bot_token']);
        $instance['guild_id']   = sanitize_text_field($new_instance['guild_id']);
        $instance['invite_url'] = esc_url_raw($new_instance['invite_url']);
        return $instance;
    }
}

// === REGISTRAZIONE WIDGET ===
function register_discord_server_widget() {
    register_widget('Discord_Server_Widget');
}
add_action('widgets_init', 'register_discord_server_widget');
