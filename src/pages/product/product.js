document.querySelectorAll('.thumbnail').forEach(thumbnail => {
    thumbnail.addEventListener('click', () => {
        const mainImage = document.getElementById('mainProductImage');
        const newSrc = thumbnail.dataset.image;

        mainImage.src = newSrc;

        document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
        thumbnail.classList.add('active');
    });
});

const stars = document.querySelectorAll('.opinion-star');
const starsInput = document.getElementById('starsInput');

stars.forEach(star => {
    star.addEventListener('mouseover', () => {
        const val = parseInt(star.dataset.value);
        stars.forEach(s => {
            s.classList.toggle('hovered', parseInt(s.dataset.value) <= val);
        });
    });

    star.addEventListener('mouseout', () => {
        stars.forEach(s => s.classList.remove('hovered'));
    });

    star.addEventListener('click', () => {
        const val = parseInt(star.dataset.value);
        starsInput.value = val;
        stars.forEach(s => {
            s.classList.toggle('selected', parseInt(s.dataset.value) <= val);
        });
    });
});