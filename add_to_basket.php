<?php
session_start();
require_once "core/db_connect.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    if(!isset($_SESSION['basket'])){
        $_SESSION['basket'] = array();
    }

    if(isset($_SESSION['basket'][$product_id])){
        $_SESSION['basket'][$product_id] += $quantity;
    } else {
        $_SESSION['basket'][$product_id] = $quantity;
    }

    header("Location: " . $_SERVER["HTTP_REFERER"]);
}
?>
