<?php
// Settings pagina 

function weather_add_settings_page() {
    add_options_page(
        'Weather Plugin Settings',
        'Weather Settings',
        'manage_options',
        'weather-settings',
        'weather_render_settings_page'
    );
}
add_action('admin_menu', 'weather_add_settings_page');

function weather_register_settings() {
    register_setting('weather_settings_group', 'weather_api_key');
    register_setting('weather_settings_group', 'weather_city');
    register_setting('weather_settings_group', 'weather_units');
    register_setting('weather_settings_group', 'weather_show_wind');
    register_setting('weather_settings_group', 'weather_show_humidity');
    register_setting('weather_settings_group', 'weather_lang');
}
add_action('admin_init', 'weather_register_settings');

function weather_render_settings_page() {
    $lang = get_option('weather_lang', 'nl');

    // Vertalingen
    switch ($lang) {
        case 'fr':
            $labels = [
                'page_title' => 'Paramètres du plugin météo',
                'api_key' => 'Clé API',
                'city' => 'Ville par défaut',
                'show_wind' => 'Afficher la vitesse du vent',
                'show_humidity' => 'Afficher l’humidité',
                'yes' => 'Oui',
                'units' => 'Unité',
                'celsius' => 'Celsius',
                'fahrenheit' => 'Fahrenheit',
                'lang' => 'Langue',
                'example_title' => 'Exemple de météo',
                'temperature' => 'Température',
                'wind' => 'Vitesse du vent',
                'humidity' => 'Humidité',
                'error_no_api' => 'Clé API non définie',
                'error_no_data' => 'Données non valides reçues',
            ];
            break;
        case 'en':
            $labels = [
                'page_title' => 'Weather Plugin Settings',
                'api_key' => 'API Key',
                'city' => 'Default City',
                'show_wind' => 'Show Wind Speed',
                'show_humidity' => 'Show Humidity',
                'yes' => 'Yes',
                'units' => 'Units',
                'celsius' => 'Celsius',
                'fahrenheit' => 'Fahrenheit',
                'lang' => 'Language',
                'example_title' => 'Weather Example',
                'temperature' => 'Temperature',
                'wind' => 'Wind Speed',
                'humidity' => 'Humidity',
                'error_no_api' => 'API key not set',
                'error_no_data' => 'No valid data received',
            ];
            break;
        case 'de':
            $labels = [
                'page_title' => 'Wetter Plugin Einstellungen',
                'api_key' => 'API-Schlüssel',
                'city' => 'Standardstadt',
                'show_wind' => 'Windgeschwindigkeit anzeigen',
                'show_humidity' => 'Luftfeuchtigkeit anzeigen',
                'yes' => 'Ja',
                'units' => 'Einheit',
                'celsius' => 'Celsius',
                'fahrenheit' => 'Fahrenheit',
                'lang' => 'Sprache',
                'example_title' => 'Wetter Beispiel',
                'temperature' => 'Temperatur',
                'wind' => 'Windgeschwindigkeit',
                'humidity' => 'Luftfeuchtigkeit',
                'error_no_api' => 'API-Schlüssel nicht gesetzt',
                'error_no_data' => 'Keine gültigen Daten empfangen',
            ];
            break;
        default: // nl
            $labels = [
                'page_title' => 'Weather Plugin Instellingen',
                'api_key' => 'API Key',
                'city' => 'Standaardstad',
                'show_wind' => 'Toon windsnelheid',
                'show_humidity' => 'Toon luchtvochtigheid',
                'yes' => 'Ja',
                'units' => 'Eenheid',
                'celsius' => 'Celsius',
                'fahrenheit' => 'Fahrenheit',
                'lang' => 'Taal',
                'example_title' => 'Voorbeeld Weer',
                'temperature' => 'Temperatuur',
                'wind' => 'Windsnelheid',
                'humidity' => 'Luchtvochtigheid',
                'error_no_api' => 'Geen API key ingevuld',
                'error_no_data' => 'Geen geldige data ontvangen',
            ];
    }

    // Instellingen ophalen
    $city = get_option('weather_city', 'Nijmegen');
    $show_wind = get_option('weather_show_wind', 0);
    $show_humidity = get_option('weather_show_humidity', 0);
    $units = get_option('weather_units', 'metric');
    $apiKey = get_option('weather_api_key', "bf3723eb904f660b18665791dce939f9");

    ?>
    <div class="wrap">
        <h1><?php echo $labels['page_title']; ?></h1>
        <form method="post" action="options.php">
            <?php settings_fields('weather_settings_group'); ?>
            <?php do_settings_sections('weather_settings_group'); ?>

            <table class="form-table">
                <tr>
                    <th><?php echo $labels['api_key']; ?></th>
                    <td><input type="text" name="weather_api_key" value="<?php echo esc_attr($apiKey); ?>" size="40"></td>
                </tr>

                <tr>
                    <th><?php echo $labels['city']; ?></th>
                    <td><input type="text" name="weather_city" value="<?php echo esc_attr($city); ?>"></td>
                </tr>

                <tr>
                    <th><?php echo $labels['show_wind']; ?></th>
                    <td>
                        <input type="checkbox" name="weather_show_wind" value="1" <?php checked($show_wind, 1); ?>>
                        <?php echo $labels['yes']; ?>
                    </td>
                </tr>

                <tr>
                    <th><?php echo $labels['show_humidity']; ?></th>
                    <td>
                        <input type="checkbox" name="weather_show_humidity" value="1" <?php checked($show_humidity, 1); ?>>
                        <?php echo $labels['yes']; ?>
                    </td>
                </tr>

                <tr>
                    <th><?php echo $labels['units']; ?></th>
                    <td>
                        <select name="weather_units">
                            <option value="metric" <?php selected($units, 'metric'); ?>><?php echo $labels['celsius']; ?></option>
                            <option value="imperial" <?php selected($units, 'imperial'); ?>><?php echo $labels['fahrenheit']; ?></option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th><?php echo $labels['lang']; ?></th>
                    <td>
                        <select name="weather_lang">
                            <option value="nl" <?php selected($lang, 'nl'); ?>>Nederlands</option>
                            <option value="en" <?php selected($lang, 'en'); ?>>Engels</option>
                            <option value="fr" <?php selected($lang, 'fr'); ?>>Frans</option>
                            <option value="de" <?php selected($lang, 'de'); ?>>Duits</option>
                        </select>
                    </td>
                </tr>

            </table>

            <?php submit_button(); ?>
        </form>

        <!-- Voorbeeldweergave in settings page -->
        <h2><?php echo $labels['example_title']; ?></h2>
        <div style="border:1px solid #ddd; padding:10px; max-width:300px;">
            <?php
            if (!$apiKey) {
                echo "<p><strong>" . $labels['error_no_api'] . "</strong></p>";
            } else {
                $weather_html = swp_get_weather($city, $show_wind, $show_humidity, $units, $lang, $labels);
                echo $weather_html;
            }
            ?>
        </div>
    </div>
    <?php
}
