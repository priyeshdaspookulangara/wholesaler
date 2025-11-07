<?php
require_once __DIR__ . '/../core/db_connect.php';

$username = 'testuser';
$password = password_hash('password', PASSWORD_DEFAULT);

$sql = "INSERT INTO admins (username, password) VALUES ('" . mysqli_real_escape_string($conn, $username) . "', '" . mysqli_real_escape_string($conn, $password) . "')";

if (mysqli_query($conn, $sql)) {
    echo "Test user created successfully.";
} else {
    echo "Error creating test user: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
