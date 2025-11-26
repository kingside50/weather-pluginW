<?php

// settings menu toevoegen
function swp_add_settings_page() {
    add_options_page(
        'Weather Plugin Settings',
        'Weather Settings',
        'manage_options',
        'swp-settings',
        'swp_render_settings_page'
    );
}
add_action('admin_menu', 'swp_add_settings_page');

// registreer instellingen
function swp_register_settings() {
    register_setting('swp_settings_group', 'swp_api_key');
    register_setting('swp_settings_group', 'swp_city');
    register_setting('swp_settings_group', 'swp_units');
    register_setting('swp_settings_group', 'swp_show_wind');
    register_setting('swp_settings_group', 'swp_show_humidity');

}
add_action('admin_init', 'swp_register_settings');

// admin pagina renderen
function swp_render_settings_page() {
    ?>
    <div class="wrap">
        <h1>Weather Plug-in Instellingen</h1>
        <form method="post" action="options.php">
            <?php settings_fields('swp_settings_group'); ?>
            <?php do_settings_sections('swp_settings_group'); ?>

            <table class="form-table">
                <tr>
                    <th>API Key</th>
                    <td><input type="text" name="swp_api_key" value="<?php echo esc_attr(get_option('swp_api_key')); ?>" size="40"></td>
                </tr>

                <tr>
                    <th>Standaardstad</th>
                    <td><input type="text" name="swp_city" value="<?php echo esc_attr(get_option('swp_city', 'Nijmegen')); ?>"></td>
                </tr>

    <tr>
    <th>Toon windsnelheid</th>
    <td>
        <input type="checkbox" name="swp_show_wind" value="1" 
        <?php checked(get_option('swp_show_wind'), 1); ?>>
        Ja, toon windsnelheid
    </td>
</tr>

<tr>
    <th>Toon luchtvochtigheid</th>
    <td>
        <input type="checkbox" name="swp_show_humidity" value="1" 
        <?php checked(get_option('swp_show_humidity'), 1); ?>>
        Ja, toon luchtvochtigheid
    </td>
</tr>

                <tr>
                    <th>Eenheid</th>
                    <td>
                        <select name="swp_units">
                            <option value="metric" <?php selected(get_option('swp_units'), 'metric'); ?>>Celsius</option>
                            <option value="imperial" <?php selected(get_option('swp_units'), 'imperial'); ?>>Fahrenheit</option>
                        </select>
                    </td>
                </tr>
            </table>

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
