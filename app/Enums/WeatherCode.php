<?php

declare(strict_types=1);

namespace App\Enums;

enum WeatherCode: int
{
    case CLEAR_SKY = 0;
    case MAINLY_CLEAR = 1;
    case PARTLY_CLOUDY = 2;
    case OVERCAST = 3;
    case FOG = 45;
    case DEPOSITING_RIME_FOG = 48;
    case DRIZZLE_LIGHT = 51;
    case DRIZZLE_MODERATE = 53;
    case DRIZZLE_DENSE = 55;
    case FREEZING_DRIZZLE_LIGHT = 56;
    case FREEZING_DRIZZLE_DENSE = 57;
    case RAIN_SLIGHT = 61;
    case RAIN_MODERATE = 63;
    case RAIN_HEAVY = 65;
    case FREEZING_RAIN_LIGHT = 66;
    case FREEZING_RAIN_HEAVY = 68;
    case SNOW_SLIGHT = 71;
    case SNOW_MODERATE = 73;
    case SNOW_HEAVY = 75;
    case SNOW_GRAINS = 77;
    case RAIN_SHOWERS_SLIGHT = 80;
    case RAIN_SHOWERS_MODERATE = 81;
    case RAIN_SHOWERS_VIOLENT = 82;
    case SNOW_SHOWERS_SLIGHT = 85;
    case SNOW_SHOWERS_HEAVY = 86;
    case THUNDERSTORM = 95;
    case THUNDERSTORM_SLIGHT_HAIL = 96;
    case THUNDERSTORM_HEAVY_HAIL = 99;

    public function description(): string
    {
        return match($this) {
            self::CLEAR_SKY => 'Cielo despejado',
            self::MAINLY_CLEAR => 'Mayormente despejado',
            self::PARTLY_CLOUDY => 'Parcialmente nublado',
            self::OVERCAST => 'Nublado',
            self::FOG => 'Niebla',
            self::DEPOSITING_RIME_FOG => 'Niebla con escarcha',
            self::DRIZZLE_LIGHT => 'Llovizna ligera',
            self::DRIZZLE_MODERATE => 'Llovizna moderada',
            self::DRIZZLE_DENSE => 'Llovizna densa',
            self::FREEZING_DRIZZLE_LIGHT => 'Llovizna helada ligera',
            self::FREEZING_DRIZZLE_DENSE => 'Llovizna helada densa',
            self::RAIN_SLIGHT => 'Lluvia leve',
            self::RAIN_MODERATE => 'Lluvia moderada',
            self::RAIN_HEAVY => 'Lluvia intensa',
            self::FREEZING_RAIN_LIGHT => 'Lluvia helada ligera',
            self::FREEZING_RAIN_HEAVY => 'Lluvia helada intensa',
            self::SNOW_SLIGHT => 'Nieve leve',
            self::SNOW_MODERATE => 'Nieve moderada',
            self::SNOW_HEAVY => 'Nieve intensa',
            self::SNOW_GRAINS => 'Granizo de nieve',
            self::RAIN_SHOWERS_SLIGHT => 'Chubascos leves',
            self::RAIN_SHOWERS_MODERATE => 'Chubascos moderados',
            self::RAIN_SHOWERS_VIOLENT => 'Chubascos violentos',
            self::SNOW_SHOWERS_SLIGHT => 'Chubascos de nieve leves',
            self::SNOW_SHOWERS_HEAVY => 'Chubascos de nieve intensos',
            self::THUNDERSTORM => 'Tormenta eléctrica',
            self::THUNDERSTORM_SLIGHT_HAIL => 'Tormenta con granizo leve',
            self::THUNDERSTORM_HEAVY_HAIL => 'Tormenta con granizo intenso',
        };
    }

    public function emoji(): string
    {
        return match($this) {
            self::CLEAR_SKY => '☀️',
            self::MAINLY_CLEAR => '🌤️',
            self::PARTLY_CLOUDY => '⛅',
            self::OVERCAST => '☁️',
            self::FOG, self::DEPOSITING_RIME_FOG => '🌫️',
            self::DRIZZLE_LIGHT, self::DRIZZLE_MODERATE, self::DRIZZLE_DENSE => '🌧️',
            self::FREEZING_DRIZZLE_LIGHT, self::FREEZING_DRIZZLE_DENSE => '🌧️',
            self::RAIN_SLIGHT, self::RAIN_MODERATE, self::RAIN_HEAVY => '🌧️',
            self::FREEZING_RAIN_LIGHT, self::FREEZING_RAIN_HEAVY => '🌧️',
            self::SNOW_SLIGHT, self::SNOW_MODERATE, self::SNOW_HEAVY, self::SNOW_GRAINS => '❄️',
            self::RAIN_SHOWERS_SLIGHT, self::RAIN_SHOWERS_MODERATE, self::RAIN_SHOWERS_VIOLENT => '☔',
            self::SNOW_SHOWERS_SLIGHT, self::SNOW_SHOWERS_HEAVY => '🌨️',
            self::THUNDERSTORM, self::THUNDERSTORM_SLIGHT_HAIL, self::THUNDERSTORM_HEAVY_HAIL => '⛈️',
        };
    }
}

# cGFuZ29saW4=
