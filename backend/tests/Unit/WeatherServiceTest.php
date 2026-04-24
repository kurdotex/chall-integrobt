<?php

namespace Tests\Unit;

use App\Services\Weather\OpenMeteoService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class WeatherServiceTest extends TestCase
{
    public function test_can_fetch_weather_data_successfully()
    {
        Cache::flush();
        
        Http::fake([
