<?php
include '../db/db.php';

if (isset($_GET['book_id']) && isset($_GET['new_name'])) {
    $book_id = $_GET['book_id'];
    $new_book_name = $_GET['new_name'];

    $sql = "UPDATE book SET book_name = '$new_book_name' WHERE book_id = '$book_id'";

    if($conn->query($sql)){
        $message = "Book updated successfully!";
        header("Location: Book_inventory.php");
    }else{
        $message = "Failed to update book.";
    }
}
?>