<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\DTOs\WeatherData;
use App\Services\OpenMeteoService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OpenMeteoServiceTest extends TestCase
{
    protected OpenMeteoService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new OpenMeteoService();
    }

    public function test_get_weather_by_city_returns_weather_data(): void
    {
        Http::fake([
            'https://geocoding-api.open-meteo.com/v1/search*' => Http::response([
                'results' => [
                    [
                        'name' => 'Madrid',
                        'latitude' => 40.4168,
                        'longitude' => -3.7038,
                        'timezone' => 'Europe/Madrid',
                    ],
                ],
            ], 200),
            'https://api.open-meteo.com/v1/forecast*' => Http::response([
                'current_weather' => [
                    'temperature' => 22.5,
                    'weathercode' => 0,
                ],
                'timezone' => 'Europe/Madrid',
            ], 200),
        ]);

        $weatherData = $this->service->getWeatherByCity('Madrid');

        $this->assertInstanceOf(WeatherData::class, $weatherData);
        $this->assertEquals(22.5, $weatherData->temperature);
        $this->assertEquals(0, $weatherData->weatherCode);
        $this->assertEquals(40.4168, $weatherData->latitude);
        $this->assertEquals(-3.7038, $weatherData->longitude);
        $this->assertEquals('Europe/Madrid', $weatherData->timezone);
    }

    public function test_get_weather_by_city_returns_null_when_city_not_found(): void
    {
        Http::fake([
            'https://geocoding-api.open-meteo.com/v1/search*' => Http::response([
                'results' => [],
            ], 200),
        ]);

        $weatherData = $this->service->getWeatherByCity('NonExistentCity');

        $this->assertNull($weatherData);
    }

    public function test_get_weather_by_city_returns_null_on_geocoding_api_error(): void
    {
        Http::fake([
            'https://geocoding-api.open-meteo.com/v1/search*' => Http::response([], 500),
        ]);

        $weatherData = $this->service->getWeatherByCity('Madrid');

        $this->assertNull($weatherData);
    }

    public function test_get_weather_by_city_returns_null_on_weather_api_error(): void
    {
        Http::fake([
            'https://geocoding-api.open-meteo.com/v1/search*' => Http::response([
                'results' => [
                    [
                        'name' => 'Madrid',
                        'latitude' => 40.4168,
                        'longitude' => -3.7038,
                        'timezone' => 'Europe/Madrid',
                    ],
                ],
            ], 200),
            'https://api.open-meteo.com/v1/forecast*' => Http::response([], 500),
        ]);

        $weatherData = $this->service->getWeatherByCity('Madrid');

        $this->assertNull($weatherData);
    }

    public function test_get_weather_by_coordinates_returns_weather_data(): void
    {
        Http::fake([
            'https://api.open-meteo.com/v1/forecast*' => Http::response([
                'current_weather' => [
                    'temperature' => 18.3,
                    'weathercode' => 61,
                ],
                'timezone' => 'Europe/London',
            ], 200),
        ]);

        $weatherData = $this->service->getWeatherByCoordinates(51.5074, -0.1278, 'Europe/London');

        $this->assertInstanceOf(WeatherData::class, $weatherData);
        $this->assertEquals(18.3, $weatherData->temperature);
        $this->assertEquals(61, $weatherData->weatherCode);
        $this->assertEquals(51.5074, $weatherData->latitude);
        $this->assertEquals(-0.1278, $weatherData->longitude);
        $this->assertEquals('Europe/London', $weatherData->timezone);
    }

    public function test_get_weather_by_coordinates_uses_auto_timezone_by_default(): void
    {
        Http::fake([
            'https://api.open-meteo.com/v1/forecast*' => Http::response([
                'current_weather' => [
                    'temperature' => 15.0,
                    'weathercode' => 3,
                ],
                'timezone' => 'auto',
            ], 200),
        ]);

        $weatherData = $this->service->getWeatherByCoordinates(48.8566, 2.3522);

        $this->assertInstanceOf(WeatherData::class, $weatherData);
        $this->assertEquals('auto', $weatherData->timezone);
    }

    public function test_get_weather_by_coordinates_returns_null_on_api_error(): void
    {
        Http::fake([
            'https://api.open-meteo.com/v1/forecast*' => Http::response([], 500),
        ]);

        $weatherData = $this->service->getWeatherByCoordinates(40.4168, -3.7038);

        $this->assertNull($weatherData);
    }

    public function test_get_weather_by_coordinates_returns_null_when_no_current_weather(): void
    {
        Http::fake([
            'https://api.open-meteo.com/v1/forecast*' => Http::response([
                'timezone' => 'Europe/Madrid',
            ], 200),
        ]);

        $weatherData = $this->service->getWeatherByCoordinates(40.4168, -3.7038);

        $this->assertNull($weatherData);
    }

    public function test_handles_negative_temperatures(): void
    {
        Http::fake([
            'https://api.open-meteo.com/v1/forecast*' => Http::response([
                'current_weather' => [
                    'temperature' => -15.5,
                    'weathercode' => 71,
                ],
                'timezone' => 'Europe/Oslo',
            ], 200),
        ]);

        $weatherData = $this->service->getWeatherByCoordinates(59.9139, 10.7522, 'Europe/Oslo');

        $this->assertEquals(-15.5, $weatherData->temperature);
    }

    public function test_handles_extreme_coordinates(): void
    {
        Http::fake([
            'https://api.open-meteo.com/v1/forecast*' => Http::response([
                'current_weather' => [
                    'temperature' => -40.0,
                    'weathercode' => 71,
                ],
                'timezone' => 'America/Anchorage',
            ], 200),
        ]);

        $weatherData = $this->service->getWeatherByCoordinates(64.8378, -147.7164);

        $this->assertInstanceOf(WeatherData::class, $weatherData);
    }
}

# cGFuZ29saW4=
