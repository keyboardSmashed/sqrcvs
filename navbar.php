<nav class="navbar navbar-dark bg-warning fixed-top">
    <div class="container-fluid d-flex justify-content-between align-items-center"> <!-- Add justify-content-between and align-items-center -->
        <a class="p-0 ms-4 navbar-brand d-flex align-items-center" href="#">
            <span class="text-dark me-2 fw-bold fs-4">Admin</span>
            <i class="bi bi-qr-code-scan text-dark fs-1"></i>
            <span class="text-dark ms-2 fw-bold fs-4">Dashboard</span>
        </a>
        <div class="d-flex align-items-center"> <!-- Wrap buttons in a flex container -->
            <div class="text-dark fw-bold me-4">
                <?php if (isset($_SESSION['admin_id'])) { ?>
                    <?php if (isset($_SESSION['img'])) {
                        echo '<img class="ms-3 me-2 rounded-pill border border-dark" style="max-width:40px;" src="' . $_SESSION['img'] . '" alt="Image">';
                    }
                    echo $_SESSION['first_name'] . " " . $_SESSION['mid_name'] . " " . $_SESSION['last_name']; ?>
                <?php } ?>
            </div>
            <button class="btn btn-clear text-dark rounded-pill" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                <span><i class="bi bi-search text-dark fs-4 pb-1 pt-1 ps-1 pe-1"></i></span>
            </button>
            <button class="btn btn-clear text-dark pt-0 pb-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span><i class="bi bi-list text-dark fs-1"></i></span>
            </button>
        </div>
    </div>
</nav>

<!-- Offcanvas components -->
<!-- Menu Canvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
    <div class="offcanvas-header bg-dark text-light">
        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">
            <div class="d-flex align-items-center mt-3">
                <div class="text-light me-3">
                    <?php if (isset($_SESSION['admin_id'])) { ?>
                        <?php if (isset($_SESSION['img'])) {
                            echo '<img class="ms-3 me-3 rounded-pill border border-light" style="max-width:40px;" src="' . $_SESSION['img'] . '" alt="Image">';
                        }
                        echo $_SESSION['first_name'] . " " . $_SESSION['mid_name'] . " " . $_SESSION['last_name']; ?>
                    <?php } ?>
                </div>
            </div>
        </h5>
        <button type="button" class="btn-close btn-close-white mt-1" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body bg-dark">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
            <li class="nav-item mb-2">
                <a class="nav-link active text-light" href="../dashboard-admin/dashboard.php"><i class="bi bi-house-fill me-3 fs-5"></i>Home</a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link active text-light" aria-current="page" href="../dashboard-admin/admin-add-stud.php"><i class="bi bi-person-plus-fill me-3 fs-5"></i>Add Student</a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link active text-light" href="../dashboard-admin/generate-report.php"><i class="bi bi-file-earmark-text-fill me-3 fs-5"></i>Generate Report</a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link active text-light" href="../scanner/scanner.php"><i class="bi bi-qr-code-scan me-3 fs-5"></i>Scanner</a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link active text-light" href="../admin-login/admin-logout.php"><i class="bi bi-box-arrow-left me-3 fs-5"></i>Logout</a>
            </li>
        </ul>
    </div>
</div>
<!-- Search Canvas -->
<div class="offcanvas offcanvas-top" style="height: 52rem;" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header text-dark bg-warning">
        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Search</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body bg-light">
        <div class="position-relative d-flex justify-content-center">
            <input class="form-control me-2" name="search" id="liveSearch" type="search" placeholder="Search" aria-label="Search">
            <a href="#" class="btn btn-primary rounded">Show All Students</a>
            <div id="searchResults" class="position-absolute bg-white shadow-sm w-100" style="display: none; margin-top:5em;"></div>
        </div>
    </div>
</div>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#liveSearch').keyup(function() {
            var query = $(this).val();
            $.ajax({
                url: 'search.php', // PHP file to handle the live search request
                method: 'POST',
                data: {
                    search: query
                },
                success: function(response) {
                    $('#searchResults').html(response);
                    $('#searchResults').show();
                }
            });
        });
    });
</script> -->