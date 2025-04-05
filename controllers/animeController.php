<?php
class AnimeFetcher {

    public function __construct() {}

    public function fetchAnimesFromAPI($page = 1) {
        $apiUrl = "https://api.jikan.moe/v4/top/anime?page=$page";
        $response = file_get_contents($apiUrl);

        if ($response === false) {
            return [];
        }

        $data = json_decode($response, true);

        if (isset($data['data'])) {
            return $data['data']; 
        } else {
            error_log("Error: 'data' key not found in the API response");
            return [];
        }
    }

    public function getAnimesForPage($page = 1) {
        return $this->fetchAnimesFromAPI($page);
    }
}
?>
