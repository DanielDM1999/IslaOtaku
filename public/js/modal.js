document.addEventListener("DOMContentLoaded", () => {
  // Get the elements for the "Add to My List" button, modal and overlay
  const addToListButton = document.querySelector(".add-to-list")
  const modalOverlay = document.querySelector(".modal-overlay") // Modal background (overlay)
  const modal = document.querySelector(".modal") // Modal content itself
  const closeButton = document.querySelector(".modal-close") // Close button

  // Function to open the modal
  function openModal() {
    modalOverlay.classList.add("active") // Show overlay
    modal.classList.add("active") // Show modal
  }

  // Function to close the modal
  function closeModal() {
    modalOverlay.classList.remove("active") // Hide overlay
    modal.classList.remove("active") // Hide modal
  }

  // Event listener for the "Add to My List" button
  if (addToListButton) {
    addToListButton.addEventListener("click", openModal)
  }

  // Event listener for the "Close" button in the modal
  if (closeButton) {
    closeButton.addEventListener("click", closeModal)
  }

  // Optional: Close the modal if the user clicks outside of the modal content
  if (modalOverlay) {
    modalOverlay.addEventListener("click", (event) => {
      if (event.target === modalOverlay) {
        closeModal() // Close modal if clicked outside the modal content
      }
    })
  }

  // Show notification if there's a success or error parameter in the URL
  const urlParams = new URLSearchParams(window.location.search)
  if (urlParams.has("success") || urlParams.has("error")) {
    // Create notification element
    const isSuccess = urlParams.has("success")
    const notification = document.createElement("div")
    notification.className = isSuccess ? "notification success" : "notification error"

    // Get translations if available
    const translations = window.translations || {}

    // Set notification text
    notification.textContent = isSuccess
      ? translations.anime_added_success || "Anime added to your list successfully!"
      : translations.error_occurred || "An error occurred while updating your list."

    // Add notification to the page
    document.body.appendChild(notification)

    // Remove after 3 seconds
    setTimeout(() => {
      notification.classList.add("fade-out")
      setTimeout(() => {
        if (notification.parentNode) {
          notification.parentNode.removeChild(notification)
        }
      }, 500)
    }, 3000)

    // Remove the success/error parameter from the URL without reloading the page
    const newUrl =
      window.location.pathname +
      "?" +
      urlParams
        .toString()
        .replace(/[?&]success=[^&]*/, "")
        .replace(/[?&]error=[^&]*/, "")

    window.history.replaceState({}, document.title, newUrl)
  }
})

document.addEventListener("DOMContentLoaded", () => {
  const writeReviewBtn = document.querySelector(".write-review")
  const reviewsContainer = document.getElementById("reviews-container")

  if (writeReviewBtn && reviewsContainer) {
    writeReviewBtn.addEventListener("click", () => {
      reviewsContainer.style.display = "block"
      reviewsContainer.scrollIntoView({ behavior: "smooth", block: "start" })
    })
  }
})
