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
                        
                        <button class="action-button write-review">
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
</div>

<div class="modal-overlay" id="addToListModalOverlay">
    <!-- Modal Content -->
    <div class="modal">
        <div class="modal-content">
            <h2><?php echo $translations['add_to_list'] ?? 'Add to My List'; ?></h2>
            <form id="addToListForm">
                <div class="form-group">
                    <label for="list-category"><?php echo $translations['select_category'] ?? 'Select Category'; ?></label>
                    <select id="list-category" name="category">
                        <option value="viewing"><?php echo $translations['viewing'] ?? 'Viewing'; ?></option>
                        <option value="completed"><?php echo $translations['completed'] ?? 'Completed'; ?></option>
                        <option value="dropped"><?php echo $translations['dropped'] ?? 'Dropped'; ?></option>
                    </select>
                </div>
                <input type="hidden" name="anime_id" value="<?php echo htmlspecialchars($animeId); ?>">
                <div class="modal-buttons">
                    <button type="submit"><?php echo $translations['save'] ?? 'Save'; ?></button>
                    <button type="button" class="modal-close"><?php echo $translations['close'] ?? 'Close'; ?></button>
                </div>
            </form>
        </div>
    </div>
</div>