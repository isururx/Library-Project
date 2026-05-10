<?php

session_start();

include '../db/db.php';
/* ADMIN ACCESS CHECK */

if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin"){

    header("Location: ../login.php");
    exit();

}

/* DELETE USER */

if(isset($_GET['delete'])){

    $userID = $_GET['delete'];

    $deleteSQL = "
        DELETE FROM user
        WHERE user_id = '$userID'
    ";

    $conn->query($deleteSQL);

    header("Location: userManagement.php");
    exit();

}

/* UPDATE USER */

if(isset($_POST['update'])){

    $userID = $_POST['user_id'];

    $firstName = $_POST['first_name'];

    $lastName = $_POST['last_name'];

    $username = $_POST['username'];

    $email = $_POST['email'];

    $updateSQL = "
        UPDATE user
        SET first_name = '$firstName',
            last_name = '$lastName',
            username = '$username',
            email = '$email'
        WHERE user_id = '$userID'
    ";

    if($conn->query($updateSQL) === TRUE){

        header("Location: userManagement.php");
        exit();

    }

}

/* FETCH USERS */

$users = $conn->query("
    SELECT *
    FROM user
    ORDER BY user_id ASC
");

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>User Management</title>

    <!-- BOOTSTRAP -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- BOOTSTRAP ICONS -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
          rel="stylesheet">

</head>

<body class="bg-light">

<div class="container py-5">

    <!-- HEADER -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold">
                User Management
            </h2>

            <p class="text-muted mb-0">
                LibraCore Administration Panel
            </p>

        </div>

        <div>

            <a href="../logout.php"
               class="btn btn-danger">

                <i class="bi bi-box-arrow-right me-1"></i>

                Logout

            </a>

        </div>

    </div>

    <!-- USER TABLE -->

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <table class="table table-hover align-middle">

                <thead class="table-dark">

                    <tr>

                        <th>User ID</th>

                        <th>First Name</th>

                        <th>Last Name</th>

                        <th>Username</th>

                        <th>Email</th>

                        <th width="150">
                            Actions
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <?php while($row = $users->fetch_assoc()){ ?>

                        <tr>

                            <td>
                                <?php echo $row['user_id']; ?>
                            </td>

                            <td>
                                <?php echo $row['first_name']; ?>
                            </td>

                            <td>
                                <?php echo $row['last_name']; ?>
                            </td>

                            <td>
                                <?php echo $row['username']; ?>
                            </td>

                            <td>
                                <?php echo $row['email']; ?>
                            </td>

                            <td>

                                <!-- EDIT BUTTON -->

                                <button
                                    class="btn btn-warning btn-sm editBtn"

                                    data-id="<?php echo $row['user_id']; ?>"

                                    data-first="<?php echo $row['first_name']; ?>"

                                    data-last="<?php echo $row['last_name']; ?>"

                                    data-username="<?php echo $row['username']; ?>"

                                    data-email="<?php echo $row['email']; ?>"

                                    data-bs-toggle="modal"
                                    data-bs-target="#editUserModal">

                                    <i class="bi bi-pencil-square"></i>

                                </button>

                                <!-- DELETE BUTTON -->

                                <a href="userManagement.php?delete=<?php echo $row['user_id']; ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Delete this user?');">

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

<!-- EDIT USER MODAL -->

<div class="modal fade"
     id="editUserModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header bg-warning">

                <h5 class="modal-title">

                    Edit User

                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <form method="POST">

                <div class="modal-body">

                    <!-- USER ID -->

                    <div class="mb-3">

                        <label class="form-label">
                            User ID
                        </label>

                        <input type="text"
                               name="user_id"
                               id="editUserID"
                               class="form-control"
                               readonly>

                    </div>

                    <!-- FIRST NAME -->

                    <div class="mb-3">

                        <label class="form-label">
                            First Name
                        </label>

                        <input type="text"
                               name="first_name"
                               id="editFirstName"
                               class="form-control"
                               required>

                    </div>

                    <!-- LAST NAME -->

                    <div class="mb-3">

                        <label class="form-label">
                            Last Name
                        </label>

                        <input type="text"
                               name="last_name"
                               id="editLastName"
                               class="form-control"
                               required>

                    </div>

                    <!-- USERNAME -->

                    <div class="mb-3">

                        <label class="form-label">
                            Username
                        </label>

                        <input type="text"
                               name="username"
                               id="editUsername"
                               class="form-control"
                               required>

                    </div>

                    <!-- EMAIL -->

                    <div class="mb-3">

                        <label class="form-label">
                            Email
                        </label>

                        <input type="email"
                               name="email"
                               id="editEmail"
                               class="form-control"
                               required>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="submit"
                            name="update"
                            class="btn btn-warning">

                        Update User

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- JS -->

<script>

    const editButtons = document.querySelectorAll(".editBtn");

    editButtons.forEach(button => {

        button.addEventListener("click", function(){

            document.getElementById("editUserID").value =
                this.dataset.id;

            document.getElementById("editFirstName").value =
                this.dataset.first;

            document.getElementById("editLastName").value =
                this.dataset.last;

            document.getElementById("editUsername").value =
                this.dataset.username;

            document.getElementById("editEmail").value =
                this.dataset.email;

        });

    });

</script>

<!-- BOOTSTRAP JS -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>