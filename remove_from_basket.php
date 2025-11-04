<?php
session_start();

if(isset($_GET['id'])){
    $product_id = $_GET['id'];
    if(isset($_SESSION['basket'][$product_id])){
        unset($_SESSION['basket'][$product_id]);
    }
}

header("Location: /enquiry-basket");
?>
