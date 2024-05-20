const slider = document.querySelector('.testimonial-slider');
const cards = document.querySelectorAll('.testimonial-card');
const leftButton = document.querySelector('.slider-button.left');
const rightButton = document.querySelector('.slider-button.right');

let currentIndex = 0;

function updateSlider() {
    const offset = -currentIndex * 200; // 200px card width
    slider.style.transform = `translateX(${offset}px)`;
}

leftButton.addEventListener('click', () => {
    currentIndex = (currentIndex > 0) ? currentIndex - 1 : cards.length - 1;
    updateSlider();
});

rightButton.addEventListener('click', () => {
    currentIndex = (currentIndex < cards.length - 1) ? currentIndex + 1 : 0;
    updateSlider();
});

// Initialize the slider position
updateSlider();
