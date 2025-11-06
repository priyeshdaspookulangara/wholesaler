<?php
session_start();
require_once "core/db_connect.php";

if(isset($_GET['id'])){
    $product_id = $_GET['id'];
    if(isset($_SESSION['basket'][$product_id])){
        unset($_SESSION['basket'][$product_id]);
    }
}

header("Location: " . BASE_URL . "/enquiry-basket");
?>
