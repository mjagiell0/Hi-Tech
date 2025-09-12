const toggleBtn = document.querySelector('.menu-toggle');
const categories = document.querySelector('.sections');

toggleBtn.addEventListener('click', () => {
    categories.classList.toggle('active');
});
const slides = document.querySelector('.slides');
const slide = document.querySelectorAll('.slide');
const prev = document.getElementById('prev');
const next = document.getElementById('next');
const dots = document.querySelectorAll('.dot');

let index = 0;
let autoSlideInterval;

function showSlide(n) {
    if (n >= slide.length) index = 0;
    else if (n < 0) index = slide.length - 1;
    else index = n;

    slides.style.transform = `translateX(${-index * 100}%)`;

    dots.forEach(dot => dot.classList.remove('active'));
    dots[index].classList.add('active');
}

function startAutoSlide() {
    clearInterval(autoSlideInterval); // zatrzymaj poprzedni timer
    autoSlideInterval = setInterval(() => showSlide(index + 1), 5000);
}

// Obsługa kliknięć
next.addEventListener('click', () => {
    showSlide(index + 1);
    startAutoSlide(); // reset timera
});
prev.addEventListener('click', () => {
    showSlide(index - 1);
    startAutoSlide(); // reset timera
});

dots.forEach((dot, i) => {
    dot.addEventListener('click', () => {
        showSlide(i);
        startAutoSlide(); // reset timera
    });
});

// Start slidera
startAutoSlide();

(function initOpinionSlider() {
    const track = document.querySelector('.opinion-slider .slider-track');

    function rotateSlides() {
        const firstSlide = track.querySelector('.opinion-slide');
        const slideWidth = firstSlide.offsetWidth;

        // Przesunięcie w lewo
        track.style.transition = 'transform 0.5s ease-in-out';
        track.style.transform = `translateX(-${slideWidth}px)`;

        // Po zakończeniu animacji — przesuń pierwszy element na koniec
        setTimeout(() => {
            track.style.transition = 'none';
            track.style.transform = 'translateX(0)';
            track.appendChild(firstSlide);
        }, 500); // czas musi być zgodny z transition
    }

    setInterval(rotateSlides, 5000);
})();