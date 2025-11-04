<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}

require_once "../core/db_connect.php";

$brand_id = trim($_GET["brand_id"]);
$sql = "SELECT * FROM sub_brands WHERE brand_id = " . mysqli_real_escape_string($conn, $brand_id);
$result = mysqli_query($conn, $sql);

$brand_sql = "SELECT name FROM brands WHERE id = " . mysqli_real_escape_string($conn, $brand_id);
$brand_result = mysqli_query($conn, $brand_sql);
$brand_row = mysqli_fetch_assoc($brand_result);
$brand_name = $brand_row['name'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sub Brands for <?php echo $brand_name; ?></title>
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
                <a href="dashboard.php">Dashboard</a>
            </li>
            <li>
                <a href="categories.php">Categories</a>
            </li>
            <li class="active">
                <a href="brands.php">Brands</a>
            </li>
            <li>
                <a href="products.php">Products</a>
            </li>
            <li>
                <a href="inquiries.php">Inquiries</a>
            </li>
            <li>
                <a href="messages.php">Contact Messages</a>
            </li>
            <li>
                <a href="users.php">Users</a>
            </li>
        </ul>
    </nav>

    <!-- Page Content -->
    <div id="content" class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Sub Brands for <?php echo $brand_name; ?></a>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </nav>

        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between">
                    <h2>Sub Brands</h2>
                    <a href="add_sub_brand.php?brand_id=<?php echo $brand_id; ?>" class="btn btn-primary">Add New Sub Brand</a>
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
                                    echo "<td><img src='../uploads/" . htmlspecialchars($row['logo']) . "' width='100'></td>";
                                    echo "<td>";
                                        echo "<a href='edit_sub_brand.php?id=". $row['id'] ."' class='btn btn-primary btn-sm'>Edit</a>";
                                        echo "&nbsp;";
                                        echo "<a href='delete_sub_brand.php?id=". $row['id'] ."' class='btn btn-danger btn-sm'>Delete</a>";
                                    echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No sub brands found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <a href="brands.php" class="btn btn-secondary">Back to Brands</a>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
