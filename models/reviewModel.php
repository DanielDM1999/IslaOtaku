<?php
class ReviewModel {
    private $conn; // Database connection

    // Constructor to initialize the database connection
    public function __construct($databaseConnection) {
        $this->conn = $databaseConnection;
    }

    // Get a list of reviews for a specific anime with pagination support
    public function getReviewsByAnimeId($animeId, $limit = 10, $offset = 0) {
        $stmt = $this->conn->prepare("
            SELECT r.review_id, r.user_id, r.anime_id, r.rating, r.comment, r.publication_date,
                   u.name as user_name, u.profile_picture
            FROM Reviews r
            JOIN Users u ON r.user_id = u.user_id
            WHERE r.anime_id = :anime_id
            ORDER BY r.publication_date DESC
            LIMIT :offset, :limit
        ");
        $stmt->bindParam(':anime_id', $animeId, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get the total number of reviews for a specific anime
    public function getReviewCountByAnimeId($animeId) {
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) as count
            FROM Reviews
            WHERE anime_id = :anime_id
        ");
        $stmt->bindParam(':anime_id', $animeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    // Get the average rating for a specific anime (rounded to 1 decimal)
    public function getAverageRatingByAnimeId($animeId) {
        $stmt = $this->conn->prepare("
            SELECT AVG(rating) as average_rating
            FROM Reviews
            WHERE anime_id = :anime_id
        ");
        $stmt->bindParam(':anime_id', $animeId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['average_rating'] ? round($result['average_rating'], 1) : 0;
    }

    // Add a new review or update an existing review for a user and anime
    public function addReview($userId, $animeId, $rating, $comment) {
        // Check if user already reviewed this anime
        if ($this->hasUserReviewedAnime($userId, $animeId)) {
            // Update existing review
            $stmt = $this->conn->prepare("
                UPDATE Reviews
                SET rating = :rating, comment = :comment, publication_date = CURRENT_TIMESTAMP
                WHERE user_id = :user_id AND anime_id = :anime_id
            ");
        } else {
            // Insert new review
            $stmt = $this->conn->prepare("
                INSERT INTO Reviews (user_id, anime_id, rating, comment)
                VALUES (:user_id, :anime_id, :rating, :comment)
            ");
        }

        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':anime_id', $animeId, PDO::PARAM_INT);
        $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);

        return $stmt->execute();
    }

    // Check if a user has already reviewed a specific anime
    public function hasUserReviewedAnime($userId, $animeId) {
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) as count
            FROM Reviews
            WHERE user_id = :user_id AND anime_id = :anime_id
        ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':anime_id', $animeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'] > 0;
    }

    // Get a specific user's review for a specific anime
    public function getUserReview($userId, $animeId) {
        $stmt = $this->conn->prepare("
            SELECT *
            FROM Reviews
            WHERE user_id = :user_id AND anime_id = :anime_id
        ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':anime_id', $animeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Delete a review by its ID and user ID (to ensure ownership)
    public function deleteReview($reviewId, $userId) {
        $stmt = $this->conn->prepare("
            DELETE FROM Reviews
            WHERE review_id = :review_id AND user_id = :user_id
        ");
        $stmt->bindParam(':review_id', $reviewId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
