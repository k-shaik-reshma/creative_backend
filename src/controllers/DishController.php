<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Services\DishService;

class DishController
{
    private $dishService;

    public function __construct()
    {
        $this->dishService = new DishService();
    }

    public function createDish(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();
        $uploadedFiles = $request->getUploadedFiles(); // Get uploaded files
    
        if (empty($data['user_id']) || empty($data['dish_name']) || empty($data['dish_type']) || empty($uploadedFiles['image'])) {
            $response->getBody()->write(json_encode(['message' => 'User ID, dish name, dish type, and image are required']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    
        $image = $uploadedFiles['image'];
    
        // Validate image upload
        if ($image->getError() !== UPLOAD_ERR_OK) {
            $response->getBody()->write(json_encode(['message' => 'Error uploading image']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    
        // Define the directory to save the image
        $directory = __DIR__ . '/../uploads/dishes';
    
        // Ensure the directory exists and is writable
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
    
        // Generate a unique filename for the image
        $filename = sprintf('%s.%s', uniqid(), pathinfo($image->getClientFilename(), PATHINFO_EXTENSION));
    
        // Move the uploaded file to the directory
        $image->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
    
        // Include the image path in the data array
        $data['image_url'] = $directory . DIRECTORY_SEPARATOR . $filename;

        $result = $this->dishService->createDish($data);
    
        if (isset($result['error'])) {
            $response->getBody()->write(json_encode(['message' => $result['error']]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    
        $response->getBody()->write(json_encode(['message' => "Dish created successfully", 'image_url' => $data['image_url']]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }


    public function updateDish(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();
        $id = $args['id'];

        if (empty($data['user_id']) || empty($data['dish_name']) || empty($data['dish_type'])) {
            $response->getBody()->write(json_encode(['message' => 'User ID, dish name, and dish type are required']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $result = $this->dishService->updateDish($id, $data);

        if (isset($result['error'])) {
            $response->getBody()->write(json_encode(['message' => $result['error']]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $response->getBody()->write(json_encode(['message' => $result['success']]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }


    public function deleteDish(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];

        $result = $this->dishService->deleteDish($id);

        if (isset($result['error'])) {
            $response->getBody()->write(json_encode(['message' => $result['error']]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $response->getBody()->write(json_encode(['message' => $result['success']]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function getDishesByUserId(Request $request, Response $response, array $args): Response
    {
        $userId = $args['user_id'];

        $dishes = $this->dishService->getDishesByUserId($userId);

        if (empty($dishes)) {
            $response->getBody()->write(json_encode(['message' => 'No dishes found for this user']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $response->getBody()->write(json_encode($dishes));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }


    public function getAllDishesWithChefDetails(Request $request, Response $response, array $args): Response
    {
        $dishes = $this->dishService->getAllDishesWithChefDetails();

        if (empty($dishes)) {
            $response->getBody()->write(json_encode(['message' => 'No dishes found']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $response->getBody()->write(json_encode($dishes));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function getDishByIdWithChefDetails(Request $request, Response $response, array $args): Response
    {
        $dishId = $args['id'];
        $dish = $this->dishService->getDishByIdWithChefDetails($dishId);

        if (!$dish) {
            $response->getBody()->write(json_encode(['message' => 'Dish not found']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $response->getBody()->write(json_encode($dish));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
