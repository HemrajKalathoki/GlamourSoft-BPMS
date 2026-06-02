<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$adid = $_SESSION['bpmsaid'];

$ret = mysqli_query($con, "SELECT AdminName FROM tbladmin WHERE ID='$adid'");
$row = mysqli_fetch_array($ret);
$name = $row['AdminName'];

/* Pending Notifications */
$ret1 = mysqli_query(
    $con,
    "SELECT tbluser.FirstName,
            tbluser.LastName,
            tblbook.ID as bid,
            tblbook.AptNumber
     FROM tblbook
     JOIN tbluser ON tbluser.ID = tblbook.UserID
     WHERE tblbook.Status IS NULL
     ORDER BY tblbook.ID DESC"
);

$num = mysqli_num_rows($ret1);
?>

<!-- Bootstrap 5 -->
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

    /* ADMIN NAVBAR */
    .admin-navbar {
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        padding: 12px 0;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1055;
    }

    .admin-brand {
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--primary-color);
        text-decoration: none;
    }

    .dropdown-menu {
      z-index: 2000 !important;
    }

    .admin-brand span {
        color: #111;
        font-weight: 600;
    }

    .menu-toggle {
        border: none;
        background: transparent;
        font-size: 1.6rem;
        color: #333;
        margin-right: 15px;
    }

    .notification-btn {
        position: relative;
        border: none;
        background: #f8f9fa;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        transition: 0.3s;
    }

    .notification-btn:hover {
        background: #fce4ec;
        color: var(--primary-color);
    }

    .notification-badge {
        position: absolute;
        top: -3px;
        right: -3px;
        background: var(--primary-color);
        color: white;
        font-size: 11px;
        padding: 4px 7px;
        border-radius: 50%;
    }

    .dropdown-menu {
        border: none;
        border-radius: 14px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        padding: 10px;
    }

    .dropdown-item {
        border-radius: 10px;
        padding: 10px 14px;
        transition: 0.3s;
        white-space: normal;
    }

    .dropdown-item:hover {
        background: #fce4ec;
        color: var(--primary-color);
    }

    .admin-profile-btn {
        border: none;
        background: #fff;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .admin-profile-btn img {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #f1f1f1;
    }

    .admin-name {
        font-weight: 600;
        color: #222;
        margin-bottom: 0;
        font-size: 14px;
    }

    .admin-role {
        font-size: 12px;
        color: #777;
        margin-bottom: 0;
    }

    .notification-scroll {
        max-height: 300px;
        overflow-y: auto;
    }

    .view-all-btn {
        text-align: center;
        padding-top: 10px;
    }

    .view-all-btn a {
        text-decoration: none;
        color: var(--primary-color);
        font-weight: 600;
    }

    @media (max-width: 768px) {

        .admin-name,
        .admin-role {
            display: none;
        }

        .admin-navbar .container-fluid {
            padding: 0 10px;
        }
    }
</style>

<!-- ADMIN NAVBAR -->
<nav class="navbar admin-navbar">

    <div class="container-fluid px-3">

        <!-- LEFT -->
        <div class="d-flex align-items-center">

            <!-- SIDEBAR TOGGLE -->
            <button id="showLeftPush" class="menu-toggle">
                <i class="bi bi-list"></i>
            </button>

            <!-- LOGO -->
            <a href="dashboard.php" class="admin-brand">
                <i class="bi bi-scissors"></i>
                Glamour<span>Soft</span>
            </a>

        </div>

        <!-- RIGHT -->
        <div class="d-flex align-items-center gap-3">

            <!-- NOTIFICATIONS -->
            <div class="dropdown position-relative">

                <button class="notification-btn dropdown-toggle"
                    type="button"
                    id="notificationDropdown"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">

                    <i class="bi bi-bell fs-5"></i>

                    <?php if ($num > 0) { ?>
                        <span class="notification-badge">
                            <?php echo $num; ?>
                        </span>
                    <?php } ?>

                </button>

                <ul class="dropdown-menu dropdown-menu-end shadow border-0"
                  aria-labelledby="notificationDropdown"
                  style="width: 350px;">

                    <li class="px-3 pb-2">
                        <h6 class="mb-0">
                            You have <?php echo $num; ?> new notification(s)
                        </h6>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <div class="notification-scroll">

                        <?php if ($num > 0) { ?>

                            <?php while ($result = mysqli_fetch_array($ret1)) { ?>

                                <li>
                                    <a class="dropdown-item"
                                       href="view-appointment.php?viewid=<?php echo $result['bid']; ?>">

                                        <i class="bi bi-calendar-check text-primary"></i>

                                        New appointment from
                                        <strong>
                                            <?php echo $result['FirstName']; ?>
                                            <?php echo $result['LastName']; ?>
                                        </strong>

                                        <br>

                                        <small class="text-muted">
                                            Appointment No:
                                            <?php echo $result['AptNumber']; ?>
                                        </small>
                                    </a>
                                </li>

                            <?php } ?>

                        <?php } else { ?>

                            <li>
                                <a class="dropdown-item text-center"
                                   href="all-appointment.php">

                                    No New Appointment Received

                                </a>
                            </li>

                        <?php } ?>

                    </div>

                    <li><hr class="dropdown-divider"></li>

                    <li class="view-all-btn">
                        <a href="new-appointment.php">
                            View All Notifications
                        </a>
                    </li>

                </ul>

            </div>

            <!-- PROFILE -->
          <div class="dropdown">

              <button class="admin-profile-btn dropdown-toggle"
                  type="button"
                  id="profileDropdown"
                  data-bs-toggle="dropdown"
                  aria-expanded="false">

                  <img src="images/admin.png" alt="Admin">

                  <div class="text-start">
                      <p class="admin-name">
                          <?php echo $name; ?>
                      </p>

                      <p class="admin-role">
                          Administrator
                      </p>
                  </div>

              </button>

              <ul class="dropdown-menu dropdown-menu-end shadow border-0"
                  aria-labelledby="profileDropdown">

                  <li>
                      <a class="dropdown-item"
                        href="admin-profile.php">

                          <i class="bi bi-person me-2"></i>
                          Profile

                      </a>
                  </li>

                  <li>
                      <a class="dropdown-item"
                        href="change-password.php">

                          <i class="bi bi-gear me-2"></i>
                          Change Password

                      </a>
                  </li>

                  <li><hr class="dropdown-divider"></li>

                  <li>
                      <a class="dropdown-item text-danger"
                        href="logout.php">

                          <i class="bi bi-box-arrow-right me-2"></i>
                          Logout

                      </a>
                  </li>

              </ul>

          </div>

        </div>

    </div>

</nav>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>