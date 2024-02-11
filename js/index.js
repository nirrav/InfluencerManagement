var slideIndex1 = 0;
var slideIndex2 = 0;

showSlides1();
showSlides2();

function showSlides1() {
    var i;
    var slides = document.querySelectorAll(".slideshow-container1 .slide");
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    slideIndex1++;
    if (slideIndex1 > slides.length) {
        slideIndex1 = 1;
    }
    slides[slideIndex1 - 1].style.display = "block";
    setTimeout(showSlides1, 1700);
}

function showSlides2() {
    var i;
    var slides = document.querySelectorAll(".slideshow-container2 .slide");
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    slideIndex2++;
    if (slideIndex2 > slides.length) {
        slideIndex2 = 1;
    }
    slides[slideIndex2 - 1].style.display = "block";
    setTimeout(showSlides2, 1700);
}

document.addEventListener("DOMContentLoaded", function () {
    // Wait for the DOM content to be fully loaded
    setTimeout(function () {
        // Select elements
        const brandLogo = document.querySelector('.brand-logo img');
        const preloader = document.querySelector('.preloader');

        // Scale up the brand logo smoothly
        brandLogo.style.transition = 'transform 2s ease-in-out';
        brandLogo.style.transform = 'scale(60)'; // Adjust the scale for smooth gliding



        // Trigger fade out animation for the preloader
        preloader.classList.add('fade-out');

        // Hide the preloader after the fade out animation completes
        setTimeout(function () {
            preloader.style.display = 'none';
        }, 2000); // Adjust the duration of fade out animation as needed
    }, 2000); // Adjust the duration before the animation starts as needed
});
// Check if the carousel track exists before proceeding
const carouselTrack = document.querySelector('.carousel-track');
if (carouselTrack) {
    // List of brand icons
    const brands = ['brand1.png', 'brand2.png', 'brand3.png'];

    // Create a document fragment to optimize performance
    const fragment = document.createDocumentFragment();

    // Loop through the brand icons and create image elements
    brands.forEach(brand => {
        const img = document.createElement('img');
        img.src = brand;
        img.alt = 'Brand'; // You can set alternative text for accessibility
        fragment.appendChild(img);
    });

    // Append all image elements to the carousel track at once
    carouselTrack.appendChild(fragment);
} else {
    console.error('Carousel track not found.');
}
