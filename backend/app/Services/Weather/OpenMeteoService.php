<?php

namespace App\Services\Weather;

use App\Contracts\WeatherServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

use App\Exceptions\WeatherServiceException;

class OpenMeteoService implements WeatherServiceInterface
{

    public function getCurrentWeather(float $latitude, float $longitude): array
    {
        $cacheKey = "weather_{$latitude}_{$longitude}";

        return Cache::remember($cacheKey, config('weather.cache_duration'), function () use ($latitude, $longitude) {
            $response = Http::get(config('weather.api_url'), [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'current_weather' => true,
            ]);

            if ($response->failed()) {
                throw new WeatherServiceException("No se pudo obtener la información del clima desde Open Meteo.");
            }

            $data = $response->json();
            $current = $data['current_weather'];

            return [
                'temperature' => $current['temperature'],
                'windspeed' => $current['windspeed'],
                'weathercode' => $current['weathercode'],
                'unit' => $data['current_weather_units']['temperature'] ?? '°C',
            ];
        });
    }
}
