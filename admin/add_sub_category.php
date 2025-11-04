<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}

require_once "../core/db_connect.php";

function create_slug($string){
    $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($string));
    return $slug;
}

$name = $slug = "";
$name_err = "";
$category_id = trim($_GET["category_id"]);

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $category_id = trim($_POST["category_id"]);
    if(empty(trim($_POST["name"]))){
        $name_err = "Please enter a sub category name.";
    } else{
        $name = trim($_POST["name"]);
        $slug = create_slug($name);
    }

    if(empty($name_err)){
        $sql = "INSERT INTO sub_categories (category_id, name, slug) VALUES (" . mysqli_real_escape_string($conn, $category_id) . ", '" . mysqli_real_escape_string($conn, $name) . "', '" . mysqli_real_escape_string($conn, $slug) . "')";

        if(mysqli_query($conn, $sql)){
            header("location: sub_categories.php?category_id=" . $category_id);
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Sub Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h2>Add Sub Category</h2>
                <p>Please fill this form to create a sub category.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?category_id=<?php echo $category_id; ?>" method="post">
                    <div class="form-group">
                        <label>Sub Category Name</label>
                        <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                        <span class="invalid-feedback"><?php echo $name_err; ?></span>
                    </div>
                    <input type="hidden" name="category_id" value="<?php echo $category_id; ?>"/>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="sub_categories.php?category_id=<?php echo $category_id; ?>" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
