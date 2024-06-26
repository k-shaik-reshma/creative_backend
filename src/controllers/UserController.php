<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Services\UserService;

class UserController
{
    private $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function createUser(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();

        // Validate input data
        if (empty($data['type']) || empty($data['password']) || empty($data['email']) || empty($data['full_name'])) {
            $response->getBody()->write(json_encode(['message' => 'Invalid input data']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        // Create user
        $result = $this->userService->createUser($data);

        if ($result) {
            $response->getBody()->write(json_encode(['message' => 'User created successfully']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } else {
            $response->getBody()->write(json_encode(['message' => 'User could not be created']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
    public function login(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();

        if (empty($data['email']) || empty($data['password'])) {
            $response->getBody()->write(json_encode(['message' => 'Email and password are required']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $authenticated = $this->userService->authenticateUser($data['email'], $data['password']);

        if (!$authenticated) {
            $response->getBody()->write(json_encode(['message' => 'Invalid credentials']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }

        $response->getBody()->write(json_encode(['message' => 'Login successful', 'user' => $authenticated]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

}
