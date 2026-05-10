<?php
include '../db/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM bookborrower WHERE borrow_id = '$id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: borrowing_book.php?msg=deleted");
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>