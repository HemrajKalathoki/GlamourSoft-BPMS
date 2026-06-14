<?php
session_start();
include('includes/dbconnection.php');
error_reporting(0);
if (strlen($_SESSION['bpmsaid'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $adminid     = $_SESSION['bpmsaid'];
        $cpassword   = md5($_POST['currentpassword']);
        $newpassword = md5($_POST['newpassword']);
        $query       = mysqli_query($con, "select ID from tbladmin where ID='$adminid' and Password='$cpassword'");
        $row         = mysqli_fetch_array($query);
        if ($row > 0) {
            $ret     = mysqli_query($con, "update tbladmin set Password='$newpassword' where ID='$adminid'");
            $msg     = "Your password was changed successfully.";
            $msgType = "success";
        } else {
            $msg     = "Your current password is incorrect.";
            $msgType = "error";
        }
    }
?>
<!DOCTYPE HTML>
<html>
<head>
<title>BPMS | Change Password</title>
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
.pw-grid {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 22px;
    align-items: start;
}
@media (max-width: 860px) {
    .pw-grid { grid-template-columns: 1fr; }
}

/* ── Security card (left) ───────────────────────────── */
.security-card {
    background: #fff;
    border-radius: 20px;
    border: 1px solid #f0f0f0;
    box-shadow: 0 4px 20px rgba(0,0,0,.05);
    overflow: hidden;
}

.security-card-banner {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    padding: 30px 24px;
    text-align: center;
}

.shield-icon {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: rgba(255,255,255,.18);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 14px;
    font-size: 30px;
    color: #fff;
    backdrop-filter: blur(4px);
}

.security-card-banner h5 {
    font-size: 16px;
    font-weight: 700;
    color: #fff;
    margin: 0 0 4px;
}

.security-card-banner p {
    font-size: 12px;
    color: rgba(255,255,255,.8);
    margin: 0;
}

.security-tips {
    padding: 22px 20px;
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.tip-row {
    display: flex;
    align-items: flex-start;
    gap: 11px;
    font-size: 13px;
    color: var(--text-muted);
    line-height: 1.5;
}

.tip-row .tip-dot {
    width: 26px;
    height: 26px;
    border-radius: 8px;
    background: #fce7ef;
    color: var(--primary-color);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    flex-shrink: 0;
    margin-top: 1px;
}

/* ── Form card (right) ──────────────────────────────── */
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

/* ── Alert ──────────────────────────────────────────── */
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

/* ── Field groups ───────────────────────────────────── */
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

/* Input wrapper for show/hide toggle */
.input-wrap {
    position: relative;
}

.field-input {
    width: 100%;
    height: 50px;
    border: 1px solid #dbe2ea;
    border-radius: 12px;
    padding: 10px 46px 10px 16px;
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

/* Eye toggle button */
.eye-toggle {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    color: var(--text-muted);
    font-size: 17px;
    padding: 0;
    line-height: 1;
    transition: color .2s;
}
.eye-toggle:hover { color: var(--primary-color); }

/* Divider between current pw and new pw section */
.field-divider {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 24px 0;
    color: var(--text-muted);
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .5px;
}
.field-divider::before,
.field-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--border);
}

/* ── Password strength ──────────────────────────────── */
.strength-wrap { margin-top: 8px; }

.strength-track {
    height: 5px;
    background: #f3f4f6;
    border-radius: 99px;
    overflow: hidden;
    margin-bottom: 5px;
}

.strength-bar {
    height: 100%;
    border-radius: 99px;
    width: 0%;
    transition: width .3s ease, background .3s ease;
}

.strength-label {
    font-size: 11px;
    color: var(--text-muted);
    font-weight: 500;
}

/* ── Match indicator ────────────────────────────────── */
.match-hint {
    font-size: 12px;
    margin-top: 6px;
    display: flex;
    align-items: center;
    gap: 5px;
    min-height: 18px;
}
.match-hint.ok   { color: #16a34a; }
.match-hint.fail { color: #dc2626; }

/* ── Form actions ───────────────────────────────────── */
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
.btn-save:disabled {
    opacity: .55;
    cursor: not-allowed;
    transform: none;
}

.btn-back {
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
.btn-back:hover {
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
        <a href="admin-profile.php">Admin Profile</a>
        <i class="bi bi-chevron-right"></i>
        <span class="current">Change Password</span>
    </div>

    <!-- Page heading -->
    <div class="page-heading">
        <h1>Change Password</h1>
        <p>Update your account password to keep your account secure.</p>
    </div>

    <div class="pw-grid">

        <!-- ── Security info card ────────────────────── -->
        <div class="security-card">

            <div class="security-card-banner">
                <div class="shield-icon">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <h5>Password Security</h5>
                <p>Keep your account safe with a strong password</p>
            </div>

            <div class="security-tips">
                <div class="tip-row">
                    <span class="tip-dot"><i class="bi bi-check-lg"></i></span>
                    Use at least 8 characters in your password.
                </div>
                <div class="tip-row">
                    <span class="tip-dot"><i class="bi bi-check-lg"></i></span>
                    Mix uppercase, lowercase, numbers and symbols.
                </div>
                <div class="tip-row">
                    <span class="tip-dot"><i class="bi bi-check-lg"></i></span>
                    Avoid using names, birthdays or common words.
                </div>
                <div class="tip-row">
                    <span class="tip-dot"><i class="bi bi-check-lg"></i></span>
                    Don't reuse passwords from other accounts.
                </div>
                <div class="tip-row">
                    <span class="tip-dot"><i class="bi bi-check-lg"></i></span>
                    Change your password regularly for best protection.
                </div>
            </div>

        </div>

        <!-- ── Form card ─────────────────────────────── -->
        <div class="form-card">

            <div class="form-card-head">
                <div class="head-icon">
                    <i class="bi bi-key"></i>
                </div>
                <div>
                    <p class="head-title">Reset Your Password</p>
                    <p class="head-sub">Enter your current password to continue</p>
                </div>
            </div>

            <div class="form-card-body">

                <?php if (!empty($msg)): ?>
                <div class="alert-box <?php echo $msgType; ?>">
                    <i class="bi bi-<?php echo $msgType === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                    <?php echo htmlspecialchars($msg); ?>
                </div>
                <?php endif; ?>

                <?php
                    $adminid = $_SESSION['bpmsaid'];
                    $ret     = mysqli_query($con, "select * from tbladmin where ID='$adminid'");
                    $row     = mysqli_fetch_array($ret);
                ?>

                <form method="post"
                      name="changepassword"
                      id="changePasswordForm"
                      action="">

                    <!-- Current password -->
                    <div class="field-group">
                        <label class="field-label">
                            <i class="bi bi-lock"></i>
                            Current Password
                        </label>
                        <div class="input-wrap">
                            <input type="password"
                                   class="field-input"
                                   name="currentpassword"
                                   id="currentpassword"
                                   placeholder="Enter your current password"
                                   required>
                            <button type="button" class="eye-toggle" onclick="toggleEye('currentpassword', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="field-divider">New Password</div>

                    <!-- New password -->
                    <div class="field-group">
                        <label class="field-label">
                            <i class="bi bi-lock-fill"></i>
                            New Password
                        </label>
                        <div class="input-wrap">
                            <input type="password"
                                   class="field-input"
                                   name="newpassword"
                                   id="newpassword"
                                   placeholder="Enter new password"
                                   required
                                   oninput="checkStrength(this.value); checkMatch()">
                            <button type="button" class="eye-toggle" onclick="toggleEye('newpassword', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <!-- Strength meter -->
                        <div class="strength-wrap">
                            <div class="strength-track">
                                <div class="strength-bar" id="strengthBar"></div>
                            </div>
                            <span class="strength-label" id="strengthLabel"></span>
                        </div>
                    </div>

                    <!-- Confirm password -->
                    <div class="field-group">
                        <label class="field-label">
                            <i class="bi bi-lock-fill"></i>
                            Confirm Password
                        </label>
                        <div class="input-wrap">
                            <input type="password"
                                   class="field-input"
                                   name="confirmpassword"
                                   id="confirmpassword"
                                   placeholder="Re-enter new password"
                                   required
                                   oninput="checkMatch()">
                            <button type="button" class="eye-toggle" onclick="toggleEye('confirmpassword', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div class="match-hint" id="matchHint"></div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" name="submit" class="btn-save" id="submitBtn">
                            <i class="bi bi-shield-check"></i>
                            Change Password
                        </button>
                        <a href="admin-profile.php" class="btn-back">
                            <i class="bi bi-arrow-left"></i>
                            Back to Profile
                        </a>
                    </div>

                </form>

            </div>
        </div><!-- /.form-card -->

    </div><!-- /.pw-grid -->

</div><!-- /.page-wrapper -->

<!-- Bootstrap 5 JS -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->

<script>
    /* ── Sidebar toggle ─────────────────────────────── */
    const wrapperEl = document.getElementById('page-wrapper');
    const toggleBtn = document.getElementById('showLeftPush');
    if (toggleBtn && wrapperEl) {
        toggleBtn.addEventListener('click', () => {
            if (window.innerWidth >= 992) wrapperEl.classList.toggle('full-width');
        });
    }

    /* ── Show / hide password ───────────────────────── */
    function toggleEye(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const icon  = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }

    /* ── Password strength meter ────────────────────── */
    function checkStrength(val) {
        const bar   = document.getElementById('strengthBar');
        const label = document.getElementById('strengthLabel');

        let score = 0;
        if (val.length >= 8)              score++;
        if (/[A-Z]/.test(val))            score++;
        if (/[0-9]/.test(val))            score++;
        if (/[^A-Za-z0-9]/.test(val))    score++;

        const levels = [
            { w: '0%',   c: 'transparent', t: '' },
            { w: '25%',  c: '#ef4444',     t: 'Weak' },
            { w: '50%',  c: '#f97316',     t: 'Fair' },
            { w: '75%',  c: '#eab308',     t: 'Good' },
            { w: '100%', c: '#22c55e',     t: 'Strong' },
        ];

        const lvl = val.length === 0 ? levels[0] : levels[score] || levels[1];
        bar.style.width      = lvl.w;
        bar.style.background = lvl.c;
        label.textContent    = lvl.t;
        label.style.color    = lvl.c;
    }

    /* ── Confirm password match ─────────────────────── */
    function checkMatch() {
        const np    = document.getElementById('newpassword').value;
        const cp    = document.getElementById('confirmpassword').value;
        const hint  = document.getElementById('matchHint');
        const btn   = document.getElementById('submitBtn');

        if (cp.length === 0) {
            hint.innerHTML = '';
            hint.className = 'match-hint';
            btn.disabled   = false;
            return;
        }

        if (np === cp) {
            hint.innerHTML = '<i class="bi bi-check-circle-fill"></i> Passwords match';
            hint.className = 'match-hint ok';
            btn.disabled   = false;
        } else {
            hint.innerHTML = '<i class="bi bi-x-circle-fill"></i> Passwords do not match';
            hint.className = 'match-hint fail';
            btn.disabled   = true;
        }
    }

    /* ── Form submit guard (mirrors original checkpass) ─ */
    document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
        const np = document.getElementById('newpassword').value;
        const cp = document.getElementById('confirmpassword').value;
        if (np !== cp) {
            e.preventDefault();
            document.getElementById('confirmpassword').focus();
        }
    });
</script>

<?php include_once('includes/footer.php'); ?>

</body>
</html>
<?php } ?>