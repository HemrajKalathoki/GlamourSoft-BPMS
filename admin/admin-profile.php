<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $adminid = $_SESSION['bpmsaid'];
        $aname   = $_POST['adminname'];
        $mobno   = $_POST['contactnumber'];
        $query   = mysqli_query($con, "update tbladmin set AdminName='$aname', MobileNumber='$mobno' where ID='$adminid'");
        if ($query) {
            $msg     = "Profile updated successfully.";
            $msgType = "success";
        } else {
            $msg     = "Something went wrong. Please try again.";
            $msgType = "error";
        }
    }
?>
<!DOCTYPE HTML>
<html>
<head>
<title>BPMS | Admin Profile</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

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
    --bg-page:         #f8f9fc;
    --text-dark:       #1f2937;
    --text-muted:      #6b7280;
    --border:          #e5e7eb;
    --sidebar-width:   280px;
    --navbar-height:   78px;
}

* { box-sizing: border-box; }

body {
    font-family: 'Poppins', sans-serif;
    background: var(--bg-page);
    margin: 0;
}

/* ── Layout ─────────────────────────────────────────── */
.page-wrapper {
    margin-left: var(--sidebar-width);
    padding: calc(var(--navbar-height) + 32px) 32px 60px;
    min-height: 100vh;
    transition: margin-left 0.3s ease;
}
.page-wrapper.full-width { margin-left: 0; }

@media (max-width: 991px) {
    .page-wrapper {
        margin-left: 0;
        padding: calc(var(--navbar-height) + 20px) 16px 40px;
    }
}

/* ── Breadcrumb ─────────────────────────────────────── */
.breadcrumb-bar {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 20px;
    font-size: 13px;
    color: var(--text-muted);
}
.breadcrumb-bar a {
    color: var(--text-muted);
    text-decoration: none;
    transition: color .2s;
}
.breadcrumb-bar a:hover { color: var(--primary-color); }
.breadcrumb-bar .bi-chevron-right { font-size: 11px; opacity: .5; }
.breadcrumb-bar .current { color: var(--primary-color); font-weight: 500; }

/* ── Page heading ───────────────────────────────────── */
.page-heading { margin-bottom: 28px; }
.page-heading h1 {
    font-size: 26px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0 0 5px;
}
.page-heading p { color: var(--text-muted); font-size: 14px; margin: 0; }

/* ── Two-column grid ────────────────────────────────── */
.profile-grid {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 22px;
    align-items: start;
}

@media (max-width: 860px) {
    .profile-grid { grid-template-columns: 1fr; }
}

/* ── Avatar card ────────────────────────────────────── */
.avatar-card {
    background: #fff;
    border-radius: 20px;
    border: 1px solid #f0f0f0;
    box-shadow: 0 4px 20px rgba(0,0,0,.05);
    padding: 36px 24px;
    text-align: center;
}

.avatar-ring {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 18px;
    box-shadow: 0 8px 24px rgba(233,30,99,.28);
}

.avatar-ring span {
    font-size: 36px;
    font-weight: 700;
    color: #fff;
    text-transform: uppercase;
    letter-spacing: 1px;
    line-height: 1;
}

.avatar-name {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0 0 4px;
}

.avatar-role {
    font-size: 13px;
    color: var(--text-muted);
    margin: 0 0 20px;
}

.avatar-divider {
    border: none;
    border-top: 1px solid var(--border);
    margin: 0 0 20px;
}

.avatar-meta {
    display: flex;
    flex-direction: column;
    gap: 12px;
    text-align: left;
}

.meta-row {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    color: var(--text-muted);
}

.meta-row .bi {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    background: #fce7ef;
    color: var(--primary-color);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
}

.meta-row span {
    flex: 1;
    word-break: break-all;
    color: var(--text-dark);
    font-weight: 500;
}

/* ── Form card ──────────────────────────────────────── */
.form-card {
    background: #fff;
    border-radius: 20px;
    border: 1px solid #f0f0f0;
    box-shadow: 0 4px 20px rgba(0,0,0,.05);
    overflow: hidden;
}

.form-card-head {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 22px 26px;
    border-bottom: 1px solid var(--border);
}

.head-icon {
    width: 38px;
    height: 38px;
    border-radius: 11px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 17px;
    flex-shrink: 0;
}

.head-title { font-size: 16px; font-weight: 600; color: var(--text-dark); margin: 0; }
.head-sub { font-size: 12px; color: var(--text-muted); margin: 2px 0 0; }

.form-card-body { padding: 28px 28px 32px; }

/* ── Alerts ─────────────────────────────────────────── */
.alert-box {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 13px 16px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 500;
    margin-bottom: 22px;
}
.alert-box.success {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #166534;
}
.alert-box.error {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #b91c1c;
}

/* ── Form fields ────────────────────────────────────── */
.field-group { margin-bottom: 20px; }

.field-label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}
.field-label .bi { color: var(--primary-color); font-size: 14px; }

.field-input {
    width: 100%;
    height: 50px;
    border: 1px solid #dbe2ea;
    border-radius: 12px;
    padding: 10px 16px;
    font-size: 14px;
    font-family: 'Poppins', sans-serif;
    color: var(--text-dark);
    background: #fff;
    transition: .25s ease;
}
.field-input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(233,30,99,.1);
}
.field-input[readonly] {
    background: #f9fafb;
    color: var(--text-muted);
    cursor: not-allowed;
    border-color: #e5e7eb;
}

.readonly-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11px;
    font-weight: 600;
    background: #f3f4f6;
    color: #9ca3af;
    padding: 2px 8px;
    border-radius: 5px;
    margin-left: auto;
    text-transform: uppercase;
    letter-spacing: .4px;
}

.form-row-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
}
@media (max-width: 560px) {
    .form-row-2 { grid-template-columns: 1fr; }
}

/* ── Submit button ──────────────────────────────────── */
.form-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 28px;
    padding-top: 22px;
    border-top: 1px solid var(--border);
}

.btn-save {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    height: 50px;
    padding: 0 32px;
    border: none;
    border-radius: 13px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: #fff;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: .25s ease;
    box-shadow: 0 4px 14px rgba(233,30,99,.25);
}
.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(233,30,99,.32);
}

.btn-change-pw {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    height: 50px;
    padding: 0 22px;
    border-radius: 13px;
    border: 1px solid var(--border);
    background: #fff;
    color: var(--text-muted);
    font-family: 'Poppins', sans-serif;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    transition: .2s ease;
}
.btn-change-pw:hover {
    background: #fce4ec;
    border-color: #f9a8c9;
    color: var(--primary-color);
    text-decoration: none;
}
</style>
</head>

<body>

<?php include_once('includes/header.php'); ?>
<?php include_once('includes/sidebar.php'); ?>

<div class="page-wrapper" id="page-wrapper">

    <!-- Breadcrumb -->
    <div class="breadcrumb-bar">
        <a href="dashboard.php"><i class="bi bi-house-door"></i> Dashboard</a>
        <i class="bi bi-chevron-right"></i>
        <span class="current">Admin Profile</span>
    </div>

    <!-- Page heading -->
    <div class="page-heading">
        <h1>Admin Profile</h1>
        <p>View and update your account information.</p>
    </div>

    <?php
        $adminid = $_SESSION['bpmsaid'];
        $ret     = mysqli_query($con, "select * from tbladmin where ID='$adminid'");
        $row     = mysqli_fetch_array($ret);
        $initials = strtoupper(substr($row['AdminName'], 0, 1));
    ?>

    <div class="profile-grid">

        <!-- ── Avatar / info sidebar card ───────────── -->
        <div class="avatar-card">

            <div class="avatar-ring">
                <span><?php echo $initials; ?></span>
            </div>

            <p class="avatar-name"><?php echo htmlspecialchars($row['AdminName']); ?></p>
            <p class="avatar-role">Administrator</p>

            <hr class="avatar-divider">

            <div class="avatar-meta">
                <div class="meta-row">
                    <i class="bi bi-person-badge"></i>
                    <span><?php echo htmlspecialchars($row['UserName']); ?></span>
                </div>
                <div class="meta-row">
                    <i class="bi bi-envelope"></i>
                    <span><?php echo htmlspecialchars($row['Email']); ?></span>
                </div>
                <div class="meta-row">
                    <i class="bi bi-telephone"></i>
                    <span><?php echo htmlspecialchars($row['MobileNumber']) ?: '—'; ?></span>
                </div>
            </div>

        </div>

        <!-- ── Edit form card ───────────────────────── -->
        <div class="form-card">

            <div class="form-card-head">
                <div class="head-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div>
                    <p class="head-title">Update Profile</p>
                    <p class="head-sub">Edit your name and contact number</p>
                </div>
            </div>

            <div class="form-card-body">

                <?php if (!empty($msg)): ?>
                <div class="alert-box <?php echo $msgType; ?>">
                    <i class="bi bi-<?php echo $msgType === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                    <?php echo htmlspecialchars($msg); ?>
                </div>
                <?php endif; ?>

                <form method="post">

                    <!-- Editable fields -->
                    <div class="form-row-2">
                        <div class="field-group">
                            <label class="field-label">
                                <i class="bi bi-person"></i>
                                Admin Name
                            </label>
                            <input type="text"
                                   class="field-input"
                                   name="adminname"
                                   id="adminname"
                                   placeholder="Enter admin name"
                                   value="<?php echo htmlspecialchars($row['AdminName']); ?>"
                                   required>
                        </div>

                        <div class="field-group">
                            <label class="field-label">
                                <i class="bi bi-telephone"></i>
                                Contact Number
                            </label>
                            <input type="text"
                                   class="field-input"
                                   name="contactnumber"
                                   id="contactnumber"
                                   placeholder="Enter contact number"
                                   value="<?php echo htmlspecialchars($row['MobileNumber']); ?>">
                        </div>
                    </div>

                    <!-- Read-only fields -->
                    <div class="form-row-2">
                        <div class="field-group">
                            <label class="field-label">
                                <i class="bi bi-person-badge"></i>
                                Username
                                <span class="readonly-badge"><i class="bi bi-lock"></i> Read only</span>
                            </label>
                            <input type="text"
                                   class="field-input"
                                   name="username"
                                   value="<?php echo htmlspecialchars($row['UserName']); ?>"
                                   readonly>
                        </div>

                        <div class="field-group">
                            <label class="field-label">
                                <i class="bi bi-envelope"></i>
                                Email Address
                                <span class="readonly-badge"><i class="bi bi-lock"></i> Read only</span>
                            </label>
                            <input type="email"
                                   class="field-input"
                                   name="email"
                                   value="<?php echo htmlspecialchars($row['Email']); ?>"
                                   readonly>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" name="submit" class="btn-save">
                            <i class="bi bi-check-lg"></i>
                            Save Changes
                        </button>
                        <a href="change-password.php" class="btn-change-pw">
                            <i class="bi bi-shield-lock"></i>
                            Change Password
                        </a>
                    </div>

                </form>

            </div>
        </div><!-- /.form-card -->

    </div><!-- /.profile-grid -->

</div><!-- /.page-wrapper -->

<!-- Bootstrap 5 JS -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->

<script>
    const wrapperEl = document.getElementById('page-wrapper');
    const toggleBtn = document.getElementById('showLeftPush');
    if (toggleBtn && wrapperEl) {
        toggleBtn.addEventListener('click', () => {
            if (window.innerWidth >= 992) wrapperEl.classList.toggle('full-width');
        });
    }
</script>

<?php include_once('includes/footer.php'); ?>

</body>
</html>
<?php } ?>