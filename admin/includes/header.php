<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$adid = $_SESSION['bpmsaid'];

$ret  = mysqli_query($con, "SELECT AdminName, Email FROM tbladmin WHERE ID='$adid'");
$row  = mysqli_fetch_array($ret);
$name = $row['AdminName'];
$email = $row['Email'] ?? '';

/* Generate initials from AdminName */
$nameParts = explode(' ', trim($name));
$initials  = strtoupper(substr($nameParts[0], 0, 1));
if (count($nameParts) > 1) {
    $initials .= strtoupper(substr(end($nameParts), 0, 1));
}

/* Pending Notifications */
$ret1 = mysqli_query($con,
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<!-- Google Fonts: Poppins -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
:root {
    --primary-color:   #e91e63;
    --secondary-color: #ff4f81;
    --dark-color:      #1f1f1f;
    --navbar-height:   78px;
}

body { font-family: 'Poppins', sans-serif; }

/* ── Navbar shell ───────────────────────────────────── */
.admin-navbar {
    background: rgba(255,255,255,.97);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    box-shadow: 0 1px 0 #f0f0f0, 0 4px 24px rgba(0,0,0,.05);
    padding: 0;
    height: var(--navbar-height);
    position: fixed;
    top: 0; left: 0; width: 100%;
    z-index: 1055;
    display: flex;
    align-items: center;
}

.navbar-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    padding: 0 24px;
    gap: 16px;
}

/* ── Left: toggle + brand ───────────────────────────── */
.navbar-left { display: flex; align-items: center; gap: 4px; }

.menu-toggle {
    width: 40px; height: 40px;
    border: none; background: transparent;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; color: #444;
    cursor: pointer; transition: background .2s, color .2s;
    flex-shrink: 0;
}
.menu-toggle:hover { background: #fce4ec; color: var(--primary-color); }

.admin-brand {
    font-size: 1.45rem; font-weight: 700;
    color: var(--primary-color); text-decoration: none;
    display: flex; align-items: center; gap: 6px;
    white-space: nowrap;
}
.admin-brand .brand-icon {
    width: 34px; height: 34px; border-radius: 10px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 16px;
}
.admin-brand .brand-text { color: #111; font-weight: 700; }
.admin-brand .brand-text em { color: var(--primary-color); font-style: normal; }

/* ── Right: actions ─────────────────────────────────── */
.navbar-right {
    display: flex; align-items: center; gap: 6px;
}

/* Notification button */
.notif-btn {
    position: relative;
    width: 42px; height: 42px; border-radius: 12px;
    border: none; background: #f5f5f5;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; color: #555; cursor: pointer;
    transition: background .2s, color .2s;
}
.notif-btn:hover { background: #fce4ec; color: var(--primary-color); }
.notif-badge {
    position: absolute; top: 4px; right: 4px;
    background: var(--primary-color); color: #fff;
    font-size: 10px; font-weight: 700;
    min-width: 18px; height: 18px;
    border-radius: 50px; padding: 0 4px;
    display: flex; align-items: center; justify-content: center;
    border: 2px solid #fff;
    animation: pop .3s ease;
}
@keyframes pop {
    0% { transform: scale(0); }
    70% { transform: scale(1.2); }
    100% { transform: scale(1); }
}

/* Notification dropdown */
.notif-dropdown {
    width: 360px; padding: 0;
    border: none; border-radius: 18px;
    box-shadow: 0 16px 48px rgba(0,0,0,.12);
    overflow: hidden;
}
.notif-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 18px 14px;
    border-bottom: 1px solid #f0f0f0;
}
.notif-header h6 { font-size: 14px; font-weight: 700; color: #111; margin: 0; }
.notif-count-pill {
    background: #fce7ef; color: var(--primary-color);
    font-size: 11px; font-weight: 700;
    padding: 3px 10px; border-radius: 50px;
}
.notif-scroll { max-height: 280px; overflow-y: auto; }
.notif-scroll::-webkit-scrollbar { width: 4px; }
.notif-scroll::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }

.notif-item {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 12px 18px;
    border-bottom: 1px solid #f9f9f9;
    text-decoration: none;
    transition: background .15s;
}
.notif-item:last-child { border-bottom: none; }
.notif-item:hover { background: #fdf2f6; text-decoration: none; }

.notif-dot {
    width: 36px; height: 36px; border-radius: 10px;
    background: #fce7ef;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; color: var(--primary-color); flex-shrink: 0; margin-top: 2px;
}
.notif-body { flex: 1; min-width: 0; }
.notif-body strong { font-size: 13px; font-weight: 600; color: #111; display: block; }
.notif-body small { font-size: 12px; color: var(--text-muted, #6b7280); display: block; margin-top: 2px; }

.notif-empty {
    display: flex; flex-direction: column; align-items: center;
    padding: 32px 20px; color: #9ca3af;
}
.notif-empty .bi { font-size: 32px; margin-bottom: 10px; opacity: .4; }
.notif-empty p { font-size: 13px; margin: 0; }

.notif-footer {
    display: flex; align-items: center; justify-content: center;
    padding: 12px 18px;
    border-top: 1px solid #f0f0f0;
    background: #fafafa;
}
.notif-footer a {
    font-size: 13px; font-weight: 600;
    color: var(--primary-color); text-decoration: none;
    display: flex; align-items: center; gap: 5px;
    transition: gap .2s;
}
.notif-footer a:hover { gap: 9px; }

/* ── Divider ────────────────────────────────────────── */
.navbar-divider {
    width: 1px; height: 32px;
    background: #e5e7eb; margin: 0 6px; flex-shrink: 0;
}

/* ── Profile button ─────────────────────────────────── */
.profile-btn {
    display: flex; align-items: center; gap: 10px;
    padding: 6px 10px 6px 6px;
    border: 1.5px solid #f0f0f0;
    border-radius: 50px;
    background: #fff; cursor: pointer;
    transition: border-color .2s, background .2s;
    font-family: 'Poppins', sans-serif;
}
.profile-btn:hover { border-color: #f9a8c9; background: #fef2f6; }
.profile-btn::after { display: none; } /* remove default BS caret */

/* Avatar circle with initials */
.profile-avatar {
    width: 38px; height: 38px; border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 700; color: #fff;
    letter-spacing: .5px; flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(233,30,99,.3);
}

.profile-info { text-align: left; line-height: 1.2; }
.profile-name {
    font-size: 13px; font-weight: 600; color: #111;
    max-width: 120px; overflow: hidden;
    text-overflow: ellipsis; white-space: nowrap;
}
.profile-role { font-size: 11px; color: #9ca3af; }

.profile-chevron {
    font-size: 12px; color: #9ca3af;
    transition: transform .2s;
}
.profile-btn.show .profile-chevron { transform: rotate(180deg); }

/* Profile dropdown */
.profile-dropdown {
    padding: 8px; border: none;
    border-radius: 16px;
    box-shadow: 0 16px 48px rgba(0,0,0,.12);
    min-width: 220px;
}

/* Mini header inside dropdown */
.profile-drop-header {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 10px 14px;
    border-bottom: 1px solid #f0f0f0; margin-bottom: 6px;
}
.drop-avatar-lg {
    width: 44px; height: 44px; border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; font-weight: 700; color: #fff; flex-shrink: 0;
    box-shadow: 0 3px 10px rgba(233,30,99,.28);
}
.drop-name { font-size: 14px; font-weight: 600; color: #111; margin: 0 0 2px; }
.drop-email { font-size: 11px; color: #9ca3af; margin: 0;
    max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

/* Dropdown items */
.profile-dropdown .dropdown-item {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 12px; border-radius: 10px;
    font-size: 13px; font-weight: 500; color: #333;
    transition: background .15s, color .15s;
}
.profile-dropdown .dropdown-item .item-icon {
    width: 30px; height: 30px; border-radius: 8px;
    background: #f5f5f5;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; color: #555; flex-shrink: 0;
    transition: background .15s, color .15s;
}
.profile-dropdown .dropdown-item:hover { background: #fce4ec; color: var(--primary-color); }
.profile-dropdown .dropdown-item:hover .item-icon {
    background: #fce7ef; color: var(--primary-color);
}
.profile-dropdown .dropdown-item.logout { color: #dc2626; }
.profile-dropdown .dropdown-item.logout .item-icon { background: #fef2f2; color: #dc2626; }
.profile-dropdown .dropdown-item.logout:hover { background: #fef2f2; }

.drop-divider { border-color: #f0f0f0; margin: 6px 0; }

/* ── Mobile ─────────────────────────────────────────── */
@media (max-width: 768px) {
    .profile-info, .navbar-divider { display: none; }
    .profile-btn { padding: 4px; border: 2px solid #f9a8c9; border-radius: 50%; }
    .profile-chevron { display: none; }
    .notif-dropdown { width: calc(100vw - 24px); right: 12px; left: auto; }
}
</style>

<!-- ADMIN NAVBAR -->
<nav class="admin-navbar">
    <div class="navbar-inner">

        <!-- LEFT: toggle + brand -->
        <div class="navbar-left">
            <button id="showLeftPush" class="menu-toggle" aria-label="Toggle sidebar">
                <i class="bi bi-list"></i>
            </button>
            <a href="dashboard.php" class="admin-brand">
                <span class="brand-icon"><i class="bi bi-scissors"></i></span>
                <span class="brand-text">Glamour<em>Soft</em></span>
            </a>
        </div>

        <!-- RIGHT: notifications + profile -->
        <div class="navbar-right">

            <!-- Notifications -->
            <div class="dropdown">
                <button class="notif-btn dropdown-toggle"
                        type="button"
                        id="notifDropdown"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                        aria-label="Notifications">
                    <i class="bi bi-bell"></i>
                    <?php if ($num > 0): ?>
                    <span class="notif-badge"><?php echo $num > 9 ? '9+' : $num; ?></span>
                    <?php endif; ?>
                </button>

                <div class="dropdown-menu dropdown-menu-end notif-dropdown"
                     aria-labelledby="notifDropdown">

                    <div class="notif-header">
                        <h6>Notifications</h6>
                        <span class="notif-count-pill">
                            <?php echo $num; ?> new
                        </span>
                    </div>

                    <div class="notif-scroll">
                        <?php if ($num > 0): ?>
                            <?php while ($result = mysqli_fetch_array($ret1)): ?>
                            <a class="notif-item"
                               href="view-appointment.php?viewid=<?php echo $result['bid']; ?>">
                                <div class="notif-dot">
                                    <i class="bi bi-calendar-check"></i>
                                </div>
                                <div class="notif-body">
                                    <strong>
                                        <?php echo htmlspecialchars($result['FirstName'] . ' ' . $result['LastName']); ?>
                                    </strong>
                                    <small>Apt #<?php echo htmlspecialchars($result['AptNumber']); ?> — pending review</small>
                                </div>
                            </a>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="notif-empty">
                                <i class="bi bi-bell-slash"></i>
                                <p>No new notifications</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="notif-footer">
                        <a href="new-appointment.php">
                            View all notifications
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                </div>
            </div><!-- /notif dropdown -->

            <div class="navbar-divider"></div>

            <!-- Profile -->
            <div class="dropdown">
                <button class="profile-btn dropdown-toggle"
                        type="button"
                        id="profileDropdown"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                    <div class="profile-avatar"><?php echo $initials; ?></div>
                    <div class="profile-info">
                        <div class="profile-name"><?php echo htmlspecialchars($name); ?></div>
                        <div class="profile-role">Administrator</div>
                    </div>
                    <i class="bi bi-chevron-down profile-chevron"></i>
                </button>

                <ul class="dropdown-menu dropdown-menu-end profile-dropdown"
                    aria-labelledby="profileDropdown">

                    <!-- Mini profile header -->
                    <li>
                        <div class="profile-drop-header">
                            <div class="drop-avatar-lg"><?php echo $initials; ?></div>
                            <div>
                                <p class="drop-name"><?php echo htmlspecialchars($name); ?></p>
                                <p class="drop-email"><?php echo htmlspecialchars($email); ?></p>
                            </div>
                        </div>
                    </li>

                    <li>
                        <a class="dropdown-item" href="admin-profile.php">
                            <span class="item-icon"><i class="bi bi-person"></i></span>
                            My Profile
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="change-password.php">
                            <span class="item-icon"><i class="bi bi-shield-lock"></i></span>
                            Change Password
                        </a>
                    </li>

                    <li><hr class="dropdown-divider drop-divider"></li>

                    <li>
                        <a class="dropdown-item logout" href="logout.php">
                            <span class="item-icon"><i class="bi bi-box-arrow-right"></i></span>
                            Sign Out
                        </a>
                    </li>

                </ul>
            </div><!-- /profile dropdown -->

        </div><!-- /navbar-right -->
    </div><!-- /navbar-inner -->
</nav>

<!-- Bootstrap 5 JS (loaded once here; pages must not double-load it) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* Rotate chevron when profile dropdown opens/closes */
(function () {
    const profileBtn = document.getElementById('profileDropdown');
    if (!profileBtn) return;
    profileBtn.addEventListener('show.bs.dropdown',  () => profileBtn.classList.add('show'));
    profileBtn.addEventListener('hide.bs.dropdown',  () => profileBtn.classList.remove('show'));
})();
</script>