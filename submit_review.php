<?php
require_once "core/db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $reviewer_name = mysqli_real_escape_string($conn, $_POST['reviewer_name']);
    $rating = mysqli_real_escape_string($conn, $_POST['rating']);
    $review_text = mysqli_real_escape_string($conn, $_POST['review_text']);

    // Basic validation
    if (!empty($product_id) && !empty($reviewer_name) && !empty($rating) && !empty($review_text)) {
        $sql = "INSERT INTO product_reviews (product_id, reviewer_name, rating, review_text) VALUES ('{$product_id}', '{$reviewer_name}', '{$rating}', '{$review_text}')";

        if (mysqli_query($conn, $sql)) {
            // Redirect back to the product page with a success message
            $product_slug_query = "SELECT slug FROM products WHERE id = {$product_id}";
            $result = mysqli_query($conn, $product_slug_query);
            $product = mysqli_fetch_assoc($result);

            header("Location: " . BASE_URL . "/product/" . $product['slug'] . "?review_success=1");
            exit();
        } else {
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
        }
    } else {
        echo "ERROR: All fields are required.";
    }

    mysqli_close($conn);
}
?>
