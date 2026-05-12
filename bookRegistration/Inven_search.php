<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="p-4">
<?php
include '../db/db.php';
include '../authCheck.php';

if(isset($_POST['search'])){
    $search = $_POST['search_1'];
    $sql = "SELECT * FROM book WHERE book_name LIKE '%$search%'";
    $result = $conn->query($sql);

    if($result && $result->num_rows > 0 ) {

        ?>

        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Book Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 

                while($row = $result->fetch_assoc()){ 
                ?>
                    <tr>     
                        <td><?php echo $row['book_id']; ?></td>
                        <td><?php echo $row['book_name']; ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm"
                                onclick="askNewName('<?php echo $row['book_id']; ?>', '<?php echo addslashes($row['book_name']); ?>')">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <a href="delete.php?id=<?php echo $row['book_id']; ?>"
                               class="btn btn-danger btn-sm">
                               <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php 
                } 
                ?>
            </tbody>
        </table>

        <?php

    } else {
        echo "No books found matching that search.";
    }
}
?>

<a href="bookInventory.php" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i>
                        Back to Inventory
                    </a>

<script>
        function askNewName(bookId, currentName) {
    let newName = prompt("Enter the new book name:", currentName);
    
    if (newName != null && newName.trim() !== "") {
        window.location.href = "update.php?book_id=" + bookId + "&new_name=" + encodeURIComponent(newName);
    }
}

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>