<?php
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Tuupola\Middleware\CorsMiddleware;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$app = AppFactory::create();

// CORS Middleware configuration
$corsOptions = [
    "origin" => ["http://localhost:4200"],
    "methods" => ["GET", "POST", "PUT", "PATCH", "DELETE", "OPTIONS"],
    "headers.allow" => ["Authorization", "Content-Type", "X-Requested-With"],
    "headers.expose" => [],
    "credentials" => true,
    "cache" => 0,
];

// Add the CORS middleware
$app->add(new CorsMiddleware($corsOptions));

$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Define app routes
$app->get('/api/hello', function ($request, $response, $args) {
    $data = ['message' => 'Hello, Sumanth'];
    $response->getBody()->write(json_encode($data));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
