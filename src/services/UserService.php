<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function createUser($data)
    {
        $user = new User();
        $user->type = $data["type"];
        $user->password = $data["password"];
        $user->email = $data["email"];
        $user->full_name = $data["full_name"];

        return $user->create();
    }

    public function authenticateUser(string $email, string $password): array
    {   
        $user = User::findByEmail($email);
    
        if (!$user || !password_verify($password, $user->password)) {
            return ['authenticated' => false]; // Authentication failed
        }
    
        return ['authenticated' => true, 'email' => $user->email, 'full_name' => $user->full_name, 'type' => $user->type, 'id' => $user->id];
    }
}
