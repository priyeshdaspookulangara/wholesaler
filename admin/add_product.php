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

$name = $slug = $description = $specifications = "";
$category_id = $sub_category_id = $brand_id = $sub_brand_id = 0;
$is_featured = $is_new_arrival = $is_promotional = 0;
$name_err = $category_err = $brand_err = "";

$sql_categories = "SELECT * FROM categories";
$result_categories = mysqli_query($conn, $sql_categories);

$sql_brands = "SELECT * FROM brands";
$result_brands = mysqli_query($conn, $sql_brands);

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(empty(trim($_POST["name"]))){
        $name_err = "Please enter a product name.";
    } else{
        $name = trim($_POST["name"]);
        $slug = create_slug($name);
    }

    $description = trim($_POST["description"]);
    $specifications = trim($_POST["specifications"]);
    $category_id = trim($_POST["category_id"]);
    $sub_category_id = trim($_POST["sub_category_id"]);
    $brand_id = trim($_POST["brand_id"]);
    $sub_brand_id = trim($_POST["sub_brand_id"]);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_new_arrival = isset($_POST['is_new_arrival']) ? 1 : 0;
    $is_promotional = isset($_POST['is_promotional']) ? 1 : 0;

    if(empty($name_err)){
        $sql = "INSERT INTO products (category_id, sub_category_id, brand_id, sub_brand_id, name, slug, description, specifications, is_featured, is_new_arrival, is_promotional) VALUES (" . mysqli_real_escape_string($conn, $category_id) . ", " . ($sub_category_id ? mysqli_real_escape_string($conn, $sub_category_id) : "NULL") . ", " . mysqli_real_escape_string($conn, $brand_id) . ", " . ($sub_brand_id ? mysqli_real_escape_string($conn, $sub_brand_id) : "NULL") . ", '" . mysqli_real_escape_string($conn, $name) . "', '" . mysqli_real_escape_string($conn, $slug) . "', '" . mysqli_real_escape_string($conn, $description) . "', '" . mysqli_real_escape_string($conn, $specifications) . "', " . mysqli_real_escape_string($conn, $is_featured) . ", " . mysqli_real_escape_string($conn, $is_new_arrival) . ", " . mysqli_real_escape_string($conn, $is_promotional) . ")";

        if(mysqli_query($conn, $sql)){
            $product_id = mysqli_insert_id($conn);

            if(isset($_FILES['images'])){
                foreach($_FILES['images']['name'] as $key=>$val){
                    $filename = $_FILES['images']['name'][$key];
                    $tmp_name = $_FILES['images']['tmp_name'][$key];
                    $ext = pathinfo($filename, PATHINFO_EXTENSION);
                    $image_path = uniqid() . "." . $ext;
                    move_uploaded_file($tmp_name, "../uploads/" . $image_path);

                    $sql_image = "INSERT INTO product_images (product_id, image_path) VALUES (" . mysqli_real_escape_string($conn, $product_id) . ", '" . mysqli_real_escape_string($conn, $image_path) . "')";
                    mysqli_query($conn, $sql_image);
                }
            }
            header("location: products.php");
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
    <title>Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h2>Add Product</h2>
                <p>Please fill this form to create a product.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Product Name</label>
                        <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                        <span class="invalid-feedback"><?php echo $name_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control"><?php echo $description; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Specifications</label>
                        <textarea name="specifications" class="form-control"><?php echo $specifications; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category_id" id="category_id" class="form-control">
                            <option value="">Select Category</option>
                            <?php while($row = mysqli_fetch_assoc($result_categories)){ ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Sub Category</label>
                        <select name="sub_category_id" id="sub_category_id" class="form-control">
                            <option value="">Select Sub Category</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Brand</label>
                        <select name="brand_id" id="brand_id" class="form-control">
                            <option value="">Select Brand</option>
                            <?php while($row = mysqli_fetch_assoc($result_brands)){ ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Sub Brand</label>
                        <select name="sub_brand_id" id="sub_brand_id" class="form-control">
                            <option value="">Select Sub Brand</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Product Images</label>
                        <input type="file" name="images[]" class="form-control" multiple>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1">
                        <label class="form-check-label" for="is_featured">
                            Featured Product
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_new_arrival" id="is_new_arrival" value="1">
                        <label class="form-check-label" for="is_new_arrival">
                            New Arrival
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_promotional" id="is_promotional" value="1">
                        <label class="form-check-label" for="is_promotional">
                            Promotional Product
                        </label>
                    </div>

                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="products.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $('#category_id').on('change', function(){
                var category_id = $(this).val();
                if(category_id){
                    $.ajax({
                        type:'POST',
                        url:'get_sub_categories.php',
                        data:'category_id='+category_id,
                        success:function(html){
                            $('#sub_category_id').html(html);
                        }
                    });
                }else{
                    $('#sub_category_id').html('<option value="">Select category first</option>');
                }
            });

            $('#brand_id').on('change', function(){
                var brand_id = $(this).val();
                if(brand_id){
                    $.ajax({
                        type:'POST',
                        url:'get_sub_brands.php',
                        data:'brand_id='+brand_id,
                        success:function(html){
                            $('#sub_brand_id').html(html);
                        }
                    });
                }else{
                    $('#sub_brand_id').html('<option value="">Select brand first</option>');
                }
            });
        });
    </script>
</body>
</html>
