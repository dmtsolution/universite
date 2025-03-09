document.addEventListener('DOMContentLoaded', function() {
    console.log('Main script loaded');

    // Cache DOM elements
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const profileBtn = document.getElementById('profile-btn');
    const contentSections = document.querySelectorAll('.content-section');
    const welcomeMessage = document.getElementById('welcome-message');
    const profileSection = document.getElementById('user-profile');

    // Debug info
    console.log('Content sections found:', contentSections.length);
    console.log('Profile section exists:', !!profileSection);
    if (profileSection) {
        console.log('Profile section ID:', profileSection.id);
        console.log('Profile section classes:', profileSection.className);
    }

    // Function to show content
    function showContent(targetId) {
        console.log('Showing content for:', targetId);

        // Hide all content sections
        contentSections.forEach(section => {
            section.style.display = 'none'; // Use direct style manipulation
            section.classList.remove('active');
            console.log('Hidden section:', section.id);
        });

        // Show target section
        const targetSection = document.getElementById(targetId);
        if (targetSection) {
            targetSection.style.display = 'block'; // Use direct style manipulation
            targetSection.classList.add('active');
            console.log('Activated section:', targetId);
        } else {
            console.warn('Target section not found:', targetId);
            // Fallback to welcome message
            if (welcomeMessage) {
                welcomeMessage.style.display = 'block';
                welcomeMessage.classList.add('active');
            }
        }
    }

    // Function to toggle sidebar
    function toggleSidebar() {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
    }

    // Add click handlers for sidebar menu items
    document.querySelectorAll('.sidebar-menu li[data-target]').forEach(item => {
        item.addEventListener('click', function() {
            // Get target content ID
            const targetId = this.getAttribute('data-target');

            // Remove active class from all menu items
            document.querySelectorAll('.sidebar-menu li').forEach(li => {
                li.classList.remove('active');
            });

            // Add active class to clicked item
            this.classList.add('active');

            // Show corresponding content
            showContent(targetId);

            // Close sidebar on mobile
            if (window.innerWidth <= 768) {
                toggleSidebar();
            }
        });
    });

    // Profile button functionality - DIRECT APPROACH
    if (profileBtn && profileSection) {
        profileBtn.addEventListener('click', function(e) {
            console.log('Profile button clicked');

            // Prevent default if it's a link
            if (e.preventDefault) e.preventDefault();

            // Remove active class from all menu items
            document.querySelectorAll('.sidebar-menu li').forEach(li => {
                li.classList.remove('active');
            });

            // DIRECT APPROACH: Hide all content sections
            contentSections.forEach(section => {
                section.style.display = 'none';
                section.classList.remove('active');
            });

            // DIRECT APPROACH: Show profile section
            profileSection.style.display = 'block';
            profileSection.classList.add('active');
            console.log('Profile section should now be visible');
        });
    } else {
        console.error('Profile button or section not found:',
                     profileBtn ? 'Button found' : 'Button missing',
                     profileSection ? 'Section found' : 'Section missing');
    }

    // Sidebar toggle functionality
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', toggleSidebar);
    }

    if (overlay) {
        overlay.addEventListener('click', toggleSidebar);
    }
});