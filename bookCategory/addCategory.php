<?php

include '../db/db.php';

if(isset($_POST['submit'])){

    $categoryID = trim($_POST['category_id']);
    $categoryName = trim($_POST['category_name']);

    $sql = "
        INSERT INTO bookcategory
        (category_id, category_Name, date_modified)
        VALUES
        ('$categoryID', '$categoryName', CURDATE())
    ";

    if($conn->query($sql) === TRUE){

        header("Location: bookCategory.php");
        exit();

    }else{

        echo "Error: " . $conn->error;

    }

}

?>