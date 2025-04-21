<h1>Top Anime</h1>

<div class="anime-list" id="anime-list">
    <?php foreach ($animes as $anime): ?>
        <div class="anime-item">
            <div class="anime-card">
                <?php
                $imageUrl = $anime['image_url'];
                echo '<img src="' . htmlspecialchars($imageUrl) . '" alt="' . htmlspecialchars($anime['name']) . '" class="anime-img">';
                ?>
                <h2><?php echo htmlspecialchars($anime['name']); ?></h2>
            </div>
        </div>
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