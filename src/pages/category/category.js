async function handleAddToCartClick(button) {
    const card = button.closest('.product-card') || button.closest('.product-card--discount');
    if (!card) return;

    const form = card.querySelector('.add-to-cart-form');
    if (!form) {
        console.warn('Brak formularza');
        return;
    }

    const quantityInput = card.querySelector('.quantity-input');
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

    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('input', () => {
            const card = input.closest('.product-card, .product-card--discount');
            const form = card.querySelector('.add-to-cart-form');
            const priceElement = card.querySelector('.product-price, .product-price--line-through');
            const basePrice = parseFloat(form.querySelector('[name="price"]')?.value);
            const quantity = parseInt(input.value, 10) || 1;
            const totalPrice = basePrice * quantity;

            const formattedPrice = new Intl.NumberFormat('pl-PL', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(totalPrice);


            priceElement.textContent = formattedPrice + ' zł';
            const discountElement = card.querySelector('.product-price--discount');
            if (discountElement) {
                const discount = parseFloat(form.querySelector('[name="discount"]')?.value);
                const totalPriceDiscount = totalPrice - totalPrice * discount;

                const formattedPriceDiscount = new Intl.NumberFormat('pl-PL', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(totalPriceDiscount);

                discountElement.textContent = '(-' + parseInt((discount * 100).toString()) + ' %) ' + formattedPriceDiscount + ' zł';
            }
        });
    });

    document.querySelectorAll('.add-to-cart-button').forEach(wrapper => {
        const button = wrapper.tagName === 'BUTTON' ? wrapper : wrapper.querySelector('button');
        if (!button) return;

        button.addEventListener('click', e => {
            e.stopPropagation();
            handleAddToCartClick(button);
        });
    });
});