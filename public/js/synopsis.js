document.addEventListener("DOMContentLoaded", () => {
    const toggleButton = document.querySelector(".toggle-synopsis")
    const synopsisWrapper = document.querySelector(".synopsis-wrapper")
    const toggleText = document.querySelector(".toggle-text")
    const synopsisShort = document.querySelector(".synopsis-short")
    const synopsisFull = document.querySelector(".synopsis-full")
  
    if (toggleButton && synopsisWrapper && synopsisShort && synopsisFull) {
      const getFullHeight = () => {
        const originalDisplay = synopsisFull.style.display
        synopsisFull.style.position = "static"
        synopsisFull.style.visibility = "hidden"
        synopsisFull.style.display = "block"
  
        const fullHeight = synopsisFull.offsetHeight
  
        synopsisFull.style.display = originalDisplay
        synopsisFull.style.position = "absolute"
        synopsisFull.style.visibility = "hidden"
  
        return fullHeight
      }
  
      toggleButton.addEventListener("click", () => {
        const isExpanded = toggleButton.classList.toggle("expanded")
  
        if (isExpanded) {
          const startHeight = synopsisWrapper.offsetHeight
          const fullHeight = getFullHeight() + 10 
          synopsisWrapper.style.height = startHeight + "px"
          synopsisWrapper.offsetHeight
          synopsisWrapper.style.height = fullHeight + "px"
  
          setTimeout(() => {
            synopsisShort.style.display = "none"
            synopsisFull.style.display = "block"
            synopsisFull.style.position = "static"
            synopsisFull.style.visibility = "visible"
            synopsisFull.style.opacity = "1"
  
            synopsisWrapper.style.height = fullHeight + "px"
          }, 150) 
  
          toggleText.textContent = "Show Less"
  
          setTimeout(() => {
            const toggleButtonBottom = toggleButton.getBoundingClientRect().bottom
            const windowHeight = window.innerHeight
  
            if (toggleButtonBottom > windowHeight) {
              window.scrollBy({
                top: toggleButtonBottom - windowHeight + 40,
                behavior: "smooth",
              })
            }
          }, 300)
        } else {
          synopsisShort.style.display = "block"
          synopsisFull.style.display = "none"
          synopsisFull.style.position = "absolute"
          synopsisFull.style.visibility = "hidden"
  
          synopsisWrapper.style.height = "50px" 
  
          toggleText.textContent = "Show More"
        }
      })
    }
  })
  