/**
 * shop-logic.js
 * Handles AJAX product loading, filtering (category, price), search, and pagination.
 * Fixed: price filter active state, category sync, initial load
 */

// 1. Initial filter state (extracts category ID and price_range from URL if coming from Home page)
let currentFilters = {
    page: 1,
    id: new URLSearchParams(window.location.search).get('id') || 'all',
    price_range: new URLSearchParams(window.location.search).get('price_range') || 'all',
    search: '',
    sort: 'latest'
};

/**
 * Syncs all filter UI elements to match currentFilters (called on page load)
 */
function syncUI() {
    // Sync price radio buttons
    const priceRadio = document.querySelector(`input[name="price_range"][value="${currentFilters.price_range}"]`);
    if (priceRadio) {
        priceRadio.checked = true;
        highlightPriceLabel(priceRadio);
    }

    // Sync category pills
    document.querySelectorAll('.cat-pill-item').forEach(pill => {
        const isActive = String(pill.dataset.val) === String(currentFilters.id);
        pill.classList.toggle('active', isActive);
    });
}

/**
 * Highlights the label of the selected price radio button
 * Removes highlight from all others first
 */
function highlightPriceLabel(selectedInput) {
    // Remove active style from all price labels
    document.querySelectorAll('input[name="price_range"]').forEach(input => {
        const label = document.querySelector(`label[for="${input.id}"]`);
        if (label) {
            label.style.fontWeight = '';
            label.style.color = '';
        }
    });

    // Add active style to selected label
    if (selectedInput) {
        const label = document.querySelector(`label[for="${selectedInput.id}"]`);
        if (label) {
            label.style.fontWeight = 'bold';
            label.style.color = 'var(--primary, #007bff)';
        }
    }
}

/**
 * Main function to fetch products from the server
 */
async function loadProducts() {
    const grid = document.getElementById('products-grid');
    if (!grid) return;

    // Show loading spinner while fetching
    grid.innerHTML = `
        <div class="col-12 text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2">Loading Products...</p>
        </div>`;

    // Prepare query string
    const query = new URLSearchParams(currentFilters).toString();

    try {
        const response = await fetch(`./fetch_products.php?${query}`);
        const data = await response.json();

        if (data.products && data.products.length > 0) {
            grid.innerHTML = data.products.map(p => renderProductCard(p, data.fav_list)).join('');
            attachCardEvents();
        } else {
            grid.innerHTML = `
                <div class="col-12 text-center alert alert-info h-25 d-flex justify-content-center align-items-center">
                    No products found for this filter.
                </div>`;
        }

        renderPagination(data.total_pages, data.current_page);

    } catch (e) {
        console.error("Shop Error:", e);
        grid.innerHTML = '<div class="col-12 text-center text-danger">Error loading products. Check console.</div>';
    }
}

/**
 * Attaches click events to Add to Cart and Favorite buttons after products render
 */
function attachCardEvents() {
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            // Replace with your cart logic
            console.log('Add to cart:', id);
        });
    });

    document.querySelectorAll('.add-to-fav').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const icon = this.querySelector('i');
            // Toggle heart color optimistically
            icon.classList.toggle('text-primary');
            icon.classList.toggle('text-dark');
            // Replace with your favorites AJAX call
            console.log('Toggle fav:', id);
        });
    });
}

/**
 * Generates HTML for a product card
 */
function renderProductCard(p, favList) {
    const isFav = favList && favList.map(String).includes(String(p.id));
    const folder = p.image && p.image.includes('-') ? p.image.split('-')[0] : 'default';

    return `
    <div class="col-lg-4 col-md-6 col-sm-12 pb-1 mb-4">
        <div class="card product-item border-0 mb-4">
            <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                <img class="img-fluid w-100" src="img/${folder}/${p.image}" alt="${p.name}" onerror="this.src='img/product-default.jpg'">
            </div>
            <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                <h6 class="text-truncate mb-3">${p.name}</h6>
                <div class="d-flex justify-content-center">
                    <h6 class="text-primary font-weight-bold">$${parseFloat(p.price).toFixed(2)}</h6>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between bg-light border">
                <a href="detail.php?id=${p.id}" class="btn btn-sm text-dark p-0"><i class="fas fa-eye text-primary mr-1"></i>View</a>
                <a href="javascript:void(0)" class="add-to-fav btn btn-sm p-0" data-id="${p.id}">
                    <i class="fas fa-heart ${isFav ? 'text-primary' : 'text-dark'}"></i>
                </a>
                <a href="javascript:void(0)" class="add-to-cart btn btn-sm text-dark p-0" data-id="${p.id}">
                    <i class="fas fa-shopping-cart text-primary mr-1"></i>Add
                </a>
            </div>
        </div>
    </div>`;
}

/**
 * Handles Pagination Generation
 */
function renderPagination(total, current) {
    const container = document.getElementById('pagination-container');
    if (!container) return;

    if (total <= 1) {
        container.innerHTML = '';
        return;
    }

    let html = '';
    for (let i = 1; i <= total; i++) {
        html += `<li class="page-item ${i == current ? 'active' : ''}">
                    <a class="page-link" href="javascript:void(0)" onclick="changePage(${i})">${i}</a>
                 </li>`;
    }
    container.innerHTML = html;
}

/**
 * Changes current page and scrolls to top
 */
function changePage(p) {
    currentFilters.page = p;
    loadProducts();
    window.scrollTo(0, 0);
}

// --- EVENT LISTENERS ---

// A. Handle Price Filter (Radio Change) — fixes active label highlight
document.addEventListener('change', (e) => {
    if (e.target.name === 'price_range') {
        currentFilters.price_range = e.target.value;
        currentFilters.page = 1;
        highlightPriceLabel(e.target); 
        loadProducts();
    }
});

// B. Handle Category & Sort clicks
document.addEventListener('click', (e) => {
    const el = e.target.closest('.filter-trigger');
    if (!el) return;

    // Skip price radios — handled by change event above
    if (el.tagName === 'INPUT' && el.type === 'radio') return;

    e.preventDefault();

    if (el.dataset.type === 'category') {
        document.querySelectorAll('.cat-pill-item').forEach(p => p.classList.remove('active'));
        el.classList.add('active');
        currentFilters.id = el.dataset.val;
    } else if (el.dataset.type === 'sort') {
        currentFilters.sort = el.dataset.val;
        const label = document.getElementById('sort-label');
        if (label) label.innerText = `Sort by: ${el.innerText}`;
    }

    currentFilters.page = 1;
    loadProducts();
});

// C. Handle Search
const searchBtn = document.getElementById('search-btn');
const searchInput = document.getElementById('search-input');

if (searchBtn && searchInput) {
    const performSearch = () => {
        currentFilters.search = searchInput.value.trim();
        currentFilters.page = 1;
        loadProducts();
    };

    searchBtn.addEventListener('click', performSearch);
    searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') performSearch();
    });
}

// D. BOOTSTRAP: Sync UI then load products on page ready
document.addEventListener('DOMContentLoaded', () => {
    syncUI();       
    loadProducts();
});