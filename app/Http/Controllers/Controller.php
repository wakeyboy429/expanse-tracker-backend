<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "Expanse Tracker API",
    description: "Authentication and expense tracking API endpoints",
    contact: new OA\Contact(email: "admin@example.com")
)]
#[OA\Server(
    url: L5_SWAGGER_CONST_HOST,
    description: "Local Development Server"
)]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    bearerFormat: "JWT",
    scheme: "bearer"
)]
#[OA\Tag(name: "Authentication", description: "User authentication endpoints")]
#[OA\Tag(name: "Transactions", description: "Income and Expense management endpoints")]
abstract class Controller
{
}
