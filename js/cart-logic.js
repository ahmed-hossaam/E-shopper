/**
 * cart-logic.js
 * Specifically for the Shopping Cart page (cart.php)
 */

document.addEventListener('DOMContentLoaded', function () {
    // Select the cart table to use event delegation
    const cartTable = document.querySelector('.table-responsive');
    if (!cartTable) return; // Exit if not on the cart page

    // Flag to prevent multiple simultaneous requests (Debouncing/Locking)
    let isProcessing = false; 

    /**
     * Updates the Cart UI elements (Subtotal, Grand Total, Badge Count)
     * @param {Object} data - Response from the server
     */
    const updateCartUI = (data) => {
        const subtotalEl = document.getElementById('subtotal');
        const grandTotalEl = document.getElementById('grand-total');
        const cartCountBadge = document.getElementById('cart-count');

        if (subtotalEl) subtotalEl.innerText = '$' + data.subtotal;
        if (grandTotalEl) grandTotalEl.innerText = '$' + data.grandTotal;
        if (cartCountBadge) cartCountBadge.innerText = data.cartCount;
        
        // Reload page if cart becomes empty to show "Empty Cart" state
        if (data.cartCount == 0) {
            location.reload();
        }
    };

    // 1. Handle Quantity Increase/Decrease (Plus & Minus)
    cartTable.addEventListener('click', function (e) {
        const btn = e.target.closest('.update-qty');
        if (!btn || isProcessing) return;

        e.preventDefault();
        const id = btn.getAttribute('data-id');
        const action = btn.getAttribute('data-action');
        const row = btn.closest('tr');
        const input = row.querySelector('.qty-input');
        const itemTotal = row.querySelector('.item-total');

        // Validation: Prevent minus action if quantity is already 1
        if (action === 'minus' && parseInt(input.value) <= 1) return;

        // Start processing request
        isProcessing = true; 
        btn.style.opacity = '0.5';

        fetch(`update_cart.php?id=${id}&action=${action}`)
            .then(res => res.json())
            .then(data => {
                isProcessing = false; // Release lock
                btn.style.opacity = '1';
                
                if (data.status === 'success') {
                    if (input) input.value = data.newQty;
                    if (itemTotal) itemTotal.innerText = '$' + data.itemTotal;
                    updateCartUI(data);
                }
            })
            .catch(err => {
                isProcessing = false;
                btn.style.opacity = '1';
                console.error("Cart Update Error:", err);
            });
    });

    // 2. Handle Item Removal
    cartTable.addEventListener('click', function (e) {
        const btn = e.target.closest('.remove-item');
        if (!btn || isProcessing) return;

        e.preventDefault();
        if (!confirm('Are you sure you want to remove this item?')) return;

        const id = btn.getAttribute('data-id');
        const row = btn.closest('tr');

        isProcessing = true;
        
        fetch(`update_cart.php?id=${id}&action=delete`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    // Visual removal animation
                    row.style.transition = '0.3s';
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(20px)';
                    
                    setTimeout(() => {
                        row.remove();
                        isProcessing = false; // Release lock after animation
                        updateCartUI(data);
                    }, 300);
                } else {
                    isProcessing = false;
                }
            })
            .catch(err => {
                isProcessing = false;
                console.error("Cart Removal Error:", err);
            });
    });
});