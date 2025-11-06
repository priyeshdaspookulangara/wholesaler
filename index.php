<?php
require_once "core/db_connect.php";
include 'templates/header.php';

$slug = isset($_GET['slug']) ? mysqli_real_escape_string($conn, $_GET['slug']) : '';

if ($slug) {
    $parts = explode('/', $slug);
    $entity = $parts[0];

    switch($entity) {
        case 'product':
            include 'templates/product_detail.php';
            break;
        case 'brands':
            include 'templates/brands.php';
            break;
        case 'category':
            include 'templates/product_listing.php';
            break;
        case 'contact':
            include 'templates/contact.php';
            break;
        case 'enquiry-basket':
            include 'templates/enquiry_basket.php';
            break;
        default:
            include 'templates/404.php';
            break;
    }
} else {
    include 'templates/home.php';
}

include 'templates/footer.php';
?>
