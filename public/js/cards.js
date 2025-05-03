document.addEventListener("DOMContentLoaded", () => {
  const animeList = document.getElementById("anime-list");
  const paginationContainer = document.querySelector(".pagination");
  const contentContainer = document.querySelector(".content");

  setupResponsiveGrid();

  paginationContainer.addEventListener("click", function (e) {
    if (e.target && e.target.matches("a.pagination-link")) {
      e.preventDefault(); 

      const href = e.target.getAttribute("href");
      const pageMatch = href.match(/page=(\d+)/);
      const page = pageMatch ? pageMatch[1] : 1;

      fetchPageContent(page);
    }
  });

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
      animeList.style.setProperty('grid-template-columns', 'repeat(6, 1fr)', 'important');
    }
  }

  function fetchPageContent(page) {
    showLoadingIndicator();

    const xhr = new XMLHttpRequest();

    xhr.open("GET", `index.php?page=${page}&ajax=true`, true);

    xhr.onload = () => {
      if (xhr.status === 200) {
        try {
          const response = JSON.parse(xhr.responseText);

          updateAnimeList(response.animes);

          updatePagination(response.pagination);

          hideLoadingIndicator();

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

  function hideLoadingIndicator() {
    const loadingOverlay = document.getElementById("loading-overlay");
    if (loadingOverlay) {
      loadingOverlay.style.display = "none";
    }
  }

  function updateAnimeList(animes) {
    animeList.innerHTML = "";

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
    paginationContainer.innerHTML = paginationHtml;

    setupPaginationLinks();
  }

  function setupPaginationLinks() {
    const links = document.querySelectorAll(".pagination a");

    links.forEach((link) => {
      link.addEventListener("click", function (e) {
        e.preventDefault();

        showLoadingIndicator();

        const href = this.getAttribute("href");
        const pageMatch = href.match(/page=(\d+)/);
        const page = pageMatch ? pageMatch[1] : 1;

        fetchPageContent(page);
      });
    });
  }

  window.addEventListener("popstate", (e) => {
    const urlParams = new URLSearchParams(window.location.search);
    const page = urlParams.get("page") || 1;
    fetchPageContent(page);
  });

  window.addEventListener("resize", () => {
    setupResponsiveGrid();
  });
});
