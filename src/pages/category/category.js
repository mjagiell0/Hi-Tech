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
            const quantity = quantityInput ? quantityInput.value : 1;

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
                    alert('Produkt dodany!');
                } else {
                    alert('Błąd serwera');
                }
            } catch (err) {
                console.error('Błąd sieci:', err);
            }
        });
    });
});