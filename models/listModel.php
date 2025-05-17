<?php
class ListModel {
    private $conn;

    public function __construct($databaseConnection) {
        $this->conn = $databaseConnection;
    }

    // Add or update anime status in the user's list
    public function addOrUpdateAnimeToList($userId, $animeId, $status) {
        try {
            // Check if the anime already exists in the user's list
            $stmt = $this->conn->prepare("SELECT * FROM Lists WHERE user_id = :user_id AND anime_id = :anime_id");
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':anime_id', $animeId, PDO::PARAM_INT);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                // If it exists, update the status
                $stmt = $this->conn->prepare("UPDATE Lists SET status = :status, last_updated = CURRENT_TIMESTAMP WHERE user_id = :user_id AND anime_id = :anime_id");
                $stmt->bindParam(':status', $status, PDO::PARAM_STR);
                $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $stmt->bindParam(':anime_id', $animeId, PDO::PARAM_INT);
                return $stmt->execute();
            } else {
                // If it doesn't exist, insert a new record
                $stmt = $this->conn->prepare("INSERT INTO Lists (user_id, anime_id, status, last_updated) VALUES (:user_id, :anime_id, :status, NOW())");
                $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $stmt->bindParam(':anime_id', $animeId, PDO::PARAM_INT);
                $stmt->bindParam(':status', $status, PDO::PARAM_STR);
                return $stmt->execute();
            }
        } catch (PDOException $e) {
            error_log("Database error in ListModel::addOrUpdateAnimeToList: " . $e->getMessage());
            return false;
        }
    }

    // Fetch a filtered list of animes based on the user's id and status (e.g., 'Watching', 'Completed', etc.)
    public function getFilteredAnimeList($userId, $status) {
        $stmt = $this->conn->prepare("
            SELECT a.anime_id, a.name, a.image_url, l.status 
            FROM Animes a
            JOIN Lists l ON a.anime_id = l.anime_id
            WHERE l.user_id = :user_id AND l.status = :status
            ORDER BY l.last_updated DESC
        ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch all animes in a user's list
    public function getUserAnimeList($userId) {
        $stmt = $this->conn->prepare("SELECT a.anime_id, a.name, a.image_url, l.status 
                                      FROM Animes a
                                      JOIN Lists l ON a.anime_id = l.anime_id
                                      WHERE l.user_id = :user_id
                                      ORDER BY l.last_updated DESC");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get the status of a specific anime in the user's list
    public function getAnimeStatus($userId, $animeId) {
        $stmt = $this->conn->prepare("SELECT status FROM Lists WHERE user_id = :user_id AND anime_id = :anime_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':anime_id', $animeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
?>
