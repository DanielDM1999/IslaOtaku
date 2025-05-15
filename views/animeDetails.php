<div class="anime-details-container">
    <div class="back-link">
        <a href="index.php" class="back-button">
            <?php echo $translations['back_to_list'] ?? 'Back to List'; ?>
        </a>
    </div>

    <div class="anime-content">
        <div class="anime-details-card">
            <div class="anime-header">
                <div class="anime-image-container">
                    <img src="<?php echo htmlspecialchars($anime['image_url']); ?>" alt="<?php echo htmlspecialchars($anime['name']); ?>" class="anime-poster">
                </div>
                
                <div class="anime-basic-info">
                    <h1 class="anime-title"><?php echo htmlspecialchars($anime['name']); ?></h1>
                    
                    <table class="anime-details-table">
                        <?php if (isset($anime['type']) && !empty($anime['type'])): ?>
                        <tr>
                            <th><?php echo $translations['type'] ?? 'Type'; ?>:</th>
                            <td><?php echo htmlspecialchars($anime['type']); ?></td>
                        </tr>
                        <?php endif; ?>
                        
                        <?php if (isset($anime['num_episodes']) && $anime['num_episodes'] > 0): ?>
                        <tr>
                            <th><?php echo $translations['episodes'] ?? 'Episodes'; ?>:</th>
                            <td><?php echo htmlspecialchars($anime['num_episodes']); ?></td>
                        </tr>
                        <?php endif; ?>
                        
                        <?php if (isset($anime['status']) && !empty($anime['status'])): ?>
                        <tr>
                            <th><?php echo $translations['status'] ?? 'Status'; ?>:</th>
                            <td><?php echo htmlspecialchars($anime['status']); ?></td>
                        </tr>
                        <?php endif; ?>
                        
                        <?php if (isset($anime['release_date']) && !empty($anime['release_date'])): ?>
                        <tr>
                            <th><?php echo $translations['release_date'] ?? 'Release Date'; ?>:</th>
                            <td><?php echo date('F j, Y', strtotime($anime['release_date'])); ?></td>
                        </tr>
                        <?php endif; ?>
                        
                        <?php if (isset($anime['genres']) && !empty($anime['genres'])): ?>
                        <tr>
                            <th><?php echo $translations['genres'] ?? 'Genres'; ?>:</th>
                            <td><?php echo htmlspecialchars(implode(', ', $anime['genres'])); ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                    
                    <?php if ($isLoggedIn): ?>
                    <div class="user-actions">
                        <button class="action-button add-to-list">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 5v14M5 12h14"/>
                            </svg>
                            <?php echo $translations['add_to_list'] ?? 'Add to My List'; ?>
                        </button>
                        
                        <button class="action-button write-review" id="toggle-review-form-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                            <?php echo $translations['write_review'] ?? 'Write a Review'; ?>
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="section-divider"></div>
            
            <div class="anime-synopsis-section">
                <h2><?php echo $translations['synopsis'] ?? 'Synopsis'; ?></h2>   
                <div class="synopsis-wrapper">
                    <div class="synopsis-short">
                        <p><?php echo nl2br(htmlspecialchars(substr($anime['synopsis'], 0, 150))); ?>...</p>
                    </div>
                    <div class="synopsis-full">
                        <p><?php echo nl2br(htmlspecialchars($anime['synopsis'])); ?></p>
                    </div>
                </div>
                
                <button class="toggle-synopsis">
                    <span class="toggle-text"><?php echo $translations['show_more'] ?? 'Show More'; ?></span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="toggle-icon">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Reviews Section (Always Visible) -->
    <div class="reviews-container" id="reviews-container">
        <div class="section-divider"></div>
        
        <div class="reviews-section">
            <div class="reviews-header">
                <h2 class="reviews-title">
                    <?php echo $translations['reviews'] ?? 'Reviews'; ?>
                    <span class="reviews-count">(<?php echo $reviewCount; ?>)</span>
                </h2>
                <div class="average-rating">
                    <div class="stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?php if ($i <= floor($averageRating)): ?>
                                <!-- Full star -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                </svg>
                            <?php elseif ($i - 0.5 <= $averageRating): ?>
                                <!-- Half star (left side filled) -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" fill="none" stroke="currentColor"></polygon>
                                    <path d="M12 2 L12 17.77" fill="none" stroke="none"></path>
                                    <path d="M12 2 L8.91 8.26 L2 9.27 L7 14.14 L5.82 21.02 L12 17.77 L12 2" fill="currentColor" stroke="none"></path>
                                </svg>
                            <?php else: ?>
                                <!-- Empty star -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                </svg>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                    <span class="rating-value"><?php echo number_format($averageRating, 1); ?></span>
                </div>
            </div>

            <?php if (count($reviews) > 0): ?>
                <div class="review-list">
                    <?php foreach ($reviews as $review): ?>
                        <div class="review-item">
                            <div class="review-header">
                                <div class="reviewer-info">
                                    <?php
                                    $profilePicture = !empty($review['profile_picture']) ? $review['profile_picture'] : 'default.jpg';
                                    ?>
                                    <img src="./uploads/profilePictures/<?php echo htmlspecialchars($profilePicture); ?>" alt="<?php echo htmlspecialchars($review['user_name']); ?>" class="reviewer-avatar">
                                    <div>
                                        <div class="reviewer-name"><?php echo htmlspecialchars($review['user_name']); ?></div>
                                        <div class="review-date"><?php echo date('M j, Y', strtotime($review['publication_date'])); ?></div>
                                    </div>
                                </div>
                                <div class="review-rating">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?php if ($i <= $review['rating']): ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                            </svg>
                                        <?php else: ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                            </svg>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <div class="review-content" data-review-id="<?php echo $review['review_id']; ?>"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></div>
                            
                            <?php if ($isLoggedIn && $currentUser['user_id'] == $review['user_id']): ?>
                                <div class="edit-delete-buttons">
                                    <button class="edit-review-btn" data-review-id="<?php echo $review['review_id']; ?>" data-rating="<?php echo $review['rating']; ?>">
                                        <?php echo $translations['edit'] ?? 'Edit'; ?>
                                    </button>
                                    <form method="post" action="index.php" style="display: inline;">
                                        <input type="hidden" name="action" value="deleteReview">
                                        <input type="hidden" name="review_id" value="<?php echo $review['review_id']; ?>">
                                        <input type="hidden" name="anime_id" value="<?php echo $animeId; ?>">
                                        <button type="submit" class="delete-review-btn">
                                            <?php echo $translations['delete'] ?? 'Delete'; ?>
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="index.php?content=animeDetails&id=<?php echo $animeId; ?>&review_page=<?php echo $i; ?>" 
                               class="pagination-button <?php echo ($reviewPage == $i) ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
                
            <?php else: ?>
                <div class="no-reviews">
                    <?php echo $translations['no_reviews_yet'] ?? 'No reviews yet. Be the first to review this anime!'; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($isLoggedIn): ?>
                <div class="review-form-section" id="review-form-section" style="display: none;">
                    <?php if (isset($reviewMessage) && !empty($reviewMessage)): ?>
                        <div class="review-message <?php echo (isset($reviewSuccess) && $reviewSuccess) ? 'review-success' : 'review-error'; ?>">
                            <?php echo $reviewMessage; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($userReview): ?>
                        <div class="user-review-notice">
                            <?php echo $translations['you_already_reviewed'] ?? 'You have already reviewed this anime. You can edit your review below.'; ?>
                        </div>
                    <?php endif; ?>
                    
                    <h3 class="review-form-title">
                        <?php echo $userReview ? ($translations['edit_your_review'] ?? 'Edit Your Review') : ($translations['write_a_review'] ?? 'Write a Review'); ?>
                    </h3>
                    
                    <form class="review-form" method="post" action="index.php">
                        <div class="rating-input">
                            <label class="rating-label"><?php echo $translations['rating'] ?? 'Rating'; ?>: <span class="selected-rating"><?php echo $userReview ? $userReview['rating'] : '5'; ?></span>/5</label>
                            <div class="star-rating">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" id="rating-<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>" <?php echo ($userReview && $userReview['rating'] == $i) || (!$userReview && $i == 5) ? 'checked' : ''; ?>>
                                    <label for="rating-<?php echo $i; ?>" data-value="<?php echo $i; ?>">★</label>
                                <?php endfor; ?>
                            </div>
                        </div>
                        
                        <div class="comment-input">
                            <label for="review-comment" class="comment-label"><?php echo $translations['comment'] ?? 'Comment'; ?>:</label>
                            <textarea id="review-comment" name="comment" class="comment-textarea" placeholder="<?php echo $translations['share_your_thoughts'] ?? 'Share your thoughts about this anime...'; ?>"><?php echo $userReview ? htmlspecialchars($userReview['comment']) : ''; ?></textarea>
                        </div>
                        
                        <input type="hidden" name="anime_id" value="<?php echo $animeId; ?>">
                        <input type="hidden" name="action" value="submitReview">
                        <button type="submit" class="submit-review">
                            <?php echo $userReview ? ($translations['update_review'] ?? 'Update Review') : ($translations['submit_review'] ?? 'Submit Review'); ?>
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <div class="login-to-review">
                    <p><?php echo $translations['login_to_review'] ?? 'Please log in to write a review.'; ?></p>
                    <a href="index.php?content=login" class="login-button">
                        <?php echo $translations['login'] ?? 'Login'; ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal-overlay" id="addToListModalOverlay">
    <!-- Modal Content -->
    <div class="modal">
        <div class="modal-content">
            <h2><?php echo $translations['add_to_list'] ?? 'Add to My List'; ?></h2>
            <form id="addToListForm" method="post" action="index.php">
                <div class="form-group">
                    <label for="list-status"><?php echo $translations['select_status'] ?? 'Select Status'; ?></label>
                    <select id="list-status" name="status">
                        <option value="Watching"><?php echo $translations['watching'] ?? 'Watching'; ?></option>
                        <option value="Completed"><?php echo $translations['completed'] ?? 'Completed'; ?></option>
                        <option value="Dropped"><?php echo $translations['dropped'] ?? 'Dropped'; ?></option>
                    </select>
                </div>
                <input type="hidden" name="anime_id" value="<?php echo htmlspecialchars($animeId); ?>">
                <input type="hidden" name="action" value="updateList">
                <input type="hidden" name="content" value="animeDetails">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($animeId); ?>">
                <div class="modal-buttons">
                    <button type="submit"><?php echo $translations['save'] ?? 'Save'; ?></button>
                    <button type="button" class="modal-close"><?php echo $translations['close'] ?? 'Close'; ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
