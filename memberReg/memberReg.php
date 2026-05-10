<?php
include '../db/db.php';
include '../authCheck.php';
$result = $conn->query("SELECT * FROM member");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Registry - Lexicon Admin</title>
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

        /* Member page specific styles */
        .stat-card {
            border-left: 4px solid #162E93;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.1) !important;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(22, 46, 147, 0.05);
        }

        .badge-active {
            background-color: #d4edda;
            color: #155724;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 500;
        }

        .add-member-card {
            border-left: 4px solid #28a745;
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

        .avatar-circle {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background-color: #162E93;
            color: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 600;
            margin-right: 8px;
        }

        .member-name-cell {
            display: flex;
            align-items: center;
        }

        /* Validation feedback */
        .input-error {
            border-color: #dc3545 !important;
        }

        .validation-msg {
            font-size: 0.78rem;
            color: #dc3545;
            display: none;
            margin-top: 3px;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR (unchanged from bookCategory.php) -->
        <div class="col-md-2 p-0">
            <div class="d-flex flex-column flex-shrink-0 p-3 bg-dark text-white vh-100">
                <a class="navbar-brand mb-4 d-flex align-items-center gap-2 text-white" href="#">
                    <i class="bi bi-building-fill text-primary fs-3"></i>
                    <div>
                        <div class="fw-bold fs-4">LibraCore</div>
                        <small class="text-secondary">Institutional Portal</small>
                    </div>
                </a>

                <ul class="nav nav-pills flex-column mb-auto gap-1 list-unstyled">
                    <li class="nav-item">
                        <a href="books.php" class="nav-link text-white">
                            <i class="bi bi-book" style="padding: 10px;"></i>
                            Book Inventory
                        </a>
                    </li>

                    <li>
                        <a href="bookCategory.php" class="nav-link text-white">
                            <i class="bi bi-grid" style="padding: 10px;"></i>
                            Categories
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="members.php" class="nav-link active">
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

            <!-- TOP NAVBAR (unchanged from bookCategory.php) -->
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
                            <a class="dropdown-item text-danger" href="logout.php">
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>

            </nav>

            <!-- PAGE CONTENT -->
            <div class="p-4">

                <h1>Member Registry</h1>

                <!-- SUMMARY & ADD MEMBER CARDS -->
                <div class="row mt-4 g-3">

                    <!-- TOTAL MEMBERS -->
                    <div class="col-md-3">
                        <div class="card shadow-sm border-0 stat-card h-100">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-muted">Total Members</h6>
                                    <h2 class="fw-bold">
                                        <?php
                                            $countResult = $conn->query("SELECT COUNT(*) as total FROM member");
                                            $countRow = $countResult->fetch_assoc();
                                            echo $countRow['total'];
                                        ?>
                                    </h2>
                                </div>
                                <i class="bi bi-people-fill fs-1 text-primary opacity-75"></i>
                            </div>
                        </div>
                    </div>

                    <!-- ADD MEMBER FORM -->
                    <div class="col-md-9">
                        <div class="card shadow-sm border-0 add-member-card h-100">
                            <div class="card-body">
                                <h6 class="text-muted mb-3">
                                    <i class="bi bi-person-plus-fill me-1 text-success"></i>
                                    Register New Member
                                </h6>

                                <form action="addMember.php" method="POST" id="addMemberForm">
                                    <div class="row g-2">

                                        <!-- Member ID -->
                                        <div class="col-md-2">
                                            <input type="text"
                                                class="form-control form-control-sm"
                                                name="member_id"
                                                id="member_id"
                                                placeholder="M001"
                                                required>
                                            <div class="validation-msg" id="idError">
                                                Format: M001
                                            </div>
                                        </div>

                                        <!-- First Name -->
                                        <div class="col-md-2">
                                            <input type="text"
                                                class="form-control form-control-sm"
                                                name="first_name"
                                                placeholder="First Name"
                                                required>
                                        </div>

                                        <!-- Last Name -->
                                        <div class="col-md-2">
                                            <input type="text"
                                                class="form-control form-control-sm"
                                                name="last_name"
                                                placeholder="Last Name"
                                                required>
                                        </div>

                                        <!-- Birthday -->
                                        <div class="col-md-2">
                                            <input type="date"
                                                class="form-control form-control-sm"
                                                name="birthday"
                                                required>
                                        </div>

                                        <!-- Email -->
                                        <div class="col-md-3">
                                            <input type="email"
                                                class="form-control form-control-sm"
                                                name="email"
                                                id="email"
                                                placeholder="email@example.com"
                                                required>
                                            <div class="validation-msg" id="emailError">
                                                Invalid email format
                                            </div>
                                        </div>

                                        <!-- Submit -->
                                        <div class="col-md-1">
                                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                                <i class="bi bi-plus-lg"></i>
                                            </button>
                                        </div>

                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>

                </div>

                <!-- SUCCESS / ERROR ALERTS -->
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Member <?php echo htmlspecialchars($_GET['success']); ?> successfully.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php elseif (isset($_GET['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <?php echo htmlspecialchars($_GET['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- MEMBER TABLE -->
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="mb-0">
                                <i class="bi bi-table me-2 text-primary"></i>
                                Registered Members
                            </h4>
                            <span class="badge bg-primary rounded-pill px-3 py-2">
                                <?php echo $countRow['total']; ?> Members
                            </span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">

                                <thead class="table-dark">
                                    <tr>
                                        <th>Member ID</th>
                                        <th>Name</th>
                                        <th>Last Name</th>
                                        <th>Birthday</th>
                                        <th>Email</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    // Re-run query for the table since countResult consumed it
                                    $tableResult = $conn->query("SELECT * FROM member");
                                    while ($row = $tableResult->fetch_assoc()):
                                        // Generate initials for avatar
                                        $initials = strtoupper(substr($row['first_name'], 0, 1) . substr($row['last_name'], 0, 1));
                                    ?>
                                        <tr>
                                            <td>
                                                <span class="badge bg-secondary rounded-pill">
                                                    <?php echo htmlspecialchars($row['member_id']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="member-name-cell">
                                                    <span class="avatar-circle"><?php echo $initials; ?></span>
                                                    <?php echo htmlspecialchars($row['first_name']); ?>
                                                </div>
                                            </td>
                                            <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                                            <td>
                                                <i class="bi bi-calendar3 me-1 text-muted"></i>
                                                <?php echo htmlspecialchars($row['birthday']); ?>
                                            </td>
                                            <td>
                                                <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>" class="text-decoration-none">
                                                    <i class="bi bi-envelope me-1 text-muted"></i>
                                                    <?php echo htmlspecialchars($row['email']); ?>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <a href="updateMember.php?id=<?php echo $row['member_id']; ?>"
                                                    class="btn btn-warning btn-sm me-1"
                                                    title="Edit Member">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <a href="deleteMember.php?id=<?php echo $row['member_id']; ?>"
                                                    class="btn btn-danger btn-sm"
                                                    title="Delete Member"
                                                    onclick="return confirm('Delete member <?php echo htmlspecialchars($row['member_id']); ?>? This cannot be undone.');">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>

                            </table>
                        </div>

                        <?php if ($countRow['total'] == 0): ?>
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-1 d-block mb-2 opacity-25"></i>
                                No members registered yet. Add one above!
                            </div>
                        <?php endif; ?>

                    </div>
                </div>

            </div><!-- /p-4 -->
        </div><!-- /col-md-10 -->
    </div><!-- /row -->
</div><!-- /container-fluid -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Client-side validation
    document.getElementById('addMemberForm').addEventListener('submit', function(e) {
        let valid = true;

        // Validate Member ID format: M followed by digits (e.g. M001)
        const memberIdInput = document.getElementById('member_id');
        const idError = document.getElementById('idError');
        const idPattern = /^M\d+$/;

        if (!idPattern.test(memberIdInput.value.trim())) {
            memberIdInput.classList.add('input-error');
            idError.style.display = 'block';
            valid = false;
        } else {
            memberIdInput.classList.remove('input-error');
            idError.style.display = 'none';
        }

        // Validate Email format
        const emailInput = document.getElementById('email');
        const emailError = document.getElementById('emailError');
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailPattern.test(emailInput.value.trim())) {
            emailInput.classList.add('input-error');
            emailError.style.display = 'block';
            valid = false;
        } else {
            emailInput.classList.remove('input-error');
            emailError.style.display = 'none';
        }

        if (!valid) e.preventDefault();
    });

    // Live feedback on Member ID input
    document.getElementById('member_id').addEventListener('input', function() {
        const idPattern = /^M\d+$/;
        const idError = document.getElementById('idError');
        if (!idPattern.test(this.value.trim()) && this.value.trim() !== '') {
            this.classList.add('input-error');
            idError.style.display = 'block';
        } else {
            this.classList.remove('input-error');
            idError.style.display = 'none';
        }
    });
</script>

</body>
</html>