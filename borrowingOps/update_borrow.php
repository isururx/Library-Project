<?php
include '../db/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $borrow_id = $_POST['borrow_id'];
    $book_id = $_POST['book_id'];
    $member_id = $_POST['member_id'];
    $status = $_POST['status'];

    $sql = "UPDATE bookborrower SET 
            book_id = '$book_id', 
            member_id = '$member_id', 
            borrow_status = '$status', 
            borrower_date_modified = NOW() 
            WHERE borrow_id = '$borrow_id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: borrowing_book.php?msg=updated");
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>