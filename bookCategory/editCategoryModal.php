<!-- EDIT CATEGORY MODAL -->

<div class="modal fade"
     id="editCategoryModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <!-- HEADER -->
            <div class="modal-header bg-warning">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-pencil-square me-2"></i>
                    Edit Category
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <!-- FORM -->

            <form action="updateCategory.php" method="POST">

                <div class="modal-body">

                    <!-- CATEGORY ID -->

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Category ID
                        </label>

                        <input type="text"
                               name="category_id"
                               id="editCategoryID"
                               class="form-control"
                               readonly>

                    </div>

                    <!-- CATEGORY NAME -->

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Category Name
                        </label>

                        <input type="text"
                               name="category_name"
                               id="editCategoryName"
                               class="form-control"
                               required>

                    </div>

                </div>

                <!-- FOOTER -->

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit"
                            name="update"
                            class="btn btn-warning">
                        <i class="bi bi-check-lg"></i>
                        Update Category
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>