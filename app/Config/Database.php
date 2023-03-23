<?php

namespace app\Config;
use PDO;
use PDOException;

class Database
{
    private string $host = "database";
    private string $port = "3306";
    private string $db_name = "rest_db";
    private string $username = "root";
    private string $password = "toor";
    private $conn;

    public function getConnection(){
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name, $this->username, $this->password);

        } catch (PDOException $exception) {
            echo "Connection failed" . $exception->getMessage();
        }

        return $this->conn;
    }


}