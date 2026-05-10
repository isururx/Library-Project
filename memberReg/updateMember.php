<?php
include '../db/db.php';
include '../authCheck.php';

// ----------------------------------------------------------
// STEP 1: Get the Member ID from the URL  e.g. ?id=M001
// If no ID is given, send back to the list
// ----------------------------------------------------------
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: memberReg.php?error=No member ID provided.");
    exit();
}

$member_id = $conn->real_escape_string($_GET['id']);

// ----------------------------------------------------------
// STEP 2: If Save button was clicked, handle the update
// $_SERVER['REQUEST_METHOD'] is POST only when a form is submitted
// ----------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Read the new values the user typed
    $new_first_name = trim($_POST['first_name']);
    $new_last_name  = trim($_POST['last_name']);
    $new_birthday   = trim($_POST['birthday']);
    $new_email      = trim($_POST['email']);

    // Validate email format before saving
    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address format.";
    } else {
        // Update the record in the database
        $sql = "UPDATE member
                SET first_name = '$new_first_name',
                    last_name  = '$new_last_name',
                    birthday   = '$new_birthday',
                    email      = '$new_email'
                WHERE member_id = '$member_id'";

        if ($conn->query($sql)) {
            // Success — go back to list with a green message
            header("Location: memberReg.php?success=Member $member_id updated successfully.");
            exit();
        } else {
            $error = "Database error: " . $conn->error;
        }
    }
}

// ----------------------------------------------------------
// STEP 3: Load the member's CURRENT data from the database
// This is used to pre-fill the form with existing details
// ----------------------------------------------------------
$result = $conn->query("SELECT * FROM member WHERE member_id = '$member_id'");

if ($result->num_rows === 0) {
    header("Location: memberReg.php?error=Member not found.");
    exit();
}

$member = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Member - LibraCore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Copied from your memberReg.php styles */
        .nav-link {
            padding: 12px 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            background-color: #052dcc;
            color: white !important;
        }
        .nav-link:hover i {
            color: white;
        }
        .form-control:focus {
            border-color: #162E93;
            box-shadow: 0 0 0 0.2rem rgba(22, 46, 147, 0.2);
        }
        .btn-primary {
            background-color: #162E93;
            border-color: #162E93;
        }
        .btn-primary:hover {
            background-color: #052dcc;
            border-color: #052dcc;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">

        <!-- ================================================
             SIDEBAR — same as your memberReg.php
        ================================================ -->
        <div class="col-md-2 p-0">
            <div class="d-flex flex-column flex-shrink-0 p-3 bg-dark text-white vh-100">

                <a class="navbar-brand mb-4 d-flex align-items-center text-white px-2 py-3" href="#">
                    <i class="bi bi-book-half text-primary me-3" style="font-size: 40px;"></i>
                    <div>
                        <h2 class="fw-bold mb-0" style="font-size: 30px;">LibraCore</h2>
                        <small class="text-secondary">Library Portal</small>
                    </div>
                </a>

                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <a href="../bookRegistration/bookInventory.php" class="nav-link text-white">
                            <i class="bi bi-book" style="padding: 10px;"></i> Book Inventory
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../bookCategory/bookCategory.php" class="nav-link text-white">
                            <i class="bi bi-grid" style="padding: 10px;"></i> Categories
                        </a>
                    </li>
                    <li class="nav-item">
                        <!-- Member Registry stays highlighted since we came from there -->
                        <a href="memberReg.php" class="nav-link active">
                            <i class="bi bi-people" style="padding: 10px;"></i> Member Registry
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../borrowingOps/borrowingBook.php" class="nav-link text-white">
                            <i class="bi bi-arrow-left-right" style="padding: 10px;"></i> Borrowing Ops
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../fineManage/fineManage.php" class="nav-link text-white">
                            <i class="bi bi-cash-stack" style="padding: 10px;"></i> Fines Management
                        </a>
                    </li>
                </ul>

            </div>
        </div>
        <!-- END SIDEBAR -->

        <!-- MAIN CONTENT -->
        <div class="col-md-10 p-0">

            <!-- NAVBAR — same as your memberReg.php -->
            <nav class="navbar navbar-expand-lg navbar-dark px-3" style="background-color: #162E93;">
                <form class="d-flex mx-auto">
                    <input class="form-control me-3" type="search"
                           placeholder="Search" style="width: 300px;">
                    <button type="button" class="btn btn-secondary">Search</button>
                </form>
                <div class="dropdown">
                    <a class="btn btn-outline-light dropdown-toggle" href="#"
                       role="button" data-bs-toggle="dropdown">
                        <?php echo $_SESSION['username']; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item text-danger" href="../logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- END NAVBAR -->

            <!-- PAGE CONTENT -->
            <div class="p-4">

                <!-- Back button + page title -->
                <div class="d-flex align-items-center mb-4">
                    <a href="memberReg.php" class="btn btn-outline-secondary me-3">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                    <h1 class="mb-0">Edit Member</h1>
                </div>

                <!-- Error message shown if email validation failed -->
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <!-- ============================================
                     EDIT FORM
                     - action posts back to this same file
                     - All inputs are pre-filled with current data
                       using  value="<?php echo $member['field']; ?>"
                     - Member ID is read-only (cannot be changed
                       because it is the primary key in the DB)
                ============================================ -->
                <div class="card shadow-sm border-0" style="max-width: 580px;">
                    <div class="card-body p-4">

                        <form action="updateMember.php?id=<?php echo urlencode($member_id); ?>" method="POST">

                            <!-- Member ID — displayed but NOT editable -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Member ID</label>
                                <input type="text"
                                       class="form-control bg-light"
                                       value="<?php echo htmlspecialchars($member['member_id']); ?>"
                                       readonly>
                                <small class="text-muted">Member ID cannot be changed.</small>
                            </div>

                            <!-- First Name — pre-filled with current value -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">First Name</label>
                                <input type="text"
                                       class="form-control"
                                       name="first_name"
                                       value="<?php echo htmlspecialchars($member['first_name']); ?>"
                                       required>
                            </div>

                            <!-- Last Name — pre-filled with current value -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Last Name</label>
                                <input type="text"
                                       class="form-control"
                                       name="last_name"
                                       value="<?php echo htmlspecialchars($member['last_name']); ?>"
                                       required>
                            </div>

                            <!-- Birthday — pre-filled with current value -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Birthday</label>
                                <input type="date"
                                       class="form-control"
                                       name="birthday"
                                       value="<?php echo htmlspecialchars($member['birthday']); ?>"
                                       required>
                            </div>

                            <!-- Email — pre-filled with current value -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email"
                                       class="form-control"
                                       name="email"
                                       value="<?php echo htmlspecialchars($member['email']); ?>"
                                       required>
                            </div>

                            <!-- Save and Cancel buttons -->
                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i> Save Changes
                                </button>
                                <a href="memberReg.php" class="btn btn-outline-secondary">
                                    Cancel
                                </a>
                            </div>

                        </form>

                    </div>
                </div>

            </div><!-- /p-4 -->
        </div><!-- /col-md-10 -->
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>