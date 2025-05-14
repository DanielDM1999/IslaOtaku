document.addEventListener("DOMContentLoaded", () => {
    const animeList = document.getElementById("anime-list");
    const paginationContainer = document.querySelector(".pagination");
    const buttons = document.querySelectorAll(".toggle-btn");
    
    // Function to fetch and display anime list based on the selected category
    const fetchAndDisplayAnimeList = (category) => {
      // Remove active state from all buttons and set for the clicked one
      buttons.forEach(button => button.classList.remove("active"));
      const activeButton = document.querySelector(`.toggle-btn[data-category="${category}"]`);
      if (activeButton) {
        activeButton.classList.add("active");
      }
  
      // Fetch the list of anime based on the selected category
      fetch(`index.php?action=filterList&status=${category}`)
        .then(response => response.json())
        .then(data => {
          // Clear the current list
          animeList.innerHTML = "";
  
          // If no animes are found, show a message
          if (data.length === 0) {
            animeList.innerHTML = "<p>No animes found in this category.</p>";
            return;
          }
  
          // Populate the anime list with new items
          data.forEach(anime => {
            const animeItem = document.createElement("a");
            animeItem.href = `index.php?content=animeDetails&id=${anime.anime_id}`;
            animeItem.className = "anime-item";
            animeItem.innerHTML = `
              <div class="anime-card">
                <img src="${anime.image_url}" alt="${anime.name}" class="anime-img">
                <h2>${anime.name}</h2>
              </div>
            `;
            animeList.appendChild(animeItem);
          });

          // After populating the list, adjust the grid layout
          setupResponsiveGrid();
        })
        .catch(error => {
          console.error("Error fetching anime list:", error);
          animeList.innerHTML = "<p>Error loading anime list. Please try again later.</p>";
        });
    };

    // Set up event listeners for category buttons
    buttons.forEach(button => {
      button.addEventListener("click", () => {
        const category = button.getAttribute("data-category");
        fetchAndDisplayAnimeList(category);
      });
    });

    // Fetch and display anime list for the default category (e.g., "Watching")
    const urlParams = new URLSearchParams(window.location.search);
    const defaultCategory = urlParams.get('status') || 'Watching';
    fetchAndDisplayAnimeList(defaultCategory);

    // Adjust grid layout when the window is resized
    window.addEventListener("resize", setupResponsiveGrid);
  
    // Function to adjust grid columns based on screen width
    function setupResponsiveGrid() {
      const screenWidth = window.innerWidth;

      // Dynamically adjust grid columns based on screen width
      if (screenWidth < 320) {
        animeList.style.setProperty("grid-template-columns", "repeat(1, 1fr)", "important");
      } else if (screenWidth <= 480) {
        animeList.style.setProperty("grid-template-columns", "repeat(2, 1fr)", "important");
      } else if (screenWidth <= 768) {
        animeList.style.setProperty("grid-template-columns", "repeat(3, 1fr)", "important");
      } else if (screenWidth <= 1300) {
        animeList.style.setProperty("grid-template-columns", "repeat(4, 1fr)", "important");
      } else {
        animeList.style.setProperty("grid-template-columns", "repeat(6, 1fr)", "important");
      }
    }
});
