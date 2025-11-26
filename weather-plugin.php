<?php
/**
 * Plugin Name: Simple Weather Plugin
 * Description: Haalt weerinformatie op van OpenWeatherMap en toont dit via een shortcode.
 * Version: 1.0
 * Author: Kingside50
 */

if (!defined('ABSPATH')) exit;

// include bestanden
require_once plugin_dir_path(__FILE__) . 'includes/settings-page.php';
require_once plugin_dir_path(__FILE__) . 'includes/api-functions.php';

// style.css 
function swp_enqueue_styles() {
    wp_enqueue_style('swp-weather-style', plugin_dir_url(__FILE__) . 'css/style.css');
}
add_action('wp_enqueue_scripts', 'swp_enqueue_styles');

// shortcode tonen
function swp_weather_shortcode($atts) {

    $global_settings = [
        'city'     => get_option('swp_city', 'Amsterdam'),
        'wind'     => get_option('swp_show_wind', 0),
        'humidity' => get_option('swp_show_humidity', 0),
        'units'    => get_option('swp_units', 'metric'), // globale fallback
    ];

    // shortcode-attributen overschrijven alleen als ze bestaan
    $atts = shortcode_atts($global_settings, $atts);

    return swp_get_weather(
        $atts['city'],
        $atts['wind'],
        $atts['humidity'],
        $atts['units'] // units van de shortcode doorgeven
    );
}
add_shortcode('weather', 'swp_weather_shortcode');
