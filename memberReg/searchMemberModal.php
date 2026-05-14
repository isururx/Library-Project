<?php

if(isset($_GET['search'])){

    include '../db/db.php';

    $search = trim($_GET['search']);

    $stmt = $conn->prepare("
        SELECT *
        FROM member
        WHERE first_name LIKE ?
           OR last_name LIKE ?
           OR member_id LIKE ?
           OR email LIKE ?
    ");

    $searchTerm = "%$search%";

    $stmt->bind_param(
        "ssss",
        $searchTerm,
        $searchTerm,
        $searchTerm,
        $searchTerm
    );

    $stmt->execute();

    $searchResult = $stmt->get_result();

?>

<!-- SEARCH RESULT MODAL -->

<div class="modal fade show"
     id="searchModal"
     tabindex="-1"
     style="display:block; background: rgba(0,0,0,0.5);">

    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg">

            <!-- HEADER -->

            <div class="modal-header bg-primary text-white">

                <h5 class="modal-title">

                    <i class="bi bi-search me-2"></i>

                    Member Search Results

                </h5>

            </div>

            <!-- BODY -->

            <div class="modal-body">

                <?php if($searchResult->num_rows > 0){ ?>

                    <table class="table table-hover align-middle">

                        <thead class="table-dark">

                            <tr>

                                <th>Member ID</th>

                                <th>First Name</th>

                                <th>Last Name</th>

                                <th>Birthday</th>

                                <th>Email</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php while($row = $searchResult->fetch_assoc()){ ?>

                                <tr>

                                    <td>
                                        <?php echo $row['member_id']; ?>
                                    </td>

                                    <td>
                                        <?php echo $row['first_name']; ?>
                                    </td>

                                    <td>
                                        <?php echo $row['last_name']; ?>
                                    </td>

                                    <td>
                                        <?php echo $row['birthday']; ?>
                                    </td>

                                    <td>
                                        <?php echo $row['email']; ?>
                                    </td>

                                </tr>

                            <?php } ?>

                        </tbody>

                    </table>

                <?php } else { ?>

                    <div class="alert alert-danger mb-0 text-center">

                        No Members Found

                    </div>

                <?php } ?>

            </div>

            <!-- FOOTER -->

            <div class="modal-footer">

                <a href="memberReg.php"
                   class="btn btn-secondary">

                    <i class="bi bi-arrow-left me-1"></i>

                    Back

                </a>

            </div>

        </div>

    </div>

</div>

<?php } ?>