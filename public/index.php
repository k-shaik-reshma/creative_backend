<?php
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Tuupola\Middleware\CorsMiddleware;
use Dotenv\Dotenv;
use App\Controllers\UserController;
use App\Controllers\DishController;

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
$app->addBodyParsingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Define app routes
$app->get('/api/hello', function ($request, $response, $args) {
    $data = ['message' => 'Hello, Reshma'];
    $response->getBody()->write(json_encode($data));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/api/v1/users', [UserController::class, 'createUser']);
$app->post('/api/v1/dishes', [DishController::class, 'createDish']);
$app->post('/api/v1/login', [UserController::class, 'login']);


$app->run();
