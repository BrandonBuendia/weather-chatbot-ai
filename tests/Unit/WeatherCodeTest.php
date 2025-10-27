<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Enums\WeatherCode;
use PHPUnit\Framework\TestCase;

class WeatherCodeTest extends TestCase
{
    public function test_clear_sky_has_correct_description(): void
    {
        $this->assertEquals('Cielo despejado', WeatherCode::CLEAR_SKY->description());
    }

    public function test_clear_sky_has_correct_emoji(): void
    {
        $this->assertEquals('☀️', WeatherCode::CLEAR_SKY->emoji());
    }

    public function test_mainly_clear_has_correct_description(): void
    {
        $this->assertEquals('Mayormente despejado', WeatherCode::MAINLY_CLEAR->description());
    }

    public function test_mainly_clear_has_correct_emoji(): void
    {
        $this->assertEquals('🌤️', WeatherCode::MAINLY_CLEAR->emoji());
    }

    public function test_partly_cloudy_has_correct_description(): void
    {
        $this->assertEquals('Parcialmente nublado', WeatherCode::PARTLY_CLOUDY->description());
    }

    public function test_partly_cloudy_has_correct_emoji(): void
    {
        $this->assertEquals('⛅', WeatherCode::PARTLY_CLOUDY->emoji());
    }

    public function test_overcast_has_correct_description(): void
    {
        $this->assertEquals('Nublado', WeatherCode::OVERCAST->description());
    }

    public function test_overcast_has_correct_emoji(): void
    {
        $this->assertEquals('☁️', WeatherCode::OVERCAST->emoji());
    }

    public function test_fog_has_correct_description(): void
    {
        $this->assertEquals('Niebla', WeatherCode::FOG->description());
    }

    public function test_fog_has_correct_emoji(): void
    {
        $this->assertEquals('🌫️', WeatherCode::FOG->emoji());
    }

    public function test_rain_slight_has_correct_description(): void
    {
        $this->assertEquals('Lluvia leve', WeatherCode::RAIN_SLIGHT->description());
    }

    public function test_rain_slight_has_correct_emoji(): void
    {
        $this->assertEquals('🌧️', WeatherCode::RAIN_SLIGHT->emoji());
    }

    public function test_rain_moderate_has_correct_description(): void
    {
        $this->assertEquals('Lluvia moderada', WeatherCode::RAIN_MODERATE->description());
    }

    public function test_rain_heavy_has_correct_description(): void
    {
        $this->assertEquals('Lluvia intensa', WeatherCode::RAIN_HEAVY->description());
    }

    public function test_snow_slight_has_correct_description(): void
    {
        $this->assertEquals('Nieve leve', WeatherCode::SNOW_SLIGHT->description());
    }

    public function test_snow_slight_has_correct_emoji(): void
    {
        $this->assertEquals('❄️', WeatherCode::SNOW_SLIGHT->emoji());
    }

    public function test_snow_moderate_has_correct_description(): void
    {
        $this->assertEquals('Nieve moderada', WeatherCode::SNOW_MODERATE->description());
    }

    public function test_snow_heavy_has_correct_description(): void
    {
        $this->assertEquals('Nieve intensa', WeatherCode::SNOW_HEAVY->description());
    }

    public function test_thunderstorm_has_correct_description(): void
    {
        $this->assertEquals('Tormenta eléctrica', WeatherCode::THUNDERSTORM->description());
    }

    public function test_thunderstorm_has_correct_emoji(): void
    {
        $this->assertEquals('⛈️', WeatherCode::THUNDERSTORM->emoji());
    }

    public function test_drizzle_light_has_correct_description(): void
    {
        $this->assertEquals('Llovizna ligera', WeatherCode::DRIZZLE_LIGHT->description());
    }

    public function test_drizzle_light_has_correct_emoji(): void
    {
        $this->assertEquals('🌧️', WeatherCode::DRIZZLE_LIGHT->emoji());
    }

    public function test_rain_showers_slight_has_correct_description(): void
    {
        $this->assertEquals('Chubascos leves', WeatherCode::RAIN_SHOWERS_SLIGHT->description());
    }

    public function test_rain_showers_slight_has_correct_emoji(): void
    {
        $this->assertEquals('☔', WeatherCode::RAIN_SHOWERS_SLIGHT->emoji());
    }

    public function test_snow_showers_slight_has_correct_description(): void
    {
        $this->assertEquals('Chubascos de nieve leves', WeatherCode::SNOW_SHOWERS_SLIGHT->description());
    }

    public function test_snow_showers_slight_has_correct_emoji(): void
    {
        $this->assertEquals('🌨️', WeatherCode::SNOW_SHOWERS_SLIGHT->emoji());
    }

    public function test_can_be_created_from_integer_value(): void
    {
        $weatherCode = WeatherCode::tryFrom(0);

        $this->assertInstanceOf(WeatherCode::class, $weatherCode);
        $this->assertEquals(WeatherCode::CLEAR_SKY, $weatherCode);
    }

    public function test_returns_null_for_invalid_code(): void
    {
        $weatherCode = WeatherCode::tryFrom(999);

        $this->assertNull($weatherCode);
    }

    public function test_all_weather_codes_have_unique_values(): void
    {
        $codes = array_map(fn($case) => $case->value, WeatherCode::cases());
        $uniqueCodes = array_unique($codes);

        $this->assertCount(count($codes), $uniqueCodes);
    }

    public function test_all_weather_codes_have_descriptions(): void
    {
        foreach (WeatherCode::cases() as $case) {
            $description = $case->description();
            $this->assertNotEmpty($description);
            $this->assertIsString($description);
        }
    }

    public function test_all_weather_codes_have_emojis(): void
    {
        foreach (WeatherCode::cases() as $case) {
            $emoji = $case->emoji();
            $this->assertNotEmpty($emoji);
            $this->assertIsString($emoji);
        }
    }

    public function test_has_28_weather_code_cases(): void
    {
        $this->assertCount(28, WeatherCode::cases());
    }
}

# cGFuZ29saW4=
