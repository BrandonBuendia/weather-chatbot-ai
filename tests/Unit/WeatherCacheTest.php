<?php

namespace Tests\Unit;

use App\Services\OpenMeteoService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WeatherCacheTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_weather_data_is_cached_on_first_request(): void
    {
        Http::fake([
            'geocoding-api.open-meteo.com/*' => Http::response([
                'results' => [
                    [
                        'latitude' => 40.4168,
                        'longitude' => -3.7038,
                        'timezone' => 'Europe/Madrid',
                        'name' => 'Madrid',
                    ],
                ],
            ]),
            'api.open-meteo.com/*' => Http::response([
                'current_weather' => [
                    'temperature' => 22.5,
                    'weathercode' => 0,
                ],
                'timezone' => 'Europe/Madrid',
            ]),
        ]);

        $service = new OpenMeteoService();

        $this->assertFalse(Cache::has('weather:madrid'));

        $result = $service->getWeatherByCity('Madrid');

        $this->assertNotNull($result);
        $this->assertTrue(Cache::has('weather:madrid'));
    }

    public function test_cached_weather_data_is_used_on_subsequent_requests(): void
    {
        Http::fake([
            'geocoding-api.open-meteo.com/*' => Http::response([
                'results' => [
                    [
                        'latitude' => 40.4168,
                        'longitude' => -3.7038,
                        'timezone' => 'Europe/Madrid',
                        'name' => 'Madrid',
                    ],
                ],
            ]),
            'api.open-meteo.com/*' => Http::response([
                'current_weather' => [
                    'temperature' => 22.5,
                    'weathercode' => 0,
                ],
                'timezone' => 'Europe/Madrid',
            ]),
        ]);

        $service = new OpenMeteoService();

        $firstResult = $service->getWeatherByCity('Madrid');
        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'geocoding-api.open-meteo.com');
        });

        Http::preventStrayRequests();

        $secondResult = $service->getWeatherByCity('Madrid');

        $this->assertEquals($firstResult->temperature, $secondResult->temperature);
    }

    public function test_cache_key_is_case_insensitive(): void
    {
        Http::fake([
            'geocoding-api.open-meteo.com/*' => Http::response([
                'results' => [
                    [
                        'latitude' => 40.4168,
                        'longitude' => -3.7038,
                        'timezone' => 'Europe/Madrid',
                        'name' => 'Madrid',
                    ],
                ],
            ]),
            'api.open-meteo.com/*' => Http::response([
                'current_weather' => [
                    'temperature' => 22.5,
                    'weathercode' => 0,
                ],
                'timezone' => 'Europe/Madrid',
            ]),
        ]);

        $service = new OpenMeteoService();

        $service->getWeatherByCity('Madrid');
        $this->assertTrue(Cache::has('weather:madrid'));

        Http::preventStrayRequests();

        $result = $service->getWeatherByCity('MADRID');
        $this->assertNotNull($result);
    }
}

# cGFuZ29saW4=
