<?php
class UserModel
{
    private $conn;

    public function __construct($databaseConnection)
    {
        $this->conn = $databaseConnection;
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByName($name)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE name = :name");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($name, $email, $hashedPassword)
    {
        $profilePicture = 'default.jpg';

        $stmt = $this->conn->prepare("INSERT INTO users (name, email, password, profile_picture) VALUES (:name, :email, :password, :profile_picture)");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':profile_picture', $profilePicture, PDO::PARAM_STR);  // Add default profile picture

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }



    public function getUserById($userId)
    {
        $stmt = $this->conn->prepare("SELECT user_id, name, email, profile_picture, registration_date FROM Users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function getUserAnimeList($userId, $status = null)
    {
        $query = "SELECT * FROM Lists WHERE user_id = :user_id";
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

    public function getUserReviews($userId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Reviews WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateUser($userId, $name, $email)
    {
        $stmt = $this->conn->prepare("UPDATE users SET name = :name, email = :email WHERE user_id = :user_id");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

        return $stmt->execute();
    }


    public function updateProfilePicture($userId, $filename)
    {
        error_log("Model.");

        $stmt = $this->conn->prepare("UPDATE Users SET profile_picture = :profile_picture WHERE user_id = :user_id");
        $stmt->bindParam(':profile_picture', $filename, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

        return $stmt->execute();
    }

}

?>