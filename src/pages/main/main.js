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

document.addEventListener("DOMContentLoaded", () => {
    const track = document.querySelector(".carousel-track");
    const items = Array.from(document.querySelectorAll(".carousel-item"));
    const startBtn = document.getElementById("start-case");
    const resultBox = document.getElementById("result");

    let currentIndex = 0;
    let speed = 100; // px per frame
    let slowing = false;
    let animationFrame;
    let position = 0;

    function getItemWidth() {
        return items[0].offsetWidth;
    }

    function spin() {
        position += speed;
        track.style.transform = `translateX(-${position}px)`;

        const itemWidth = getItemWidth();
        if (position >= itemWidth) {
            position = 0;
            currentIndex = (currentIndex + 1) % items.length;
            // Przesuwamy pierwszy element na koniec
            track.appendChild(items[currentIndex]);
        }

        if (slowing) {
            speed *= 0.97;
            if (speed < 2) {
                cancelAnimationFrame(animationFrame);
                setTimeout(() => {
                    finalizeResult();
                }, 2000);
                return;
            }
        }

        animationFrame = requestAnimationFrame(spin);
    }

    function submitRewardForm(rewardId) {
        const form = document.createElement("form");
        form.method = "POST";
        form.action = "../../classes/actions/SaveRewardAction.php";
        form.target = "hidden-frame";

        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "reward_product_id";
        input.value = rewardId;

        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }

    function startSpin() {
        resultBox.textContent = "";
        speed = 100;
        slowing = false;
        position = 0;
        currentIndex = 0;

        animationFrame = requestAnimationFrame(spin);

        setTimeout(() => {
            slowing = true;
        }, 2500);
    }

    function finalizeResult() {
        const carousel = document.querySelector(".carousel");
        const carouselCenter = carousel.getBoundingClientRect().left + carousel.offsetWidth / 2;

        let closestItem = null;
        let closestDistance = Infinity;

        track.querySelectorAll(".carousel-item").forEach(item => {
            const rect = item.getBoundingClientRect();
            const itemCenter = rect.left + rect.width / 2;
            const distance = Math.abs(itemCenter - carouselCenter);

            if (distance < closestDistance) {
                closestDistance = distance;
                closestItem = item;
            }
        });

        // Ukryj karuzelę i przycisk
        document.getElementById("carousel-container").style.display = "none";
        document.getElementById("start-case").style.display = "none";

        // Pokaż zwycięski produkt jako kartę
        const winnerCard = document.getElementById("winner-card");
        winnerCard.style.display = "block";
        setTimeout(() => {
            winnerCard.classList.add("visible");
        }, 100);

        // Zachowaj klasę rzadkości
        const rarityClass = [...closestItem.classList].find(cls =>
            cls.startsWith("rarity-")
        );

        winnerCard.innerHTML = `
        <h2 style="font-size: 2.0rem">Gratulacje! Twoja dzisiejsza wygrana:</h2>
        <div class="winner-card-content ${rarityClass}">
            ${closestItem.innerHTML}
        </div>`;

        const rewardId = closestItem.getAttribute("data-product-id");

        submitRewardForm(rewardId);

    }

    startBtn.addEventListener("click", startSpin);
});