document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.remove-from-cart-button').forEach(button => {
        button.addEventListener('click', async () => {
            const productId = button.dataset.id;

            try {
                const response = await fetch('../../classes/actions/RemoveFromCartAction.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `product_id=${encodeURIComponent(productId)}`
                });

                const result = await response.json();
                if (result.status === 'success') {
                    location.reload(); // odświeżenie koszyka po usunięciu
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
            console.log(maxValue);
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

                const result = await response.json();
                if (result.status === 'success') {
                    location.reload();
                } else {
                    alert(result.message || 'Nie udało się zaktualizować ilości.');
                }
            } catch (err) {
                console.error('Błąd sieci:', err);
            }
        });
    });
});