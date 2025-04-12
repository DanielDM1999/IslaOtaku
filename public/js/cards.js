function getCardsPerRow() {
    const container = document.querySelector(".anime-list")
    if (!container) return 4 // Default fallback
  
    const containerWidth = container.offsetWidth
  
    // Use the same breakpoints as in your CSS
    if (containerWidth <= 480) {
      return Math.floor(containerWidth / 150) // For mobile
    } else if (containerWidth <= 768) {
      return Math.floor(containerWidth / 200) // For tablet
    } else {
      return Math.floor(containerWidth / 250) // For desktop
    }
  }
  
  // Calculate cards per row on page load
  document.addEventListener("DOMContentLoaded", () => {
    const cardsPerRow = getCardsPerRow()
    console.log("Cards per row:", cardsPerRow)
  
    // Update URL with the calculated value
    const urlParams = new URLSearchParams(window.location.search)
    const currentPage = urlParams.get("page") || 1
  
    urlParams.set("cardsPerRow", cardsPerRow)
  
    // Keep the current page when updating cardsPerRow
    if (currentPage) {
      urlParams.set("page", currentPage)
    }
  
    const newUrl = window.location.pathname + "?" + urlParams.toString()
    window.history.replaceState({}, "", newUrl)
  })
  
  // Recalculate on window resize
  window.addEventListener("resize", () => {
    // Use debounce to avoid excessive calculations during resize
    clearTimeout(window.resizeTimer)
    window.resizeTimer = setTimeout(() => {
      const newCardsPerRow = getCardsPerRow()
      const urlParams = new URLSearchParams(window.location.search)
      const currentCardsPerRow = Number.parseInt(urlParams.get("cardsPerRow") || 4)
  
      // Only update if the number of cards per row has changed
      if (newCardsPerRow !== currentCardsPerRow) {
        // Keep the same page when possible
        const currentPage = Number.parseInt(urlParams.get("page") || 1)
  
        urlParams.set("cardsPerRow", newCardsPerRow)
        urlParams.set("page", currentPage)
  
        const newUrl = window.location.pathname + "?" + urlParams.toString()
        window.location.href = newUrl // Reload to adjust items per page
      }
    }, 250)
  })
  