<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "Challenge IntegroBT API",
    description: "API for management of branches and employees with weather integration.",
    contact: new OA\Contact(email: "jose.vivas.ar@gmail.com"),
    license: new OA\License(name: "MIT", url: "https:
)]
#[OA\Server(url: "/", description: "Default Server")]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer",
    bearerFormat: "JWT"
)]
class OpenApi
{
}
