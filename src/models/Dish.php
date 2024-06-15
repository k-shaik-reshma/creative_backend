<?php

namespace App\Models;

use PDO;
use App\Config\Database;

class Dish
{
    private $conn;
    private $table = 'dishes';

    public $id;
    public $user_id;
    public $dish_name;
    public $dish_type;
    public $description;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table . " (user_id, dish_name, dish_type, description) VALUES (:user_id, :dish_name, :dish_type, :description)";
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->dish_name = htmlspecialchars(strip_tags($this->dish_name));
        $this->dish_type = htmlspecialchars(strip_tags($this->dish_type));
        $this->description = htmlspecialchars(strip_tags($this->description));

        // Bind data
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':dish_name', $this->dish_name);
        $stmt->bindParam(':dish_type', $this->dish_type);
        $stmt->bindParam(':description', $this->description);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }


    public function update()
    {
        if (empty($this->user_id) || empty($this->dish_name) || empty($this->dish_type)) {
            return false;
        }

        $query = "UPDATE " . $this->table . " SET user_id = :user_id, dish_name = :dish_name, dish_type = :dish_type, description = :description WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->dish_name = htmlspecialchars(strip_tags($this->dish_name));
        $this->dish_type = htmlspecialchars(strip_tags($this->dish_type));
        $this->description = htmlspecialchars(strip_tags($this->description));

        // Bind data
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':dish_name', $this->dish_name);
        $stmt->bindParam(':dish_type', $this->dish_type);
        $stmt->bindParam(':description', $this->description);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function delete()
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind data
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}


