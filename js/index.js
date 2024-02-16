    /* PRELOADER */

document.addEventListener("DOMContentLoaded", function () {
    // Wait for the DOM content to be fully loaded
    setTimeout(function () {
        // Select elements
        const brandLogo = document.querySelector('.brand-logo img');
        const preloader = document.querySelector('.preloader');

        // Scale up the brand logo smoothly
        brandLogo.style.transition = 'transform 2s ease-in-out';
        brandLogo.style.transform = 'scale(100)'; // Adjust the scale for smooth gliding



        // Trigger fade out animation for the preloader
        preloader.classList.add('fade-out');

        // Hide the preloader after the fade out animation completes
        setTimeout(function () {
            preloader.style.display = 'none';
        }, 1700); // Adjust the duration of fade out animation as needed
    }, 2000); // Adjust the duration before the animation starts as needed
});

    /* SLIDESHOW */

    // Wait for the DOM content to load before executing JavaScript
    document.addEventListener("DOMContentLoaded", function() {
        // Define variables for slideshows
        var slideshows = document.querySelectorAll('.slideshow-container1, .slideshow-container2');
        
        // Iterate through each slideshow
        slideshows.forEach(function(slideshow) {
            var slides = slideshow.querySelectorAll('.slide'); // Get all slides within the current slideshow
            var slideIndex = 0; // Set the initial slide index
            
            // Show the first slide initially
            slides[slideIndex].style.display = "block";
            slides[slideIndex].style.opacity = 1;

            // Define the function to display the next slide
            function showNextSlide() {
                // Hide the current slide
                slides[slideIndex].style.opacity = 0;
                setTimeout(function() {
                    slides[slideIndex].style.display = "none";
                    // Increment the slide index
                    slideIndex = (slideIndex + 1) % slides.length;
                    // Show the next slide
                    slides[slideIndex].style.display = "block";
                    slides[slideIndex].style.opacity = 1;
                }, 1000); // Adjust the transition duration here (in milliseconds)
            }

            // Set an interval to automatically switch slides
            setInterval(showNextSlide, 2000); // Adjust the interval duration here (in milliseconds)
        });
    });
