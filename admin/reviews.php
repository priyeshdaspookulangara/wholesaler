<?php
session_start();
require_once "../core/db_connect.php";

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}

// Handle Approve Action
if (isset($_GET['action']) && $_GET['action'] == 'approve' && isset($_GET['id'])) {
    $review_id = mysqli_real_escape_string($conn, $_GET['id']);
    $conn->query("UPDATE product_reviews SET status = 'approved' WHERE id = {$review_id}");
    header("Location: " . BASE_URL . "/admin/reviews.php");
    exit;
}

// Handle Delete Action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $review_id = mysqli_real_escape_string($conn, $_GET['id']);
    $conn->query("DELETE FROM product_reviews WHERE id = {$review_id}");
    header("Location: " . BASE_URL . "/admin/reviews.php");
    exit;
}

$sql = "SELECT r.*, p.name as product_name FROM product_reviews r JOIN products p ON r.product_id = p.id ORDER BY r.created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Reviews</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="d-flex">
    <!-- Sidebar -->
    <nav id="sidebar" class="bg-dark text-white p-3" style="width: 250px; height: 100vh;">
        <div class="sidebar-header">
            <h3>Admin Panel</h3>
        </div>
        <ul class="list-unstyled components">
            <li><a href="<?php echo BASE_URL; ?>/admin/dashboard.php">Dashboard</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/categories.php">Categories</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/brands.php">Brands</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/products.php">Products</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/reviews.php">Reviews</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/inquiries.php">Inquiries</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/messages.php">Contact Messages</a></li>
            <li><a href="<?php echo BASE_URL; ?>/admin/users.php">Users</a></li>
        </ul>
    </nav>

    <!-- Page Content -->
    <div id="content" class="container-fluid p-4">
        <div class="d-flex justify-content-between">
            <h2>Manage Product Reviews</h2>
            <a href="<?php echo BASE_URL; ?>/admin/logout.php" class="btn btn-danger">Logout</a>
        </div>

        <table class="table table-bordered table-striped mt-4">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Reviewer</th>
                    <th>Rating</th>
                    <th>Review</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['reviewer_name']); ?></td>
                            <td><?php echo str_repeat('&#9733;', $row['rating']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($row['review_text'])); ?></td>
                            <td>
                                <span class="badge <?php echo $row['status'] == 'approved' ? 'bg-success' : 'bg-warning'; ?>">
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </td>
                            <td><?php echo $row['created_at']; ?></td>
                            <td>
                                <?php if ($row['status'] == 'pending'): ?>
                                    <a href="?action=approve&id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">Approve</a>
                                <?php endif; ?>
                                <a href="?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this review?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7">No reviews found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
