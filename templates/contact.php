<?php
$name = $email = $message = "";
$name_err = $email_err = $message_err = "";
$success_msg = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["name"]))){
        $name_err = "Please enter your name.";
    } else {
        $name = trim($_POST["name"]);
    }

    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    if(empty(trim($_POST["message"]))){
        $message_err = "Please enter a message.";
    } else {
        $message = trim($_POST["message"]);
    }

    if(empty($name_err) && empty($email_err) && empty($message_err)){
        $sql = "INSERT INTO contact_messages (name, email, message) VALUES ('" . mysqli_real_escape_string($conn, $name) . "', '" . mysqli_real_escape_string($conn, $email) . "', '" . mysqli_real_escape_string($conn, $message) . "')";
        if(mysqli_query($conn, $sql)){
            $success_msg = "Your message has been sent successfully!";
            $name = $email = $message = "";
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
}
?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <h2>Contact Us</h2>
        <p>Please fill out the form below to get in touch with us.</p>

        <?php if($success_msg): ?>
        <div class="alert alert-success"><?php echo $success_msg; ?></div>
        <?php endif; ?>

        <form action="/contact" method="post">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                <span class="invalid-feedback"><?php echo $name_err; ?></span>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <label>Message</label>
                <textarea name="message" class="form-control <?php echo (!empty($message_err)) ? 'is-invalid' : ''; ?>"><?php echo $message; ?></textarea>
                <span class="invalid-feedback"><?php echo $message_err; ?></span>
            </div>
            <div class="form-group mt-3">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
        </form>
    </div>
</div>
