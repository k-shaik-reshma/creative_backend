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

        if (empty($data['user_id']) || empty($data['dish_name']) || empty($data['dish_type']) || empty($data['description'])) {
            $response->getBody()->write(json_encode(['message' => 'User ID, dish name, and dish type, description are required']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        } 

        $result = $this->dishService->createDish($data);

        if (!$result) {
            $response->getBody()->write(json_encode(['message' => 'Dish could not be created']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }

        $response->getBody()->write(json_encode(['message' => 'Dish created successfully']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }
}
