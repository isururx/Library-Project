<?php
include '../db/db.php';
$id = $_GET['id'];

$res = $conn->query("SELECT * FROM bookcategory WHERE category_id = $id");
$row = $res->fetch_assoc();

if (isset($_POST['update'])) {
    $newName = $_POST['category_name'];
    $date = date("Y-m-d H:i:s");

    $sql = "UPDATE bookcategory SET category_Name='$newName', date_modified='$date' WHERE category_id=$id";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: bookCategory.php?msg=updated");
    }
}
?>

