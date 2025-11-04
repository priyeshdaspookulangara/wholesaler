<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}

require_once "../core/db_connect.php";

$id = trim($_GET["id"]);
$sql = "SELECT * FROM enquiries WHERE id = " . mysqli_real_escape_string($conn, $id);
$result = mysqli_query($conn, $sql);
$enquiry = mysqli_fetch_assoc($result);

$sql_items = "SELECT p.name, ei.quantity
              FROM enquiry_items ei
              JOIN products p ON ei.product_id = p.id
              WHERE ei.enquiry_id = " . mysqli_real_escape_string($conn, $id);
$result_items = mysqli_query($conn, $sql_items);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Inquiry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h2>Inquiry Details</h2>
                <div class="card">
                    <div class="card-header">
                        Inquiry #<?php echo htmlspecialchars($enquiry['id']); ?>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">From: <?php echo htmlspecialchars($enquiry['name']); ?></h5>
                        <p class="card-text"><strong>Email:</strong> <?php echo htmlspecialchars($enquiry['email']); ?></p>
                        <p class="card-text"><strong>Phone:</strong> <?php echo htmlspecialchars($enquiry['phone']); ?></p>
                        <p class="card-text"><strong>Message:</strong> <?php echo htmlspecialchars($enquiry['message']); ?></p>
                        <p class="card-text"><strong>Date:</strong> <?php echo htmlspecialchars($enquiry['created_at']); ?></p>

                        <hr>
                        <h5>Inquired Products:</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(mysqli_num_rows($result_items) > 0){
                                    while($row = mysqli_fetch_assoc($result_items)){
                                        echo "<tr>";
                                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='2'>No products in this inquiry.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <a href="inquiries.php" class="btn btn-primary">Back to Inquiries</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
