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

// Instantiate the AnimeFetcher and fetch the animes
$animeFetcher = new AnimeFetcher();
$animes = $animeFetcher->getAnimesForPage(1);
?>

<!DOCTYPE html>
<html lang="<?php echo isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'es'; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./public/css/globals.css">
    <link rel="stylesheet" href="./public/css/header.css">
    <link rel="stylesheet" href="./public/css/content.css">
    <title>IslaOtaku</title>
</head>

<body>
    <?php include(__DIR__ . '/includes/header.php'); ?>

    <div class="content">
        <h1>Top Animes</h1>

        <div class="anime-list">
            <?php foreach ($animes as $anime): ?>
                <div class="anime-item">
                    <div class="anime-card">
                        <?php
                        $imageUrl = $anime['images']['jpg']['image_url'];  
                        echo '<img src="' . htmlspecialchars($imageUrl) . '" alt="' . htmlspecialchars($anime['title']) . '" class="anime-img">';
                        ?>
                        <h2><?php echo htmlspecialchars($anime['title']); ?></h2>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>

    <?php include(__DIR__ . '/includes/footer.php'); ?>
</body>

</html>