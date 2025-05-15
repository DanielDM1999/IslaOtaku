<?php
require_once(__DIR__ . '/../config/Database.php');
require_once(__DIR__ . '/../models/UserModel.php');

class UserController
{
    private $userModel;

    public function __construct()
    {
        $db = new Database();
        $this->userModel = new UserModel($db->getConnection());
    }

    private function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function getUserModel()
    {
        return $this->userModel;
    }

    public function login($email, $password)
    {
        if (empty($email) || empty($password)) {
            return false;
        }

        $user = $this->userModel->getUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $this->startSession();

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['last_activity'] = time();

            return true;
        }

        return false;
    }

    public function register($name, $email, $password, $confirmPassword)
    {
        if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
            return 'All fields are required';
        }

        if ($password !== $confirmPassword) {
            return 'Passwords do not match';
        }

        if ($this->userModel->getUserByEmail($email)) {
            return 'Email already in use';
        }

        if ($this->userModel->getUserByName($name)) {
            return 'Username already taken';
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $userId = $this->userModel->createUser($name, $email, $hashedPassword);

        if ($userId) {
            $this->startSession();

            $_SESSION['user_id'] = $userId;
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION['last_activity'] = time();

            return true;
        }

        return 'Registration failed';
    }

    public function logout()
    {
        $this->startSession();

        $_SESSION = array();
        session_destroy();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
    }

    public function isLoggedIn()
    {
        $this->startSession();

        // Check if session is expired
        if ($this->isSessionExpired()) {
            return false;
        }

        return isset($_SESSION['user_id']);
    }

    public function getCurrentUser()
    {
        if (!$this->isLoggedIn()) {
            return null;
        }

        // Update last activity time
        $_SESSION['last_activity'] = time();

        return $this->userModel->getUserById($_SESSION['user_id']);
    }

    public function getUserAnimeList($status = null)
    {
        if (!$this->isLoggedIn()) {
            return [];
        }

        return $this->userModel->getUserAnimeList($_SESSION['user_id'], $status);
    }

    public function getUserReviews()
    {
        if (!$this->isLoggedIn()) {
            return [];
        }

        return $this->userModel->getUserReviews($_SESSION['user_id']);
    }

    public function isSessionExpired()
    {
        $this->startSession();

        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > 1800) {
            $this->logout();
            return true;
        }
        return false;
    }

    public function updateProfilePicture($userId, $fileData)
    {
        error_log("Starting profile picture update process for user ID: " . $userId);

        // Get current user to access the username
        $currentUser = $this->getCurrentUser();
        if (!$currentUser || !isset($currentUser['name'])) {
            error_log("Could not retrieve username for user ID: " . $userId);
            return 'Could not retrieve user information.';
        }
        
        // Sanitize username for folder name (remove special characters)
        $username = preg_replace('/[^a-zA-Z0-9_-]/', '_', $currentUser['name']);
        error_log("Using username for folder: " . $username);

        // Check if file was uploaded properly
        if ($fileData['error'] !== UPLOAD_ERR_OK) {
            error_log("File upload error: " . $fileData['error']);
            return 'File upload failed with error code: ' . $fileData['error'];
        }

        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $detectedType = finfo_file($fileInfo, $fileData['tmp_name']);
        finfo_close($fileInfo);

        error_log("Detected file type: " . $detectedType);

        if (!in_array($detectedType, $allowedTypes)) {
            error_log("Invalid file type: " . $detectedType);
            return 'Invalid file type. Only JPG, PNG, and GIF are allowed.';
        }

        // Validate file size (max 2MB)
        $maxSize = 2 * 1024 * 1024; // 2MB in bytes
        if ($fileData['size'] > $maxSize) {
            error_log("File too large: " . $fileData['size'] . " bytes");
            return 'File is too large. Maximum size is 2MB.';
        }

        // Create base profilePictures directory if it doesn't exist
        $baseUploadDir = __DIR__ . '/../uploads/profilePictures/';
        if (!file_exists($baseUploadDir)) {
            error_log("Creating base upload directory: " . $baseUploadDir);
            $dirCreated = mkdir($baseUploadDir, 0777, true);
            if (!$dirCreated) {
                error_log("Failed to create base upload directory");
                return 'Failed to create upload directory. Check server permissions.';
            }
        }

        // Create user-specific directory inside profilePictures
        $userUploadDir = $baseUploadDir . $username . '/';
        if (!file_exists($userUploadDir)) {
            error_log("Creating user-specific upload directory: " . $userUploadDir);
            $dirCreated = mkdir($userUploadDir, 0777, true);
            if (!$dirCreated) {
                error_log("Failed to create user-specific upload directory");
                return 'Failed to create user directory. Check server permissions.';
            }
        }

        // Check if directory is writable
        if (!is_writable($userUploadDir)) {
            error_log("User upload directory is not writable: " . $userUploadDir);
            chmod($userUploadDir, 0777); // Try to make it writable
            if (!is_writable($userUploadDir)) {
                return 'Upload directory is not writable. Please check server permissions.';
            }
        }

        // Generate a filename - using a consistent name for easy replacement
        $fileExtension = pathinfo($fileData['name'], PATHINFO_EXTENSION);
        $newFilename = 'profile.' . $fileExtension;
        $targetPath = $userUploadDir . $newFilename;
        
        // This is the path that will be stored in the database
        $dbPath = $username . '/' . $newFilename;

        error_log("Target path for upload: " . $targetPath);
        error_log("Path to store in database: " . $dbPath);
        error_log("Temporary file exists: " . (file_exists($fileData['tmp_name']) ? 'Yes' : 'No'));

        // Delete any existing profile pictures in the user's directory
        $this->cleanUserProfileDirectory($userUploadDir);

        // Move the uploaded file
        if (!move_uploaded_file($fileData['tmp_name'], $targetPath)) {
            $error = error_get_last();
            error_log("Failed to move uploaded file. PHP Error: " . ($error ? json_encode($error) : 'Unknown error'));
            return 'Failed to save the uploaded file. Server error occurred.';
        }

        error_log("File successfully moved to: " . $targetPath);

        // Update the user's profile picture in the database - store only the path
        $result = $this->userModel->updateProfilePicture($userId, $dbPath);

        if ($result) {
            error_log("Database updated successfully with new path: " . $dbPath);
            return true;
        }

        error_log("Failed to update profile picture in database");
        return 'Failed to update profile picture in the database.';
    }


    private function cleanUserProfileDirectory($directory) {
        if (is_dir($directory)) {
            $files = glob($directory . '*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    error_log("Deleting old profile picture: " . $file);
                    unlink($file);
                }
            }
        }
    }

    public function updateUserProfile($userId, $name, $email, $currentPassword = null, $newPassword = null)
    {
        // Get current user data to check if username is changing
        $currentUser = $this->userModel->getUserWithPasswordById($userId);
        if (!$currentUser) {
            return 'User not found';
        }
        
        $oldUsername = $currentUser['name'] ?? '';
        $oldUsernameSanitized = preg_replace('/[^a-zA-Z0-9_-]/', '_', $oldUsername);
        
        // Validate input
        if (empty($name) || empty($email)) {
            return 'Name and email are required';
        }

        // Check if username is already taken by another user
        $existingUser = $this->userModel->getUserByName($name);
        if ($existingUser && $existingUser['user_id'] != $userId) {
            return 'Username already taken';
        }

        // Check if email is already in use by another user
        $existingUser = $this->userModel->getUserByEmail($email);
        if ($existingUser && $existingUser['user_id'] != $userId) {
            return 'Email already in use';
        }
        
        // Handle password change if requested
        if (!empty($currentPassword) && !empty($newPassword)) {
            // Verify current password
            if (!password_verify($currentPassword, $currentUser['password'])) {
                return 'Current password is incorrect';
            }
            
            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            // Update the password
            $passwordResult = $this->userModel->updatePassword($userId, $hashedPassword);
            if (!$passwordResult) {
                return 'Failed to update password';
            }
        }

        // Update user profile
        $result = $this->userModel->updateUser($userId, $name, $email);

        if ($result) {
            // Update session data
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            
            // If username changed, update profile picture path
            if ($oldUsername !== $name && !empty($currentUser['profile_picture'])) {
                $newUsernameSanitized = preg_replace('/[^a-zA-Z0-9_-]/', '_', $name);
                
                // Only proceed if the old username folder exists
                $oldUserDir = __DIR__ . '/../uploads/profilePictures/' . $oldUsernameSanitized . '/';
                if (file_exists($oldUserDir)) {
                    // Create new username directory
                    $newUserDir = __DIR__ . '/../uploads/profilePictures/' . $newUsernameSanitized . '/';
                    if (!file_exists($newUserDir)) {
                        mkdir($newUserDir, 0777, true);
                    }
                    
                    // Move files from old directory to new directory
                    $files = glob($oldUserDir . '*');
                    foreach ($files as $file) {
                        if (is_file($file)) {
                            $fileName = basename($file);
                            rename($file, $newUserDir . $fileName);
                        }
                    }
                    
                    // Update profile picture path in database
                    $oldPath = $currentUser['profile_picture'];
                    $newPath = str_replace($oldUsernameSanitized . '/', $newUsernameSanitized . '/', $oldPath);
                    $this->userModel->updateProfilePicture($userId, $newPath);
                    
                    // Remove old directory if empty
                    if (count(glob($oldUserDir . '*')) === 0) {
                        rmdir($oldUserDir);
                    }
                }
            }

            return true;
        }

        return 'Failed to update profile';
    }
}
?>