<?php

include '../db/db.php';

if(isset($_POST['update'])){

    $categoryID = $_POST['category_id'];

    $categoryName = $_POST['category_name'];

    $sql = "
        UPDATE bookcategory
        SET category_Name = '$categoryName',
            date_modified = CURDATE()
        WHERE category_id = '$categoryID'
    ";

    if($conn->query($sql) === TRUE){

        header("Location: bookCategory.php");
        exit();

    }else{

        echo "Error: " . $conn->error;

    }

}

?>