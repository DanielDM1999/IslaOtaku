<?php
require_once(__DIR__ . '/../config/Database.php');
require_once(__DIR__ . '/../models/ReviewModel.php');

class ReviewController {
    private $reviewModel;

    public function __construct() {
        $db = new Database();
        $this->reviewModel = new ReviewModel($db->getConnection());
    }

    public function getReviewsByAnimeId($animeId, $limit = 10, $offset = 0) {
        return $this->reviewModel->getReviewsByAnimeId($animeId, $limit, $offset);
    }

    public function getReviewCountByAnimeId($animeId) {
        return $this->reviewModel->getReviewCountByAnimeId($animeId);
    }

    public function getAverageRatingByAnimeId($animeId) {
        return $this->reviewModel->getAverageRatingByAnimeId($animeId);
    }

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

        // Validate comment (optional)
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

    public function hasUserReviewedAnime($userId, $animeId) {
        return $this->reviewModel->hasUserReviewedAnime($userId, $animeId);
    }

    public function getUserReview($userId, $animeId) {
        return $this->reviewModel->getUserReview($userId, $animeId);
    }

    public function deleteReview($reviewId, $userId) {
        return $this->reviewModel->deleteReview($reviewId, $userId);
    }
}
?>
