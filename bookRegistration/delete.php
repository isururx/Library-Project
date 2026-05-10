<?php
include '../db/db.php';

$id = $_GET['id'];
$sql = "DELETE FROM book WHERE book_id = '$id'";

if($conn->query($sql)){
        $message = "Book Deleted successfully!";
        header("Location: Book_inventory.php");
    }else{
        $message = "Failed to Delete book.";
    }
?>