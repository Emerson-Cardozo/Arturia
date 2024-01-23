<?php

class Database {
    private $host = 'database:3306';
    private $username = 'root';
    private $password = 'tiger';
    private $dbname = 'arturia_store';

    public function connect() {
        try {
            $conn = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            echo "Erro na conexão: " . $e->getMessage();
            die();
        }
    }
}
