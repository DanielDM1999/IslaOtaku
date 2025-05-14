<div class="profile-container">
    <h1><?php echo $translations['profile']; ?></h1>
    
    <?php if (isset($updateSuccess) && $updateSuccess): ?>
    <div class="success-message">
        <?php echo $translations['profile_updated'] ?? 'Profile updated successfully'; ?>
    </div>
    <?php endif; ?>
    
    <?php if (isset($profileUpdateError) && $profileUpdateError): ?>
    <div class="error-message">
        <?php echo $profileUpdateError; ?>
    </div>
    <?php endif; ?>
    
    <div class="profile-tabs">
        <div class="tab-buttons">
            <button class="tab-button active" data-tab="profile-info"><?php echo $translations['profile_info'] ?? 'Profile Information'; ?></button>
            <button class="tab-button" data-tab="anime-stats"><?php echo $translations['anime_stats']; ?></button>
        </div>
        
        <div class="tab-content">
            <!-- Profile Info Tab -->
            <div class="tab-pane active" id="profile-info">
                <div class="profile-edit-container">
                    <div class="profile-header">
                        <h2><?php echo $translations['account_info']; ?></h2>
                        <button type="button" id="edit-profile-btn" class="edit-button">
                            <?php echo $translations['edit'] ?? 'Edit'; ?>
                        </button>
                    </div>
                    
                    <!-- Profile Picture Section -->
                    <div class="profile-section">
                        <div class="profile-picture-wrapper">
                            <div class="profile-picture-container">
                                <?php 
                                // Determine the profile picture path
                                $profilePicturePath = 'default.jpg'; // Default image
                                if (isset($currentUser['profile_picture']) && !empty($currentUser['profile_picture'])) {
                                    $profilePicturePath = $currentUser['profile_picture'];
                                }
                                ?>
                                <img id="profile-image-preview" 
                                     src="./uploads/profilePictures/<?php echo htmlspecialchars($profilePicturePath); ?>" 
                                     alt="Profile Picture" class="profile-picture">
                                <div class="picture-overlay" id="picture-overlay">
                                    <i class="picture-icon">📷</i>
                                    <span class="picture-text"><?php echo $translations['change_picture'] ?? 'Change Picture'; ?></span>
                                </div>
                            </div>
                            
                            <form id="picture-form" method="POST" enctype="multipart/form-data" class="profile-picture-form">
                                <input type="hidden" name="update_picture" value="1">
                                <input type="file" id="profile_picture" name="profile_picture" accept="image/*" class="picture-upload-input">
                                
                                <div class="picture-actions" id="picture-actions" style="display: none;">
                                    <button type="submit" class="confirm-picture-button">
                                        <?php echo $translations['confirm'] ?? 'Confirm'; ?>
                                    </button>
                                    <button type="button" id="cancel-picture-btn" class="cancel-picture-button">
                                        <?php echo $translations['cancel'] ?? 'Cancel'; ?>
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <div class="profile-info">
                            <h3><?php echo htmlspecialchars($currentUser['name']); ?></h3>
                            <p class="profile-email"><?php echo htmlspecialchars($currentUser['email']); ?></p>
                            <p class="profile-date">
                                <span class="label"><?php echo $translations['member_since']; ?>:</span>
                                <span class="value"><?php echo isset($currentUser['registration_date']) ? date('F j, Y', strtotime($currentUser['registration_date'])) : 'N/A'; ?></span>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Profile Info Form -->
                    <form id="profile-form" method="POST" class="profile-form">
                        <input type="hidden" name="update_profile" value="1">
                        <div class="form-fields">
                            <div class="form-field">
                                <label for="name"><?php echo $translations['username']; ?>:</label>
                                <input type="text" id="name" name="name" 
                                       value="<?php echo htmlspecialchars($currentUser['name']); ?>" 
                                       readonly class="profile-input">
                            </div>
                            
                            <div class="form-field">
                                <label for="email"><?php echo $translations['email']; ?>:</label>
                                <input type="email" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($currentUser['email']); ?>" 
                                       readonly class="profile-input">
                            </div>
                            
                            <div class="form-actions" style="display: none;">
                                <button type="submit" class="save-button">
                                    <?php echo $translations['save'] ?? 'Save'; ?>
                                </button>
                                <button type="button" id="cancel-edit-btn" class="cancel-button">
                                    <?php echo $translations['cancel'] ?? 'Cancel'; ?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Anime Stats Tab -->
            <div class="tab-pane" id="anime-stats">
                <div class="profile-section">
                    <h2><?php echo $translations['anime_stats']; ?></h2>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <span class="stat-value"><?php echo count($userController->getUserAnimeList('watching')); ?></span>
                            <span class="stat-label"><?php echo $translations['watching']; ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value"><?php echo count($userController->getUserAnimeList('completed')); ?></span>
                            <span class="stat-label"><?php echo $translations['completed']; ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value"><?php echo count($userController->getUserAnimeList('plan_to_watch')); ?></span>
                            <span class="stat-label"><?php echo $translations['plan_to_watch']; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>