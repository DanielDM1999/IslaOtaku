<?php
require_once(__DIR__ . '/../config/Database.php'); // Include the database configuration
require_once(__DIR__ . '/../models/ReviewModel.php'); // Include the Review model

class ReviewController {
    private $reviewModel; // Review model instance

    // Constructor to initialize the Review model with a database connection
    public function __construct() {
        $db = new Database();
        $this->reviewModel = new ReviewModel($db->getConnection());
    }

    // Retrieves reviews for a specific anime by its ID, with pagination
    public function getReviewsByAnimeId($animeId, $limit = 10, $offset = 0) {
        return $this->reviewModel->getReviewsByAnimeId($animeId, $limit, $offset);
    }

    // Gets the total number of reviews for a specific anime
    public function getReviewCountByAnimeId($animeId) {
        return $this->reviewModel->getReviewCountByAnimeId($animeId);
    }

    // Gets the average rating for a specific anime
    public function getAverageRatingByAnimeId($animeId) {
        return $this->reviewModel->getAverageRatingByAnimeId($animeId);
    }

    // Adds or updates a review for an anime
    public function addReview($userId, $animeId, $rating, $comment) {
        global $translations;

        // Validate rating
        if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            return [
                'success' => false, 
                'message_key' => 'rating_invalid',
                'message' => $translations['rating_invalid'] ?? 'Rating must be between 1 and 5'
            ];
        }

        // Validate comment
        if (empty($comment)) {
            $comment = '';
        } elseif (strlen($comment) > 1000) {
            return [
                'success' => false, 
                'message_key' => 'comment_too_long',
                'message' => $translations['comment_too_long'] ?? 'Comment must be less than 1000 characters'
            ];
        }

        // Add or update the review
        $result = $this->reviewModel->addReview($userId, $animeId, $rating, $comment);
        
        if ($result) {
            return [
                'success' => true, 
                'message_key' => 'review_submitted_success',
                'message' => $translations['review_submitted_success'] ?? 'Review submitted successfully'
            ];
        } else {
            return [
                'success' => false, 
                'message_key' => 'review_submitted_error',
                'message' => $translations['review_submitted_error'] ?? 'Failed to submit review'
            ];
        }
    }

    // Checks if a user has reviewed a specific anime
    public function hasUserReviewedAnime($userId, $animeId) {
        return $this->reviewModel->hasUserReviewedAnime($userId, $animeId);
    }

    // Retrieves a specific user's review for a specific anime
    public function getUserReview($userId, $animeId) {
        return $this->reviewModel->getUserReview($userId, $animeId);
    }

    // Deletes a review by its ID and associated user
    public function deleteReview($reviewId, $userId) {
        return $this->reviewModel->deleteReview($reviewId, $userId);
    }
}
?>
