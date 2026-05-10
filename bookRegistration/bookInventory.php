<?php
include '../db/db.php';
include '../authCheck.php';

$result = $conn->query("SELECT * FROM book");

$query = "SELECT COUNT(*) AS total FROM book";
$count = mysqli_query($conn, $query);

$row = mysqli_fetch_assoc($count);

$totalBooks = $row['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .nav-link{
            padding: 12px 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-link:hover{
            background-color: #052dcc;
            color: white !important;
        }

        .nav-link:hover i{
            color: white;
        }
    </style>
</head>
<body>
<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <div class="col-md-2 p-0">
            <div class="d-flex flex-column flex-shrink-0 p-3 bg-dark text-white vh-100">

                <a class="navbar-brand mb-4 d-flex align-items-center text-white px-2 py-3" href="#">
                    <i class="bi bi-book-half text-primary me-3" style="font-size: 40px;"></i>
                    <div>
                        <h2 class="fw-bold mb-0" style="font-size: 30px;">
                            LibraCore
                        </h2>
                        <small class="text-secondary">
                            Library Portal
                        </small>
                    </div>
                </a>

                <ul class="nav nav-pills flex-column mb-auto">

                    <li class="nav-item">
                        <a href="bookInventory.php"  class="nav-link active">
                            <i class="bi bi-book" style="padding: 10px;"></i>
                            Book Inventory
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="../bookCategory/bookCategory.php"
                        class="nav-link text-white">
                            <i class="bi bi-grid" style="padding: 10px;"></i>
                            Categories
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="../memberReg/memberReg.php" class="nav-link text-white">
                            <i class="bi bi-people" style="padding: 10px;"></i>
                            Member Registry
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="../borrowingOps/borrowingBook.php" class="nav-link text-white">
                            <i class="bi bi-arrow-left-right" style="padding: 10px;"></i>
                            Borrowing Ops
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="../fineManage/fineManage.php" class="nav-link text-white">
                            <i class="bi bi-cash-stack" style="padding: 10px;"></i>
                            Fines Management
                        </a>
                    </li>

                </ul>

            </div>

        </div>

        <!-- MAIN CONTENT -->
        <div class="col-md-10 p-0">

            <!-- TOP NAVBAR -->
            <nav class="navbar navbar-expand-lg navbar-dark px-3" style="background-color: #162E93;">
                <form class="d-flex mx-auto">
                    <input class="form-control me-3"
                        type="search"
                        placeholder="Search"
                        style="width: 300px;">
                    <button type="button" class="btn btn-secondary">
                        Search
                    </button>
                </form>

                <div class="dropdown">
                    <a class="btn btn-outline-light dropdown-toggle"
                    href="#"
                    role="button"
                    data-bs-toggle="dropdown">
                        <?php echo $_SESSION['username']; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item text-danger" href="../logout.php">
                                Logout
                            </a>
                        </li>
                    </ul>

                </div>
            </nav>

            <!-- PAGE CONTENT -->
            <div class="p-4">
                <h1>Manage Book Inventory</h1>

                <!-- SUMMARY CARDS -->
                <div class="row mt-4">

                    <!-- TOTAL CATEGORIES -->
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-muted">Total Books</h6>
                                    <h2 class="fw-bold"><?php echo $totalBooks; ?></h2>
                                </div>
                                <i class="bi bi-grid-fill fs-1 text-primary"></i>
                            </div>
                        </div>
                    </div>

                    <!-- ADD Book -->
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div class="w-100">

                                    <h6 class="text-muted mb-3">
                                        Add New Book
                                    </h6>

                                    <a href="Book_reg.php" class="btn btn-outline-primary">
                                        New Book
                                    <i class="bi bi-arrow-right"></i> 
                                    </a>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Inventory TABLE -->
                <div class="card shadow-sm border-0 mt-5">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3" href="..\memberReg\memberReg.php">
                            <h4 class="mb-0">Book Inventory</h4>
                        </div>

                        <table class="table table-hover align-middle">

                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Book Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php while($row = $result->fetch_assoc()){ ?>
                                    <tr>
                                        <td><?php echo $row['book_id']; ?></td>
                                        <td><?php echo $row['book_name']; ?></td>
                                        <td>
                                            <button class="btn btn-warning btn-sm" onclick="askNewName('<?php echo $row['book_id']; ?>', '<?php echo addslashes($row['book_name']); ?>')">
                                            <i class="bi bi-pencil-square"></i>

                                            </button>
                                            <a href="delete.php?id=<?php echo $row['book_id']; ?>"
                                            class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
</script>

<script>
        function askNewName(bookId, currentName) {
    // This pops up a simple input box (native modal)
    let newName = prompt("Enter the new book name:", currentName);
    
    // If they typed something and didn't click Cancel
    if (newName != null && newName.trim() !== "") {
        window.location.href = "update.php?book_id=" + bookId + "&new_name=" + encodeURIComponent(newName);
    }
}

</script>

</body>

</body>
</html>