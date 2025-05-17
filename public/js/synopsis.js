document.addEventListener("DOMContentLoaded", () => {
  // Select necessary elements for toggling the synopsis
  const toggleButton = document.querySelector(".toggle-synopsis");
  const synopsisWrapper = document.querySelector(".synopsis-wrapper");
  const toggleText = document.querySelector(".toggle-text");
  const synopsisShort = document.querySelector(".synopsis-short");
  const synopsisFull = document.querySelector(".synopsis-full");

  if (toggleButton && synopsisWrapper && synopsisShort && synopsisFull) {
      // Function to calculate the height of the full synopsis when hidden
      const getFullHeight = () => {
          const originalDisplay = synopsisFull.style.display;

          // Temporarily show full synopsis off-screen to get height
          synopsisFull.style.position = "static";
          synopsisFull.style.visibility = "hidden";
          synopsisFull.style.display = "block";

          const fullHeight = synopsisFull.offsetHeight;

          // Revert styles back to hidden state
          synopsisFull.style.display = originalDisplay;
          synopsisFull.style.position = "absolute";
          synopsisFull.style.visibility = "hidden";

          return fullHeight;
      };

      // Handle toggle button click to expand/collapse synopsis
      toggleButton.addEventListener("click", () => {
          const isExpanded = toggleButton.classList.toggle("expanded");

          if (isExpanded) {
              // Expand synopsis: animate height and swap short/full text
              const startHeight = synopsisWrapper.offsetHeight;
              const fullHeight = getFullHeight() + 10;
              synopsisWrapper.style.height = `${startHeight}px`;
              synopsisWrapper.offsetHeight; // force reflow
              synopsisWrapper.style.height = `${fullHeight}px`;

              setTimeout(() => {
                  synopsisShort.style.display = "none";
                  synopsisFull.style.display = "block";
                  synopsisFull.style.position = "static";
                  synopsisFull.style.visibility = "visible";
                  synopsisFull.style.opacity = "1";
                  synopsisWrapper.style.height = `${fullHeight}px`;
              }, 150);

              // Update toggle text to "Show Less"
              toggleText.textContent = translations['show_less'] || "Show Less";

              // Scroll page if toggle button is out of view
              setTimeout(() => {
                  const toggleButtonBottom = toggleButton.getBoundingClientRect().bottom;
                  const windowHeight = window.innerHeight;

                  if (toggleButtonBottom > windowHeight) {
                      window.scrollBy({
                          top: toggleButtonBottom - windowHeight + 40,
                          behavior: "smooth",
                      });
                  }
              }, 300);
          } else {
              // Collapse synopsis: show short text and reset height
              synopsisShort.style.display = "block";
              synopsisFull.style.display = "none";
              synopsisFull.style.position = "absolute";
              synopsisFull.style.visibility = "hidden";
              synopsisWrapper.style.height = "70px";

              // Update toggle text to "Show More"
              toggleText.textContent = translations['show_more'] || "Show More";
          }
      });
  }
});
