<?php
include '../db/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $borrow_id = $_POST['borrow_id'];
    $book_id = $_POST['book_id'];
    $member_id = $_POST['member_id'];
    $status = $_POST['status'];

    $sql = "INSERT INTO bookborrower (borrow_id, book_id, member_id, borrow_status, borrower_date_modified) 
            VALUES ('$borrow_id', '$book_id', '$member_id', '$status', NOW())";

    if ($conn->query($sql) === TRUE) {
        header("Location: borrowing_book.php?msg=added");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>