document.addEventListener('DOMContentLoaded', () => {
    const toggleButton = document.querySelector('.toggle-synopsis');
    const synopsisText = document.querySelector('.synopsis-text');

    if (toggleButton && synopsisText) {
        toggleButton.addEventListener('click', () => {
            const fullText = synopsisText.getAttribute('data-full-synopsis');
            const isExpanded = synopsisText.classList.toggle('expanded');

            if (isExpanded) {
                synopsisText.textContent = fullText;
                toggleButton.textContent = 'Show Less';
            } else {
                synopsisText.textContent = fullText.slice(0, 150) + '...';
                toggleButton.textContent = 'Show More';
            }
        });
    }
});
