<?php
// Determine the language
$lang = isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'es';

// Include the language file based on the selected language
include(__DIR__ . "/../dictionaries/$lang.php");

// Check if the language has been selected and update the cookie
if (isset($_POST['lang'])) {
    $lang = $_POST['lang'];
    setcookie('lang', $lang, time() + (86400 * 30), "/");
    header("Location: " . $_SERVER['PHP_SELF']); 
}

?>

<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IslaOtaku</title>
</head>

<body>
    <header class="header">
        <div class="header-container">
            <div class="logo-title">
                <div class="logo">
                    <img src="./public/images/icon.png" alt="Logo" class="logo-img" />
                </div>
                <h1 class="title">IslaOtaku</h1>
            </div>
            <nav class="nav">
                <a href="#" class="nav-link"><?php echo $translations['login']; ?></a>
                <a href="#" class="nav-link"><?php echo $translations['register']; ?></a>

                <form action="" method="post">
                    <select class="language-select" name="lang" onchange="this.form.submit()">
                        <option value="en" <?php echo ($lang == 'en') ? 'selected' : ''; ?>>English</option>
                        <option value="es" <?php echo ($lang == 'es') ? 'selected' : ''; ?>>Español</option>
                    </select>
                </form>
            </nav>
        </div>
    </header>
</body>

</html>
