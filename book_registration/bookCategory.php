<?php
include '../db/db.php';
$result = $conn->query("SELECT * FROM bookcategory");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Categories - Lexicon Admin</title>
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
                        <a href="Book_reg.php" class="nav-link text-white">
                            <i class="bi bi-book" style="padding: 10px;"></i>
                            Book Inventory
                        </a>
                    </li>

                    <li>
                        <a href="bookCategory.php" class="nav-link active">
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

                                    <form class="d-flex gap-2">
                                        <input type="text"
                                            class="form-control"
                                            placeholder="Category name">
                                        <button type="submit"
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
                                            <a href="updateCategory.php?id=<?php echo $row['category_id']; ?>"
                                            class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <a href="deleteCategory.php?id=<?php echo $row['category_id']; ?>"
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</body>
</html>