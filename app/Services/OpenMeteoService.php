<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\WeatherData;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenMeteoService
{
    private readonly string $baseUrl;
    private readonly string $geocodingUrl;
    private readonly int $cacheTtl;

    public function __construct()
    {
        $this->baseUrl = config('services.openmeteo.base_url');
        $this->geocodingUrl = config('services.openmeteo.geocoding_url');
        $this->cacheTtl = config('services.openmeteo.cache_ttl', 900);
    }

    public function getWeatherByCity(string $city): ?WeatherData
    {
        $cacheKey = $this->getCacheKey($city);

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($city) {
            try {
                $coordinates = $this->geocodeCity($city);

                if (!$coordinates) {
                    return null;
                }

                return $this->getWeatherByCoordinates(
                    $coordinates['latitude'],
                    $coordinates['longitude'],
                    $coordinates['timezone']
                );
            } catch (\Exception $e) {
                Log::error('Error getting weather by city', [
                    'city' => $city,
                    'error' => $e->getMessage(),
                ]);
                return null;
            }
        });
    }

    public function getWeatherByCoordinates(float $latitude, float $longitude, string $timezone = 'auto'): ?WeatherData
    {
        try {
            $response = Http::get($this->baseUrl, [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'current_weather' => true,
                'timezone' => $timezone,
            ]);

            if (!$response->successful()) {
                return null;
            }

            $data = $response->json();
            $currentWeather = $data['current_weather'] ?? null;

            if (!$currentWeather) {
                return null;
            }

            return new WeatherData(
                temperature: $currentWeather['temperature'],
                weatherCode: $currentWeather['weathercode'],
                latitude: $latitude,
                longitude: $longitude,
                timezone: $data['timezone'] ?? $timezone,
            );
        } catch (\Exception $e) {
            Log::error('Error getting weather by coordinates', [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    private function geocodeCity(string $city): ?array
    {
        try {
            $response = Http::get($this->geocodingUrl, [
                'name' => $city,
                'count' => 1,
                'language' => 'es',
                'format' => 'json',
            ]);

            if (!$response->successful()) {
                return null;
            }

            $data = $response->json();
            $results = $data['results'] ?? [];

            if (empty($results)) {
                return null;
            }

            $first = $results[0];

            return [
                'latitude' => $first['latitude'],
                'longitude' => $first['longitude'],
                'timezone' => $first['timezone'] ?? 'auto',
                'name' => $first['name'] ?? $city,
            ];
        } catch (\Exception $e) {
            Log::error('Error geocoding city', [
                'city' => $city,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    private function getCacheKey(string $city): string
    {
        return 'weather:' . strtolower(trim($city));
    }
}

# cGFuZ29saW4=
