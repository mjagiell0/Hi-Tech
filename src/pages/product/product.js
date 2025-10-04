document.querySelectorAll('.thumbnail').forEach(thumbnail => {
    thumbnail.addEventListener('click', () => {
        const mainImage = document.getElementById('mainProductImage');
        const newSrc = thumbnail.dataset.image;

        mainImage.src = newSrc;

        document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
        thumbnail.classList.add('active');
    });
});