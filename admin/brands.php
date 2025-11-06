<?php
session_start();
require_once "../core/db_connect.php";

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}

$sql = "SELECT * FROM brands";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brands</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .wrapper{
            display: flex;
            width: 100%;
        }
        #sidebar {
            min-width: 250px;
            max-width: 250px;
            background: #7386D5;
            color: #fff;
            transition: all 0.3s;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <h3>Admin Panel</h3>
        </div>

        <ul class="list-unstyled components">
            <p>Wholesaler Site</p>
            <li>
                <a href="<?php echo BASE_URL; ?>/admin/dashboard.php">Dashboard</a>
            </li>
            <li>
                <a href="<?php echo BASE_URL; ?>/admin/categories.php">Categories</a>
            </li>
            <li class="active">
                <a href="<?php echo BASE_URL; ?>/admin/brands.php">Brands</a>
            </li>
            <li>
                <a href="<?php echo BASE_URL; ?>/admin/products.php">Products</a>
            </li>
            <li>
                <a href="<?php echo BASE_URL; ?>/admin/reviews.php">Reviews</a>
            </li>
            <li>
                <a href="<?php echo BASE_URL; ?>/admin/inquiries.php">Inquiries</a>
            </li>
            <li>
                <a href="<?php echo BASE_URL; ?>/admin/messages.php">Contact Messages</a>
            </li>
            <li>
                <a href="<?php echo BASE_URL; ?>/admin/users.php">Users</a>
            </li>
        </ul>
    </nav>

    <!-- Page Content -->
    <div id="content" class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Brands</a>
                <a href="<?php echo BASE_URL; ?>/admin/logout.php" class="btn btn-danger">Logout</a>
            </div>
        </nav>

        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between">
                    <h2>Brands</h2>
                    <a href="<?php echo BASE_URL; ?>/admin/add_brand.php" class="btn btn-primary">Add New Brand</a>
                </div>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Logo</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(mysqli_num_rows($result) > 0){
                            while($row = mysqli_fetch_assoc($result)){
                                echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['slug']) . "</td>";
                                    echo "<td><img src='" . BASE_URL . "/uploads/" . htmlspecialchars($row['logo']) . "' width='100'></td>";
                                    echo "<td>";
                                        echo "<a href='" . BASE_URL . "/admin/edit_brand.php?id=". $row['id'] ."' class='btn btn-primary btn-sm'>Edit</a>";
                                        echo "&nbsp;";
                                        echo "<a href='" . BASE_URL . "/admin/delete_brand.php?id=". $row['id'] ."' class='btn btn-danger btn-sm'>Delete</a>";
                                        echo "&nbsp;";
                                        echo "<a href='" . BASE_URL . "/admin/sub_brands.php?brand_id=". $row['id'] ."' class='btn btn-info btn-sm'>Sub Brands</a>";
                                    echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No brands found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
