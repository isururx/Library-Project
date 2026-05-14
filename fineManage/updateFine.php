<?php
include '../db/db.php';

$message = "";
$message_type = "";

/* If no fine ID is passed in the URL, go back */
if (!isset($_GET['id'])) {
    header("Location: fineManage.php");
    exit();
}

$fine_id = $_GET['id'];

/* Get selected fine record */
$stmt = $conn->prepare("SELECT * FROM fine WHERE fine_id = ?");
$stmt->bind_param("s", $fine_id);
$stmt->execute();
$result = $stmt->get_result();

/* If fine ID does not exist, go back */
if ($result->num_rows == 0) {
    header("Location: fineManage.php");
    exit();
}

$fine = $result->fetch_assoc();

/* Update fine */
if (isset($_POST['update_fine'])) {

    $member_id = trim($_POST['member_id']);
    $book_id = trim($_POST['book_id']);
    $fine_amount = trim($_POST['fine_amount']);

    if ($fine_amount < 2 || $fine_amount > 500) {
        $message = "Fine amount must be between LKR 2 and LKR 500.";
        $message_type = "danger";
    } else {

        $update_stmt = $conn->prepare("
            UPDATE fine
            SET member_id = ?,
                book_id = ?,
                fine_amount = ?,
                fine_date_modified = NOW()
            WHERE fine_id = ?
        ");

        $update_stmt->bind_param("ssss", $member_id, $book_id, $fine_amount, $fine_id);

        if ($update_stmt->execute()) {
            header("Location: fineManage.php?success=updated");
            exit();
        } else {
            $message = "Error updating fine: " . $conn->error;
            $message_type = "danger";
        }
    }
}

/* Load dropdown data */
$members = $conn->query("SELECT member_id, first_name, last_name FROM member ORDER BY member_id ASC");
$books = $conn->query("SELECT book_id, book_name FROM book ORDER BY book_id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Fine - Lexicon Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <h2 class="mb-4">Update Fine</h2>

            <?php if (!empty($message)) { ?>
                <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                    <?php echo $message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php } ?>

            <form method="POST" action="">

                <div class="mb-3">
                    <label class="form-label">Fine ID</label>
                    <input type="text"
                           class="form-control"
                           value="<?php echo htmlspecialchars($fine['fine_id']); ?>"
                           readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Member ID</label>

                    <select name="member_id" class="form-select" required>

                        <?php while ($member = $members->fetch_assoc()) { ?>

                            <option value="<?php echo htmlspecialchars($member['member_id']); ?>"
                                <?php if ($member['member_id'] == $fine['member_id']) echo "selected"; ?>>

                                <?php
                                    echo htmlspecialchars(
                                        $member['member_id'] . " - " .
                                        $member['first_name'] . " " .
                                        $member['last_name']
                                    );
                                ?>

                            </option>

                        <?php } ?>

                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Book ID</label>

                    <select name="book_id" class="form-select" required>

                        <?php while ($book = $books->fetch_assoc()) { ?>

                            <option value="<?php echo htmlspecialchars($book['book_id']); ?>"
                                <?php if ($book['book_id'] == $fine['book_id']) echo "selected"; ?>>

                                <?php
                                    echo htmlspecialchars(
                                        $book['book_id'] . " - " . $book['book_name']
                                    );
                                ?>

                            </option>

                        <?php } ?>

                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Fine Amount (LKR)</label>
                    <input type="number"
                           name="fine_amount"
                           class="form-control"
                           min="2"
                           max="500"
                           value="<?php echo htmlspecialchars($fine['fine_amount']); ?>"
                           required>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" name="update_fine" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i>
                        Update Fine
                    </button>

                    <a href="fineManage.php" class="btn btn-secondary">
                        Back
                    </a>
                </div>

                <small class="text-muted d-block mt-3">
                    Fine amount must be between LKR 2 and LKR 500.
                </small>

            </form>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>