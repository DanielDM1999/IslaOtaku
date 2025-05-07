document.addEventListener("DOMContentLoaded", () => {
  // Get the current language from the html lang attribute
  const currentLang = document.documentElement.lang

  // Create an object to store translations for validation messages
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
  }

  // Get validation messages based on current language
  const messages = validationMessages[currentLang] || validationMessages.en

  // Login form validation
  const loginForm = document.querySelector('form[action*="content=login"]')
  if (loginForm) {
    loginForm.addEventListener("submit", (e) => {
      let hasError = false

      const email = document.getElementById("email").value
      const password = document.getElementById("password").value

      // Clear any previous error messages
      document.getElementById("login-email-error").textContent = ""
      document.getElementById("login-password-error").textContent = ""

      if (!email || !password) {
        e.preventDefault()
        if (!email) document.getElementById("login-email-error").textContent = messages.required_email
        if (!password) document.getElementById("login-password-error").textContent = messages.required_password
        hasError = true
      }

      if (!isValidEmail(email)) {
        e.preventDefault()
        document.getElementById("login-email-error").textContent = messages.invalid_email
        hasError = true
      }

      if (hasError) return false
    })
  }

  // Registration form validation
  const registerForm = document.querySelector('form[action*="content=register"]')
  if (registerForm) {
    registerForm.addEventListener("submit", (e) => {
      let hasError = false

      const name = document.getElementById("name").value
      const email = document.getElementById("email").value
      const password = document.getElementById("password").value
      const confirmPassword = document.getElementById("confirm_password").value

      // Clear any previous error messages
      document.getElementById("name-error").textContent = ""
      document.getElementById("email-error").textContent = ""
      document.getElementById("password-error").textContent = ""
      document.getElementById("confirm-password-error").textContent = ""

      if (!name || !email || !password || !confirmPassword) {
        e.preventDefault()
        if (!name) document.getElementById("name-error").textContent = messages.required_name
        if (!email) document.getElementById("email-error").textContent = messages.required_email
        if (!password) document.getElementById("password-error").textContent = messages.required_password
        if (!confirmPassword)
          document.getElementById("confirm-password-error").textContent = messages.required_confirm_password
        hasError = true
      }

      if (!isValidEmail(email)) {
        e.preventDefault()
        document.getElementById("email-error").textContent = messages.invalid_email
        hasError = true
      }

      if (password.length < 6) {
        e.preventDefault()
        document.getElementById("password-error").textContent = messages.password_length
        hasError = true
      }

      if (password !== confirmPassword) {
        e.preventDefault()
        document.getElementById("confirm-password-error").textContent = messages.passwords_not_match
        hasError = true
      }

      if (hasError) return false
    })
  }

  function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    return emailRegex.test(email)
  }
})
