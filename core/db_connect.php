<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'jeoczvkk_priyesh');
define('DB_PASSWORD', 'pearlsPearls2#');
define('DB_NAME', 'jeoczvkk_wholesale');

$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>
