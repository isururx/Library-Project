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
                        <a href="books.php" class="nav-link text-white">
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
            <nav class="navbar navbar-expand-lg bg-primary navbar-dark px-3">

                <form class="d-flex">
                    <input class="form-control me-3"
                        type="search"
                        placeholder="Search">

                    <button class="btn btn-light">
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

                <h2>Book Categories</h2>

            </div>

        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</body>
</html>