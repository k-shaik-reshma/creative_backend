<?php

namespace App\Models;

use PDO;
use App\Config\Database;

class User
{
    private $conn;
    private $table = 'users';

    public $id;
    public $type;
    public $timestamp;
    public $password;
    public $email;
    public $full_name;

    public $phone_number;
    public $location;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function create()
    {
        if (empty($this->type) || empty($this->password) || empty($this->email) || empty($this->full_name) || empty($this->phone_number) || empty($this->location)) {
            return false;
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $query = "INSERT INTO " . $this->table . " (type, password, email, full_name, phone_number, location) VALUES (:type, :password, :email, :full_name, :phone_number, :location)";
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->type = htmlspecialchars(strip_tags($this->type));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->full_name = htmlspecialchars(strip_tags($this->full_name));
        $this->phone_number = htmlspecialchars(strip_tags($this->phone_number));
        $this->location = htmlspecialchars(strip_tags($this->location));

        // Bind data
        $stmt->bindParam(':type', $this->type);
        $passwordHash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $passwordHash);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':full_name', $this->full_name);
        $stmt->bindParam(':phone_number', $this->phone_number);
        $stmt->bindParam(':location', $this->location);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }


    public static function findByEmail(string $email): ?User
    {
        $database = new Database();
        $conn = $database->connect();

        $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null; // User not found
        }

        // Create new User object and populate it with fetched data
        $user = new User();
        $user->id = $row['id'];
        $user->type = $row['type'];
        $user->password = $row['password'];
        $user->email = $row['email'];
        $user->full_name = $row['full_name'];

        return $user;
    }


    public function findById(int $id): ?User
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null; // User not found
        }

        $this->id = $row['id'];
        $this->type = $row['type'];
        $this->password = $row['password'];
        $this->email = $row['email'];
        $this->full_name = $row['full_name'];

        return $this;
    }

}
