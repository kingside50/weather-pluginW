<?php
function swp_get_weather($city, $show_wind, $show_humidity, $units) {

    $apiKey = get_option('swp_api_key');
    if (!$apiKey) {
        return "<p><strong>Error:</strong> Geen API key ingevuld.</p>";
    }

    // Zorg dat units gebruikt worden zoals doorgegeven
    $unit_symbol = ($units === 'metric') ? '°C' : '°F';

    $url = "https://api.openweathermap.org/data/2.5/weather?q={$city}&units={$units}&appid={$apiKey}";

    $response = wp_remote_get($url);
    if (is_wp_error($response)) {
        return "<p>Kon geen verbinding maken met de API.</p>";
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);
    if (!isset($data['main'])) {
        return "<p>Geen geldige data ontvangen.</p>";
    }

    $temp     = $data['main']['temp'];
    $desc     = ucfirst($data['weather'][0]['description']);
    $humidity = $data['main']['humidity'];
    $wind     = $data['wind']['speed'];
    $icon     = $data['weather'][0]['icon'];
    $icon_url = "http://openweathermap.org/img/wn/{$icon}@2x.png";

    $extra_html = "";
    if ($show_wind == 1) {
        $extra_html .= "<p>Windsnelheid: {$wind} m/s</p>";
    }
    if ($show_humidity == 1) {
        $extra_html .= "<p>Luchtvochtigheid: {$humidity}%</p>";
    }

    return "
    <div class='swp-weather'>
        <p><strong>Weer in {$city}:</strong></p>
        <p><img src='{$icon_url}' alt='{$desc}' style='vertical-align:middle;'> {$desc}</p>
        <p>Temperatuur: {$temp}{$unit_symbol}</p>
        {$extra_html}
    </div>
    ";
}

