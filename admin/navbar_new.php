<?php
// Make sure no output above this line, not even spaces

// Process form submission and redirect
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tag_schedule'])) {
    $dep_id = $_POST['dep_id'];
    $year_id = $_POST['year_id'];
    $section_id = $_POST['section_id'];
    $sched_id = $_POST['sched_id'];

    // Process the form data (insert into the database, etc.)

    // Redirect to avoid resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit(); // Make sure no further code is executed after redirection
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Dashboard</title>
    <style>
        body {
            opacity: 0; /* Start invisible */
            transition: opacity .4s ease-in-out; /* Fade-in transition */
        }

        body.loaded {
            opacity: 1; /* Fade to visible */
        }

        /* Existing styles ... */
        .main-nav .nav-item {
            position: relative;
            overflow: hidden;
            padding: 5px;
            border-radius: 12px;
            transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        }

        .main-nav .nav-link {
            transition: transform 0.3s ease, text-shadow 0.3s ease;
            position: relative;
            color: #ffffff;
            text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.7), 0 0 20px rgba(255, 255, 255, 0.8);
        }

        .main-nav .nav-link::after {
            content: '';
            display: block;
            width: 100%;
            height: 2px;
            background: white;
            transition: transform 0.3s ease;
            position: absolute;
            left: 0;
            bottom: -5px;
            transform: scaleX(0);
            z-index: 1;
        }

        .main-nav .nav-item:hover .nav-link {
            transform: scale(1.05);
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.9), 0 0 25px rgba(255, 255, 255, 1);
        }

        .main-nav .nav-item:hover .bi {
            transform: scale(1.05);
            color: white !important;
        }

        .main-nav .nav-item:hover .nav-link::after {
            transform: scaleX(1);
        }

        .main-nav .nav-item:hover {
            cursor: pointer;
            background-color: #FFA500;
            transform: translateY(-3px);
        }

        /* New styles for the logo and text border */
        .logo-container {
            border: 2px solid #FFA500;
            /* Border color matching hover color */
            border-radius: 12px;
            /* Rounded corners */
            padding: 10px;
            /* Padding around the logo and text */
            background: linear-gradient(135deg, #FFC107, #FF8F00);
            /* 3D yellow-orange gradient background */
            margin-right: 10px;
            /* Space to the right of the logo */
            display: flex;
            /* Use flex to align items */
            align-items: center;
            /* Center items vertically */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            /* Shadow effect */
            transition: box-shadow 0.3s ease;
            /* Smooth transition for shadow */
        }

        .logo-container:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.7);
            /* Darker shadow on hover */
        }

        .logo-text {
            color: #212529;
            /* Bootstrap's black color */
            text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.8);
            /* Fading white shadow effect */
        }

        .search-container {
            display: flex;
            align-items: center;
        }

        .search-input {
            width: 0;
            opacity: 0;
            transition: width 0.3s ease, opacity 0.3s ease;
            overflow: hidden;
            pointer-events: none;
            /* Prevent interaction when hidden */
        }

        .search-input.show {
            width: 200px;
            /* Adjust width as needed */
            opacity: 1;
            pointer-events: auto;
            /* Allow interaction when visible */
        }

        .search-icon {
            cursor: pointer;
            transition: transform 0.3s ease;
            /* Smooth zoom-in/out effect */
        }

        .search-icon:hover {
            transform: scale(1.2);
            /* Scale up the search icon */
            text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.8);
            /* White shadow effect */
        }
    </style>
</head>

<body>
    <div class="text-light" style="background-color: #0f038f;">
        <div class="container-sm d-flex justify-content-between align-items-center pt-3 pb-3">
            <div class="logo-container logo-text">
                <i class="bi bi-qr-code fs-1 me-2"></i>
                <div class="text-start fw-bold fs-5">
                    <div>Admin</div>
                    <div>Dashboard</div>
                </div>
            </div>
            <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                <span class="text-dark"><i class="bi bi-list fs-4 me-2"></i></span>
            </button>
            <nav class="navbar d-none d-lg-block main-nav">
                <ul class="navbar-nav d-flex flex-row me-auto mb-2 mb-lg-0">
                    <li class="nav-item ps-4 pe-4 me-5">
                        <a class="nav-link active d-flex align-items-center text-light" aria-current="page" href="../scanner/scanner.php" target="_blank" title="Use the scanner to scan QR codes.">
                            <i class="bi bi-qr-code-scan fs-5 me-2 text-light"></i>Scanner
                        </a>
                    </li>
                    <li class="nav-item ps-4 pe-4 me-5">
                        <a class="nav-link active d-flex align-items-center text-light" href="../admin/admin_add_stud.php" target="_blank" title="Add new students to the system.">
                            <i class="bi bi-person-plus fs-5 me-2 text-light"></i>Add Student
                        </a>
                    </li>
                    <li class="nav-item ps-4 pe-4 me-5">
                        <a class="nav-link active d-flex align-items-center text-light" href="#" title="Generate and view reports.">
                            <i class="bi bi-file-earmark-text fs-5 me-2 text-light"></i>Reports
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="text-center pe-5 d-none d-lg-block">
                <div class="search-container">
                    <i class="bi bi-search fs-5 me-2 search-icon" onclick="showSearch()"></i>
                    <input type="text" class="form-control search-input" id="searchInput" placeholder="Search">
                </div>
            </div>
        </div>
    </div>

    <!-- Offcanvas menu -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasNavbarLabel">Menu</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#" title="Use the scanner to scan QR codes.">
                        <i class="bi bi-qr-code-scan fs-5 me-2"></i>Scanner
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#" title="Add new students to the system.">
                        <i class="bi bi-person-plus fs-5 me-2"></i>Add Student
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#" title="Generate and view reports.">
                        <i class="bi bi-file-earmark-text fs-5 me-2"></i>Reports
                    </a>
                </li>
                <li class="nav-item">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-search fs-5 me-2"></i>
                        <input type="text" class="form-control" placeholder="Search">
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <script>
        function showSearch() {
            const input = document.getElementById("searchInput");
            input.classList.toggle("show"); // Use toggle for better user experience
            if (input.classList.contains("show")) {
                input.focus(); // Focus the input when shown
            }
        }

        document.addEventListener('click', function(event) {
            const input = document.getElementById("searchInput");
            const searchContainer = document.querySelector('.search-container');
            if (!searchContainer.contains(event.target) && input.classList.contains("show")) {
                input.classList.remove("show");
                input.value = ""; // Clear the input when hidden
            }
        });

        // Fade-in effect
        window.onload = function() {
            document.body.classList.add('loaded');
        };
    </script>
</body>

</html>
