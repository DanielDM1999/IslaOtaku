<?php
require_once(__DIR__ . '/../config/Database.php'); // Include the database configuration
require_once(__DIR__ . '/../models/AnimeModel.php'); // Include the Anime model

class AnimeController {
    private $animeModel; // Anime model instance

    // Constructor to initialize the Anime model with a database connection
    public function __construct() {
        $db = new Database();
        $this->animeModel = new AnimeModel($db->getConnection());
    }

    // Retrieves a list of animes with pagination
    public function getAnimesForPage($offset, $limit) {
        return $this->animeModel->getAnimesForPage($offset, $limit);
    }

    // Gets the total count of animes in the database
    public function getTotalAnimesCount() {
        return $this->animeModel->getTotalAnimesCount();
    }
    
    // Searches for animes by name
    public function searchAnimes($query) {
        return $this->animeModel->searchAnimes($query);
    }
    
    // Retrieves the details of an anime, including its genres
    public function getAnimeDetails($animeId) {
        $anime = $this->animeModel->getAnimeById($animeId); // Get anime details by ID
        
        if ($anime) {
            // If the anime exists, fetch its genres
            $anime['genres'] = $this->animeModel->getAnimeGenres($animeId);
        }
        
        return $anime; // Return the anime details
    }
}
?>
