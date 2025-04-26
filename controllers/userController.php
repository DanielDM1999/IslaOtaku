<?php
require_once(__DIR__ . '/../config/Database.php');
require_once(__DIR__ . '/../models/UserModel.php');

class UserController {
    private $userModel;

    public function __construct() {
        $db = new Database();
        $this->userModel = new UserModel($db->getConnection());
    }

    public function login($email, $password) {
        if (empty($email) || empty($password)) {
            return false;
        }

        $user = $this->userModel->getUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['last_activity'] = time();

            return true;
        }

        return false;
    }

    public function register($name, $email, $password, $confirmPassword) {
        if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
            return "All fields are required";
        }

        if ($password !== $confirmPassword) {
            return "Passwords do not match";
        }

        if ($this->userModel->getUserByEmail($email)) {
            return "Email already in use";
        }

        if ($this->userModel->getUserByName($name)) {
            return "Username already taken";
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $userId = $this->userModel->createUser($name, $email, $hashedPassword);

        if ($userId) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $_SESSION['user_id'] = $userId;
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION['last_activity'] = time();

            return true;
        }

        return "Registration failed";
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = array();
        session_destroy();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
    }

    public function isLoggedIn() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['user_id']);
    }

    public function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }

        return $this->userModel->getUserById($_SESSION['user_id']);
    }

    public function getUserAnimeList($status = null) {
        if (!$this->isLoggedIn()) {
            return [];
        }

        return $this->userModel->getUserAnimeList($_SESSION['user_id'], $status);
    }

    public function getUserReviews() {
        if (!$this->isLoggedIn()) {
            return [];
        }

        return $this->userModel->getUserReviews($_SESSION['user_id']);
    }
}
?>
