document.addEventListener("DOMContentLoaded", () => {
    // Login form validation
    const loginForm = document.querySelector('form[action*="content=login"]')
    if (loginForm) {
      loginForm.addEventListener("submit", (e) => {
        const email = document.getElementById("email").value
        const password = document.getElementById("password").value
  
        if (!email || !password) {
          e.preventDefault()
          alert("Please fill in all fields")
          return false
        }
  
        if (!isValidEmail(email)) {
          e.preventDefault()
          alert("Please enter a valid email address")
          return false
        }
      })
    }
  
    // Registration form validation
    const registerForm = document.querySelector('form[action*="content=register"]')
    if (registerForm) {
      registerForm.addEventListener("submit", (e) => {
        const name = document.getElementById("name").value
        const email = document.getElementById("email").value
        const password = document.getElementById("password").value
        const confirmPassword = document.getElementById("confirm_password").value
  
        if (!name || !email || !password || !confirmPassword) {
          e.preventDefault()
          alert("Please fill in all fields")
          return false
        }
  
        if (!isValidEmail(email)) {
          e.preventDefault()
          alert("Please enter a valid email address")
          return false
        }
  
        if (password.length < 6) {
          e.preventDefault()
          alert("Password must be at least 6 characters long")
          return false
        }
  
        if (password !== confirmPassword) {
          e.preventDefault()
          alert("Passwords do not match")
          return false
        }
      })
    }
  
    // Helper function to validate email
    function isValidEmail(email) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
      return emailRegex.test(email)
    }
  })
  