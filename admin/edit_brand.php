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

if(isset($_POST["id"]) && !empty($_POST["id"])){
    $id = $_POST["id"];

    if(empty(trim($_POST["name"]))){
        $name_err = "Please enter a brand name.";
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
        $sql = "UPDATE brands SET name = '" . mysqli_real_escape_string($conn, $name) . "', slug = '" . mysqli_real_escape_string($conn, $slug) . "'";
        if(!empty($logo)){
            $sql .= ", logo = '" . mysqli_real_escape_string($conn, $logo) . "'";
        }
        $sql .= " WHERE id = " . mysqli_real_escape_string($conn, $id);

        if(mysqli_query($conn, $sql)){
            header("location: brands.php");
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    mysqli_close($conn);
} else {
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        $id =  trim($_GET["id"]);

        $sql = "SELECT * FROM brands WHERE id = " . mysqli_real_escape_string($conn, $id);
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) == 1){
            $row = mysqli_fetch_assoc($result);
            $name = $row["name"];
            $logo = $row["logo"];
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
    <title>Edit Brand</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h2>Edit Brand</h2>
                <p>Please edit the input values and submit to update the brand record.</p>
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Brand Name</label>
                        <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                        <span class="invalid-feedback"><?php echo $name_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Logo</label>
                        <input type="file" name="logo" class="form-control <?php echo (!empty($logo_err)) ? 'is-invalid' : ''; ?>">
                        <span class="invalid-feedback"><?php echo $logo_err; ?></span>
                        <img src="../uploads/<?php echo $logo; ?>" width="100" class="mt-2">
                    </div>
                    <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="brands.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
