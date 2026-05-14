<?php

include '../db/db.php';
include '../authCheck.php';

// STEP 1: Get the member ID from the URL
// When the delete button is clicked, the URL looks like:
// deleteMember.php?id=M001
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // If no ID was passed, go back with an error
    header("Location: memberReg.php?error=No member ID provided.");
    exit();
}

// Clean the ID to prevent SQL injection
$member_id = $conn->real_escape_string($_GET['id']);

// STEP 2: Delete the member from the database
$sql = "DELETE FROM member WHERE member_id = '$member_id'";

if ($conn->query($sql)) {
    // Success — redirect back with a success message
    header("Location: memberReg.php?success=Member $member_id deleted successfully.");
} else {
    // Failed — could be because this member has borrow or fine
    // records linked to them in other tables
    header("Location: memberReg.php?error=Cannot delete member $member_id. They may have existing borrow or fine records.");
}

exit();
?>