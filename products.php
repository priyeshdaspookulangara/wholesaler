<?php
require_once 'core/db_connect.php';
require_once 'templates/header.php';

$sql = "SELECT p.*, b.name as brand_name, c.name as category_name, pi.image_path
        FROM products p
        JOIN brands b ON p.brand_id = b.id
        JOIN categories c ON p.category_id = c.id
        LEFT JOIN product_images pi ON p.id = pi.product_id";
$result = mysqli_query($conn, $sql);

$products = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
}
?>

    <!-- Hero Section -->
    <section class="products-hero">
        <div class="products-hero-content">
            <h1>Discover Our Products</h1>
            <p>Explore premium beverages from the world's leading brands</p>
        </div>
    </section>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <div class="filter-container">
            <div class="filter-group">
                <select class="filter-select" id="categoryFilter">
                    <option value="">All Categories</option>
                    <option value="4">Soft Drinks</option>
                </select>

                <select class="filter-select" id="brandFilter">
                    <option value="">All Brands</option>
                </select>

                <div class="search-box">
                    <input type="text" class="search-input" id="searchInput" placeholder="Search products...">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                </div>
            </div>

            <div class="view-toggle">
                <button class="view-btn active" onclick="switchView('grid')" data-view="grid">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <rect x="3" y="3" width="7" height="7" rx="1"/>
                        <rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="14" y="14" width="7" height="7" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" rx="1"/>
                    </svg>
                </button>
                <button class="view-btn" onclick="switchView('list')" data-view="list">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <rect x="3" y="4" width="18" height="4" rx="1"/>
                        <rect x="3" y="10" width="18" height="4" rx="1"/>
                        <rect x="3" y="16" width="18" height="4" rx="1"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Products Section -->
    <section class="products-section">
        <div class="section-header">
            <div class="results-count" id="resultsCount"></div>
            <select class="sort-select" id="sortSelect">
                <option value="default">Featured</option>
                <option value="name-asc">Name: A to Z</option>
                <option value="name-desc">Name: Z to A</option>
                <option value="price-asc">Price: Low to High</option>
                <option value="price-desc">Price: High to Low</option>
            </select>
        </div>

        <div class="products-grid" id="productsGrid">
            <!-- Products will be loaded here -->
        </div>

        <div class="pagination" id="pagination">
        </div>
    </section>

    <script>
        const productsData = <?php echo json_encode($products); ?>;
    </script>

<?php
mysqli_close($conn);
require_once 'templates/footer.php';
?>
