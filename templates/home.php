<?php
$sql_featured = "SELECT * FROM products WHERE is_featured = 1 LIMIT 8";
$result_featured = mysqli_query($conn, $sql_featured);

$sql_new_arrival = "SELECT * FROM products WHERE is_new_arrival = 1 LIMIT 8";
$result_new_arrival = mysqli_query($conn, $sql_new_arrival);

$sql_promotional = "SELECT * FROM products WHERE is_promotional = 1 LIMIT 8";
$result_promotional = mysqli_query($conn, $sql_promotional);
?>

<div class="row">
    <div class="col-md-12">
        <h2>Best Sellers</h2>
        <div class="row">
            <?php while($row = mysqli_fetch_assoc($result_featured)): ?>
            <div class="col-md-3">
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
            <?php endwhile; ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h2>New Arrivals</h2>
        <div class="row">
            <?php while($row = mysqli_fetch_assoc($result_new_arrival)): ?>
            <div class="col-md-3">
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
            <?php endwhile; ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h2>Promotional Products</h2>
        <div class="row">
            <?php while($row = mysqli_fetch_assoc($result_promotional)): ?>
            <div class="col-md-3">
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
            <?php endwhile; ?>
        </div>
    </div>
</div>
