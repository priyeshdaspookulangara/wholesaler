<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}

require_once "../core/db_connect.php";

$username = $password = "";
$username_err = $password_err = "";

if(isset($_POST["id"]) && !empty($_POST["id"])){
    $id = $_POST["id"];

    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        $sql = "SELECT id FROM admins WHERE username = '" . mysqli_real_escape_string($conn, trim($_POST["username"])) . "' AND id != " . mysqli_real_escape_string($conn, $id);
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) == 1){
            $username_err = "This username is already taken.";
        } else{
            $username = trim($_POST["username"]);
        }
    }

    if(!empty(trim($_POST["password"]))){
        if(strlen(trim($_POST["password"])) < 6){
            $password_err = "Password must have atleast 6 characters.";
        } else{
            $password = trim($_POST["password"]);
        }
    }

    if(empty($username_err) && empty($password_err)){
        $sql = "UPDATE admins SET username = '" . mysqli_real_escape_string($conn, $username) . "'";
        if(!empty($password)){
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql .= ", password = '" . mysqli_real_escape_string($conn, $hashed_password) . "'";
        }
        $sql .= " WHERE id = " . mysqli_real_escape_string($conn, $id);

        if(mysqli_query($conn, $sql)){
            header("location: users.php");
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    mysqli_close($conn);
} else {
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        $id =  trim($_GET["id"]);

        $sql = "SELECT username FROM admins WHERE id = " . mysqli_real_escape_string($conn, $id);
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) == 1){
            $row = mysqli_fetch_assoc($result);
            $username = $row["username"];
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
    <title>Edit Admin User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h2>Edit Admin User</h2>
                <p>Please edit the input values and submit to update the user record.</p>
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                        <span class="invalid-feedback"><?php echo $username_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                        <span class="invalid-feedback"><?php echo $password_err; ?></span>
                        <small class="form-text text-muted">Leave blank if you don't want to change the password.</small>
                    </div>
                    <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="users.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
