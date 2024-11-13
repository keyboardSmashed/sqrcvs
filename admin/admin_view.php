<style>
    .table td,
    .table th {
        font-size: 0.85rem;
        /* Adjust size as needed */
    }
</style>

<div class="pt-3" style="background-color: #FFA500;"></div>
<div class="container">
    <div class="row mt-5">
        <!-- Profile Card -->
        <div class="col-lg-3">
            <div class="card shadow rounded">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <?php if (isset($_SESSION['admin_id'])) { ?>
                            <div>
                                <?php if (isset($_SESSION['img'])) { ?>
                                    <img class="rounded-pill border border-light shadow-sm" style="max-width: 80px;" src="<?php echo $_SESSION['img']; ?>" alt="Image">
                                <?php } ?>
                            </div>
                            <div class="ms-4 text-start">
                                <div class="fs-3 fw-bold text-primary"><?php echo $_SESSION['first_name']; ?></div>
                                <div class="fs-5"><?php echo $_SESSION['mid_name']; ?></div>
                                <div class="fs-5"><?php echo $_SESSION['last_name']; ?></div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <!-- Recently Tagged Section -->
            <div class="mt-4 ps-2">
                <span class="fst-italic text-muted">Recently Tagged</span><br>
                <span class="fw-bold text-dark">Student 1</span>
            </div>
        </div>

        <!-- Scan Logs Section -->
        <div class="col-lg-6">
            <div class="card shadow rounded bg-white">
                <div class="card-body">
                    <div class="fs-4 fw-bold text-center mb-4">Scan Logs</div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Full Name</th>
                                <th>Date</th> <!-- Separate Date Column -->
                                <th>Time</th> <!-- Separate Time Column -->
                                <th>Type of Scan</th> <!-- Include Type of Scan -->
                            </tr>
                        </thead>
                        <tbody id="scan-logs-body">
                            <!-- Scan logs will be dynamically populated here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Manage Academic Year Details Section -->
        <div class="col-lg-3">
            <div class="card shadow rounded">
                <div class="card-body">
                    <div class="fs-5 fw-bold text-dark text-center">Admin Tools</div>

                    <!-- include manage academic year button -->
                    <?php include_once '../admin/manage_academic_year.php'; ?>

                    <!-- include manage departments button -->
                    <?php include_once '../admin/manage_department.php'; ?>

                    <!-- include manage schedule button -->
                    <?php include_once '../admin/manage_sched.php'; ?>

                    <!-- <i class="bi bi-bookmark-plus"></i> Tag Students -->
                    <?php include_once '../admin/tag_stud.php'; ?>

                    <!-- include bulk send button -->
                    <?php include_once '../admin/bulk_send.php'; ?>

                    
                </div>
            </div>
        </div>


        <!-- Include Bootstrap JS (to handle the modal and Bootstrap components) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        