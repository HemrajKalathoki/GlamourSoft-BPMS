<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid'] == 0)) {
    header('location:logout.php');
} else {
?>
<!DOCTYPE HTML>
<html>
<head>
<title>BPMS | Sales Reports</title>
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
.page-heading p {
    color: var(--text-muted);
    font-size: 14px;
    margin: 0;
}

/* ── Card ───────────────────────────────────────────── */
.report-card {
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,.06);
    border: 1px solid #f0f0f0;
    max-width: 680px;
}

.report-card-head {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 22px 28px;
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
.head-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-dark);
    margin: 0;
}
.head-sub {
    font-size: 12px;
    color: var(--text-muted);
    margin: 2px 0 0;
}

/* ── Form body ──────────────────────────────────────── */
.report-card-body { padding: 28px 28px 32px; }

.form-label-custom {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.form-input-custom {
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
    appearance: none;
}
.form-input-custom:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(233,30,99,.1);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
    margin-bottom: 22px;
}
@media (max-width: 540px) {
    .form-row { grid-template-columns: 1fr; }
}

/* ── Report type toggle ─────────────────────────────── */
.type-toggle-wrap {
    margin-bottom: 28px;
}

.type-toggle-label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 12px;
}

.type-toggle {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.type-option {
    flex: 1;
    min-width: 140px;
}

.type-option input[type="radio"] {
    display: none;
}

.type-option label {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 13px 18px;
    border-radius: 14px;
    border: 1.5px solid var(--border);
    background: #fafafa;
    cursor: pointer;
    transition: .2s ease;
    font-size: 14px;
    font-weight: 500;
    color: var(--text-muted);
    user-select: none;
    width: 100%;
}

.type-option label .type-icon {
    width: 32px;
    height: 32px;
    border-radius: 9px;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    color: var(--text-muted);
    transition: .2s ease;
    flex-shrink: 0;
}

.type-option input[type="radio"]:checked + label {
    border-color: var(--primary-color);
    background: #fce7ef;
    color: #9d174d;
}

.type-option input[type="radio"]:checked + label .type-icon {
    background: var(--primary-color);
    color: #fff;
}

.type-option label:hover {
    border-color: #f9a8c9;
    background: #fef2f6;
}

/* ── Submit button ──────────────────────────────────── */
.btn-generate {
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
.btn-generate:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(233,30,99,.32);
}

/* ── Error message ──────────────────────────────────── */
.alert-error {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #b91c1c;
    border-radius: 12px;
    padding: 12px 16px;
    font-size: 13px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
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
        <span class="current">Sales Reports</span>
    </div>

    <!-- Page heading -->
    <div class="page-heading">
        <h1>Sales Reports</h1>
        <p>Generate month-wise or year-wise sales summaries.</p>
    </div>

    <!-- Form card -->
    <div class="report-card">

        <div class="report-card-head">
            <div class="head-icon">
                <i class="bi bi-graph-up-arrow"></i>
            </div>
            <div>
                <p class="head-title">Configure Report</p>
                <p class="head-sub">Select a date range and grouping type</p>
            </div>
        </div>

        <div class="report-card-body">

            <?php if ($msg): ?>
            <div class="alert-error">
                <i class="bi bi-exclamation-circle"></i>
                <?php echo $msg; ?>
            </div>
            <?php endif; ?>

            <form method="post"
                  name="salesreport"
                  action="sales-reports-detail.php"
                  enctype="multipart/form-data">

                <!-- Date range -->
                <div class="form-row">
                    <div>
                        <label class="form-label-custom" for="fromdate">From Date</label>
                        <input type="date"
                               class="form-input-custom"
                               name="fromdate"
                               id="fromdate"
                               required>
                    </div>
                    <div>
                        <label class="form-label-custom" for="todate">To Date</label>
                        <input type="date"
                               class="form-input-custom"
                               name="todate"
                               id="todate"
                               required>
                    </div>
                </div>

                <!-- Report type -->
                <div class="type-toggle-wrap">
                    <span class="type-toggle-label">Report Type</span>
                    <div class="type-toggle">

                        <div class="type-option">
                            <input type="radio"
                                   name="requesttype"
                                   id="mtwise"
                                   value="mtwise"
                                   checked>
                            <label for="mtwise">
                                <span class="type-icon">
                                    <i class="bi bi-calendar3"></i>
                                </span>
                                Month Wise
                            </label>
                        </div>

                        <div class="type-option">
                            <input type="radio"
                                   name="requesttype"
                                   id="yrwise"
                                   value="yrwise">
                            <label for="yrwise">
                                <span class="type-icon">
                                    <i class="bi bi-calendar-range"></i>
                                </span>
                                Year Wise
                            </label>
                        </div>

                    </div>
                </div>

                <button type="submit" name="submit" class="btn-generate">
                    <i class="bi bi-graph-up-arrow"></i>
                    Generate Report
                </button>

            </form>

        </div>
    </div><!-- /.report-card -->

</div><!-- /.page-wrapper -->

<!-- Bootstrap 5 JS -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->

<script>
    const sidebarEl = document.getElementById('sidebar-wrapper');
    const wrapperEl = document.getElementById('page-wrapper');
    const toggleBtn = document.getElementById('showLeftPush');
    if (toggleBtn && wrapperEl) {
        toggleBtn.addEventListener('click', () => {
            if (window.innerWidth >= 992) {
                wrapperEl.classList.toggle('full-width');
            }
        });
    }
</script>

<?php include_once('includes/footer.php'); ?>

</body>
</html>
<?php } ?>