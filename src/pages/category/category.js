document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.product-card, .product-card--discount').forEach(card => {
        card.addEventListener('click', function (e) {
            // Zignoruj kliknięcia na input i button
            if (
                e.target.closest('input') ||
                e.target.closest('button') ||
                e.target.closest('svg')
            ) {
                return;
            }

            const href = this.getAttribute('data-href');
            if (href) {
                window.location.href = href;
            }
        });
    });

    document.querySelectorAll('.add-to-cart-button').forEach(wrapper => {
        const button = wrapper.tagName === 'BUTTON' ? wrapper : wrapper.querySelector('button');
        if (!button) return;

        button.addEventListener('click', async e => {
            e.stopPropagation();

            const card = button.closest('.product-card') || button.closest('.product-card--discount');
            const form = card.querySelector('.add-to-cart-form');
            const quantityInput = card.querySelector('.quantity-input');
            let quantity = quantityInput ? quantityInput.value : 1;
            const stockQuantity = form.querySelector('[name="stock_quantity"]').value;

            if (parseInt(stockQuantity, 10) < parseInt(quantity,10)) {
                quantity = stockQuantity;
                quantityInput.value = quantity;
            }

            if (!form) {
                console.warn('Brak formularza');
                return;
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
                    showToast('Coś poszło nie tak. Spróbuj ponownie później', 'error')
                }
            } catch (err) {
                console.error('Błąd sieci:', err);
            }
        });
    });
});