<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Language handling: determine and set language preference
$lang = isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'es';

if (isset($_POST['lang'])) {
    $lang = $_POST['lang'];
    setcookie('lang', $lang, time() + (86400 * 30), "/");

    $queryParams = $_GET;
    $queryParams['lang'] = $lang;
    $redirectUrl = $_SERVER['PHP_SELF'] . '?' . http_build_query($queryParams);

    header("Location: $redirectUrl");
    exit;
}

// Load the appropriate language dictionary
include(__DIR__ . "/dictionaries/$lang.php");

require_once(__DIR__ . '/controllers/UserController.php');
require_once(__DIR__ . '/controllers/AnimeController.php');
require_once(__DIR__ . '/controllers/ListController.php');

$userController = new UserController();
$animeController = new AnimeController();
$listController = new ListController();

// Handle AJAX search requests for anime
if (isset($_GET['action']) && $_GET['action'] === 'search' && isset($_GET['query'])) {
    header('Content-Type: application/json');
    $query = htmlspecialchars($_GET['query']);
    $results = $animeController->searchAnimes($query);
    echo json_encode($results);
    exit();
}

// Handle user logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $userController->logout();
    header("Location: index.php");
    exit();
}

// Determine the content to load based on query parameters
$content = isset($_GET['content']) ? $_GET['content'] : 'home';
$allowedContent = ['home', 'login', 'register', 'profile', 'reviews', 'animeDetails', 'lists'];

if (!in_array($content, $allowedContent)) {
    $content = 'home';
}

// Redirect to login if accessing restricted pages without authentication
if (in_array($content, ['profile', 'my-reviews']) && !$userController->isLoggedIn()) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header("Location: index.php?content=login");
    exit();
}

// Handle login form submission
$loginError = '';
if ($content === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($userController->login($email, $password)) {
        $redirect = isset($_SESSION['redirect_url']) ? $_SESSION['redirect_url'] : 'index.php';
        unset($_SESSION['redirect_url']);
        header("Location: $redirect");
        exit();
    } else {
        $loginError = 'Invalid email or password';
    }
}

// Handle registration form submission
$registerError = '';
if ($content === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['email'])) {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    $result = $userController->register($name, $email, $password, $confirmPassword);

    if ($result === true) {
        header("Location: index.php");
        exit();
    } else {
        $registerError = $result;
    }
}

// Check user authentication status
$isLoggedIn = $userController->isLoggedIn();
$currentUser = $isLoggedIn ? $userController->getCurrentUser() : null;
$userName = $isLoggedIn ? $currentUser['name'] : '';

// Handle anime details page logic
if ($content === 'animeDetails' && isset($_GET['id'])) {
    $animeId = (int) $_GET['id'];
    $anime = $animeController->getAnimeDetails($animeId);

    if (!$anime) {
        header("Location: index.php");
        exit();
    }
} else if ($content === 'home') {
    // Pagination logic for the homepage anime list
    $itemsPerPage = 24;
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $page = max(1, $page);
    $offset = ($page - 1) * $itemsPerPage;

    $animes = $animeController->getAnimesForPage($offset, $itemsPerPage);
    $totalAnimes = $animeController->getTotalAnimesCount();
    $totalPages = ceil($totalAnimes / $itemsPerPage);

    $page = min($page, max(1, $totalPages));
}

// Handle profile updates
$updateSuccess = false;
$profileUpdateError = '';

if ($content === 'profile' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        // Update profile information
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';

        if (empty($name) || empty($email)) {
            $profileUpdateError = $translations['all_fields_required'] ?? 'All fields are required';
        } else {
            $result = $userController->updateUserProfile($currentUser['user_id'], $name, $email);
            if ($result === true) {
                $updateSuccess = true;
                $currentUser = $userController->getCurrentUser();
            } else {
                $profileUpdateError = $result;
            }
        }
    } elseif (isset($_POST['update_picture']) && isset($_FILES['profile_picture'])) {
        // Update profile picture
        $result = $userController->updateProfilePicture($currentUser['user_id'], $_FILES['profile_picture']);
        if ($result === true) {
            $updateSuccess = true;
            $currentUser = $userController->getCurrentUser();
        } else {
            $profileUpdateError = $result;
        }
    }
}

// Ensure the default profile picture exists
$defaultImagePath = __DIR__ . '/uploads/profilePictures/default.jpg';
if (!file_exists($defaultImagePath)) {
    if (!file_exists(dirname($defaultImagePath))) {
        mkdir(dirname($defaultImagePath), 0777, true);
    }

    if (function_exists('imagecreatetruecolor')) {
        $image = imagecreatetruecolor(200, 200);
        $bgColor = imagecolorallocate($image, 240, 240, 240);
        $textColor = imagecolorallocate($image, 100, 100, 100);
        imagefill($image, 0, 0, $bgColor);
        imagestring($image, 5, 40, 80, 'No Image', $textColor);
        imagejpeg($image, $defaultImagePath);
        imagedestroy($image);
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'filterList' && isset($_GET['status'])) {
    $userId = $_SESSION['user_id']; 

    $status = isset($_GET['status']) ? $_GET['status'] : 'Watching'; 
        
    $listController = new ListController();
    $animeList = $listController->getFilteredAnimeList($userId, $status);

    echo json_encode($animeList);
    exit;
}

// Handle anime list update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'updateList' && isset($_POST['anime_id'], $_POST['status'])) {
    // Check if user is logged in
    if (!$isLoggedIn) {
        header("Location: index.php?content=login");
        exit;
    }
    
    try {
        $userId = $currentUser['user_id'];
        $animeId = (int) $_POST['anime_id'];
        $status = $_POST['status'];

        // Add or update the anime in the user's list
        $result = $listController->addOrUpdateAnimeToList($userId, $animeId, $status);
        
        if ($result) {
            header("Location: index.php?content=animeDetails&id={$animeId}&success=1");
        } else {
            header("Location: index.php?content=animeDetails&id={$animeId}&error=1");
        }
        exit;
    } catch (Exception $e) {
        header("Location: index.php?content=animeDetails&id={$animeId}&error=1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./public/css/globals.css">
    <link rel="stylesheet" href="./public/css/header.css">
    <link rel="stylesheet" href="./public/css/content.css">
    <link rel="stylesheet" href="./public/css/footer.css">
    <link rel="stylesheet" href="./public/css/login.css">
    <link rel="stylesheet" href="./public/css/register.css">
    <link rel="stylesheet" href="./public/css/hero.css">
    <?php if ($content === 'animeDetails'): ?>
        <link rel="stylesheet" href="./public/css/animeDetails.css">
    <?php endif; ?>
    <?php if ($content === 'profile'): ?>
        <link rel="stylesheet" href="./public/css/profile.css">
    <?php endif; ?>
    <?php if ($content === 'lists'): ?>
        <link rel="stylesheet" href="./public/css/lists.css">
    <?php endif; ?>

    <title>
        IslaOtaku<?php echo ($content === 'animeDetails' && isset($anime)) ? ' - ' . htmlspecialchars($anime['name']) : ''; ?>
    </title>
</head>

<body>
    <header class="header">
        <div class="header-container">
            <div class="logo-title">
                <a href="index.php" class="logo-link">
                    <img src="./public/images/icon.png" alt="Logo" class="logo-img">
                    <h1 class="title">IslaOtaku</h1>
                </a>
            </div>

            <nav class="nav">
                <?php if ($isLoggedIn): ?>
                    <div class="user-menu">
                        <a href="index.php?content=profile" class="user-btn">
                            <?php
                            // Determine the profile picture path
                            $profilePicturePath = 'default.jpg'; // Default image
                            if (isset($currentUser['profile_picture']) && !empty($currentUser['profile_picture'])) {
                                $profilePicturePath = $currentUser['profile_picture'];
                            }
                            ?>
                            <img src="./uploads/profilePictures/<?php echo htmlspecialchars($profilePicturePath); ?>"
                                alt="User" class="user-icon">
                        </a>

                        <a href="index.php?content=profile" class="nav-link user-btn">
                            <span class="username"><?php echo htmlspecialchars($userName); ?></span>
                        </a>

                        <a href="index.php?content=lists" class="nav-link my-lists-btn">
                            <?php echo $translations['my_lists'] ?? 'My Lists'; ?>
                        </a>

                        <a href="index.php?action=logout" class="nav-link logout-btn">
                            <?php echo $translations['logout']; ?>
                        </a>
                    </div>
                <?php else: ?>
                    <a href="index.php?content=login" class="nav-link"><?php echo $translations['login']; ?></a>
                    <a href="index.php?content=register" class="nav-link"><?php echo $translations['register']; ?></a>
                <?php endif; ?>

                <form action="" method="post" class="lang-form">
                    <select class="language-select" name="lang" onchange="this.form.submit()">
                        <option value="en" <?php echo ($lang == 'en') ? 'selected' : ''; ?>>English</option>
                        <option value="es" <?php echo ($lang == 'es') ? 'selected' : ''; ?>>Español</option>
                    </select>
                </form>
            </nav>
        </div>
    </header>


    <div class="content">
        <?php
        switch ($content) {
            case 'login':
                include(__DIR__ . '/views/login.php');
                break;
            case 'register':
                include(__DIR__ . '/views/register.php');
                break;
            case 'profile':
                include(__DIR__ . '/views/profile.php');
                break;
            case 'reviews':
                include(__DIR__ . '/views/reviews.php');
                break;
            case 'animeDetails':
                include(__DIR__ . '/views/animeDetails.php');
                break;
            case 'lists':
                include(__DIR__ . '/views/lists.php');
                break;
            default:
                include(__DIR__ . '/views/home.php');
                break;
        }
        ?>
    </div>

    <footer class="footer">
        <div class="footer-container">
            <p>&copy; <?php echo date("Y"); ?> IslaOtaku. All Rights Reserved.</p>
            <nav class="footer-nav">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Contact</a>
            </nav>
        </div>
    </footer>

    <?php if ($content === 'home'): ?>
        <script src="./public/js/cards.js"></script>
        <script src="./public/js/search.js"></script>
    <?php endif; ?>
    <script src="./public/js/auth.js"></script>
    <?php if ($content === 'animeDetails'): ?>
        <script>
            const translations = <?php echo json_encode($translations); ?>;
        </script>
        <script src="./public/js/synopsis.js"></script>
        <script src="./public/js/modal.js"></script>
    <?php endif; ?>
    <?php if ($content === 'profile'): ?>
        <script src="./public/js/profile.js"></script>
    <?php endif; ?>
    <?php if ($content === 'lists'): ?>
        <script src="./public/js/lists.js"></script>
    <?php endif; ?>
</body>

</html>