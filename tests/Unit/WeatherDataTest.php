<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\DTOs\WeatherData;
use App\Enums\WeatherCode;
use PHPUnit\Framework\TestCase;

class WeatherDataTest extends TestCase
{
    public function test_creates_weather_data_with_all_properties(): void
    {
        $weatherData = new WeatherData(
            temperature: 22.5,
            weatherCode: 0,
            latitude: 40.4168,
            longitude: -3.7038,
            timezone: 'Europe/Madrid'
        );

        $this->assertEquals(22.5, $weatherData->temperature);
        $this->assertEquals(0, $weatherData->weatherCode);
        $this->assertEquals(40.4168, $weatherData->latitude);
        $this->assertEquals(-3.7038, $weatherData->longitude);
        $this->assertEquals('Europe/Madrid', $weatherData->timezone);
    }

    public function test_is_immutable_readonly_class(): void
    {
        $weatherData = new WeatherData(
            temperature: 15.0,
            weatherCode: 61,
            latitude: 51.5074,
            longitude: -0.1278,
            timezone: 'Europe/London'
        );

        $this->assertTrue((new \ReflectionClass($weatherData))->isReadOnly());
    }

    public function test_get_weather_description_returns_correct_spanish_description(): void
    {
        $weatherData = new WeatherData(
            temperature: 20.0,
            weatherCode: 0,
            latitude: 40.4168,
            longitude: -3.7038,
            timezone: 'Europe/Madrid'
        );

        $this->assertEquals('Cielo despejado', $weatherData->getWeatherDescription());
    }

    public function test_get_weather_description_for_rain(): void
    {
        $weatherData = new WeatherData(
            temperature: 14.0,
            weatherCode: 61,
            latitude: 52.52,
            longitude: 13.405,
            timezone: 'Europe/Berlin'
        );

        $this->assertEquals('Lluvia leve', $weatherData->getWeatherDescription());
    }

    public function test_get_weather_description_for_unknown_code(): void
    {
        $weatherData = new WeatherData(
            temperature: 18.0,
            weatherCode: 999,
            latitude: 48.8566,
            longitude: 2.3522,
            timezone: 'Europe/Paris'
        );

        $this->assertEquals('Desconocido', $weatherData->getWeatherDescription());
    }

    public function test_get_weather_emoji_returns_correct_emoji(): void
    {
        $weatherData = new WeatherData(
            temperature: 25.0,
            weatherCode: 0,
            latitude: 40.4168,
            longitude: -3.7038,
            timezone: 'Europe/Madrid'
        );

        $this->assertEquals('☀️', $weatherData->getWeatherEmoji());
    }

    public function test_get_weather_emoji_for_rain(): void
    {
        $weatherData = new WeatherData(
            temperature: 12.0,
            weatherCode: 61,
            latitude: 51.5074,
            longitude: -0.1278,
            timezone: 'Europe/London'
        );

        $this->assertEquals('🌧️', $weatherData->getWeatherEmoji());
    }

    public function test_get_weather_emoji_for_snow(): void
    {
        $weatherData = new WeatherData(
            temperature: -2.0,
            weatherCode: 71,
            latitude: 59.9139,
            longitude: 10.7522,
            timezone: 'Europe/Oslo'
        );

        $this->assertEquals('❄️', $weatherData->getWeatherEmoji());
    }

    public function test_get_weather_emoji_for_unknown_code(): void
    {
        $weatherData = new WeatherData(
            temperature: 18.0,
            weatherCode: 999,
            latitude: 48.8566,
            longitude: 2.3522,
            timezone: 'Europe/Paris'
        );

        $this->assertEquals('🌡️', $weatherData->getWeatherEmoji());
    }

    public function test_to_array_returns_complete_data_array(): void
    {
        $weatherData = new WeatherData(
            temperature: 22.5,
            weatherCode: 1,
            latitude: 40.4168,
            longitude: -3.7038,
            timezone: 'Europe/Madrid'
        );

        $array = $weatherData->toArray();

        $this->assertIsArray($array);
        $this->assertEquals(22.5, $array['temperature']);
        $this->assertEquals(1, $array['weather_code']);
        $this->assertEquals('Mayormente despejado', $array['weather_description']);
        $this->assertEquals('🌤️', $array['weather_emoji']);
        $this->assertEquals(40.4168, $array['latitude']);
        $this->assertEquals(-3.7038, $array['longitude']);
        $this->assertEquals('Europe/Madrid', $array['timezone']);
    }

    public function test_handles_negative_temperatures(): void
    {
        $weatherData = new WeatherData(
            temperature: -10.5,
            weatherCode: 71,
            latitude: 64.1466,
            longitude: -21.9426,
            timezone: 'Atlantic/Reykjavik'
        );

        $this->assertEquals(-10.5, $weatherData->temperature);
        $this->assertEquals('Nieve leve', $weatherData->getWeatherDescription());
    }

    public function test_handles_extreme_high_temperatures(): void
    {
        $weatherData = new WeatherData(
            temperature: 45.0,
            weatherCode: 0,
            latitude: 24.4539,
            longitude: 54.3773,
            timezone: 'Asia/Dubai'
        );

        $this->assertEquals(45.0, $weatherData->temperature);
        $this->assertEquals('Cielo despejado', $weatherData->getWeatherDescription());
    }
}

# cGFuZ29saW4=
