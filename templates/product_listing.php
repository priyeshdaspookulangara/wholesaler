<?php
$category_slug = $parts[1];
$sql = "SELECT p.*, c.name as category_name, sc.name as sub_category_name, b.name as brand_name, sb.name as sub_brand_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN sub_categories sc ON p.sub_category_id = sc.id
        LEFT JOIN brands b ON p.brand_id = b.id
        LEFT JOIN sub_brands sb ON p.sub_brand_id = sb.id
        WHERE c.slug = '" . mysqli_real_escape_string($conn, $category_slug) . "'";

if(isset($parts[2])){
    $sub_category_slug = $parts[2];
    $sql .= " AND sc.slug = '" . mysqli_real_escape_string($conn, $sub_category_slug) . "'";
}

$result = mysqli_query($conn, $sql);

?>

<div class="row">
    <div class="col-md-3">
        <h4>Filter by Brand</h4>
        <ul class="list-group">
            <?php
            $sql_brands_filter = "SELECT DISTINCT b.name, b.slug FROM brands b JOIN products p ON b.id = p.brand_id JOIN categories c ON p.category_id = c.id WHERE c.slug = '" . mysqli_real_escape_string($conn, $category_slug) . "'";
            $result_brands_filter = mysqli_query($conn, $sql_brands_filter);
            while($row_brand_filter = mysqli_fetch_assoc($result_brands_filter)):
            ?>
            <li class="list-group-item"><a href="<?php echo BASE_URL; ?>/category/<?php echo htmlspecialchars($category_slug); ?>/<?php echo htmlspecialchars($row_brand_filter['slug']); ?>"><?php echo htmlspecialchars($row_brand_filter['name']); ?></a></li>
            <?php endwhile; ?>
        </ul>
    </div>
    <div class="col-md-9">
        <div class="row">
            <?php
            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)):
            ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <?php
                    $sql_img = "SELECT image_path FROM product_images WHERE product_id = " . $row['id'] . " LIMIT 1";
                    $result_img = mysqli_query($conn, $sql_img);
                    $img = mysqli_fetch_assoc($result_img);
                    ?>
                    <img src="<?php echo BASE_URL; ?>/uploads/<?php echo htmlspecialchars($img['image_path']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['name']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                        <a href="<?php echo BASE_URL; ?>/product/<?php echo htmlspecialchars($row['slug']); ?>" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
            <?php
                endwhile;
            } else {
                echo "<p>No products found in this category.</p>";
            }
            ?>
        </div>
    </div>
</div>
