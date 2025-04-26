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
}
?>
