<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}

require_once "../core/db_connect.php";

$category_id = trim($_GET["category_id"]);
$sql = "SELECT * FROM sub_categories WHERE category_id = " . mysqli_real_escape_string($conn, $category_id);
$result = mysqli_query($conn, $sql);

$category_sql = "SELECT name FROM categories WHERE id = " . mysqli_real_escape_string($conn, $category_id);
$category_result = mysqli_query($conn, $category_sql);
$category_row = mysqli_fetch_assoc($category_result);
$category_name = $category_row['name'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sub Categories for <?php echo $category_name; ?></title>
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
            <li class="active">
                <a href="categories.php">Categories</a>
            </li>
            <li>
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
                <a class="navbar-brand" href="#">Sub Categories for <?php echo $category_name; ?></a>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </nav>

        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between">
                    <h2>Sub Categories</h2>
                    <a href="add_sub_category.php?category_id=<?php echo $category_id; ?>" class="btn btn-primary">Add New Sub Category</a>
                </div>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Slug</th>
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
                                    echo "<td>";
                                        echo "<a href='edit_sub_category.php?id=". $row['id'] ."' class='btn btn-primary btn-sm'>Edit</a>";
                                        echo "&nbsp;";
                                        echo "<a href='delete_sub_category.php?id=". $row['id'] ."' class='btn btn-danger btn-sm'>Delete</a>";
                                    echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No sub categories found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <a href="categories.php" class="btn btn-secondary">Back to Categories</a>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
