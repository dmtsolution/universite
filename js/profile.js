// This is a dedicated script for profile functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('Profile script loaded');

    // Get the profile button and profile section
    const profileBtn = document.getElementById('profile-btn');
    const profileSection = document.getElementById('user-profile');

    // Debug: Check if elements exist
    console.log('Profile button exists:', !!profileBtn);
    console.log('Profile section exists:', !!profileSection);

    if (profileBtn && profileSection) {
        // Add click event listener to profile button
        profileBtn.addEventListener('click', function(e) {
            console.log('Profile button clicked');

            // Prevent default behavior if it's a link
            e.preventDefault();

            // Hide all content sections
            const contentSections = document.querySelectorAll('.content-section');
            console.log('Found content sections:', contentSections.length);

            contentSections.forEach(section => {
                section.classList.remove('active');
                console.log('Removed active class from:', section.id);
            });

            // Show profile section
            profileSection.classList.add('active');
            console.log('Added active class to profile section');

            // Remove active class from all sidebar menu items
            document.querySelectorAll('.sidebar-menu li').forEach(li => {
                li.classList.remove('active');
            });
        });
    } else {
        console.error('Profile button or section not found');
    }
});