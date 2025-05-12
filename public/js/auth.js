document.addEventListener("DOMContentLoaded", () => {
  // Get the current language from the <html lang="..."> attribute
  const currentLang = document.documentElement.lang;

  // Define translations for validation messages
  const validationMessages = {
    en: {
      required_name: "Please fill in your username",
      required_email: "Please fill in your email",
      required_password: "Please fill in your password",
      required_confirm_password: "Please confirm your password",
      invalid_email: "Please enter a valid email address",
      password_length: "Password must be at least 6 characters long",
      passwords_not_match: "Passwords do not match",
    },
    es: {
      required_name: "Por favor, introduce tu nombre de usuario",
      required_email: "Por favor, introduce tu correo electrónico",
      required_password: "Por favor, introduce tu contraseña",
      required_confirm_password: "Por favor, confirma tu contraseña",
      invalid_email: "Por favor, introduce una dirección de correo electrónico válida",
      password_length: "La contraseña debe tener al menos 6 caracteres",
      passwords_not_match: "Las contraseñas no coinciden",
    },
  };

  // Select the appropriate translations based on the current language, defaulting to English
  const messages = validationMessages[currentLang] || validationMessages.en;

  // Handle login form validation
  const loginForm = document.querySelector('form[action*="content=login"]');
  if (loginForm) {
    loginForm.addEventListener("submit", (e) => {
      let hasError = false;

      // Retrieve the values from the email and password fields
      const email = document.getElementById("email").value;
      const password = document.getElementById("password").value;

      // Clear previous error messages for the login form
      document.getElementById("login-email-error").textContent = "";
      document.getElementById("login-password-error").textContent = "";

      // Check if email or password fields are empty
      if (!email || !password) {
        e.preventDefault();
        if (!email) document.getElementById("login-email-error").textContent = messages.required_email;
        if (!password) document.getElementById("login-password-error").textContent = messages.required_password;
        hasError = true;
      }

      // Check if the email format is valid
      if (!isValidEmail(email)) {
        e.preventDefault();
        document.getElementById("login-email-error").textContent = messages.invalid_email;
        hasError = true;
      }

      // Prevent form submission if there are errors
      if (hasError) return false;
    });
  }

  // Handle registration form validation
  const registerForm = document.querySelector('form[action*="content=register"]');
  if (registerForm) {
    registerForm.addEventListener("submit", (e) => {
      let hasError = false;

      // Retrieve the values from all required registration fields
      const name = document.getElementById("name").value;
      const email = document.getElementById("email").value;
      const password = document.getElementById("password").value;
      const confirmPassword = document.getElementById("confirm_password").value;

      // Clear previous error messages for the registration form
      document.getElementById("name-error").textContent = "";
      document.getElementById("email-error").textContent = "";
      document.getElementById("password-error").textContent = "";
      document.getElementById("confirm-password-error").textContent = "";

      // Check if any required fields are empty
      if (!name || !email || !password || !confirmPassword) {
        e.preventDefault();
        if (!name) document.getElementById("name-error").textContent = messages.required_name;
        if (!email) document.getElementById("email-error").textContent = messages.required_email;
        if (!password) document.getElementById("password-error").textContent = messages.required_password;
        if (!confirmPassword)
          document.getElementById("confirm-password-error").textContent = messages.required_confirm_password;
        hasError = true;
      }

      // Check if the email format is valid
      if (!isValidEmail(email)) {
        e.preventDefault();
        document.getElementById("email-error").textContent = messages.invalid_email;
        hasError = true;
      }

      // Check if the password meets the minimum length requirement
      if (password.length < 6) {
        e.preventDefault();
        document.getElementById("password-error").textContent = messages.password_length;
        hasError = true;
      }

      // Check if the password and confirmation password match
      if (password !== confirmPassword) {
        e.preventDefault();
        document.getElementById("confirm-password-error").textContent = messages.passwords_not_match;
        hasError = true;
      }

      // Prevent form submission if there are errors
      if (hasError) return false;
    });
  }

  // Helper function to validate email format
  function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }
});
