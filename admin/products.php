<?php
require_once __DIR__ . '/../core/db_connect.php';
require_once __DIR__ . '/../templates/admin_header.php';

$sql = "SELECT p.id, p.name, c.name as category, sc.name as sub_category, b.name as brand, sb.name as sub_brand
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN sub_categories sc ON p.sub_category_id = sc.id
        LEFT JOIN brands b ON p.brand_id = b.id
        LEFT JOIN sub_brands sb ON p.sub_brand_id = sb.id";
$result = mysqli_query($conn, $sql);
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mt-4">Products</h1>
        <a href="add_product.php" class="btn btn-primary">Add New Product</a>
    </div>
    <table class="table table-bordered table-striped mt-4">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Category</th>
                <th>Sub Category</th>
                <th>Brand</th>
                <th>Sub Brand</th>
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
                        echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['sub_category']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['brand']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['sub_brand']) . "</td>";
                        echo "<td>";
                            echo "<a href='edit_product.php?id=". $row['id'] ."' class='btn btn-primary btn-sm'>Edit</a>";
                            echo "&nbsp;";
                            echo "<a href='delete_product.php?id=". $row['id'] ."' class='btn btn-danger btn-sm'>Delete</a>";
                        echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No products found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php
mysqli_close($conn);
require_once __DIR__ . '/../templates/admin_footer.php';
?>
