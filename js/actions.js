// js/actions.js
document.addEventListener('DOMContentLoaded', function () {
    
    // 1. Add to Favorites (Global Listener)
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.add-to-fav');
        if (!btn) return;
        e.preventDefault();

        const productId = btn.getAttribute('data-id');
        const icon = btn.querySelector('i');
        const badge = document.getElementById('fav-count');

        fetch(`add_to_fav.php?id=${productId}&ajax=1`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    // Toggle classes to update the icon color immediately
                    icon.classList.toggle('text-primary');
                    icon.classList.toggle('text-dark');
                    if (badge) badge.innerText = data.newCount;
                }
            });
    });

    // 2. Add to Cart (Global Listener)
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.add-to-cart');
        if (!btn) return;
        e.preventDefault();

        const productId = btn.getAttribute('data-id');
        const cartBadge = document.getElementById('cart-count');

        fetch(`add_to_cart.php?id=${productId}&ajax=1`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    if (cartBadge) cartBadge.innerText = data.newCount;
                    
                    // Simple visual feedback for the user
                    const originalHTML = btn.innerHTML;
                    btn.innerHTML = 'Added!';
                    setTimeout(() => btn.innerHTML = originalHTML, 1000);
                }
            });
    });
});