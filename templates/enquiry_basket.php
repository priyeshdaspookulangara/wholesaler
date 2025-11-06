<?php
$basket_items = array();
if(isset($_SESSION['basket']) && !empty($_SESSION['basket'])){
    $product_ids = implode(',', array_keys($_SESSION['basket']));
    $sql = "SELECT id, name, slug FROM products WHERE id IN (" . $product_ids . ")";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($result)){
        $basket_items[] = $row;
    }
}

$name = $email = $phone = $message = "";
$name_err = $email_err = $phone_err = "";
$success_msg = "";

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_inquiry'])){
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

    if(empty(trim($_POST["phone"]))){
        $phone_err = "Please enter your phone number.";
    } else {
        $phone = trim($_POST["phone"]);
    }

    $message = trim($_POST["message"]);

    if(empty($name_err) && empty($email_err) && empty($phone_err)){
        $sql = "INSERT INTO enquiries (name, email, phone, message) VALUES ('" . mysqli_real_escape_string($conn, $name) . "', '" . mysqli_real_escape_string($conn, $email) . "', '" . mysqli_real_escape_string($conn, $phone) . "', '" . mysqli_real_escape_string($conn, $message) . "')";
        if(mysqli_query($conn, $sql)){
            $enquiry_id = mysqli_insert_id($conn);
            foreach($_SESSION['basket'] as $product_id => $quantity){
                $sql_item = "INSERT INTO enquiry_items (enquiry_id, product_id, quantity) VALUES (" . mysqli_real_escape_string($conn, $enquiry_id) . ", " . mysqli_real_escape_string($conn, $product_id) . ", " . mysqli_real_escape_string($conn, $quantity) . ")";
                mysqli_query($conn, $sql_item);
            }
            unset($_SESSION['basket']);
            $success_msg = "Your inquiry has been sent successfully!";
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
}
?>

<div class="row">
    <div class="col-md-12">
        <h2>Enquiry Basket</h2>
        <?php if(!empty($basket_items)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($basket_items as $item): ?>
                <tr>
                    <td><a href="<?php echo BASE_URL; ?>/product/<?php echo htmlspecialchars($item['slug']); ?>"><?php echo htmlspecialchars($item['name']); ?></a></td>
                    <td><?php echo htmlspecialchars($_SESSION['basket'][$item['id']]); ?></td>
                    <td><a href="<?php echo BASE_URL; ?>/remove_from_basket.php?id=<?php echo htmlspecialchars($item['id']); ?>" class="btn btn-danger btn-sm">Remove</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <hr>

        <h3>Submit Your Inquiry</h3>
        <?php if($success_msg): ?>
        <div class="alert alert-success"><?php echo $success_msg; ?></div>
        <?php endif; ?>
        <form action="<?php echo BASE_URL; ?>/enquiry-basket" method="post">
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
                <label>Phone</label>
                <input type="text" name="phone" class="form-control <?php echo (!empty($phone_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $phone; ?>">
                <span class="invalid-feedback"><?php echo $phone_err; ?></span>
            </div>
            <div class="form-group">
                <label>Message</label>
                <textarea name="message" class="form-control"><?php echo $message; ?></textarea>
            </div>
            <div class="form-group mt-3">
                <input type="submit" name="submit_inquiry" class="btn btn-primary" value="Submit Inquiry">
            </div>
        </form>
        <?php else: ?>
        <p>Your enquiry basket is empty.</p>
        <?php endif; ?>
    </div>
</div>
