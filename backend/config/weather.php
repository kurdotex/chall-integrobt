<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Weather Service Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may define the configuration for your weather service provider.
    |
    */

    'api_url' => env('WEATHER_API_URL', 'https://api.open-meteo.com/v1/forecast'),

    'cache_duration' => env('WEATHER_CACHE_DURATION', 1800), // Default: 30 minutes in seconds

];
