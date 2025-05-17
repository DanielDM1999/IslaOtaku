<div class="login-form-container">
    <h1><?php echo $translations['login']; ?></h1>
    <?php if (!empty($loginError)): ?>
        <div class="error-message"><?php echo $loginError; ?></div>
    <?php endif; ?>
    <form action="index.php?content=login" method="POST">
        <label for="text"><?php echo $translations['email']; ?>:</label>
        <input type="text" id="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
        <div class="error-message" id="login-email-error"><?php echo isset($errors['email']) ? $translations[$errors['email']] : ''; ?></div>

        <label for="password"><?php echo $translations['password']; ?>:</label>
        <input type="password" id="password" name="password">
        <div class="error-message" id="login-password-error"><?php echo isset($errors['password']) ? $translations[$errors['password']] : ''; ?></div>

        <button type="submit"><?php echo $translations['login']; ?></button>
        <p class="form-link"><?php echo $translations['no_account']; ?> 
        <a href="index.php?content=register"><?php echo $translations['register']; ?></a></p>
    </form>
</div>
