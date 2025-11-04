<?php
if(isset($_POST["id"]) && !empty($_POST["id"])){
    require_once "../core/db_connect.php";

    $id = trim($_POST["id"]);
    $sql = "DELETE FROM categories WHERE id = " . mysqli_real_escape_string($conn, $id);

    if(mysqli_query($conn, $sql)){
        header("location: categories.php");
        exit();
    } else{
        echo "Oops! Something went wrong. Please try again later.";
    }

    mysqli_close($conn);

} else{
    if(empty(trim($_GET["id"]))){
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="mt-5 mb-3">
                    <h2>Delete Category</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger">
                            <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                            <p>Are you sure you want to delete this category? This action cannot be undone.</p>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="categories.php" class="btn btn-secondary">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
