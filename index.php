<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$lang = isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'es';

if (isset($_POST['lang'])) {
    $lang = $_POST['lang'];
    setcookie('lang', $lang, time() + (86400 * 30), "/");

    // Preserve all query parameters
    $queryParams = $_GET;
    $queryParams['lang'] = $lang; // Add or update the language parameter in the query string
    $redirectUrl = $_SERVER['PHP_SELF'] . '?' . http_build_query($queryParams);

    header("Location: $redirectUrl");
    exit;
}


include(__DIR__ . "/dictionaries/$lang.php");

require_once(__DIR__ . '/controllers/UserController.php');
require_once(__DIR__ . '/controllers/AnimeController.php');

$userController = new UserController();
$animeFetcher = new AnimeFetcher();

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $userController->logout();
    header("Location: index.php");
    exit();
}

$content = isset($_GET['content']) ? $_GET['content'] : 'home';

$allowedContent = ['home', 'login', 'register', 'profile', 'reviews'];

if (!in_array($content, $allowedContent)) {
    $content = 'home';
}

if (in_array($content, ['profile', 'my-reviews']) && !$userController->isLoggedIn()) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header("Location: index.php?content=login");
    exit();
}

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

$isLoggedIn = $userController->isLoggedIn();
$currentUser = $isLoggedIn ? $userController->getCurrentUser() : null;
$userName = $isLoggedIn ? $currentUser['name'] : '';

if ($content === 'home') {
    $itemsPerPage = 20;
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $page = max(1, $page);
    $offset = ($page - 1) * $itemsPerPage;

    $animes = $animeFetcher->getAnimesForPageFromDb($offset, $itemsPerPage);
    $totalAnimes = $animeFetcher->getTotalAnimesCount();
    $totalPages = ceil($totalAnimes / $itemsPerPage);

    $page = min($page, max(1, $totalPages));
}
?>

<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./public/css/globals.css">
    <link rel="stylesheet" href="./public/css/header.css">
    <link rel="stylesheet" href="./public/css/footer.css">
    <?php if ($content === 'home'): ?>
        <link rel="stylesheet" href="./public/css/content.css">
    <?php endif; ?>
    <title>IslaOtaku</title>
</head>

<body>
    <header class="header">
        <div class="header-container">
            <div class="logo-title">
                <a href="index.php" class="logo-link">
                    <img src="./public/images/icon.png" alt="Logo" class="logo-img" />
                    <h1 class="title">IslaOtaku</h1>
                </a>

            </div>

            <nav class="nav">
                <?php if ($isLoggedIn): ?>
                    <div class="user-menu">
                        <span class="username"><?php echo htmlspecialchars($userName); ?></span>
                        <div class="dropdown">
                            <button class="dropdown-btn"><?php echo $translations['account']; ?> ▼</button>
                            <div class="dropdown-content">
                                <a href="index.php?content=profile"><?php echo $translations['profile']; ?></a>
                                <a href="index.php?action=logout"><?php echo $translations['logout']; ?></a>
                            </div>
                        </div>
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
            case 'my-reviews':
                include(__DIR__ . '/views/reviews.php');
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
    <?php endif; ?>
</body>

</html>