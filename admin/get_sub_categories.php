<?php
require_once "../core/db_connect.php";

if(!empty($_POST["category_id"])){
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $query = "SELECT * FROM sub_categories WHERE category_id = ".$category_id;
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0){
        echo '<option value="">Select Sub Category</option>';
        while($row = mysqli_fetch_assoc($result)){
            echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
        }
    }else{
        echo '<option value="">No Sub Category Found</option>';
    }
}
?>
