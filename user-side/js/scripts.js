/*!
* Start Bootstrap - Creative v7.0.7 (https://startbootstrap.com/theme/creative)
* Copyright 2013-2023 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-creative/blob/master/LICENSE)
?*/

// Global event listener for DOMContentLoaded
window.addEventListener('DOMContentLoaded', event => {
    // Navbar shrink functionality
    const navbarShrink = () => {
        const navbarCollapsible = document.body.querySelector('#mainNav');
        if (!navbarCollapsible) return;

        if (window.scrollY === 0) {
            navbarCollapsible.classList.remove('navbar-shrink');
        } else {
            navbarCollapsible.classList.add('navbar-shrink');
        }

    };

    // Shrink navbar on load and scroll
    navbarShrink();


    document.addEventListener('scroll', navbarShrink);

    // Activate ScrollSpy
    const mainNav = document.body.querySelector('#mainNav');
    if (mainNav) {
        new bootstrap.ScrollSpy(document.body, {
            target: '#mainNav',
            rootMargin: '0px 0px -40%',
        });
    }

    // Responsive nav link collapse
    const navbarToggler = document.body.querySelector('.navbar-toggler');
    const responsiveNavItems = [].slice.call(
        document.querySelectorAll('#navbarResponsive .nav-link')
    );
    responsiveNavItems.forEach(responsiveNavItem => {
        responsiveNavItem.addEventListener('click', () => {
            if (window.getComputedStyle(navbarToggler).display !== 'none') {
                navbarToggler.click();
            }
        });
    });

    // Initialize SimpleLightbox for portfolio links
    new SimpleLightbox({
        elements: '#portfolio a.portfolio-box'
    });
});

// Login form validation
document.addEventListener('DOMContentLoaded', () => {
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const loginButton = document.querySelector('.login-btn');

    if (loginButton) {
        loginButton.addEventListener('click', event => {
            if (emailInput.value.trim() === '' || passwordInput.value.trim() === '') {
                alert('Please fill in all fields.');
                event.preventDefault();
            }
        });
    }
});

// Toggle post form visibility
const createPostButton = document.getElementById('createPostButton');
const postForm = document.getElementById('postForm');

if (createPostButton && postForm) {
    createPostButton.addEventListener('click', () => {
        postForm.style.display = postForm.style.display === 'none' ? 'block' : 'none';
    });
}

// Navbar scroll effect
$(window).scroll(() => {
    if ($(window).scrollTop() > 100) {
        $('.navbar').addClass('scrolled');
    } else {
        $('.navbar').removeClass('scrolled');
    }
});

// Profile popup toggle
const profileButton = document.getElementById('profileButton');
const profilePopup = document.getElementById('profilePopup');

if (profileButton && profilePopup) {
    profileButton.addEventListener('click', () => {
        profilePopup.style.display = profilePopup.style.display === 'none' ? 'block' : 'none';
    });
}

// Signup form password match validation
document.addEventListener('DOMContentLoaded', () => {
    const signupForm = document.getElementById('signupForm');
    if (signupForm) {
        signupForm.addEventListener('submit', event => {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (password !== confirmPassword) {
                alert('Passwords do not match!');
                event.preventDefault();
            }
        });
    }
});
