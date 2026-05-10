<?php
include '../db/db.php';
include '../authCheck.php';

/* SUGGEST NEXT FINE ID */
$next_fine_id = "F001";

$last_fine_result = $conn->query("
    SELECT fine_id 
    FROM fine 
    ORDER BY CAST(SUBSTRING(fine_id, 2) AS UNSIGNED) DESC 
    LIMIT 1
");

if ($last_fine_result->num_rows > 0) {
    $last_fine = $last_fine_result->fetch_assoc();
    $last_id = $last_fine['fine_id'];   // Example: F005

    $number = (int) substr($last_id, 1); // Gets 005 as number 5
    $number++;                          // 5 becomes 6

    $next_fine_id = "F" . str_pad($number, 3, "0", STR_PAD_LEFT);
}

$message = "";
$message_type = "";

/* ADD FINE */
if (isset($_POST['add_fine'])) {

    $fine_id = trim($_POST['fine_id']);
    $member_id = trim($_POST['member_id']);
    $book_id = trim($_POST['book_id']);
    $fine_amount = trim($_POST['fine_amount']);

    if ($fine_amount < 2 || $fine_amount > 500) {
        $message = "Fine amount must be between LKR 2 and LKR 500.";
        $message_type = "danger";
    } else {

        $check = $conn->prepare("SELECT fine_id FROM fine WHERE fine_id = ?");
        $check->bind_param("s", $fine_id);
        $check->execute();
        $check_result = $check->get_result();

        if ($check_result->num_rows > 0) {
            $message = "Fine ID already exists.";
            $message_type = "danger";
        } else {

            $stmt = $conn->prepare("
                INSERT INTO fine 
                (fine_id, book_id, member_id, fine_amount, fine_date_modified)
                VALUES (?, ?, ?, ?, NOW())
            ");

            $stmt->bind_param("ssss", $fine_id, $book_id, $member_id, $fine_amount);

            if ($stmt->execute()) {
                header("Location: fineManage.php?success=added");
                exit();
            } else {
                $message = "Error adding fine: " . $conn->error;
                $message_type = "danger";
            }
        }
    }
}

/* SUCCESS MESSAGE */
if (isset($_GET['success'])) {
    if ($_GET['success'] == "added") {
        $message = "Fine added successfully.";
        $message_type = "success";
    } elseif ($_GET['success'] == "updated") {
        $message = "Fine updated successfully.";
        $message_type = "success";
    } elseif ($_GET['success'] == "deleted") {
        $message = "Fine deleted successfully.";
        $message_type = "success";
    }
}

/* SUMMARY DATA */
$total_fines_result = $conn->query("SELECT COUNT(*) AS total_fines FROM fine");
$total_fines = $total_fines_result->fetch_assoc()['total_fines'];

$total_amount_result = $conn->query("SELECT SUM(CAST(fine_amount AS DECIMAL(10,2))) AS total_amount FROM fine");
$total_amount = $total_amount_result->fetch_assoc()['total_amount'];

if ($total_amount == NULL) {
    $total_amount = 0;
}

/* DROPDOWN DATA */
$members = $conn->query("SELECT member_id, first_name, last_name FROM member ORDER BY member_id ASC");
$books = $conn->query("SELECT book_id, book_name FROM book ORDER BY book_id ASC");

/* DISPLAY FINE RECORDS */
$fine_result = $conn->query("
    SELECT 
        f.fine_id,
        f.member_id,
        CONCAT(m.first_name, ' ', m.last_name) AS member_name,
        b.book_name,
        f.fine_amount,
        f.fine_date_modified
    FROM fine f
    JOIN member m ON f.member_id = m.member_id
    JOIN book b ON f.book_id = b.book_id
    ORDER BY f.fine_id ASC
");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fines Management</title>
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

        html, body {
            min-height: 100%;
        }

        .sidebar {
            min-height: 100vh;
            height: 100%;
            background-color: #212529;
        }

        .main-row {
            min-height: 100vh;
        }

    </style>
</head>

<body>


<div class="container-fluid">
    <div class="row main-row">

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
                        <a href="../bookCategory/bookCategory.php" class="nav-link text-white">
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
                        <a href="fineManage.php" class= "nav-link active">
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

                <h1>Manage Library Fines</h1>
                <?php if (!empty($message)) { ?>
                    <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show mt-3" role="alert">
                        <?php echo $message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php } ?>

                <!-- SUMMARY CARDS -->
                <div class="row mt-4">

                    <!-- TOTAL FINES -->
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-muted">Total Fines</h6>
                                    <h2 class="fw-bold"><?php echo $total_fines; ?></h2>
                                </div>
                                <i class="bi bi-cash-stack fs-1 text-primary"></i>
                            </div>
                        </div>
                    </div>

                    <!-- TOTAL FINE AMOUNT -->
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-muted">Total Fine Amount</h6>
                                    <h2 class="fw-bold">LKR <?php echo number_format($total_amount, 2); ?></h2>
                                </div>
                                <i class="bi bi-wallet2 fs-1 text-success"></i>
                            </div>
                        </div>
                    </div>

                    <!-- FINE RANGE INFO -->
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-muted">Allowed Fine Range</h6>
                                    <h2 class="fw-bold">2 - 500</h2>
                                    <small class="text-muted">LKR per fine</small>
                                </div>
                                <i class="bi bi-exclamation-circle fs-1 text-warning"></i>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- ADD FINE FORM -->
                <div class="card shadow-sm border-0 mt-4">

                    <div class="card-body">

                        <h4 class="mb-3">Add New Fine</h4>

                        <form method="POST" action="">

                            <div class="row g-3">

                                <div class="col-md-3">
                                    <label class="form-label">Fine ID</label>
                                    <input type="text" 
                                        name="fine_id" 
                                        class="form-control" 
                                        value="<?php echo $next_fine_id; ?>" 
                                        readonly
                                        required>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Member ID</label>
                                    <select name="member_id" 
                                        class="form-select" 
                                        required
                                        oninvalid="this.setCustomValidity('Please select the library member.')"
                                        onchange="this.setCustomValidity('')">

                                    <option value="" selected disabled>Select Member</option>

                                        <?php while ($member = $members->fetch_assoc()) { ?>
                                            <option value="<?php echo $member['member_id']; ?>">
                                                <?php 
                                                    echo $member['member_id'] . " - " . 
                                                        $member['first_name'] . " " . 
                                                        $member['last_name']; 
                                                ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Book ID</label>
                                    <select name="book_id" 
                                        class="form-select" 
                                        required
                                        oninvalid="this.setCustomValidity('Please select the book.')"
                                        onchange="this.setCustomValidity('')">

                                        <option value="" selected disabled>Select Book</option>

                                        <?php while ($book = $books->fetch_assoc()) { ?>
                                            <option value="<?php echo $book['book_id']; ?>">
                                                <?php echo $book['book_id'] . " - " . $book['book_name']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Fine Amount (LKR)</label>
                                    <input type="number" 
                                        name="fine_amount" 
                                        class="form-control" 
                                        placeholder="Amount" 
                                        min="2" 
                                        max="500" 
                                        required
                                        oninvalid="this.setCustomValidity('Please enter a fine amount between LKR 2 and LKR 500.')"
                                        oninput="this.setCustomValidity('')">
                                </div>

                            </div>

                            <div class="mt-3 d-flex gap-2">
                                <button type="submit" name="add_fine" class="btn btn-primary">
                                    <i class="bi bi-plus-lg"></i>
                                    Add Fine
                                </button>

                                <button type="reset" class="btn btn-secondary">
                                    Reset
                                </button>
                            </div>

                            <small class="text-muted d-block mt-3">
                                Fine amount must be between LKR 2 and LKR 500.
                            </small>

                        </form>

                    </div>

                </div>

                <!-- FINES TABLE -->
                <div class="card shadow-sm border-0 mt-5">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <h4 class="mb-0">Assigned Fines</h4>

                            <input type="text" name="fine_search" class="form-control w-25" placeholder="Search fines">

                        </div>

                        <div class="table-responsive">

                            <table class="table table-hover align-middle">

                                <thead class="table-dark">
                                    <tr>
                                        <th>Fine ID</th>
                                        <th>Member ID</th>
                                        <th>Member Name</th>
                                        <th>Book Name</th>
                                        <th>Fine Amount</th>
                                        <th>Date Modified</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php if ($fine_result->num_rows > 0) { ?>

                                        <?php while ($row = $fine_result->fetch_assoc()) { ?>

                                            <tr>
                                                <td><?php echo htmlspecialchars($row['fine_id']); ?></td>

                                                <td><?php echo htmlspecialchars($row['member_id']); ?></td>

                                                <td><?php echo htmlspecialchars($row['member_name']); ?></td>

                                                <td><?php echo htmlspecialchars($row['book_name']); ?></td>

                                                <td>
                                                    <span class="badge bg-warning text-dark">
                                                        LKR <?php echo number_format((float)$row['fine_amount'], 2); ?>
                                                    </span>
                                                </td>

                                                <td><?php echo htmlspecialchars($row['fine_date_modified']); ?></td>

                                                <td>
                                                    <a href="updateFine.php?id=<?php echo urlencode($row['fine_id']); ?>" 
                                                    class="btn btn-sm btn-warning">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>

                                                    <a href="deleteFine.php?id=<?php echo urlencode($row['fine_id']); ?>" 
                                                        class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this fine?');">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>

                                        <?php } ?>

                                    <?php } else { ?>

                                        <tr>
                                            <td colspan="7" class="text-center text-muted">
                                                No fine records found.
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
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>



</body>
</html>