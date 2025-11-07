// Use the dynamically passed productsData
const products = productsData.map(p => ({
    id: p.id,
    name: p.name,
    slug: p.slug,
    brand: p.brand_name,
    brandId: p.brand_id,
    category: p.category_name,
    description: p.description,
    specifications: p.specifications,
    image: `uploads/${p.image_path}`, // Assuming images are in the uploads folder
    price: Math.floor(Math.random() * 100) + 20, // Replace with actual price from DB if available
    originalPrice: Math.floor(Math.random() * 50) + 120, // Replace with actual price from DB if available
    badge: p.is_featured ? "Featured" : (p.is_new_arrival ? "New" : null)
}));

// Get unique brands and categories
const brands = [...new Map(products.map(p => [p.brandId, { id: p.brandId, name: p.brand }])).values()];
const categories = [...new Map(products.map(p => [p.category_id, { id: p.category_id, name: p.category }])).values()];


// Populate brand and category filters
const brandFilter = document.getElementById('brandFilter');
brands.forEach(brand => {
    const option = document.createElement('option');
    option.value = brand.id;
    option.textContent = brand.name;
    brandFilter.appendChild(option);
});

const categoryFilter = document.getElementById('categoryFilter');
categories.forEach(category => {
    const option = document.createElement('option');
    option.value = category.id;
    option.textContent = category.name;
    // categoryFilter.appendChild(option); // Uncomment if you want to dynamically populate categories
});


let filteredProducts = [...products];
let currentSort = 'default';
let currentPage = 1;
const productsPerPage = 12;

// Filter and Search
function filterAndSortProducts() {
    const category = document.getElementById('categoryFilter').value;
    const brand = document.getElementById('brandFilter').value;
    const search = document.getElementById('searchInput').value.toLowerCase();

    filteredProducts = products.filter(product => {
        const matchesCategory = !category || product.category_id == category;
        const matchesBrand = !brand || product.brandId == brand;
        const matchesSearch = !search ||
            product.name.toLowerCase().includes(search) ||
            product.brand.toLowerCase().includes(search) ||
            (product.description && product.description.toLowerCase().includes(search));

        return matchesCategory && matchesBrand && matchesSearch;
    });

    sortProducts();
    renderProducts();
    renderPagination();
}

// Sort Products
function sortProducts() {
    const sort = document.getElementById('sortSelect').value;
    currentSort = sort;

    switch(sort) {
        case 'name-asc':
            filteredProducts.sort((a, b) => a.name.localeCompare(b.name));
            break;
        case 'name-desc':
            filteredProducts.sort((a, b) => b.name.localeCompare(a.name));
            break;
        case 'price-asc':
            filteredProducts.sort((a, b) => a.price - b.price);
            break;
        case 'price-desc':
            filteredProducts.sort((a, b) => b.price - a.price);
            break;
        default:
            // Keep original "featured" order from DB
            break;
    }
}

// Render Products for the current page
function renderProducts() {
    const grid = document.getElementById('productsGrid');
    const resultsCount = document.getElementById('resultsCount');

    if (filteredProducts.length === 0) {
        grid.innerHTML = `
            <div class="empty-state" style="grid-column: 1/-1;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M16 16s-1.5-2-4-2-4 2-4 2"></path>
                    <line x1="9" y1="9" x2="9.01" y2="9"></line>
                    <line x1="15" y1="9" x2="15.01" y2="9"></line>
                </svg>
                <h3>No products found</h3>
                <p>Try adjusting your filters or search terms</p>
            </div>
        `;
        resultsCount.textContent = 'No products found';
        return;
    }

    resultsCount.textContent = `Showing ${filteredProducts.length} ${filteredProducts.length === 1 ? 'product' : 'products'}`;

    const startIndex = (currentPage - 1) * productsPerPage;
    const endIndex = startIndex + productsPerPage;
    const paginatedProducts = filteredProducts.slice(startIndex, endIndex);

    grid.innerHTML = paginatedProducts.map(product => `
        <div class="product-card" onclick="viewProduct(${product.id})">
            <div class="product-image-container">
                <img src="${product.image}" alt="${product.name}" class="product-image" loading="lazy">
                ${product.badge ? `<div class="product-badge">${product.badge}</div>` : ''}
                <div class="product-actions">
                    <button class="action-btn" onclick="event.stopPropagation(); addToWishlist(${product.id})" title="Add to Wishlist">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                    </button>
                    <button class="action-btn" onclick="event.stopPropagation(); quickView(${product.id})" title="Quick View">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="product-info">
                <div class="product-brand">${product.brand}</div>
                <div class="product-title">${product.name}</div>
                <div class="product-specs">
                    ${product.specifications ? `<span class="spec-badge">${product.specifications}</span>` : ''}
                </div>
                <div class="product-footer">
                    <div class="product-price">
                        <span class="current-price">₹${product.price}</span>
                        <span class="original-price">₹${product.originalPrice}</span>
                    </div>
                    <button class="add-to-cart" onclick="event.stopPropagation(); addToCart(${product.id})">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

// Render Pagination
function renderPagination() {
    const pagination = document.getElementById('pagination');
    const pageCount = Math.ceil(filteredProducts.length / productsPerPage);
    pagination.innerHTML = '';

    if (pageCount <= 1) return;

    // Previous button
    const prevButton = document.createElement('button');
    prevButton.className = 'page-btn';
    prevButton.innerHTML = '‹';
    prevButton.disabled = currentPage === 1;
    prevButton.addEventListener('click', () => {
        currentPage--;
        renderProducts();
        renderPagination();
    });
    pagination.appendChild(prevButton);

    // Page number buttons
    for (let i = 1; i <= pageCount; i++) {
        const pageButton = document.createElement('button');
        pageButton.className = 'page-btn' + (i === currentPage ? ' active' : '');
        pageButton.textContent = i;
        pageButton.addEventListener('click', () => {
            currentPage = i;
            renderProducts();
            renderPagination();
        });
        pagination.appendChild(pageButton);
    }

    // Next button
    const nextButton = document.createElement('button');
    nextButton.className = 'page-btn';
    nextButton.innerHTML = '›';
    nextButton.disabled = currentPage === pageCount;
    nextButton.addEventListener('click', () => {
        currentPage++;
        renderProducts();
        renderPagination();
    });
    pagination.appendChild(nextButton);
}


// Event Listeners
document.getElementById('categoryFilter').addEventListener('change', filterAndSortProducts);
document.getElementById('brandFilter').addEventListener('change', filterAndSortProducts);
document.getElementById('searchInput').addEventListener('input', filterAndSortProducts);
document.getElementById('sortSelect').addEventListener('change', () => {
    filterAndSortProducts();
});

// Product Actions
function viewProduct(id) {
    // In a real application, you would redirect to a product detail page
    // window.location.href = `product_detail.php?id=${id}`;
    const product = products.find(p => p.id === id);
    alert(`Viewing product: ${product.name}`);
}

function addToCart(id) {
    const product = products.find(p => p.id == id);
    console.log('Add to cart:', product);
    showNotification(`✓ ${product.name} added to cart!`, 'success');
}

function addToWishlist(id) {
    const product = products.find(p => p.id == id);
    console.log('Add to wishlist:', product);
    showNotification(`♥ ${product.name} added to wishlist!`, 'wishlist');
}

function quickView(id) {
    const product = products.find(p => p.id == id);
    // In a real app, you'd open a modal with product details
    alert(`Quick View: ${product.name}\n\n${product.description || ''}\n\nPrice: ₹${product.price}`);
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    const color = type === 'success' ? 'linear-gradient(135deg, #10b981 0%, #059669 100%)' : 'linear-gradient(135deg, #ec4899 0%, #be185d 100%)';
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: ${color};
        color: white;
        padding: 16px 24px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        z-index: 10000;
        animation: slideIn 0.3s ease, slideOut 0.3s ease 2.7s;
        font-weight: 600;
    `;
    notification.innerHTML = message;
    document.body.appendChild(notification);

    setTimeout(() => notification.remove(), 3000);
}

// Add keyframe animations to the document head
const styleSheet = document.createElement("style");
styleSheet.type = "text/css";
styleSheet.innerText = `
    @keyframes slideIn { from { transform: translateX(120%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    @keyframes slideOut { from { transform: translateX(0); opacity: 1; } to { transform: translateX(120%); opacity: 0; } }
`;
document.head.appendChild(styleSheet);


// Initial Render
document.addEventListener('DOMContentLoaded', () => {
    filterAndSortProducts();
});

// Mobile Menu Functions
function toggleMobileMenu() {
    const mobileNav = document.getElementById('mobileNav');
    const mobileOverlay = document.getElementById('mobileOverlay');
    const burgerMenu = document.querySelector('.burger-menu');

    mobileNav.classList.toggle('active');
    mobileOverlay.classList.toggle('active');
    burgerMenu.classList.toggle('active');
    document.body.style.overflow = mobileNav.classList.contains('active') ? 'hidden' : '';
}

function toggleSubmenu(id) {
    const submenu = document.getElementById('submenu-' + id);
    const toggle = event.currentTarget.querySelector('.mobile-nav-toggle');
    submenu.classList.toggle('active');
    toggle.classList.toggle('active');
}

// View Toggle Function
function switchView(view) {
    const grid = document.getElementById('productsGrid');
    document.querySelectorAll('.view-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelector(`.view-btn[data-view="${view}"]`).classList.add('active');
    grid.classList.toggle('list-view', view === 'list');
}

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
        }
    });
});
