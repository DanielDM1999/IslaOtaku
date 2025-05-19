<?php
require_once(__DIR__ . '/../config/Database.php');
require_once(__DIR__ . '/../models/UserModel.php');

class UserController
{
    private $userModel;

    public function __construct()
    {
        $db = new Database();
        $this->userModel = new UserModel($db->getConnection()); // Initialize UserModel with DB connection
    }

    private function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start(); // Start session if not already started
        }
    }

    public function getUserModel()
    {
        return $this->userModel; // Getter for UserModel instance
    }

    public function login($email, $password)
    {
        if (empty($email) || empty($password)) {
            return false; // Validate inputs
        }

        $user = $this->userModel->getUserByEmail($email); // Fetch user by email

        // Verify password and set session variables if valid
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
        // Check required fields
        if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
            return 'All fields are required';
        }

        // Check password confirmation
        if ($password !== $confirmPassword) {
            return 'Passwords do not match';
        }

        // Check if email or username already exist
        if ($this->userModel->getUserByEmail($email)) {
            return 'Email already in use';
        }

        if ($this->userModel->getUserByName($name)) {
            return 'Username already taken';
        }

        // Hash password and create user
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $userId = $this->userModel->createUser($name, $email, $hashedPassword);

        if ($userId) {
            $this->startSession();

            // Set session for newly registered user
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

        $_SESSION = array(); // Clear session data
        session_destroy();   // Destroy session

        // Clear session cookie if used
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

        // Return false if session expired or user_id not set
        if ($this->isSessionExpired()) {
            return false;
        }

        return isset($_SESSION['user_id']);
    }

    public function getCurrentUser()
    {
        if (!$this->isLoggedIn()) {
            return null; // Return null if not logged in
        }

        $_SESSION['last_activity'] = time(); // Update last activity timestamp

        return $this->userModel->getUserById($_SESSION['user_id']); // Fetch user data
    }

    public function getUserAnimeList($status = null)
    {
        if (!$this->isLoggedIn()) {
            return []; // Return empty array if not logged in
        }

        return $this->userModel->getUserAnimeList($_SESSION['user_id'], $status);
    }

    public function getUserReviews()
    {
        if (!$this->isLoggedIn()) {
            return []; // Return empty array if not logged in
        }

        return $this->userModel->getUserReviews($_SESSION['user_id']);
    }

    public function isSessionExpired()
    {
        $this->startSession();

        // Check if last activity was more than 30 minutes ago
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > 1800) {
            $this->logout(); // Logout user on session expiration
            return true;
        }
        return false;
    }

    public function updateProfilePicture($userId, $fileData)
    {
        error_log("Starting profile picture update process for user ID: " . $userId);

        // Get current user info
        $currentUser = $this->getCurrentUser();
        if (!$currentUser || !isset($currentUser['name'])) {
            error_log("Could not retrieve username for user ID: " . $userId);
            return 'Could not retrieve user information.';
        }
        
        // Sanitize username for folder name
        $username = preg_replace('/[^a-zA-Z0-9_-]/', '_', $currentUser['name']);
        error_log("Using username for folder: " . $username);

        // Check for upload errors
        if ($fileData['error'] !== UPLOAD_ERR_OK) {
            error_log("File upload error: " . $fileData['error']);
            return 'File upload failed with error code: ' . $fileData['error'];
        }

        // Validate file MIME type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $detectedType = finfo_file($fileInfo, $fileData['tmp_name']);
        finfo_close($fileInfo);

        error_log("Detected file type: " . $detectedType);

        if (!in_array($detectedType, $allowedTypes)) {
            error_log("Invalid file type: " . $detectedType);
            return 'Invalid file type. Only JPG, PNG, and GIF are allowed.';
        }

        // Validate max file size (2MB)
        $maxSize = 2 * 1024 * 1024;
        if ($fileData['size'] > $maxSize) {
            error_log("File too large: " . $fileData['size'] . " bytes");
            return 'File is too large. Maximum size is 2MB.';
        }

        // Create base and user-specific upload directories if needed
        $baseUploadDir = __DIR__ . '/../uploads/profilePictures/';
        if (!file_exists($baseUploadDir)) {
            error_log("Creating base upload directory: " . $baseUploadDir);
            $dirCreated = mkdir($baseUploadDir, 0777, true);
            if (!$dirCreated) {
                error_log("Failed to create base upload directory");
                return 'Failed to create upload directory. Check server permissions.';
            }
        }

        $userUploadDir = $baseUploadDir . $username . '/';
        if (!file_exists($userUploadDir)) {
            error_log("Creating user-specific upload directory: " . $userUploadDir);
            $dirCreated = mkdir($userUploadDir, 0777, true);
            if (!$dirCreated) {
                error_log("Failed to create user-specific upload directory");
                return 'Failed to create user directory. Check server permissions.';
            }
        }

        // Ensure directory is writable, attempt to fix permissions if not
        if (!is_writable($userUploadDir)) {
            error_log("User upload directory is not writable: " . $userUploadDir);
            chmod($userUploadDir, 0777);
            if (!is_writable($userUploadDir)) {
                return 'Upload directory is not writable. Please check server permissions.';
            }
        }

        // Generate filename and paths
        $fileExtension = pathinfo($fileData['name'], PATHINFO_EXTENSION);
        $newFilename = 'profile.' . $fileExtension;
        $targetPath = $userUploadDir . $newFilename;
        $dbPath = $username . '/' . $newFilename;

        error_log("Target path for upload: " . $targetPath);
        error_log("Path to store in database: " . $dbPath);
        error_log("Temporary file exists: " . (file_exists($fileData['tmp_name']) ? 'Yes' : 'No'));

        // Remove any existing profile pictures before saving new one
        $this->cleanUserProfileDirectory($userUploadDir);

        // Move uploaded file to target directory
        if (!move_uploaded_file($fileData['tmp_name'], $targetPath)) {
            $error = error_get_last();
            error_log("Failed to move uploaded file. PHP Error: " . ($error ? json_encode($error) : 'Unknown error'));
            return 'Failed to save the uploaded file. Server error occurred.';
        }

        error_log("File successfully moved to: " . $targetPath);

        // Update DB with new profile picture path
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
                    unlink($file); // Delete old profile pictures
                }
            }
        }
    }

    public function updateUserProfile($userId, $name, $email, $currentPassword = null, $newPassword = null)
    {
        // Get current user data for validation and to check username change
        $currentUser = $this->userModel->getUserWithPasswordById($userId);
        if (!$currentUser) {
            return 'User not found';
        }
        
        $oldUsername = $currentUser['name'] ?? '';
        $oldUsernameSanitized = preg_replace('/[^a-zA-Z0-9_-]/', '_', $oldUsername);
        
        // Validate required fields
        if (empty($name) || empty($email)) {
            return 'Name and email are required';
        }

        // Check if new username or email already taken by others
        $existingUser = $this->userModel->getUserByName($name);
        if ($existingUser && $existingUser['user_id'] != $userId) {
            return 'Username already taken';
        }

        $existingUser = $this->userModel->getUserByEmail($email);
        if ($existingUser && $existingUser['user_id'] != $userId) {
            return 'Email already in use';
        }
        
        // Handle password change if both current and new password provided
        if (!empty($currentPassword) && !empty($newPassword)) {
            if (!password_verify($currentPassword, $currentUser['password'])) {
                return 'Current password is incorrect';
            }
            
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $passwordResult = $this->userModel->updatePassword($userId, $hashedPassword);
            if (!$passwordResult) {
                return 'Failed to update password';
            }
        }

        // Update user profile data
        $result = $this->userModel->updateUser($userId, $name, $email);

        if ($result) {
            // Update session with new data
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            
            // If username changed and user has a profile picture, move picture folder
            if ($oldUsername !== $name && !empty($currentUser['profile_picture'])) {
                $newUsernameSanitized = preg_replace('/[^a-zA-Z0-9_-]/', '_', $name);
                
                $oldUserDir = __DIR__ . '/../uploads/profilePictures/' . $oldUsernameSanitized . '/';
                if (file_exists($oldUserDir)) {
                    $newUserDir = __DIR__ . '/../uploads/profilePictures/' . $newUsernameSanitized . '/';
                    if (!file_exists($newUserDir)) {
                        mkdir($newUserDir, 0777, true);
                    }
                    
                    // Move files from old to new directory
                    $files = glob($oldUserDir . '*');
                    foreach ($files as $file) {
                        if (is_file($file)) {
                            $fileName = basename($file);
                            rename($file, $newUserDir . $fileName);
                        }
                    }
                    
                    // Update DB path for profile picture
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
