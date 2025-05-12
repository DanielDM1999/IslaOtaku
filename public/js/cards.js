document.addEventListener("DOMContentLoaded", () => {
  const animeList = document.getElementById("anime-list");
  const paginationContainer = document.querySelector(".pagination");
  const contentContainer = document.querySelector(".content");

  // Adjust grid layout based on screen size
  setupResponsiveGrid();

  // Handle pagination link clicks
  paginationContainer.addEventListener("click", function (e) {
    if (e.target && e.target.matches("a.pagination-link")) {
      e.preventDefault(); 

      // Extract page number from the href attribute
      const href = e.target.getAttribute("href");
      const pageMatch = href.match(/page=(\d+)/);
      const page = pageMatch ? pageMatch[1] : 1;

      // Fetch and update content for the clicked page
      fetchPageContent(page);
    }
  });

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

  function fetchPageContent(page) {
    // Display a loading indicator while content is being fetched
    showLoadingIndicator();

    const xhr = new XMLHttpRequest();

    // Send a GET request to the server to fetch the page content
    xhr.open("GET", `index.php?page=${page}&ajax=true`, true);

    xhr.onload = () => {
      if (xhr.status === 200) {
        try {
          // Parse the JSON response
          const response = JSON.parse(xhr.responseText);

          // Update the anime list with new data
          updateAnimeList(response.animes);

          // Update the pagination controls
          updatePagination(response.pagination);

          // Hide the loading indicator
          hideLoadingIndicator();

          // Update the browser history to reflect the current page
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

    xhr.onerror = () => {
      console.error("Network error occurred");
      hideLoadingIndicator();
    };

    xhr.send();
  }

  function updateAnimeList(animes) {
    // Clear the current anime list
    animeList.innerHTML = "";

    // Populate the anime list with new items
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

  function updatePagination(paginationHtml) {
    // Update the pagination container with new controls
    paginationContainer.innerHTML = paginationHtml;

    // Reattach event listeners to pagination links
    setupPaginationLinks();
  }

  function setupPaginationLinks() {
    // Attach click event listeners to all pagination links
    const links = document.querySelectorAll(".pagination a");

    links.forEach((link) => {
      link.addEventListener("click", function (e) {
        e.preventDefault();

        // Show loading indicator during navigation
        showLoadingIndicator();

        // Extract page number from href and fetch the corresponding page
        const href = this.getAttribute("href");
        const pageMatch = href.match(/page=(\d+)/);
        const page = pageMatch ? pageMatch[1] : 1;

        fetchPageContent(page);
      });
    });
  }

  // Handle browser back/forward navigation
  window.addEventListener("popstate", (e) => {
    const urlParams = new URLSearchParams(window.location.search);
    const page = urlParams.get("page") || 1;
    fetchPageContent(page);
  });

  // Adjust grid layout when the window is resized
  window.addEventListener("resize", () => {
    setupResponsiveGrid();
  });
});
