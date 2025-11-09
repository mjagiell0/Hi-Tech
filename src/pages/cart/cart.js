document.addEventListener('DOMContentLoaded', () => {
    const message = sessionStorage.getItem('toastMessage');
    const type = sessionStorage.getItem('toastType') || 'info';

    if (message) {
        showToast(message, type);
        sessionStorage.removeItem('toastMessage');
        sessionStorage.removeItem('toastType');
    }

    document.getElementById('checkout-button').addEventListener('click', () => {
        window.location.href = '../order-summary/order-summary-address-select.php';
    });

    document.querySelectorAll('.remove-from-cart-button').forEach(button => {
        button.addEventListener('click', async () => {
            const productId = button.dataset.id;

            try {
                const response = await fetch('../../classes/actions/RemoveFromCartAction.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `product_id=${encodeURIComponent(productId)}`
                });

                if (response.ok) {
                    sessionStorage.setItem('toastMessage', 'Usunięto produkt');
                    sessionStorage.setItem('toastType', 'info');
                    location.reload();
                } else {
                    alert('Nie udało się usunąć produktu.');
                }
            } catch (err) {
                console.error('Błąd sieci:', err);
            }
        });
    });

    document.querySelectorAll('.cart-quantity-input').forEach(input => {
        input.addEventListener('change', async () => {
            const productId = input.dataset.id;
            const maxValue = parseInt(input.max, 10);
            let newQuantity = parseInt(input.value, 10);

            if (newQuantity > maxValue) {
                newQuantity = maxValue;
                input.value = maxValue;
            }

            try {
                const response = await fetch('../../classes/actions/UpdateCartQuantityAction.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `product_id=${encodeURIComponent(productId)}&quantity=${encodeURIComponent(newQuantity)}`
                });

                if (response.ok) {
                    location.reload();
                } else {
                    sessionStorage.setItem('toastMessage', 'Nie udało się zaktualizować ilości.');
                    sessionStorage.setItem('toastType', 'error');
                }
            } catch (err) {
                console.error('Błąd sieci:', err);
            }
        });
    });
});