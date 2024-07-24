document.addEventListener("DOMContentLoaded", function () {
    // Wait for the DOM content to be fully loaded
    setTimeout(function () {
        // Select elements
        const brandLogo = document.querySelector('.brand-logo img');
        const preloader = document.querySelector('.preloader');

        // Scale up the brand logo smoothly
        brandLogo.style.transition = 'transform 2s ease-in-out';
        brandLogo.style.transform = 'scale(30)'; // Adjust the scale for smooth gliding

        // Hide the preloader after the fade out animation completes
        setTimeout(function () {
            preloader.style.display = 'none';
        }, 2000); // Adjust the duration before the animation starts as needed
    }, 2000); // Adjust the duration before the animation starts as needed
});

window.onload = function () {
    // Trigger fade out animation for the preloader
    const preloader = document.querySelector('.preloader');
    preloader.classList.add('fade-out');
};



/* SLIDESHOW */
document.addEventListener("DOMContentLoaded", function () {
    const slides1 = document.querySelectorAll(".slideshow-container1 .slide");
    const slides2 = document.querySelectorAll(".slideshow-container2 .slide");
    let currentSlide1 = 0;
    let currentSlide2 = 0;

    function showSlide(slides, index) {
        slides.forEach((slide, i) => {
            slide.style.display = i === index ? "block" : "none";
            slide.style.opacity = i === index ? "1" : "0.8";
        });
    }

    function nextSlide1() {
        currentSlide1 = (currentSlide1 + 1) % slides1.length;
        showSlide(slides1, currentSlide1);
    }

    function nextSlide2() {
        currentSlide2 = (currentSlide2 + 1) % slides2.length;
        showSlide(slides2, currentSlide2);
    }

    // Initialize slides
    showSlide(slides1, currentSlide1);
    showSlide(slides2, currentSlide2);

    // Set intervals for slide transitions
    setInterval(nextSlide1, 3500); // Change every 5 seconds
    setInterval(nextSlide2, 3500);
});


document.addEventListener('DOMContentLoaded', function () {
    const testimonialInner = document.querySelector('.testimonial-inner');
    const testimonials = document.querySelectorAll('.testimonial-item');
    let currentIndex = 0;
    const totalItems = testimonials.length;

    // Clone the first item and append it to the end
    const firstClone = testimonials[0].cloneNode(true);
    testimonialInner.appendChild(firstClone);

    function autoScroll() {
        currentIndex++;
        testimonialInner.style.transition = 'transform 0.5s ease-in-out';
        testimonialInner.style.transform = `translateX(-${currentIndex * 100}%)`;

        if (currentIndex === totalItems) {
            setTimeout(() => {
                testimonialInner.style.transition = 'none';
                testimonialInner.style.transform = `translateX(0)`;
                currentIndex = 0;
            }, 500);
        }
    }

    setInterval(autoScroll, 3000); // Change slide every 3 seconds
});

