document.addEventListener("DOMContentLoaded", () => {
  // Get references to key elements
  const animeList = document.getElementById("anime-list");
  const paginationContainer = document.querySelector(".pagination");
  const contentContainer = document.querySelector(".content");

  // Set up initial responsive grid based on screen width
  setupResponsiveGrid();

  // Add event listeners to all pagination links
  setupPaginationLinks();

  // Function to set up the responsive grid
  function setupResponsiveGrid() {
    const screenWidth = window.innerWidth;
    console.log(`Screen width detected: ${screenWidth}px`);

    if (screenWidth < 320) {
      animeList.style.setProperty('grid-template-columns', 'repeat(1, 1fr)', 'important');
      console.log("Setting grid-template-columns to: repeat(1, 1fr)");
    } else if (screenWidth <= 480) {
      animeList.style.setProperty('grid-template-columns', 'repeat(2, 1fr)', 'important');
      console.log("Setting grid-template-columns to: repeat(2, 1fr)");
    } else if (screenWidth <= 768) {
      animeList.style.setProperty('grid-template-columns', 'repeat(3, 1fr)', 'important');
      console.log("Setting grid-template-columns to: repeat(3, 1fr)");
    } else if (screenWidth <= 1300) {
      animeList.style.setProperty('grid-template-columns', 'repeat(4, 1fr)', 'important');
      console.log("Setting grid-template-columns to: repeat(4, 1fr)");
    } else {
      animeList.style.setProperty('grid-template-columns', 'repeat(5, 1fr)', 'important');
      console.log("Setting grid-template-columns to: repeat(5, 1fr)");
    }

    console.log(`Grid-template-columns now set to: ${animeList.style.gridTemplateColumns}`);
  }

  // Function to set up pagination links with AJAX
  function setupPaginationLinks() {
    // Get all pagination links
    const links = document.querySelectorAll(".pagination a");

    // Add click event listener to each link
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

  // Function to show loading indicator
  function showLoadingIndicator() {
    // Create loading overlay if it doesn't exist
    if (!document.getElementById("loading-overlay")) {
      const loadingOverlay = document.createElement("div");
      loadingOverlay.id = "loading-overlay";
      loadingOverlay.innerHTML = `
        <div class="loading-spinner"></div>
        <p>Loading...</p>
      `;
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

  // Function to fetch page content via AJAX
  function fetchPageContent(page) {
    // Create a new XMLHttpRequest
    const xhr = new XMLHttpRequest();

    // Configure it to get the page content
    xhr.open("GET", `index.php?page=${page}&ajax=true`, true);

    // Set up the callback for when the request completes
    xhr.onload = () => {
      if (xhr.status === 200) {
        try {
          // Parse the response
          const response = JSON.parse(xhr.responseText);

          // Update the anime list with new content
          updateAnimeList(response.animes);

          // Update the pagination links
          updatePagination(response.pagination);

          // Hide loading indicator
          hideLoadingIndicator();

          // Update URL without refreshing the page
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

  // Function to update the anime list with new content
  function updateAnimeList(animes) {
    // Clear the current list
    animeList.innerHTML = "";

    // Add each anime to the list
    animes.forEach((anime) => {
      const animeItem = document.createElement("div");
      animeItem.className = "anime-item";

      animeItem.innerHTML = `
        <div class="anime-card">
          <img src="${anime.image_url}" alt="${anime.name}" class="anime-img">
          <h2>${anime.name}</h2>
        </div>
      `;

      animeList.appendChild(animeItem);
    });
  }

  // Function to update pagination links
  function updatePagination(paginationHtml) {
    // Update the pagination container with new HTML
    paginationContainer.innerHTML = paginationHtml;

    // Re-attach event listeners to the new pagination links
    setupPaginationLinks();
  }

  // Handle browser back/forward buttons
  window.addEventListener("popstate", (e) => {
    // Get the page from the URL
    const urlParams = new URLSearchParams(window.location.search);
    const page = urlParams.get("page") || 1;

    // Fetch the content for this page
    fetchPageContent(page);
  });

  // Update grid on window resize
  window.addEventListener("resize", () => {
    console.log('Resizing window:', window.innerWidth);
    setupResponsiveGrid();
  });
});
