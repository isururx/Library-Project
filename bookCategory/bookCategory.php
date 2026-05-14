<?php
include '../db/db.php';
include '../authCheck.php';
include 'editCategoryModal.php';
include 'searchCategoryModal.php';

$result = $conn->query("SELECT * FROM bookcategory");

/* GET NEXT CATEGORY ID */
$nextCategoryID = "C001";
$resultID = $conn->query("
    SELECT category_id
    FROM bookcategory
    ORDER BY CAST(SUBSTRING(category_id, 2) AS UNSIGNED) DESC
    LIMIT 1
");

if($resultID->num_rows > 0){
    $rowID = $resultID->fetch_assoc();
    $lastID = $rowID['category_id'];
    $number = (int) substr($lastID, 1);
    $number++;
    $nextCategoryID = "C" . str_pad($number, 3, "0", STR_PAD_LEFT);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Categories</title>
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

        html, body{
            height: 100%;
            margin: 0;
        }

        .sidebar{
            min-height: 100vh;
            height: 100%;
        }
    </style>
</head>
<body>
<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <div class="col-md-2 p-0">
            <div class="sidebar d-flex flex-column flex-shrink-0 p-3 bg-dark text-white">

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
                        <a href="../bookRegistration/bookInventory.php" class="nav-link text-white">
                            <i class="bi bi-book" style="padding: 10px;"></i>
                            Book Inventory
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="bookCategory.php"
                        class="nav-link active">
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
                <form method="GET" class="d-flex mx-auto">
                    <input class="form-control me-3"
                        type="search"
                        name="search"
                        placeholder="Search By Category Name"
                        style="width: 300px;"
                        required>
                    <button type="submit"
                            class="btn btn-secondary">
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

                <h1>Manage Book Categories</h1>
                
                <!-- SUMMARY CARDS -->
                <div class="row mt-4">

                    <!-- TOTAL CATEGORIES -->
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-muted">Total Categories</h6>
                                    <h2 class="fw-bold">12</h2>
                                </div>
                                <i class="bi bi-grid-fill fs-1 text-primary"></i>
                            </div>
                        </div>
                    </div>

                    <!-- ADD CATEGORY -->
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div class="w-100">

                                    <h6 class="text-muted mb-3">
                                        Add New Category
                                    </h6>

                                    <form action="addCategory.php" method="POST" class="d-flex gap-2">

                                        <!-- CATEGORY ID -->

                                        <input type="text"
                                            name="category_id"
                                            class="form-control"
                                            value="<?php echo $nextCategoryID; ?>"
                                            readonly>

                                        <!-- CATEGORY NAME -->

                                        <input type="text"
                                            name="category_name"
                                            class="form-control"
                                            placeholder="Category name"
                                            required>

                                        <button type="submit"
                                                name="submit"
                                                class="btn btn-primary">

                                            <i class="bi bi-plus-lg"></i>

                                        </button>

                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- CATEGORY TABLE -->
                <div class="card shadow-sm border-0 mt-5">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="mb-0">Book Categories</h4>
                        </div>

                        <table class="table table-hover align-middle">

                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Category Name</th>
                                    <th>Date Modified</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php while($row = $result->fetch_assoc()){ ?>
                                    <tr>
                                        <td><?php echo $row['category_id']; ?></td>
                                        <td><?php echo $row['category_Name']; ?></td>
                                        <td><?php echo $row['date_modified']; ?></td>
                                        <td>
                                            <button
                                                class="btn btn-warning btn-sm editBtn"
                                                data-id="<?php echo $row['category_id']; ?>"
                                                data-name="<?php echo $row['category_Name']; ?>"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editCategoryModal">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            
                                            <a href="deleteCategory.php?id=<?php echo $row['category_id']; ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to delete this category?');">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const editButtons = document.querySelectorAll(".editBtn");
    editButtons.forEach(button => {
        button.addEventListener("click", function(){
            document.getElementById("editCategoryID").value =
                this.dataset.id;
            document.getElementById("editCategoryName").value =
                this.dataset.name;
        });
    });

</script>

</body>

</body>
</html>