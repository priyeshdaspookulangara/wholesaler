<?php
$letter = isset($_GET['letter']) ? mysqli_real_escape_string($conn, $_GET['letter']) : '';

$sql = "SELECT * FROM brands";
if ($letter) {
    $sql .= " WHERE name LIKE '" . $letter . "%'";
}
$sql .= " ORDER BY name";
$result = mysqli_query($conn, $sql);
?>

<div class="row">
    <div class="col-md-12">
        <h2>Browse by Brand</h2>
        <div class="mb-3">
            <?php foreach (range('A', 'Z') as $char): ?>
                <a href="/brands?letter=<?php echo $char; ?>" class="btn btn-primary"><?php echo $char; ?></a>
            <?php endforeach; ?>
        </div>

        <div class="row">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="col-md-3">
                        <div class="card mb-4">
                            <a href="/product/brand/<?php echo htmlspecialchars($row['slug']); ?>">
                                <img src="/uploads/<?php echo htmlspecialchars($row['logo']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['name']); ?>">
                                <div class="card-body">
                                    <h5 class="card-title text-center"><?php echo htmlspecialchars($row['name']); ?></h5>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No brands found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
