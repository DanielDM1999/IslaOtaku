<?php
require_once(__DIR__ . '/../models/ListModel.php');
require_once(__DIR__ . '/../config/Database.php');

class ListController {
    private $listModel;

    public function __construct() {
        $db = new Database();
        $this->listModel = new ListModel($db->getConnection());
    }

    // Add or update anime to the user's list
    public function addOrUpdateAnimeToList($userId, $animeId, $status) {
        try {
            return $this->listModel->addOrUpdateAnimeToList($userId, $animeId, $status);
        } catch (Exception $e) {
            error_log("Error in ListController::addOrUpdateAnimeToList: " . $e->getMessage());
            return false;
        }
    }

    // Handle add/update/remove action for the anime list
    public function handleAnimeListAction($action, $userId, $animeId, $status = null) {
        switch ($action) {
            case 'add':
            case 'update':
            case 'updateList':  // Added this case to handle our form submission
                if ($status) {
                    $result = $this->addOrUpdateAnimeToList($userId, $animeId, $status);
                    return json_encode(['success' => $result, 'message' => $result ? 'Anime added/updated in your list successfully!' : 'Failed to update anime in your list.']);
                }
                break;
            default:
                return json_encode(['success' => false, 'message' => 'Invalid action.']);
        }
    }

    // Get anime list filtered by status (optional)
    public function getFilteredAnimeList($userId, $status) {
        return $this->listModel->getFilteredAnimeList($userId, $status);
    }

    // Remove anime from the list
    public function removeAnimeFromList($userId, $animeId) {
        $this->listModel->removeAnimeFromList($userId, $animeId);
    }
}
?>
