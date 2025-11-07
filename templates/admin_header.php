<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: " . BASE_URL . "/admin/index.php");
    exit;
}

require_once __DIR__ . '/../core/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/admin_style.css">
</head>
<body>
<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <div class="bg-dark border-right" id="sidebar-wrapper">
        <div class="sidebar-heading text-white">Wholesaler Admin</div>
        <div class="list-group list-group-flush">
            <a href="<?php echo BASE_URL; ?>/admin/dashboard.php" class="list-group-item list-group-item-action bg-dark text-white">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </a>
            <a href="<?php echo BASE_URL; ?>/admin/products.php" class="list-group-item list-group-item-action bg-dark text-white">
                <i class="fas fa-box me-2"></i>Products
            </a>
            <a href="<?php echo BASE_URL; ?>/admin/categories.php" class="list-group-item list-group-item-action bg-dark text-white">
                <i class="fas fa-sitemap me-2"></i>Categories
            </a>
            <a href="<?php echo BASE_URL; ?>/admin/brands.php" class="list-group-item list-group-item-action bg-dark text-white">
                <i class="fas fa-tags me-2"></i>Brands
            </a>
            <a href="<?php echo BASE_URL; ?>/admin/inquiries.php" class="list-group-item list-group-item-action bg-dark text-white">
                <i class="fas fa-envelope me-2"></i>Inquiries
            </a>
            <a href="<?php echo BASE_URL; ?>/admin/messages.php" class="list-group-item list-group-item-action bg-dark text-white">
                <i class="fas fa-comment-dots me-2"></i>Messages
            </a>
            <a href="<?php echo BASE_URL; ?>/admin/users.php" class="list-group-item list-group-item-action bg-dark text-white">
                <i class="fas fa-users me-2"></i>Users
            </a>
        </div>
    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <div class="container-fluid">
                <button class="btn btn-primary" id="menu-toggle"><i class="fas fa-bars"></i></button>

                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>/admin/logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid pt-4">
