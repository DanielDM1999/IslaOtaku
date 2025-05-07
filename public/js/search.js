document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("anime-search")
    const searchButton = document.getElementById("search-button")
    const animeList = document.getElementById("anime-list")
    const pagination = document.getElementById("pagination")
  
    // Debounce function to limit how often the search is triggered
    function debounce(func, wait) {
      let timeout
      return function (...args) {
        clearTimeout(timeout)
        timeout = setTimeout(() => func.apply(this, args), wait)
      }
    }
  
    // Function to perform the search
    function performSearch() {
      const query = searchInput.value.trim()
  
      // If search is empty, reload the page to show all anime
      if (query === "") {
        window.location.href = "index.php"
        return
      }
  
      // Show loading state
      if (animeList.children.length === 0 || !animeList.querySelector(".loading")) {
        animeList.innerHTML = '<div class="loading">Searching...</div>'
      }
      pagination.style.display = "none"
  
      // Make AJAX request
      fetch(`index.php?action=search&query=${encodeURIComponent(query)}`)
        .then((response) => response.json())
        .then((data) => {
          // Clear the loading state
          animeList.innerHTML = ""
  
          if (data.length === 0) {
            animeList.innerHTML = '<div class="no-results">No anime found matching your search.</div>'
            return
          }
  
          // Display the results
          data.forEach((anime) => {
            const animeItem = document.createElement("a")
            animeItem.className = "anime-item"
            animeItem.href = `index.php?content=animeDetails&id=${anime.anime_id}`
            animeItem.innerHTML = `
              <div class="anime-card">
                  <img src="${anime.image_url}" alt="${anime.name}" class="anime-img">
                  <h2>${anime.name}</h2>
              </div>
            `
            animeList.appendChild(animeItem)
          })
        })
        .catch((error) => {
          console.error("Error:", error)
          animeList.innerHTML = '<div class="error">An error occurred while searching. Please try again.</div>'
        })
    }
  
    // Debounced search function that triggers as user types
    const debouncedSearch = debounce(performSearch, 300)
  
    // Search when typing in the input
    searchInput.addEventListener("input", debouncedSearch)
  
    // Search when the button is clicked
    searchButton.addEventListener("click", performSearch)
  })
  