document.addEventListener("DOMContentLoaded", () => {
  // Toggle review form section
  const toggleReviewFormBtn = document.getElementById("toggle-review-form-btn")
  const reviewFormSection = document.getElementById("review-form-section")

  if (toggleReviewFormBtn && reviewFormSection) {
    // Check URL parameters first
    const urlParams = new URLSearchParams(window.location.search)
    const shouldShowForm =
      urlParams.has("review_success") ||
      urlParams.has("delete_success") ||
      urlParams.has("review_error") ||
      urlParams.has("show_review_form")

    // Set initial state based on URL parameters only
    if (shouldShowForm) {
      reviewFormSection.style.display = "block"
      toggleReviewFormBtn.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        ${translations.hide_review_form}
      `

      // Only scroll if we have URL parameters
      setTimeout(() => {
        reviewFormSection.scrollIntoView({ behavior: "smooth", block: "start" })
      }, 100)
    } else {
      // Always start with form closed on page load/reload
      reviewFormSection.style.display = "none"
      toggleReviewFormBtn.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
          <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
        </svg>
        ${translations.write_review}
      `
    }

    // Handle toggle button click
    toggleReviewFormBtn.addEventListener("click", (e) => {
      // Prevent any default behavior
      e.preventDefault()
      e.stopPropagation()

      // Check current display state
      const isCurrentlyVisible = reviewFormSection.style.display === "block"

      if (!isCurrentlyVisible) {
        // OPENING the form
        reviewFormSection.style.display = "block"

        // Update button text
        toggleReviewFormBtn.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
          </svg>
          ${translations.hide_review_form}
        `

        // Scroll to form
        setTimeout(() => {
          reviewFormSection.scrollIntoView({ behavior: "smooth", block: "start" })
        }, 50)
      } else {
        // CLOSING the form - Save current scroll position
        const currentScrollPosition = window.scrollY || window.pageYOffset

        // Hide the form
        reviewFormSection.style.display = "none"

        // Update button text
        toggleReviewFormBtn.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
          </svg>
          ${translations.write_review}
        `

        // Restore scroll position
        setTimeout(() => {
          window.scrollTo({
            top: currentScrollPosition,
            behavior: "auto", // Use "auto" instead of "smooth" to prevent visible scrolling
          })
        }, 0)
      }
    })
  }

  // Star rating functionality
  const starLabels = document.querySelectorAll(".star-rating label")

  starLabels.forEach((label) => {
    label.addEventListener("click", function () {
      // Update visual feedback
      const selectedRating = document.querySelector(".selected-rating")
      if (selectedRating) {
        selectedRating.textContent = this.getAttribute("data-value")
      }
    })
  })

  // Create the custom dialog
  const createDeleteDialog = () => {
    // Check if dialog already exists
    if (document.getElementById("delete-confirm-dialog")) {
      return document.getElementById("delete-confirm-overlay")
    }

    // Create dialog container
    const dialogOverlay = document.createElement("div")
    dialogOverlay.id = "delete-confirm-overlay"
    dialogOverlay.className = "dialog-overlay"

    const dialogBox = document.createElement("div")
    dialogBox.id = "delete-confirm-dialog"
    dialogBox.className = "dialog-box"

    // Create dialog content
    const dialogContent = document.createElement("div")
    dialogContent.className = "dialog-content"

    const dialogTitle = document.createElement("h3")
    dialogTitle.className = "dialog-title"
    dialogTitle.textContent = translations.confirm_deletion

    const dialogMessage = document.createElement("p")
    dialogMessage.className = "dialog-message"
    dialogMessage.textContent = translations.delete_review_confirm

    const dialogActions = document.createElement("div")
    dialogActions.className = "dialog-actions"

    const cancelButton = document.createElement("button")
    cancelButton.className = "dialog-button cancel-button"
    cancelButton.textContent = translations.cancel

    const confirmButton = document.createElement("button")
    confirmButton.className = "dialog-button confirm-button"
    confirmButton.textContent = translations.delete

    // Assemble the dialog
    dialogActions.appendChild(cancelButton)
    dialogActions.appendChild(confirmButton)

    dialogContent.appendChild(dialogTitle)
    dialogContent.appendChild(dialogMessage)
    dialogContent.appendChild(dialogActions)

    dialogBox.appendChild(dialogContent)
    dialogOverlay.appendChild(dialogBox)

    document.body.appendChild(dialogOverlay)

    return dialogOverlay
  }

  // Delete review with custom dialog
  const deleteButtons = document.querySelectorAll(".delete-review-btn")

  deleteButtons.forEach((button) => {
    button.addEventListener("click", (e) => {
      e.preventDefault()

      // Store the form that would be submitted
      const form = button.closest("form")

      // Create and show the dialog
      const dialog = createDeleteDialog()
      dialog.style.display = "flex"

      // Add animation class
      setTimeout(() => {
        dialog.classList.add("active")
      }, 10)

      // Handle dialog buttons
      const cancelButton = dialog.querySelector(".cancel-button")
      const confirmButton = dialog.querySelector(".confirm-button")

      // Close dialog function
      const closeDialog = () => {
        dialog.classList.remove("active")
        setTimeout(() => {
          dialog.style.display = "none"
        }, 300) // Match this with your CSS transition time
      }

      // Cancel button closes the dialog
      cancelButton.addEventListener("click", () => {
        closeDialog()
      })

      // Clicking outside the dialog closes it
      dialog.addEventListener("click", (e) => {
        if (e.target === dialog) {
          closeDialog()
        }
      })

      // Confirm button submits the form
      confirmButton.addEventListener("click", () => {
        closeDialog()
        if (form) {
          form.submit()
        }
      })

      // Close on escape key
      document.addEventListener("keydown", function escapeClose(e) {
        if (e.key === "Escape") {
          closeDialog()
          document.removeEventListener("keydown", escapeClose)
        }
      })
    })
  })

  // Edit review functionality
  const editButtons = document.querySelectorAll(".edit-review-btn")

  editButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const reviewId = this.getAttribute("data-review-id")
      const rating = this.getAttribute("data-rating")
      const commentElement = document.querySelector(`.review-content[data-review-id="${reviewId}"]`)

      if (commentElement) {
        const comment = commentElement.textContent.trim()

        // Populate the form with existing review data
        const ratingInput = document.querySelector(`#rating-${rating}`)
        const selectedRating = document.querySelector(".selected-rating")
        const commentTextarea = document.querySelector("#review-comment")

        if (ratingInput) ratingInput.checked = true
        if (selectedRating) selectedRating.textContent = rating
        if (commentTextarea) commentTextarea.value = comment

        // Make sure the review form is visible
        if (reviewFormSection) {
          reviewFormSection.style.display = "block"
        }

        // Scroll to the form
        if (reviewFormSection) {
          reviewFormSection.scrollIntoView({ behavior: "smooth" })
        }

        // Update button text if it exists
        if (toggleReviewFormBtn) {
          toggleReviewFormBtn.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            ${translations.hide_review_form}
          `
        }
      }
    })
  })
})
