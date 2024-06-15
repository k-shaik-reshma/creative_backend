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
}
