<?php

if(isset($_GET['search'])){

    include '../db/db.php';

    $search = trim($_GET['search']);

    $stmt = $conn->prepare("
        SELECT *
        FROM bookcategory
        WHERE category_Name LIKE ?
    ");

    $searchTerm = "%$search%";

    $stmt->bind_param("s", $searchTerm);

    $stmt->execute();

    $searchResult = $stmt->get_result();

?>

<!-- SEARCH RESULT MODAL -->

<div class="modal fade show"
     id="searchModal"
     tabindex="-1"
     style="display:block; background: rgba(0,0,0,0.5);">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg">

            <!-- HEADER -->

            <div class="modal-header bg-primary text-white">

                <h5 class="modal-title">

                    <i class="bi bi-search me-2"></i>

                    Search Results

                </h5>

            </div>

            <!-- BODY -->

            <div class="modal-body">

                <?php if($searchResult->num_rows > 0){ ?>

                    <table class="table table-hover align-middle">

                        <thead class="table-dark">

                            <tr>

                                <th>Category ID</th>

                                <th>Category Name</th>

                                <th>Date Modified</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php while($row = $searchResult->fetch_assoc()){ ?>

                                <tr>

                                    <td>
                                        <?php echo $row['category_id']; ?>
                                    </td>

                                    <td>
                                        <?php echo $row['category_Name']; ?>
                                    </td>

                                    <td>
                                        <?php echo $row['date_modified']; ?>
                                    </td>

                                </tr>

                            <?php } ?>

                        </tbody>

                    </table>

                <?php } else { ?>

                    <div class="alert alert-danger mb-0 text-center">

                        No Categories Found

                    </div>

                <?php } ?>

            </div>

            <!-- FOOTER -->

            <div class="modal-footer">

                <a href="bookCategory.php"
                   class="btn btn-secondary">

                    <i class="bi bi-arrow-left me-1"></i>

                    Back

                </a>

            </div>

        </div>

    </div>

</div>

<?php } ?>