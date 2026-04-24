<?php

namespace App\Contracts;

interface WeatherServiceInterface
{
    public function getCurrentWeather(float $latitude, float $longitude): array;
}
