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

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table . " (type, password, email, full_name) VALUES (:type, :password, :email, :full_name)";
        echo $query;
        print($query);
        print_r($this->$query);
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->type = htmlspecialchars(strip_tags($this->type));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->full_name = htmlspecialchars(strip_tags($this->full_name));

        // Bind data
        $stmt->bindParam(':type', $this->type);
        $passwordHash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $passwordHash);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':full_name', $this->full_name);
        print("HI this is password");
        print($this->full_name);
        print ("HI this is password");
        print($stmt->queryString);
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
}
