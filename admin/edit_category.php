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

if(isset($_POST["id"]) && !empty($_POST["id"])){
    $id = $_POST["id"];

    if(empty(trim($_POST["name"]))){
        $name_err = "Please enter a category name.";
    } else{
        $name = trim($_POST["name"]);
        $slug = create_slug($name);
    }

    if(empty($name_err)){
        $sql = "UPDATE categories SET name = '" . mysqli_real_escape_string($conn, $name) . "', slug = '" . mysqli_real_escape_string($conn, $slug) . "' WHERE id = " . mysqli_real_escape_string($conn, $id);

        if(mysqli_query($conn, $sql)){
            header("location: categories.php");
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    mysqli_close($conn);
} else {
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        $id =  trim($_GET["id"]);

        $sql = "SELECT * FROM categories WHERE id = " . mysqli_real_escape_string($conn, $id);
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) == 1){
            $row = mysqli_fetch_assoc($result);
            $name = $row["name"];
        } else{
            header("location: error.php");
            exit();
        }

    } else{
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h2>Edit Category</h2>
                <p>Please edit the input values and submit to update the category record.</p>
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group">
                        <label>Category Name</label>
                        <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                        <span class="invalid-feedback"><?php echo $name_err; ?></span>
                    </div>
                    <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="categories.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
