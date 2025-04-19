<?php
// Check if the language cookie is set, otherwise default to Spanish
$lang = isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'es';
include(__DIR__ . "/dictionaries/$lang.php");
include(__DIR__ . '/controllers/animeController.php');

// If the language has been selected and posted, update the cookie
if (isset($_POST['lang'])) {
    $lang = $_POST['lang'];
    setcookie('lang', $lang, time() + (86400 * 30), "/");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Check if this is an AJAX request
if (isset($_GET['ajax']) && $_GET['ajax'] === 'true') {
    // Fixed number of items per page
    $itemsPerPage = 20;
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $page = max(1, $page);
    $offset = ($page - 1) * $itemsPerPage;

    // Fetch animes
    $animeFetcher = new AnimeFetcher();
    $animes = $animeFetcher->getAnimesForPageFromDb($offset, $itemsPerPage);
    $totalAnimes = $animeFetcher->getTotalAnimesCount();
    $totalPages = ceil($totalAnimes / $itemsPerPage);

    // Ensure current page doesn't exceed total pages
    $page = min($page, max(1, $totalPages));

    // Generate pagination HTML
    ob_start();

    // Pagination HTML generation
    if ($totalPages > 1) {
        if ($page > 1) {
            echo '<a href="?page=1" class="pagination-control">&laquo;</a>';
            echo '<a href="?page=' . ($page - 1) . '" class="pagination-control">&lsaquo;</a>';
        }

        // Determine range of page numbers to show
        $range = 5;
        $start = max(1, $page - floor($range / 2));
        $end = min($totalPages, $start + $range - 1);

        if ($end == $totalPages) {
            $start = max(1, $end - $range + 1);
        }

        if ($start > 1) {
            echo '<a href="?page=1">1</a>';
            if ($start > 2) {
                echo '<span class="pagination-ellipsis">&hellip;</span>';
            }
        }

        for ($i = $start; $i <= $end; $i++) {
            $activeClass = ($i == $page) ? 'active' : '';
            echo '<a href="?page=' . $i . '" class="' . $activeClass . '">' . $i . '</a>';
        }

        if ($end < $totalPages) {
            if ($end < $totalPages - 1) {
                echo '<span class="pagination-ellipsis">&hellip;</span>';
            }
            echo '<a href="?page=' . $totalPages . '">' . $totalPages . '</a>';
        }

        if ($page < $totalPages) {
            echo '<a href="?page=' . ($page + 1) . '" class="pagination-control">&rsaquo;</a>';
            echo '<a href="?page=' . $totalPages . '" class="pagination-control">&raquo;</a>';
        }
    }

    $paginationHtml = ob_get_clean();

    // Prepare response
    $response = [
        'animes' => $animes,
        'pagination' => $paginationHtml,
        'currentPage' => $page,
        'totalPages' => $totalPages
    ];

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Regular page load (not AJAX)
$itemsPerPage = 20;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$page = max(1, $page);
$offset = ($page - 1) * $itemsPerPage;

$animeFetcher = new AnimeFetcher();
$animes = $animeFetcher->getAnimesForPageFromDb($offset, $itemsPerPage);
$totalAnimes = $animeFetcher->getTotalAnimesCount();
$totalPages = ceil($totalAnimes / $itemsPerPage);

// Ensure current page doesn't exceed total pages
$page = min($page, max(1, $totalPages));
?>

<!DOCTYPE html>
<html lang="<?php echo isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'es'; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./public/css/content.css">
    <title>IslaOtaku</title>
</head>

<body>
    <?php include(__DIR__ . '/includes/header.php'); ?>

    <div class="content">
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

        <!-- Pagination Controls -->
        <div class="pagination" id="pagination">
            <?php if ($totalPages > 1): ?>
                <?php if ($page > 1): ?>
                    <a href="?page=1" class="pagination-control">&laquo;</a>
                    <a href="?page=<?php echo $page - 1; ?>" class="pagination-control">&lsaquo;</a>
                <?php endif; ?>

                <?php
                // Determine range of page numbers to show
                $range = 5;
                $start = max(1, $page - floor($range / 2));
                $end = min($totalPages, $start + $range - 1);

                if ($end == $totalPages) {
                    $start = max(1, $end - $range + 1);
                }

                if ($start > 1): ?>
                    <a href="?page=1">1</a>
                    <?php if ($start > 2): ?>
                        <span class="pagination-ellipsis">&hellip;</span>
                    <?php endif; ?>
                <?php endif; ?>

                <?php for ($i = $start; $i <= $end; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($end < $totalPages): ?>
                    <?php if ($end < $totalPages - 1): ?>
                        <span class="pagination-ellipsis">&hellip;</span>
                    <?php endif; ?>
                    <a href="?page=<?php echo $totalPages; ?>"><?php echo $totalPages; ?></a>
                <?php endif; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1; ?>" class="pagination-control">&rsaquo;</a>
                    <a href="?page=<?php echo $totalPages; ?>" class="pagination-control">&raquo;</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php include(__DIR__ . '/includes/footer.php'); ?>

    <script src="./public/js/cards.js">
</body>

</html>