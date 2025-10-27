<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Enums\WeatherCode;

readonly class WeatherData
{
    public function __construct(
        public float $temperature,
        public int $weatherCode,
        public float $latitude,
        public float $longitude,
        public string $timezone,
    ) {
    }

    public function getWeatherDescription(): string
    {
        return WeatherCode::tryFrom($this->weatherCode)?->description() ?? 'Desconocido';
    }

    public function getWeatherEmoji(): string
    {
        return WeatherCode::tryFrom($this->weatherCode)?->emoji() ?? '🌡️';
    }

    public function toArray(): array
    {
        return [
            'temperature' => $this->temperature,
            'weather_code' => $this->weatherCode,
            'weather_description' => $this->getWeatherDescription(),
            'weather_emoji' => $this->getWeatherEmoji(),
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'timezone' => $this->timezone,
        ];
    }
}

# cGFuZ29saW4=
