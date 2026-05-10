<?php

include '../db/db.php';

if(isset($_GET['id'])){

    $categoryID = $_GET['id'];

    $sql = "
        DELETE FROM bookcategory
        WHERE category_id = '$categoryID'
    ";

    if($conn->query($sql) === TRUE){

        header("Location: bookCategory.php");
        exit();

    }else{

        echo "Error: " . $conn->error;

    }

}else{

    header("Location: bookCategory.php");
    exit();

}

?>