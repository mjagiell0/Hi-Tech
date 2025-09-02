const toggleBtn = document.querySelector('.menu-toggle');
const categories = document.querySelector('.categories');

toggleBtn.addEventListener('click', () => {
    categories.classList.toggle('active');
});