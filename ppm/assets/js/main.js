// JavaScript for mobile menu toggle and theme switching

document.addEventListener('DOMContentLoaded', function () {
    // Mobile menu toggle
    const menuButton = document.querySelector('.navbar-toggle');
    const navMenu = document.querySelector('.navbar-nav');

    if (menuButton && navMenu) {
        menuButton.addEventListener('click', function () {
            navMenu.classList.toggle('active');
        });
    }   
});
