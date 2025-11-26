<?php
function swp_get_weather($city, $show_wind, $show_humidity, $units) {

    $apiKey = get_option('weather_api_key');
    $lang   = get_option('weather_lang', 'nl');

    if (!$apiKey) {
        return "<p><strong>Error:</strong> Geen API key ingevuld.</p>";
    }

    // units
    $unit_symbol = ($units === 'metric') ? '°C' : '°F';

    // taal labels
    if ($lang === 'fr') {
        $labels = [
            'weather_in' => 'Météo à',
            'temperature' => 'Température',
            'wind' => 'Vitesse du vent',
            'humidity' => 'Humidité',
            'error_api' => "Aucune clé API fournie.",
            'error_no_data' => "Données invalides reçues."
        ];
    } elseif ($lang === 'en') {
        $labels = [
            'weather_in' => 'Weather in',
            'temperature' => 'Temperature',
            'wind' => 'Wind speed',
            'humidity' => 'Humidity',
            'error_api' => "No API key provided.",
            'error_no_data' => "No valid data received."
        ];
    } elseif ($lang === 'de') {
        $labels = [
            'weather_in' => 'Wetter in',
            'temperature' => 'Temperatur',
            'wind' => 'Windgeschwindigkeit',
            'humidity' => 'Luftfeuchtigkeit',
            'error_api' => "Kein API-Schlüssel eingegeben.",
            'error_no_data' => "Keine gültigen Daten erhalten."
        ];
    } else {
        $labels = [
            'weather_in' => 'Weer in',
            'temperature' => 'Temperatuur',
            'wind' => 'Windsnelheid',
            'humidity' => 'Luchtvochtigheid',
            'error_api' => "Geen API key ingevuld.",
            'error_no_data' => "Geen geldige data ontvangen."
        ];
    }

    // API request
    $url = "https://api.openweathermap.org/data/2.5/weather?q={$city}&units={$units}&lang={$lang}&appid={$apiKey}";
    $response = wp_remote_get($url);
    if (is_wp_error($response)) return "<p>{$labels['error_api']}</p>";

    $data = json_decode(wp_remote_retrieve_body($response), true);
    if (!isset($data['main'])) return "<p>{$labels['error_no_data']}</p>";

    $temp = $data['main']['temp'];
    $desc = ucfirst($data['weather'][0]['description']);
    $humidity_value = $data['main']['humidity'];
    $wind_value = $data['wind']['speed'];
    $icon = $data['weather'][0]['icon'];
    $icon_url = "http://openweathermap.org/img/wn/{$icon}@2x.png";

    $extra_html = "";
    if ($show_wind == 1) $extra_html .= "<p>{$labels['wind']}: {$wind_value} m/s</p>";
    if ($show_humidity == 1) $extra_html .= "<p>{$labels['humidity']}: {$humidity_value}%</p>";

    return "
    <div class='swp-weather'>
        <p><strong>{$labels['weather_in']} {$city}:</strong></p>
        <p><img src='{$icon_url}' alt='{$desc}' style='vertical-align:middle;'> {$desc}</p>
        <p>{$labels['temperature']}: {$temp}{$unit_symbol}</p>
        {$extra_html}
    </div>
    ";
}
