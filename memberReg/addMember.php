<?php

include '../db/db.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $member_id = trim($_POST['member_id']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $birthday = trim($_POST['birthday']);
    $email = trim($_POST['email']);

    /* MEMBER ID VALIDATION */

    if(!preg_match('/^M[0-9]{3}$/', $member_id)){

        die("Invalid Member ID Format");

    }

    /* EMAIL VALIDATION */

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){

        die("Invalid Email Format");

    }

    /* CHECK EXISTING MEMBER ID */

    $check = $conn->prepare("
        SELECT member_id
        FROM member
        WHERE member_id = ?
    ");

    $check->bind_param("s", $member_id);

    $check->execute();

    $result = $check->get_result();

    if($result->num_rows > 0){

        die("Member ID Already Exists");

    }

    /* INSERT MEMBER */

    $stmt = $conn->prepare("
        INSERT INTO member
        (member_id, first_name, last_name, birthday, email)
        VALUES
        (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "sssss",
        $member_id,
        $first_name,
        $last_name,
        $birthday,
        $email
    );

    if($stmt->execute()){

        header("Location: memberReg.php?success=added");
        exit();

    }else{

        echo "Error: " . $conn->error;

    }

}

?>