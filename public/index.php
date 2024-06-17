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

$app->addRoutingMiddleware();

// CORS Middleware configuration
$corsOptions = [
    "origin" => ["*"],
    "methods" => ["GET", "POST", "PUT", "PATCH", "DELETE", "OPTIONS"],
    "headers.allow" => ["Authorization", "Content-Type", "X-Requested-With"],
    "headers.expose" => [],
    "credentials" => true,
    "cache" => 0,
];


// Route to serve dish images
$app->get('/uploads/dishes/{filename}', function ($request, $response, $args) {
    $filename = $args['filename'];
    $filePath = __DIR__ . '/../src/upload/dishes/' . $filename;

    // Security check: Ensure the file exists and is within the intended directory
    if (file_exists($filePath) && realpath($filePath) === $filePath) {
        // Guess the MIME type based on the file extension
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $filePath);
        finfo_close($finfo);

        // Read the file content and return it as the response
        $fileContent = file_get_contents($filePath);
        $response->getBody()->write($fileContent);

        return $response->withHeader('Content-Type', $mimeType);
    } else {
        // Return a 404 Not Found response if the file doesn't exist or is outside the intended directory
        return $response->withStatus(404, 'File Not Found');
    }
});


// Add the CORS middleware
$app->add(new CorsMiddleware($corsOptions));

$app->addBodyParsingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Define app routes
$app->get('/api/hello', function ($request, $response, $args) {
    $data = ['message' => 'Hello, Reshma'];
    $response->getBody()->write(json_encode($data));
    return $response->withHeader('Content-Type', 'application/json');
});

// auth routes
$app->post('/api/v1/login', [UserController::class, 'login']);

// user routes
$app->post('/api/v1/users', [UserController::class, 'createUser']);

// dish routes
$app->post('/api/v1/dishes', [DishController::class, 'createDish']);
$app->put('/api/v1/dishes/{id}', [DishController::class, 'updateDish']);
$app->delete('/api/v1/dishes/{id}', [DishController::class, 'deleteDish']);
$app->get('/api/v1/users/{user_id}/dishes', [DishController::class, 'getDishesByUserId']);
$app->get('/api/v1/dishes', [DishController::class, 'getAllDishesWithChefDetails']);
$app->get('/api/v1/dish/{id}', [DishController::class, 'getDishByIdWithChefDetails']);


$app->run();
