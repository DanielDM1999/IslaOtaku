document.addEventListener("DOMContentLoaded", () => {
  // Select the required DOM elements
  const toggleButton = document.querySelector(".toggle-synopsis");
  const synopsisWrapper = document.querySelector(".synopsis-wrapper");
  const toggleText = document.querySelector(".toggle-text");
  const synopsisShort = document.querySelector(".synopsis-short");
  const synopsisFull = document.querySelector(".synopsis-full");

  // Proceed only if all required elements exist
  if (toggleButton && synopsisWrapper && synopsisShort && synopsisFull) {

    // Function to calculate the full height of the synopsis content
    const getFullHeight = () => {
      const originalDisplay = synopsisFull.style.display;
      synopsisFull.style.position = "static";
      synopsisFull.style.visibility = "hidden";
      synopsisFull.style.display = "block";

      const fullHeight = synopsisFull.offsetHeight;

      // Restore original display settings
      synopsisFull.style.display = originalDisplay;
      synopsisFull.style.position = "absolute";
      synopsisFull.style.visibility = "hidden";

      return fullHeight;
    };

    // Toggle button click event
    toggleButton.addEventListener("click", () => {
      const isExpanded = toggleButton.classList.toggle("expanded");

      if (isExpanded) {
        // Expand the synopsis and adjust height for smooth transition
        const startHeight = synopsisWrapper.offsetHeight;
        const fullHeight = getFullHeight() + 10;  // Add extra space for smooth animation
        synopsisWrapper.style.height = startHeight + "px"; // Start from the current height
        synopsisWrapper.offsetHeight; // Force a reflow to trigger the transition
        synopsisWrapper.style.height = fullHeight + "px"; // Animate to full height

        setTimeout(() => {
          // Show the full synopsis after the transition
          synopsisShort.style.display = "none";
          synopsisFull.style.display = "block";
          synopsisFull.style.position = "static";
          synopsisFull.style.visibility = "visible";
          synopsisFull.style.opacity = "1";

          // Final height adjustment after the transition
          synopsisWrapper.style.height = fullHeight + "px";
        }, 150); // Delay the change to allow the animation to complete

        toggleText.textContent = "Show Less"; // Change button text to "Show Less"

        // Scroll the page to ensure the toggle button is in view after expansion
        setTimeout(() => {
          const toggleButtonBottom = toggleButton.getBoundingClientRect().bottom;
          const windowHeight = window.innerHeight;

          if (toggleButtonBottom > windowHeight) {
            window.scrollBy({
              top: toggleButtonBottom - windowHeight + 40,
              behavior: "smooth",
            });
          }
        }, 300); // Delay the scroll to allow expansion animation to finish
      } else {
        // Collapse the synopsis and reset to original state
        synopsisShort.style.display = "block";
        synopsisFull.style.display = "none";
        synopsisFull.style.position = "absolute";
        synopsisFull.style.visibility = "hidden";

        // Set the height back to a fixed small value
        synopsisWrapper.style.height = "50px"; 

        toggleText.textContent = "Show More"; // Change button text to "Show More"
      }
    });
  }
});
