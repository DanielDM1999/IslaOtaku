<?php
class Database {
    private $host = "localhost";
    private $db_name = "isla_otaku";
    private $username = "root";
    private $password = "";
    public $conn;

    // Method to get database connection using PDO
    public function getConnection() {
        $this->conn = null;

        try {    
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name, 
                $this->username, 
                $this->password,
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );
            $this->conn->exec("set names utf8mb4");
                      
            return $this->conn;
        } catch(PDOException $exception) {
            error_log("Database connection error: " . $exception->getMessage());
            echo "<!-- Connection error: " . $exception->getMessage() . " -->";
            return null;
        }
    }
}
?>

