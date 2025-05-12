document.addEventListener('DOMContentLoaded', function() {
    // Tab switching
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanes = document.querySelectorAll('.tab-pane');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            
            // Remove active class from all buttons and panes
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('active'));
            
            // Add active class to current button and pane
            this.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        });
    });
    
    // Profile editing
    const editButton = document.getElementById('edit-profile-btn');
    const cancelButton = document.getElementById('cancel-edit-btn');
    const formActions = document.querySelector('.form-actions');
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    let originalName, originalEmail;
    
    if (editButton) {
        editButton.addEventListener('click', function() {
            // Save original values
            originalName = nameInput.value;
            originalEmail = emailInput.value;
            
            // Enable editing
            nameInput.readOnly = false;
            emailInput.readOnly = false;
            nameInput.classList.add('editing');
            emailInput.classList.add('editing');
            
            // Show form actions
            formActions.style.display = 'flex';
            editButton.style.display = 'none';
        });
    }
    
    if (cancelButton) {
        cancelButton.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Restore original values
            nameInput.value = originalName;
            emailInput.value = originalEmail;
            
            // Disable editing
            nameInput.readOnly = true;
            emailInput.readOnly = true;
            nameInput.classList.remove('editing');
            emailInput.classList.remove('editing');
            
            // Hide form actions
            formActions.style.display = 'none';
            editButton.style.display = 'block';
        });
    }
    
    // Profile picture upload
    const profilePictureContainer = document.querySelector('.profile-picture-container');
    const profilePictureInput = document.getElementById('profile_picture');
    const profileImagePreview = document.getElementById('profile-image-preview');
    const pictureActions = document.getElementById('picture-actions');
    const cancelPictureBtn = document.getElementById('cancel-picture-btn');
    const pictureForm = document.getElementById('picture-form');
    const confirmPictureBtn = document.querySelector('.confirm-picture-button');
    let originalImageSrc;
    
    if (profilePictureContainer && profilePictureInput) {
        // Click on the profile picture container triggers file input
        profilePictureContainer.addEventListener('click', function() {
            profilePictureInput.click();
        });
        
        // Handle file selection
        profilePictureInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                // Save original image source
                originalImageSrc = profileImagePreview.src;
                
                // Create a preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    profileImagePreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
                
                // Show action buttons
                pictureActions.style.display = 'flex';
            }
        });
        
        // Cancel picture change
        if (cancelPictureBtn) {
            cancelPictureBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Restore original image
                profileImagePreview.src = originalImageSrc;
                
                // Reset file input
                profilePictureInput.value = '';
                
                // Hide action buttons
                pictureActions.style.display = 'none';
            });
        }
        
        // Ensure form submits properly
        if (pictureForm) {
            pictureForm.addEventListener('submit', function(e) {
                // Don't prevent default - let the form submit normally
                console.log('Form is submitting with file:', profilePictureInput.files[0]?.name);
                
                // You can add validation here if needed
                if (profilePictureInput.files.length === 0) {
                    e.preventDefault();
                    alert('Please select a file first.');
                    return false;
                }
            });
        }
    }
});