<?php
require_once __DIR__ . '/../core/db_connect.php';
require_once __DIR__ . '/../templates/admin_header.php';

function create_slug($string){
    $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($string));
    return $slug;
}

$name = $slug = "";
$name_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(empty(trim($_POST["name"]))){
        $name_err = "Please enter a category name.";
    } else{
        $name = trim($_POST["name"]);
        $slug = create_slug($name);
    }

    if(empty($name_err)){
        $sql = "INSERT INTO categories (name, slug) VALUES ('" . mysqli_real_escape_string($conn, $name) . "', '" . mysqli_real_escape_string($conn, $slug) . "')";

        if(mysqli_query($conn, $sql)){
            header("location: categories.php");
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    mysqli_close($conn);
}
?>

<div class="container-fluid">
    <h1 class="mt-4">Add Category</h1>
    <p>Please fill this form to create a category.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Category Name</label>
            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
            <span class="invalid-feedback"><?php echo $name_err; ?></span>
        </div>
        <div class="form-group mt-3">
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="categories.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php
require_once __DIR__ . '/../templates/admin_footer.php';
?>
