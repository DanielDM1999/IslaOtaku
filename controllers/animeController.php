<?php
class AnimeFetcher {
    private $pdo;

    public function __construct() {
        $host = "localhost";
        $dbname = "isla_otaku";
        $username = "root";
        $password = "";

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function getAnimesForPageFromDb($offset, $limit) {
        $stmt = $this->pdo->prepare(
            "SELECT anime_id, name, image_url FROM animes LIMIT :offset, :limit"
        );
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalAnimesCount() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM animes");
        return $stmt->fetchColumn();
    }
}

?>
