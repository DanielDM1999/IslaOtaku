<?php
class AnimeModel {
    private $conn;

    public function __construct($databaseConnection) {
        $this->conn = $databaseConnection;
    }

    public function getAnimesForPage($offset, $limit) {
        $stmt = $this->conn->prepare("SELECT anime_id, name, image_url FROM animes LIMIT :offset, :limit");
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalAnimesCount() {
        $stmt = $this->conn->query("SELECT COUNT(*) FROM animes");
        return $stmt->fetchColumn();
    }
    
    public function searchAnimes($query) {
        $searchTerm = "%$query%"; // Changed to match anywhere in the name
        $stmt = $this->conn->prepare("SELECT anime_id, name, image_url FROM animes WHERE name LIKE :query ORDER BY name ASC");
        $stmt->bindParam(':query', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAnimeById($animeId) {
        $stmt = $this->conn->prepare("SELECT * FROM animes WHERE anime_id = :anime_id");
        $stmt->bindParam(':anime_id', $animeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getAnimeGenres($animeId) {
        $stmt = $this->conn->prepare("
            SELECT g.name 
            FROM genres g
            JOIN AnimeGenres ag ON g.genre_id = ag.genre_id
            WHERE ag.anime_id = :anime_id
            ORDER BY g.name ASC
        ");
        $stmt->bindParam(':anime_id', $animeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
}
?>
