<?php
require_once 'Model/Database.php';

class Product {
    private $dbModel;

    public function __construct() {
        $this->dbModel = new Database();
    }

    public function getProducts() {
        $conn = $this->dbModel->connect();
        $stmt = $conn->query("SELECT * FROM Products");
        return $stmt->fetchAll();
    }
}
