<?php
$product_slug = $parts[1];
$sql = "SELECT p.*, c.name as category_name, sc.name as sub_category_name, b.name as brand_name, sb.name as sub_brand_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN sub_categories sc ON p.sub_category_id = sc.id
        LEFT JOIN brands b ON p.brand_id = b.id
        LEFT JOIN sub_brands sb ON p.sub_brand_id = sb.id
        WHERE p.slug = '" . mysqli_real_escape_string($conn, $product_slug) . "'";
$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);

$sql_images = "SELECT image_path FROM product_images WHERE product_id = " . $product['id'];
$result_images = mysqli_query($conn, $sql_images);
?>

<div class="row">
    <div class="col-md-6">
        <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php $i = 0; while($row_image = mysqli_fetch_assoc($result_images)): ?>
                <div class="carousel-item <?php echo ($i == 0) ? 'active' : ''; ?>">
                    <img src="/uploads/<?php echo htmlspecialchars($row_image['image_path']); ?>" class="d-block w-100" alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
                <?php $i++; endwhile; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
    <div class="col-md-6">
        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
        <p><strong>Category:</strong> <?php echo htmlspecialchars($product['category_name']); ?> <?php if($product['sub_category_name']) echo '> ' . htmlspecialchars($product['sub_category_name']); ?></p>
        <p><strong>Brand:</strong> <?php echo htmlspecialchars($product['brand_name']); ?> <?php if($product['sub_brand_name']) echo '> ' . htmlspecialchars($product['sub_brand_name']); ?></p>
        <p><?php echo htmlspecialchars($product['description']); ?></p>

        <form action="/add_to_basket.php" method="post">
            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
            <div class="input-group mb-3" style="max-width: 200px;">
                <input type="number" name="quantity" class="form-control" value="1" min="1">
                <button class="btn btn-primary" type="submit">Add to Enquiry Basket</button>
            </div>
        </form>

        <hr>
        <h3>Specifications</h3>
        <table class="table table-bordered">
            <?php
            $specs = explode("\n", $product['specifications']);
            foreach ($specs as $spec) {
                $parts = explode(":", $spec);
                if (count($parts) == 2) {
                    echo "<tr>";
                    echo "<th>" . htmlspecialchars(trim($parts[0])) . "</th>";
                    echo "<td>" . htmlspecialchars(trim($parts[1])) . "</td>";
                    echo "</tr>";
                }
            }
            ?>
        </table>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-12">
        <h3>Other Items in this Brand</h3>
        <div class="row">
            <?php
            $sql_related = "SELECT * FROM products WHERE brand_id = " . $product['brand_id'] . " AND id != " . $product['id'] . " LIMIT 4";
            $result_related = mysqli_query($conn, $sql_related);
            while($row_related = mysqli_fetch_assoc($result_related)):
            ?>
            <div class="col-md-3">
                <div class="card mb-4">
                    <?php
                    $sql_img_related = "SELECT image_path FROM product_images WHERE product_id = " . $row_related['id'] . " LIMIT 1";
                    $result_img_related = mysqli_query($conn, $sql_img_related);
                    $img_related = mysqli_fetch_assoc($result_img_related);
                    ?>
                    <img src="/uploads/<?php echo htmlspecialchars($img_related['image_path']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row_related['name']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($row_related['name']); ?></h5>
                        <a href="/product/<?php echo htmlspecialchars($row_related['slug']); ?>" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>
