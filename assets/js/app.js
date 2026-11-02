const API = 'api/weather.php';

const $ = id => document.getElementById(id);

let selectedCity = null;

const weatherTypes = {
    0: ['Cerah', '☀️'],
    1: ['Cerah berawan', '🌤️'],
    2: ['Berawan sebagian', '⛅'],
    3: ['Mendung', '☁️'],
    45: ['Berkabut', '🌫️'],
    48: ['Kabut tebal', '🌫️'],
    51: ['Gerimis ringan', '🌦️'],
    53: ['Gerimis', '🌦️'],
    55: ['Gerimis lebat', '🌧️'],
    61: ['Hujan ringan', '🌧️'],
    63: ['Hujan sedang', '🌧️'],
    65: ['Hujan lebat', '🌧️'],
    71: ['Salju ringan', '🌨️'],
    73: ['Salju', '❄️'],
    75: ['Salju lebat', '❄️'],
    80: ['Hujan lokal', '🌦️'],
    81: ['Hujan sedang', '🌧️'],
    82: ['Hujan lebat', '⛈️'],
    95: ['Badai petir', '⛈️'],
    96: ['Petir dan hujan es', '⛈️'],
    99: ['Badai petir kuat', '⛈️']
};

function getWeather(code) {
    return weatherTypes[code] || ['Tidak diketahui', '🌤️'];
}

function formatTime(date) {
    return new Date(date).toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: false
    });
}

function formatDay(date) {
    return new Date(date + 'T12:00:00').toLocaleDateString(
        'id-ID',
        { weekday: 'long' }
    );
}

function formatDate() {
    return new Date().toLocaleDateString('id-ID', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
}

function showStatus(message, error = false) {
    $('status').textContent = message;
    $('status').style.color = error ? '#ff8585' : '#8ebeff';
}

async function request(url) {
    const response = await fetch(url);

    const data = await response.json();

    if (!response.ok || data.error) {
        throw new Error(data.error || 'Terjadi kesalahan.');
    }

    return data;
}

// SEARCH CITY

async function searchCity() {

    const city = $('cityInput').value.trim();

    if (!city) {
        showStatus('Masukkan nama kota terlebih dahulu.', true);
        return;
    }

    try {

        showStatus('Mencari kota...');

        const data = await request(
            `${API}?action=search&city=${encodeURIComponent(city)}`
        );

        if (!data.results || data.results.length === 0) {
            showStatus('Kota tidak ditemukan.', true);
            return;
        }

        // Pilih hasil pertama yang paling relevan
        const result = data.results[0];

        await loadWeather({
            name: result.name,
            country: result.country || '',
            latitude: result.latitude,
            longitude: result.longitude
        });

    } catch (error) {

        showStatus(error.message, true);

    }
}

// LOAD WEATHER

async function loadWeather(city) {

    try {

        showStatus('Mengambil data cuaca...');

        $('temperature').textContent = '--';
        $('weatherDescription').textContent = 'Loading...';

        $('hourlyForecast').innerHTML =
            '<div class="loading">Loading forecast...</div>';

        $('weeklyForecast').innerHTML =
            '<div class="loading">Loading forecast...</div>';

        const data = await request(
            `${API}?action=weather&lat=${city.latitude}&lon=${city.longitude}`
        );

        selectedCity = city;

        renderWeather(data, city);

        showStatus('Data cuaca berhasil diperbarui.');

    } catch (error) {

        showStatus(error.message, true);

    }

}

// CURRENT WEATHER

function renderWeather(data, city) {

    const current = data.current;
    const daily = data.daily;

    const weather = getWeather(current.weather_code);

    $('cityName').textContent = city.name;
    $('countryName').textContent = city.country;

    $('currentDate').textContent = formatDate();

    $('temperature').textContent =
        Math.round(current.temperature_2m);

    $('weatherIcon').textContent = weather[1];

    $('weatherDescription').textContent = weather[0];

    $('feelsLike').textContent =
        Math.round(current.apparent_temperature) + '°C';

    $('humidity').textContent =
        current.relative_humidity_2m + '%';

    $('wind').textContent =
        current.wind_speed_10m + ' km/h';

    $('rain').textContent =
        current.precipitation + ' mm';

    $('locationText').textContent =
        city.name + ', ' + city.country;

    $('coordinates').textContent =
        `Coordinates: ${city.latitude.toFixed(2)}, ${city.longitude.toFixed(2)}`;

    $('sunrise').textContent =
        formatTime(daily.sunrise[0]);

    $('sunset').textContent =
        formatTime(daily.sunset[0]);

    $('uv').textContent =
        daily.uv_index_max[0];

    renderHourly(data);

    renderWeekly(data);

}

// HOURLY FORECAST

function renderHourly(data) {

    const hourly = data.hourly;
    const currentTime = data.current.time;

    let start = hourly.time.findIndex(time =>
        time >= currentTime
    );

    if (start < 0) start = 0;

    const html = hourly.time
        .slice(start, start + 24)
        .map((time, index) => {

            const i = start + index;

            const weather = getWeather(
                hourly.weather_code[i]
            );

            const active = index === 0 ? 'active' : '';

            return `
                <div class="hour-card ${active}">

                    <small>
                        ${index === 0 ? 'Now' : formatTime(time)}
                    </small>

                    <div class="hour-icon">
                        ${weather[1]}
                    </div>

                    <strong>
                        ${Math.round(hourly.temperature_2m[i])}°
                    </strong>

                    <span class="rain-chance">
                        💧 ${hourly.precipitation_probability[i] ?? 0}%
                    </span>

                </div>
            `;

        }).join('');

    $('hourlyForecast').innerHTML = html;

}

// WEEKLY FORECAST

function renderWeekly(data) {

    const daily = data.daily;

    const html = daily.time.map((date, i) => {

        const weather = getWeather(
            daily.weather_code[i]
        );

        return `
            <div class="day-row">

                <span class="day-name">
                    ${i === 0 ? 'Hari ini' : formatDay(date)}
                </span>

                <span class="day-icon">
                    ${weather[1]}
                </span>

                <span class="day-weather">
                    ${weather[0]}
                </span>

                <span class="day-temp">
                    ${Math.round(daily.temperature_2m_max[i])}°
                    <span>
                        ${Math.round(daily.temperature_2m_min[i])}°
                    </span>
                </span>

                <span style="text-align:right;color:#8ebeff">
                    UV ${daily.uv_index_max[i]}
                </span>

            </div>
        `;

    }).join('');

    $('weeklyForecast').innerHTML = html;

}

// EVENTS

$('searchBtn').addEventListener('click', searchCity);

$('cityInput').addEventListener('keydown', event => {

    if (event.key === 'Enter') {
        searchCity();
    }

});

// DEFAULT CITY

loadWeather({
    name: 'Jakarta',
    country: 'Indonesia',
    latitude: -6.2088,
    longitude: 106.8456
});