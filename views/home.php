<div class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title"><?php echo $translations['discover_anime']; ?></h1>
        <p class="hero-description">
            <?php echo $translations['hero_description'];?>
        </p>
        <div class="search-container">
            <input 
                type="text" 
                id="anime-search" 
                class="search-input" 
                placeholder="<?php echo $translations['search_placeholder']; ?>" 
            />
            <button id="search-button" class="search-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="search-icon">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </button>
        </div>
    </div>
</div>

<h1><?php echo $translations['top_anime'] ?? 'Top Anime'; ?></h1>

<div class="anime-list" id="anime-list">
    <?php foreach ($animes as $anime): ?>
        <a href="index.php?content=animeDetails&id=<?php echo $anime['anime_id']; ?>" class="anime-item">
            <div class="anime-card">
                <?php
                $imageUrl = $anime['image_url'];
                echo '<img src="' . htmlspecialchars($imageUrl) . '" alt="' . htmlspecialchars($anime['name']) . '" class="anime-img">';
                ?>
                <h2><?php echo htmlspecialchars($anime['name']); ?></h2>
            </div>
        </a>
    <?php endforeach; ?>
</div>

<div class="pagination" id="pagination">
    <?php if ($totalPages > 1): ?>
        <?php if ($page > 1): ?>
            <a href="index.php?page=1" class="pagination-control">&laquo;</a>
            <a href="index.php?page=<?php echo $page - 1; ?>" class="pagination-control">&lsaquo;</a>
        <?php endif; ?>

        <?php
        $range = 5;
        $start = max(1, $page - floor($range / 2));
        $end = min($totalPages, $start + $range - 1);

        if ($end == $totalPages) {
            $start = max(1, $end - $range + 1);
        }

        if ($start > 1): ?>
            <a href="index.php?page=1">1</a>
            <?php if ($start > 2): ?>
                <span class="pagination-ellipsis">&hellip;</span>
            <?php endif; ?>
        <?php endif; ?>

        <?php for ($i = $start; $i <= $end; $i++): ?>
            <a href="index.php?page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <?php if ($end < $totalPages): ?>
            <?php if ($end < $totalPages - 1): ?>
                <span class="pagination-ellipsis">&hellip;</span>
            <?php endif; ?>
            <a href="index.php?page=<?php echo $totalPages; ?>"><?php echo $totalPages; ?></a>
        <?php endif; ?>

        <?php if ($page < $totalPages): ?>
            <a href="index.php?page=<?php echo $page + 1; ?>" class="pagination-control">&rsaquo;</a>
            <a href="index.php?page=<?php echo $totalPages; ?>" class="pagination-control">&raquo;</a>
        <?php endif; ?>
    <?php endif; ?>
</div>
