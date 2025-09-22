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
});