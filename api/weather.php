<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config.php';

function respond($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data);
    exit;
}

function fetchApi($url) {
    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_CONNECTTIMEOUT => 8,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_USERAGENT => 'WeatherAppPHP/1.0'
    ]);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($response === false || $status !== 200) {
        throw new Exception('Gagal menghubungi layanan cuaca.');
    }

    $data = json_decode($response, true);

    if (!is_array($data)) {
        throw new Exception('Respons API tidak valid.');
    }

    return $data;
}

try {

    $action = $_GET['action'] ?? '';

    // SEARCH CITY
    if ($action === 'search') {

        $city = trim($_GET['city'] ?? '');

        if ($city === '' || strlen($city) > 100) {
            respond([
                'error' => 'Masukkan nama kota yang valid.'
            ], 400);
        }

        $url = GEOCODING_API . '?' . http_build_query([
            'name' => $city,
            'count' => 8,
            'language' => 'id',
            'format' => 'json'
        ]);

        $data = fetchApi($url);

        respond([
            'results' => $data['results'] ?? []
        ]);
    }

    // GET WEATHER
    if ($action === 'weather') {

        $lat = filter_var(
            $_GET['lat'] ?? null,
            FILTER_VALIDATE_FLOAT
        );

        $lon = filter_var(
            $_GET['lon'] ?? null,
            FILTER_VALIDATE_FLOAT
        );

        if (
            $lat === false ||
            $lon === false ||
            $lat < -90 || $lat > 90 ||
            $lon < -180 || $lon > 180
        ) {
            respond([
                'error' => 'Koordinat tidak valid.'
            ], 400);
        }

        $params = [
            'latitude' => $lat,
            'longitude' => $lon,

            'current' => implode(',', [
                'temperature_2m',
                'relative_humidity_2m',
                'apparent_temperature',
                'is_day',
                'precipitation',
                'weather_code',
                'wind_speed_10m'
            ]),

            'hourly' => implode(',', [
                'temperature_2m',
                'precipitation_probability',
                'weather_code'
            ]),

            'daily' => implode(',', [
                'weather_code',
                'temperature_2m_max',
                'temperature_2m_min',
                'sunrise',
                'sunset',
                'uv_index_max'
            ]),

            'forecast_days' => 7,
            'timezone' => 'auto'
        ];

        $url = WEATHER_API . '?' . http_build_query($params);

        $data = fetchApi($url);

        respond($data);
    }

    respond([
        'error' => 'Action tidak ditemukan.'
    ], 404);

} catch (Throwable $e) {

    respond([
        'error' => $e->getMessage()
    ], 500);

}