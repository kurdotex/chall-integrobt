<?php

namespace App\Exceptions;

use Exception;

class WeatherServiceException extends Exception
{
    public function __construct($message = "Error en el servicio de clima", $code = 503)
    {
        parent::__construct($message, $code);
    }
}
