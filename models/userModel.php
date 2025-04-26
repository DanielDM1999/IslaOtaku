<?php
class UserModel {
    private $conn;

    public function __construct($databaseConnection) {
        $this->conn = $databaseConnection;
    }

    public function getUserByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByName($name) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE name = :name");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($name, $email, $hashedPassword) {
        $stmt = $this->conn->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function getUserById($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserAnimeList($userId, $status = null) {
        $query = "SELECT * FROM anime_list WHERE user_id = :user_id";
        if ($status) {
            $query .= " AND status = :status";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        if ($status) {
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserReviews($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
