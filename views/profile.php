<div class="profile-container">
    <h1><?php echo $translations['profile']; ?></h1>
    
    <?php if ($currentUser): ?>
    <div class="profile-info">
        <div class="profile-section">
            <h2><?php echo $translations['account_info']; ?></h2>
            <div class="info-item">
                <span class="label"><?php echo $translations['username']; ?>:</span>
                <span class="value"><?php echo htmlspecialchars($currentUser['name']); ?></span>
            </div>
            <div class="info-item">
                <span class="label"><?php echo $translations['email']; ?>:</span>
                <span class="value"><?php echo htmlspecialchars($currentUser['email']); ?></span>
            </div>
            <div class="info-item">
                <span class="label"><?php echo $translations['member_since']; ?>:</span>
                <span class="value"><?php echo date('F j, Y', strtotime($currentUser['created_at'])); ?></span>
            </div>
        </div>
        
        <div class="profile-section">
            <h2><?php echo $translations['anime_stats']; ?></h2>
            <?php 
            $watching = count($userController->getUserAnimeList('watching'));
            $completed = count($userController->getUserAnimeList('completed'));
            $planToWatch = count($userController->getUserAnimeList('plan_to_watch'));
            $reviews = count($userController->getUserReviews());
            ?>
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-value"><?php echo $watching; ?></span>
                    <span class="stat-label"><?php echo $translations['watching']; ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-value"><?php echo $completed; ?></span>
                    <span class="stat-label"><?php echo $translations['completed']; ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-value"><?php echo $planToWatch; ?></span>
                    <span class="stat-label"><?php echo $translations['plan_to_watch']; ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-value"><?php echo $reviews; ?></span>
                    <span class="stat-label"><?php echo $translations['reviews']; ?></span>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="error-message"><?php echo $translations['not_logged_in']; ?></div>
    <?php endif; ?>
</div>
