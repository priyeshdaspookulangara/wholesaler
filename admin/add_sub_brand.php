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

$name = $slug = $logo = "";
$name_err = $logo_err = "";
$brand_id = trim($_GET["brand_id"]);

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $brand_id = trim($_POST["brand_id"]);
    if(empty(trim($_POST["name"]))){
        $name_err = "Please enter a sub brand name.";
    } else{
        $name = trim($_POST["name"]);
        $slug = create_slug($name);
    }

    if(isset($_FILES["logo"]) && $_FILES["logo"]["error"] == 0){
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["logo"]["name"];
        $filetype = $_FILES["logo"]["type"];
        $filesize = $_FILES["logo"]["size"];

        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");

        $maxsize = 5 * 1024 * 1024;
        if($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");

        if(in_array($filetype, $allowed)){
            $logo = uniqid() . "." . $ext;
            move_uploaded_file($_FILES["logo"]["tmp_name"], "../uploads/" . $logo);
        } else{
            $logo_err = "Error: There was a problem uploading your file. Please try again.";
        }
    }

    if(empty($name_err) && empty($logo_err)){
        $sql = "INSERT INTO sub_brands (brand_id, name, slug, logo) VALUES (" . mysqli_real_escape_string($conn, $brand_id) . ", '" . mysqli_real_escape_string($conn, $name) . "', '" . mysqli_real_escape_string($conn, $slug) . "', '" . mysqli_real_escape_string($conn, $logo) . "')";

        if(mysqli_query($conn, $sql)){
            header("location: sub_brands.php?brand_id=" . $brand_id);
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
    <title>Add Sub Brand</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h2>Add Sub Brand</h2>
                <p>Please fill this form to create a sub brand.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?brand_id=<?php echo $brand_id; ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Sub Brand Name</label>
                        <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                        <span class="invalid-feedback"><?php echo $name_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Logo</label>
                        <input type="file" name="logo" class="form-control <?php echo (!empty($logo_err)) ? 'is-invalid' : ''; ?>">
                        <span class="invalid-feedback"><?php echo $logo_err; ?></span>
                    </div>
                    <input type="hidden" name="brand_id" value="<?php echo $brand_id; ?>"/>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="sub_brands.php?brand_id=<?php echo $brand_id; ?>" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
