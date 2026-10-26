<?php
namespace src\core;
use PDO;
use PDOException;

class Database {
    private $db_config;
    public function __construct($db_config){
        $this->db_config = $db_config;
    }
    
    public function getConnection(){
        try {
            $dsn = "mysql:host=" . $this->db_config["HOST"] . ";dbname=" . $this->db_config["DATABASE_NAME"] . ";charset=utf8mb4";
            $conn = new PDO($dsn, $this->db_config["USER"], $this->db_config["PASS"]);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $conn;
        } catch (PDOException $e) {
            die("Database Connection Failed: " . $e->getMessage());
        }
    }
}
?>