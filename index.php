<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Weather App | Live Weather</title>

    <link rel="stylesheet"
          href="assets/css/style.css">
</head>

<body>

<div class="app">

    <aside class="sidebar">

        <div class="logo">
            <span>☁</span>
            Weather<span class="blue">App</span>
        </div>

        <div class="side-menu">
            <div class="menu active">
                <span>◉</span> Overview
            </div>

            <div class="menu">
                <span>◷</span> Forecast
            </div>
        </div>

        <div class="sidebar-bottom">
            <div class="mini-card">
                <span>🌍</span>
                <p>Weather around the world</p>
                <small>Live weather information</small>
            </div>
        </div>

    </aside>

    <main class="main">

        <header class="topbar">

            <div>
                <h2>Weather Dashboard</h2>
                <p>Informasi cuaca secara real-time</p>
            </div>

            <div class="search-area">

                <input
                    type="text"
                    id="cityInput"
                    placeholder="Cari kota..."
                    autocomplete="off"
                >

                <button id="searchBtn">
                    Search
                </button>

            </div>

        </header>

        <div id="status" class="status"></div>

        <section class="dashboard">

            <div class="current-card">

                <div class="current-header">
                    <div>
                        <span class="eyebrow">
                            CURRENT WEATHER
                        </span>

                        <h1 id="cityName">Jakarta</h1>

                        <p id="countryName">Indonesia</p>
                    </div>

                    <div class="date" id="currentDate"></div>
                </div>

                <div class="weather-main">

                    <div class="temperature">
                        <span id="temperature">--</span>
                        <sup>°C</sup>
                    </div>

                    <div class="weather-icon"
                         id="weatherIcon">🌤️</div>

                </div>

                <h3 id="weatherDescription">
                    Loading weather...
                </h3>

                <p class="feels">
                    Terasa seperti
                    <strong id="feelsLike">--°C</strong>
                </p>

                <div class="weather-stats">

                    <div class="stat">
                        <span>💧</span>
                        <div>
                            <small>Humidity</small>
                            <strong id="humidity">--%</strong>
                        </div>
                    </div>

                    <div class="stat">
                        <span>💨</span>
                        <div>
                            <small>Wind Speed</small>
                            <strong id="wind">-- km/h</strong>
                        </div>
                    </div>

                    <div class="stat">
                        <span>🌧️</span>
                        <div>
                            <small>Precipitation</small>
                            <strong id="rain">-- mm</strong>
                        </div>
                    </div>

                </div>

            </div>

            <div class="right-column">

                <div class="info-card">

                    <h3>Sun Information</h3>

                    <div class="sun-row">
                        <span>🌅 Sunrise</span>
                        <strong id="sunrise">--:--</strong>
                    </div>

                    <div class="sun-row">
                        <span>🌇 Sunset</span>
                        <strong id="sunset">--:--</strong>
                    </div>

                    <div class="sun-row">
                        <span>☀️ UV Index</span>
                        <strong id="uv">--</strong>
                    </div>

                </div>

                <div class="info-card">

                    <h3>Location</h3>

                    <div class="location-icon">📍</div>

                    <p id="locationText">
                        Jakarta, Indonesia
                    </p>

                    <small id="coordinates">
                        Coordinates: --
                    </small>

                </div>

            </div>

        </section>

        <section class="section">

            <div class="section-header">
                <div>
                    <h2>Hourly Forecast</h2>
                    <p>Prakiraan cuaca 24 jam ke depan</p>
                </div>
            </div>

            <div class="hourly-list" id="hourlyForecast">
                <div class="loading">Loading...</div>
            </div>

        </section>

        <section class="section">

            <div class="section-header">
                <div>
                    <h2>7 Days Forecast</h2>
                    <p>Prakiraan cuaca mingguan</p>
                </div>
            </div>

            <div class="weekly-list" id="weeklyForecast">
                <div class="loading">Loading...</div>
            </div>

        </section>

        <footer>
            <p>
                WeatherApp © 2026 |
                Powered by Open-Meteo
            </p>
        </footer>

    </main>

</div>

<script src="assets/js/app.js"></script>

</body>
</html>