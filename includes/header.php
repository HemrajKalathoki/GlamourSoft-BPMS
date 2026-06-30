<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    :root {
        --primary-color: #e91e63;
        --secondary-color: #ff4f81;
        --dark-color: #1f1f1f;
        --light-color: #ffffff;
    }

    body {
        font-family: 'Poppins', sans-serif;
    }

    .navbar-custom {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        padding: 12px 0;
        transition: 0.3s ease;
    }

    .navbar-brand {
        font-size: 1.7rem;
        font-weight: 700;
        color: var(--primary-color) !important;
        letter-spacing: 0.5px;
    }

    .navbar-brand span {
        color: #111;
        font-weight: 600;
    }

    .nav-link {
        color: #333 !important;
        font-weight: 500;
        margin: 0 8px;
        transition: 0.3s;
        position: relative;
    }

    .nav-link:hover,
    .nav-link.active {
        color: var(--primary-color) !important;
    }

    .nav-link::after {
        content: '';
        position: absolute;
        width: 0%;
        height: 2px;
        background: var(--primary-color);
        left: 0;
        bottom: 0;
        transition: 0.3s;
    }

    .nav-link:hover::after {
        width: 100%;
    }

    .btn-auth {
        border-radius: 30px;
        padding: 8px 18px;
        font-weight: 500;
        transition: 0.3s;
    }

    .btn-login {
        border: 1px solid var(--primary-color);
        color: var(--primary-color);
    }

    .btn-login:hover {
        background: var(--primary-color);
        color: white;
    }

    .btn-signup {
        background: var(--primary-color);
        color: white;
    }

    .btn-signup:hover {
        background: #c2185b;
        color: white;
    }

    .dropdown-menu {
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        border-radius: 12px;
        padding: 10px;
    }

    .dropdown-item {
        border-radius: 8px;
        padding: 10px 14px;
        transition: 0.3s;
    }

    .dropdown-item:hover {
        background: #fce4ec;
        color: var(--primary-color);
    }

    @media (max-width: 991px) {

        .navbar-collapse {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin-top: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        .nav-link {
            margin: 10px 0;
        }
    }
</style>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light navbar-custom sticky-top">

    <div class="container">

        <!-- BRAND -->
        <a class="navbar-brand" href="index.php">
            <i class="bi bi-scissors"></i>
            Glamour<span>Soft</span>
        </a>

        <!-- TOGGLER -->
        <button class="navbar-toggler border-0 shadow-none"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNavbar"
            aria-controls="mainNavbar"
            aria-expanded="false"
            aria-label="Toggle navigation">

            <i class="bi bi-list fs-2"></i>
        </button>

        <!-- NAVIGATION -->
        <div class="collapse navbar-collapse" id="mainNavbar">

            <!-- CENTER MENU -->
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link active" href="index.php">
                        Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="about.php">
                        About
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="services.php">
                        Services
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="contact.php">
                        Contact
                    </a>
                </li>

            </ul>

            <!-- RIGHT SIDE -->
            <div class="d-flex align-items-center gap-2 flex-wrap">

                <?php if (empty($_SESSION['bpmsuid'])) { ?>

                    <!-- ADMIN -->
                    <!-- <a href="admin/index.php" class="btn btn-dark btn-auth">
                        <i class="bi bi-shield-lock"></i> Admin
                    </a> -->

                    <!-- LOGIN -->
                    <a href="login.php" class="btn btn-login btn-auth">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </a>

                    <!-- SIGNUP -->
                    <a href="signup.php" class="btn btn-signup btn-auth">
                        <i class="bi bi-person-plus"></i> Signup
                    </a>

                <?php } else { ?>

                    <!-- USER DROPDOWN -->
                    <div class="dropdown">

                        <button class="btn btn-signup dropdown-toggle"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">

                            <i class="bi bi-person-circle"></i>
                            My Account
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end">

                            <li>
                                <a class="dropdown-item" href="book-appointment.php">
                                    <i class="bi bi-calendar-check"></i>
                                    Book Appointment
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="booking-history.php">
                                    <i class="bi bi-clock-history"></i>
                                    Booking History
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="invoice-history.php">
                                    <i class="bi bi-receipt"></i>
                                    Invoice History
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="profile.php">
                                    <i class="bi bi-person"></i>
                                    Profile
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="change-password.php">
                                    <i class="bi bi-gear"></i>
                                    Settings
                                </a>
                            </li>

                            <li><hr class="dropdown-divider"></li>

                            <li>
                                <a class="dropdown-item text-danger" href="logout.php">
                                    <i class="bi bi-box-arrow-right"></i>
                                    Logout
                                </a>
                            </li>

                        </ul>
                    </div>

                <?php } ?>

            </div>

        </div>

    </div>

</nav>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
