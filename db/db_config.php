<?php
$servername = "sql12.freesqldatabase.com";
$username = "sql12825358";
$password = "kpcY784hQ3";
$dbname = "sql12825358";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Database connected successfully";

?>