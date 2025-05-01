<div class="login-form-container">
    <h1><?php echo $translations['login']; ?></h1>
    <?php if (!empty($loginError)): ?>
        <div class="error-message"><?php echo $loginError; ?></div>
    <?php endif; ?>
    <form action="index.php?content=login" method="POST">
        <label for="email"><?php echo $translations['email']; ?>:</label>
        <input type="email" id="email" name="email" required>
        
        <label for="password"><?php echo $translations['password']; ?>:</label>
        <input type="password" id="password" name="password" required>
        
        <button type="submit"><?php echo $translations['login']; ?></button>
        <p class="form-link"><?php echo $translations['no_account']; ?> <a href="index.php?content=register"><?php echo $translations['register']; ?></a></p>
    </form>
</div>
