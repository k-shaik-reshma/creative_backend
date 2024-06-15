<?php

namespace App\Services;

use App\Models\Dish;

class DishService
{
    public function createDish($data)
    {
        $dish = new Dish();
        $dish->user_id = $data['user_id'];
        $dish->dish_name = $data['dish_name'];
        $dish->dish_type = $data['dish_type'];
        $dish->description = $data['description'];
        return $dish->create();
    }
}
