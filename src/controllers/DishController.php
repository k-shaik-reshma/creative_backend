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

        if (empty($data['user_id']) || empty($data['dish_name']) || empty($data['dish_type'])) {
            $response->getBody()->write(json_encode(['message' => 'User ID, dish name, and dish type are required']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        } 

        $result = $this->dishService->createDish($data);

        if (isset($result['error'])) {
            $response->getBody()->write(json_encode(['message' => $result['error']]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $response->getBody()->write(json_encode(['message' => "Dish created successfully"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }
}
