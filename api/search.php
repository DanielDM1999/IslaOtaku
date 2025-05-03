<?php
require_once(__DIR__ . '/../controllers/AnimeController.php');

// Set the content type to JSON
header('Content-Type: application/json');

// Get the search query
$query = isset($_GET['query']) ? $_GET['query'] : '';

// Sanitize the input
$query = htmlspecialchars($query);

// Initialize the anime controller
$animeController = new AnimeController();

// Get search results
$results = $animeController->searchAnimes($query);

// Return the results as JSON
echo json_encode($results);
?>
