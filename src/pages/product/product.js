async function handleAddToCartClick(button) {
    const form = button.closest('.add-to-cart-form');
    if (!form) {
        console.warn('Brak formularza');
        return;
    }

    const quantityInput = form.querySelector('.quantity-input');
    let quantity = quantityInput ? quantityInput.value : 1;
    const stockQuantity = form.querySelector('[name="stock_quantity"]').value;

    if (parseInt(stockQuantity, 10) < parseInt(quantity, 10)) {
        quantity = stockQuantity;
        if (quantityInput) quantityInput.value = quantity;
    }

    const formData = new FormData(form);
    formData.set('quantity', quantity);

    try {
        const response = await fetch('../../classes/actions/ProductToCartAction.php', {
            method: 'POST',
            body: formData
        });
        if (response.ok) {
            showToast('Produkt dodany!', 'success');
        } else {
            showToast('Coś poszło nie tak. Spróbuj ponownie później', 'error');
        }
    } catch (err) {
        console.error('Błąd sieci:', err);
    }
}

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

document.getElementById('quantity').addEventListener('change', (e) => {
    const quantityInput = e.target;
    const max = parseInt(quantityInput.max);
    const value = parseInt(quantityInput.value);

    if (value > max) {
        quantityInput.value = max;
    }
});

document.getElementById('add-to-cart-button').addEventListener('click', function (e) {
    e.preventDefault();
    handleAddToCartClick(e.target);
});




