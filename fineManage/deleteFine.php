<?php
include '../db/db.php';

if (isset($_GET['id'])) {

    $fine_id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM fine WHERE fine_id = ?");
    $stmt->bind_param("s", $fine_id);

    if ($stmt->execute()) {
        header("Location: fineManage.php?success=deleted");
        exit();
    } else {
        echo "Error deleting fine: " . $conn->error;
    }

} else {
    header("Location: fineManage.php");
    exit();
}
?>