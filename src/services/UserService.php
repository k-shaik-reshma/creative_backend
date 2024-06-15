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

    public function authenticateUser(string $email, string $password): bool
    {   
        $user = User::findByEmail($email);

        if (!$user || !password_verify($password, $user->password)) {
            return false; // Authentication failed
        }

        return true; // Authentication succeeded
    }
}
