<div class="register-form-container">
    <h1><?php echo $translations['register']; ?></h1>
    <?php if (!empty($registerError)): ?>
        <div class="error-message"><?php echo $registerError; ?></div>
    <?php endif; ?>
    <form action="index.php?content=register" method="POST">
        <label for="name"><?php echo $translations['username']; ?>:</label>
        <input type="text" id="name" name="name" class="input-field">
        <div class="error-message" id="name-error"></div>
        
        <label for="email"><?php echo $translations['email']; ?>:</label>
        <input type="text" id="email" name="email" class="input-field">
        <div class="error-message" id="email-error"></div>

        <label for="password"><?php echo $translations['password']; ?>:</label>
        <input type="password" id="password" name="password" class="input-field">
        <div class="error-message" id="password-error"></div>

        <label for="confirm_password"><?php echo $translations['confirm_password']; ?>:</label>
        <input type="password" id="confirm_password" name="confirm_password" class="input-field">
        <div class="error-message" id="confirm-password-error"></div>

        <button type="submit" class="submit-button"><?php echo $translations['register']; ?></button>
        <p class="form-link"><?php echo $translations['have_account']; ?> 
            <a href="index.php?content=login"><?php echo $translations['login']; ?></>
        </p>
    </form>
</div>