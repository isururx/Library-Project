<?php
include '../db/db.php';


$query = "SELECT bb.*, b.book_name FROM bookborrower bb 
          LEFT JOIN book b ON bb.book_id = b.book_id";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOOK BORROWING OPS - Lexicon Admin</title>
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
                    <i class="bi bi-person-badge" style="padding: 10px;"></i> Staff Management
                </a>
            </li>

            <li class="nav-item">
                <a href="books.php" class="nav-link text-white">
                    <i class="bi bi-book" style="padding: 10px;"></i> Book Inventory
                </a>
            </li>

            <li>
                <a href="bookCategory.php" class="nav-link text-white">
                    <i class="bi bi-grid" style="padding: 10px;"></i> Categories
                </a>
            </li>

            <li class="nav-item">
                <a href="members.php" class="nav-link text-white">
                    <i class="bi bi-people" style="padding: 10px;"></i> Member Registry
                </a>
            </li>

            <li class="nav-item">
                <a href="borrowing_book" class="nav-link active">
                    <i class="bi bi-arrow-left-right" style="padding: 10px;"></i> Borrowing Ops
                </a>
            </li>

            <li class="nav-item">
                <a href="fines.php" class="nav-link text-white">
                    <i class="bi bi-cash-stack" style="padding: 10px;"></i> Fines Management
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

            <div class="p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h1>Book Borrowing Details</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBorrowModal">
                        <i class="bi bi-plus-lg me-2"></i> Add Borrow Record
                    </button>
                </div>

                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-body">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Borrow ID</th>
                                    <th>Book ID</th>
                                    <th>Member ID</th>
                                    <th>Book Name</th>
                                    <th>Status</th>
                                    <th>Modified Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()){ ?>
                                <tr>
                                    <td><strong><?php echo $row['borrow_id']; ?></strong></td>
                                    <td><?php echo $row['book_id']; ?></td>
                                    <td><?php echo $row['member_id']; ?></td>
                                    <td><?php echo $row['book_name']; ?></td>
                                    <td>
                                        <span class="badge <?php echo ($row['borrow_status'] == 'borrowed') ? 'bg-danger' : 'bg-success'; ?>">
                                            <?php echo ucfirst($row['borrow_status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo $row['borrower_date_modified']; ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm edit-btn" 
                                            data-id="<?php echo $row['borrow_id']; ?>"
                                            data-book="<?php echo $row['book_id']; ?>"
                                            data-member="<?php echo $row['member_id']; ?>"
                                            data-status="<?php echo $row['borrow_status']; ?>"
                                            data-bs-toggle="modal" data-bs-target="#editBorrowModal">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <a href="delete_borrow.php?id=<?php echo $row['borrow_id']; ?>" 
                                           class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
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

<div class="modal fade" id="addBorrowModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Borrow Entry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            
            <form action="insert_borrow.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Borrow ID</label>
                        <input type="text" name="borrow_id" class="form-control" placeholder="BR001" pattern="BR[0-9]{3}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Book ID</label>
                        <input type="text" name="book_id" class="form-control" placeholder="B001" pattern="B[0-9]{3}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Member ID</label>
                        <input type="text" name="member_id" class="form-control" placeholder="M001" pattern="M[0-9]{3}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="borrowed">Borrowed</option>
                            <option value="available">Available</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100">Add Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editBorrowModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Edit Borrow Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="update_borrow.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="borrow_id" id="edit_id">
                    <div class="mb-3">
                        <label class="form-label">Book ID</label>
                        <input type="text" name="book_id" id="edit_book" class="form-control" pattern="B[0-9]{3}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Member ID</label>
                        <input type="text" name="member_id" id="edit_member" class="form-control" pattern="M[0-9]{3}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Borrow Status</label>
                        <select name="status" id="edit_status" class="form-select">
                            <option value="borrowed">Borrowed</option>
                            <option value="available">Available</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning w-100">Update Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>


    // Edit Button logic
    
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('edit_id').value = this.dataset.id;
            document.getElementById('edit_book').value = this.dataset.book;
            document.getElementById('edit_member').value = this.dataset.member;
            document.getElementById('edit_status').value = this.dataset.status;
        });
    });
</script>

</body>
</html>