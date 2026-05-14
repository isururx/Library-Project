<!DOCTYPE html>
<html lang="en">

<?php
include '../db/db.php';

$id = $_GET['id'];
$sql = "DELETE FROM book WHERE book_id = '$id'";


try{
if($conn->query($sql)){
        $message = "Book Deleted successfully!";
        echo $message;
        header("Location: bookinventory.php");
        exit();
    }
}

    catch (mysqli_sql_exception $e) {

            if ($e->getCode() == 1451) {
                
                echo "<script>
                alert('This book cannot be deleted because it has been borrowed by a user. Please remove the borrowing records for this book first.');
                
                window.location.href = 'bookinventory.php';
                    </script>";
            } else {
                echo "Database error: " . $e->getMessage();
            }
        }


?>

</html>