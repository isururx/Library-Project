<?php
include '../db/db.php';

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Registration - Lexicon Admin</title>

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

            <div class="d-flex flex-column flex-shrink-0 p-3 bg-dark text-white vh-100">

                <a class="navbar-brand mb-4 d-flex align-items-center gap-2 text-white" href="#">

                    <i class="bi bi-building-fill text-primary fs-3"></i>

                    <div>
                        <div class="fw-bold fs-4">Lexicon Admin</div>
                        <small class="text-secondary">Institutional Portal</small>
                    </div>

                </a>

                <ul class="nav nav-pills flex-column mb-auto">

                    <li class="nav-item">
                        <a href="staff.php" class="nav-link text-white">
                            <i class="bi bi-person-badge" style="padding: 10px;"></i>
                            Staff Management
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="Book_inventory.php" class="nav-link active">
                            <i class="bi bi-book" style="padding: 10px;"></i>
                            Book Inventory
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="bookCategory.php" class="nav-link text-white">
                            <i class="bi bi-grid" style="padding: 10px;"></i>
                            Categories
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="members.php" class="nav-link text-white">
                            <i class="bi bi-people" style="padding: 10px;"></i>
                            Member Registry
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="borrow.php" class="nav-link text-white">
                            <i class="bi bi-arrow-left-right" style="padding: 10px;"></i>
                            Borrowing Ops
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="fines.php" class="nav-link text-white">
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

                <div class="container-fluid justify-content-end d-flex">
                    <i class="bi bi-person-circle text-white fs-3" style="padding-right: 10px;"></i>
                    <a class="navbar-brand" href="#">
                        User
                    </a>
                </div>

            </nav>

            <!-- PAGE CONTENT -->
            <div class="p-4">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="page-title">Book Registration</h2>
                        <p class="text-muted mb-0">Register new books into the library inventory</p>
                    </div>

                    <a href="Book_inventory.php" class="btn btn-outline-primary">
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
                                           placeholder="Enter Book ID number"
                                           required>
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