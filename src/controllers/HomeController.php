<?php
namespace App\Controllers;

class HomeController {
    public function index() {
        echo "Welcome to our homepage!";
    }

    public function sellMessage() {
        echo "Interested in selling? Contact us for more information!";
    }
}