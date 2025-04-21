document.addEventListener("DOMContentLoaded", () => {
  // Get references to key elements
  const animeList = document.getElementById("anime-list");
  const paginationContainer = document.querySelector(".pagination");
  const contentContainer = document.querySelector(".content");

  // Set up initial responsive grid based on screen width
  setupResponsiveGrid();

  // Add event listeners to the pagination container using event delegation
  paginationContainer.addEventListener("click", function (e) {
    // Check if the clicked element is a pagination link
    if (e.target && e.target.matches("a.pagination-link")) {
      e.preventDefault(); // Prevent default link behavior

      // Get the page number from the href attribute of the clicked link
      const href = e.target.getAttribute("href");
      const pageMatch = href.match(/page=(\d+)/);
      const page = pageMatch ? pageMatch[1] : 1;

      // Fetch new page content via AJAX
      fetchPageContent(page);
    }
  });

  // Function to set up the responsive grid
  function setupResponsiveGrid() {
    const screenWidth = window.innerWidth;

    if (screenWidth < 320) {
      animeList.style.setProperty('grid-template-columns', 'repeat(1, 1fr)', 'important');
    } else if (screenWidth <= 480) {
      animeList.style.setProperty('grid-template-columns', 'repeat(2, 1fr)', 'important');
    } else if (screenWidth <= 768) {
      animeList.style.setProperty('grid-template-columns', 'repeat(3, 1fr)', 'important');
    } else if (screenWidth <= 1300) {
      animeList.style.setProperty('grid-template-columns', 'repeat(4, 1fr)', 'important');
    } else {
      animeList.style.setProperty('grid-template-columns', 'repeat(5, 1fr)', 'important');
    }
  }

  // Function to fetch page content via AJAX
  function fetchPageContent(page) {
    // Show loading indicator
    showLoadingIndicator();

    // Create a new XMLHttpRequest
    const xhr = new XMLHttpRequest();

    // Configure it to get the page content
    xhr.open("GET", `index.php?page=${page}&ajax=true`, true);

    // Set up the callback for when the request completes
    xhr.onload = () => {
      if (xhr.status === 200) {
        try {
          // Parse the response (assuming JSON response)
          const response = JSON.parse(xhr.responseText);

          // Update the anime list with new content
          updateAnimeList(response.animes);

          // Update the pagination links with the new HTML
          updatePagination(response.pagination);

          // Hide loading indicator
          hideLoadingIndicator();

          // Update the URL to reflect the new page without refreshing the page
          window.history.pushState({ page: page }, `Page ${page}`, `?page=${page}`);
        } catch (e) {
          console.error("Error parsing JSON response:", e);
          hideLoadingIndicator();
        }
      } else {
        console.error("Request failed. Status:", xhr.status);
        hideLoadingIndicator();
      }
    };

    // Handle network errors
    xhr.onerror = () => {
      console.error("Network error occurred");
      hideLoadingIndicator();
    };

    // Send the request
    xhr.send();
  }

  // Function to show loading indicator
  function showLoadingIndicator() {
    if (!document.getElementById("loading-overlay")) {
      const loadingOverlay = document.createElement("div");
      loadingOverlay.id = "loading-overlay";
      loadingOverlay.innerHTML = `<div class="loading-spinner"></div><p>Loading...</p>`;
      contentContainer.appendChild(loadingOverlay);
    } else {
      document.getElementById("loading-overlay").style.display = "flex";
    }
  }

  // Function to hide loading indicator
  function hideLoadingIndicator() {
    const loadingOverlay = document.getElementById("loading-overlay");
    if (loadingOverlay) {
      loadingOverlay.style.display = "none";
    }
  }

  // Function to update the anime list with new content
  function updateAnimeList(animes) {
    // Clear the current list (empty the container)
    animeList.innerHTML = "";

    // Add each anime to the list by creating new elements
    animes.forEach((anime) => {
      const animeItem = document.createElement("div");
      animeItem.className = "anime-item";  // You can style this class as you wish
      animeItem.innerHTML = `
        <div class="anime-card">
          <img src="${anime.image_url}" alt="${anime.name}" class="anime-img">
          <h2>${anime.name}</h2>
        </div>
      `;
      animeList.appendChild(animeItem);  // Add the newly created anime item to the list
    });
  }

  // Function to update pagination links
  function updatePagination(paginationHtml) {
    // Update the pagination container with new HTML
    paginationContainer.innerHTML = paginationHtml;

    // Re-attach event listeners to the new pagination links
    setupPaginationLinks();
  }

  // Function to attach event listeners to pagination links after content is updated
  function setupPaginationLinks() {
    const links = document.querySelectorAll(".pagination a");

    links.forEach((link) => {
      link.addEventListener("click", function (e) {
        e.preventDefault(); // Prevent default link behavior

        // Show loading indicator
        showLoadingIndicator();

        // Get the page number from the href
        const href = this.getAttribute("href");
        const pageMatch = href.match(/page=(\d+)/);
        const page = pageMatch ? pageMatch[1] : 1;

        // Fetch the new content via AJAX
        fetchPageContent(page);
      });
    });
  }

  // Handle browser back/forward buttons
  window.addEventListener("popstate", (e) => {
    const urlParams = new URLSearchParams(window.location.search);
    const page = urlParams.get("page") || 1;
    fetchPageContent(page);
  });

  // Update grid on window resize
  window.addEventListener("resize", () => {
    setupResponsiveGrid();
  });
});
