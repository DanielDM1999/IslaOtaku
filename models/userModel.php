<?php
class UserModel
{
    private $conn;

    public function __construct($databaseConnection)
    {
        $this->conn = $databaseConnection;
    }

    // Get a user record by email
    public function getUserByEmail($email)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get a user record by name
    public function getUserByName($name)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE name = :name");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new user with name, email, and hashed password
    public function createUser($name, $email, $hashedPassword)
    {
        $profilePicture = 'default.jpg'; // Default profile picture

        $stmt = $this->conn->prepare("INSERT INTO users (name, email, password, profile_picture) VALUES (:name, :email, :password, :profile_picture)");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':profile_picture', $profilePicture, PDO::PARAM_STR);

        // Execute and return last inserted ID or false on failure
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Get user info by user ID (excluding password)
    public function getUserById($userId)
    {
        $stmt = $this->conn->prepare("SELECT user_id, name, email, profile_picture, registration_date FROM Users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get user info by user ID including password (for internal use)
    public function getUserWithPasswordById($userId)
    {
        $stmt = $this->conn->prepare("SELECT user_id, name, email, password, profile_picture, registration_date FROM Users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get the anime list for a user, optionally filtered by status
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

    // Get all reviews written by a user
    public function getUserReviews($userId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Reviews WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update a user's name and email
    public function updateUser($userId, $name, $email)
    {
        $stmt = $this->conn->prepare("UPDATE users SET name = :name, email = :email WHERE user_id = :user_id");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Update a user's password
    public function updatePassword($userId, $hashedPassword)
    {
        $stmt = $this->conn->prepare("UPDATE users SET password = :password WHERE user_id = :user_id");
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Update a user's profile picture filename
    public function updateProfilePicture($userId, $filename)
    {
        $stmt = $this->conn->prepare("UPDATE Users SET profile_picture = :profile_picture WHERE user_id = :user_id");
        $stmt->bindParam(':profile_picture', $filename, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
?>
