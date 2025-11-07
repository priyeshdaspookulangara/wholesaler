<?php
require_once __DIR__ . '/../core/db_connect.php';
require_once __DIR__ . '/../templates/admin_header.php';

// Fetch data for summary cards
$products_count_result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM products");
$products_count = mysqli_fetch_assoc($products_count_result)['count'];

$categories_count_result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM categories");
$categories_count = mysqli_fetch_assoc($categories_count_result)['count'];

$brands_count_result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM brands");
$brands_count = mysqli_fetch_assoc($brands_count_result)['count'];

$inquiries_count_result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM enquiries");
$inquiries_count = mysqli_fetch_assoc($inquiries_count_result)['count'];

mysqli_close($conn);
?>

<div class="container-fluid">
    <h1 class="mt-4">Dashboard</h1>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>! This is your admin dashboard.</p>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Products</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $products_count; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Categories</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $categories_count; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-sitemap fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Brands</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $brands_count; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tags fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">New Inquiries</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $inquiries_count; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-envelope fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../templates/admin_footer.php';
?>
