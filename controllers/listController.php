<?php
require_once(__DIR__ . '/../config/Database.php');
require_once(__DIR__ . '/../models/ListModel.php');

class ListController {
    private $listModel;

    public function __construct() {
        $db = new Database();
        $this->listModel = new ListModel($db->getConnection());
    }

    // Add or update anime in the user's list
    public function addOrUpdateAnimeToList($userId, $animeId, $status) {
        $this->listModel->addOrUpdateAnimeToList($userId, $animeId, $status);
    }

    public function getFilteredAnimeList($userId, $status) {
        return $this->listModel->getFilteredAnimeList($userId, $status);
    }

    // Get the anime list for a specific user
    public function getUserAnimeList($userId) {
        return $this->listModel->getUserAnimeList($userId);
    }

    // Get the status of a specific anime in the user's list
    public function getAnimeStatus($userId, $animeId) {
        return $this->listModel->getAnimeStatus($userId, $animeId);
    }

    // Remove an anime from the user's list
    public function removeAnimeFromList($userId, $animeId) {
        $this->listModel->removeAnimeFromList($userId, $animeId);
    }

    // Handle add/update anime to list
    public function handleAnimeListAction($action, $userId, $animeId, $status = null) {
        switch ($action) {
            case 'add':
            case 'update':
                if ($status) {
                    $this->addOrUpdateAnimeToList($userId, $animeId, $status);
                }
                break;
            case 'remove':
                $this->removeAnimeFromList($userId, $animeId);
                break;
        }
    }
}
?>
