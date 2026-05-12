<?php
include '../db/db.php';
include '../authCheck.php';

$message = "";

if(isset($_POST['registerBook'])){

    $title = $_POST['title'];
    $id= $_POST['ID'];
    $category = $_POST['category'];

    $sql = "INSERT INTO book
    (book_id, book_name, category_id)
    VALUES
    ('$id', '$title', '$category')";

    if($conn->query($sql)){
        $message = "Book registered successfully!";
    }else{
        $message = "Failed to register book.";
    }
}

$categoryResult = $conn->query("SELECT * FROM bookcategory");

/* GET NEXT CATEGORY ID */
$nextCategoryID = "B001";
$resultID = $conn->query("
    SELECT book_id
    FROM book
    ORDER BY CAST(SUBSTRING(book_id, 2) AS UNSIGNED) DESC
    LIMIT 1
");

if($resultID->num_rows > 0){
    $rowID = $resultID->fetch_assoc();
    $lastID = $rowID['book_id'];
    $number = (int) substr($lastID, 1);
    $number++;
    $nextbookID = "B" . str_pad($number, 3, "0", STR_PAD_LEFT);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Registration</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body{
            background-color: #f5f7fb;
        }

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

        .form-card{
            border: none;
            border-radius: 15px;
        }

        .form-control,
        .form-select{
            padding: 12px;
            border-radius: 10px;
        }

        .page-title{
            font-weight: 700;
            color: #162E93;
        }
    </style>
</head>
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
                <div>
                </div>
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

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="page-title">Book Registration</h2>
                        <p class="text-muted mb-0">Register new books into the library inventory</p>
                    </div>

                    <a href="bookinventory.php" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i>
                        Back to Inventory
                    </a>
                </div>

                <?php if($message != ""){ ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <?php echo $message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php } ?>

                <div class="card shadow-sm form-card">
                    <div class="card-body p-4">

                        <form method="POST">

                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Book Title</label>
                                    <input type="text"
                                           name="title"
                                           class="form-control"
                                           placeholder="Enter book title"
                                           required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Book ID Number</label>
                                    <input type="text"
                                           name="ID"
                                           class="form-control"
                                           value="<?php echo $nextbookID;
                                           ?>"
                                           readonly
                                           >
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Book Category</label>
                                    <select name="category" class="form-select" required>
                                        <option value="">Select Category</option>

                                        <?php while($category = $categoryResult->fetch_assoc()){ ?>
                                            <option value="<?php echo $category['category_id']; ?>">
                                                <?php echo $category['category_Name']; ?>
                                            </option>
                                        <?php } ?>

                                    </select>
                                </div>

                                <div class="col-12 mt-3">
                                    <button type="submit"
                                            name="registerBook"
                                            class="btn btn-primary px-4">
                                        <i class="bi bi-save"></i>
                                        Register Book
                                    </button>

                                    <button type="reset"
                                            class="btn btn-secondary px-4 ms-2">
                                        <i class="bi bi-arrow-clockwise"></i>
                                        Reset
                                    </button>
                                </div>

                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>