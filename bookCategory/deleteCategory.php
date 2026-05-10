<?php
include '../db/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM bookcategory WHERE category_id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: bookCategory.php?msg=deleted");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>