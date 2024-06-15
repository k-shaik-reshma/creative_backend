<?php

namespace App\Services;

use App\Models\Dish;
use App\Models\User;

class DishService
{
    public function createDish($data)
    {
        $user = new User();
        $userExists = $user->findById($data['user_id']);

        if (!$userExists) {
            return ['error' => 'User does not exist'];
        }

        $dish = new Dish();
        $dish->user_id = $data['user_id'];
        $dish->dish_name = $data['dish_name'];
        $dish->dish_type = $data['dish_type'];
        $dish->description = $data['description'];
        return $dish->create();
    }
}
