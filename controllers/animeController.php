<?php
require_once(__DIR__ . '/../config/Database.php');
require_once(__DIR__ . '/../models/AnimeModel.php');

class AnimeController {
    private $animeModel;

    public function __construct() {
        $db = new Database();
        $this->animeModel = new AnimeModel($db->getConnection());
    }

    public function getAnimesForPage($offset, $limit) {
        return $this->animeModel->getAnimesForPage($offset, $limit);
    }

    public function getTotalAnimesCount() {
        return $this->animeModel->getTotalAnimesCount();
    }
}
?>
