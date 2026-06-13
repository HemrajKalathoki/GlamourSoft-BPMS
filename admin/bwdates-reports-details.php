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
<title>BPMS || B/W Date Reports</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- Google Fonts: Poppins -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>

/* ── Root tokens (match header/sidebar system) ──────── */
:root {
    --primary-color:   #e91e63;
    --secondary-color: #ff4f81;
    --dark-color:      #1f1f1f;
    --light-color:     #ffffff;
    --bg-page:         #f8f9fc;
    --text-dark:       #1f2937;
    --text-muted:      #6b7280;
    --border:          #e5e7eb;
    --row-hover:       #fdf2f6;
    --sidebar-width:   280px;
    --navbar-height:   78px;
}

* {
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background: var(--bg-page);
    margin: 0;
}

/* ── Page wrapper — mirrors sidebar layout ──────────── */
.details-wrapper {
    margin-left: var(--sidebar-width);
    padding: calc(var(--navbar-height) + 30px) 30px 50px;
    min-height: 100vh;
    transition: margin-left 0.3s ease;
}

.details-wrapper.full-width {
    margin-left: 0;
}

@media (max-width: 991px) {
    .details-wrapper {
        margin-left: 0;
        padding: calc(var(--navbar-height) + 20px) 16px 40px;
    }
}

/* ── Page heading ───────────────────────────────────── */
.page-heading {
    margin-bottom: 28px;
}

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

.breadcrumb-bar a:hover {
    color: var(--primary-color);
}

.breadcrumb-bar .bi-chevron-right {
    font-size: 11px;
    opacity: .5;
}

.breadcrumb-bar .current {
    color: var(--primary-color);
    font-weight: 500;
}

/* ── Summary strip ──────────────────────────────────── */
.summary-strip {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 22px;
}

.date-pill {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: #fce7ef;
    color: #9d174d;
    font-size: 13px;
    font-weight: 600;
    padding: 7px 16px;
    border-radius: 50px;
    border: 1px solid #f9a8c9;
}

.date-pill .bi {
    font-size: 13px;
}

.arrow-pill {
    color: var(--text-muted);
    font-size: 16px;
    line-height: 1;
}

.count-wrap {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    margin-left: auto;
    font-size: 13px;
    color: var(--text-muted);
}

.count-bubble {
    background: var(--primary-color);
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    padding: 3px 11px;
    border-radius: 50px;
    min-width: 28px;
    text-align: center;
    line-height: 1.6;
}

/* ── Result card ────────────────────────────────────── */
.result-card {
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,.06);
    border: 1px solid #f0f0f0;
}

/* Card header */
.result-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 26px;
    border-bottom: 1px solid var(--border);
    background: #fff;
}

.head-left {
    display: flex;
    align-items: center;
    gap: 12px;
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

/* Back button — mirrors sidebar-link hover style */
.btn-new-search {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 9px 18px;
    border-radius: 12px;
    border: 1px solid var(--border);
    background: #fff;
    color: var(--text-muted);
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    transition: .2s ease;
}

.btn-new-search:hover {
    background: #fce4ec;
    border-color: #f9a8c9;
    color: var(--primary-color);
    text-decoration: none;
}

/* ── Table ──────────────────────────────────────────── */
.result-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.result-table thead tr {
    background: #fafafa;
    border-bottom: 1px solid var(--border);
}

.result-table thead th {
    padding: 13px 20px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .65px;
    color: var(--text-muted);
    white-space: nowrap;
}

.result-table tbody tr {
    border-bottom: 1px solid #f3f4f6;
    transition: background .15s ease;
}

.result-table tbody tr:last-child {
    border-bottom: none;
}

.result-table tbody tr:hover {
    background: var(--row-hover);
}

.result-table tbody td {
    padding: 15px 20px;
    color: var(--text-dark);
    vertical-align: middle;
}

.cell-num {
    color: var(--text-muted);
    font-size: 13px;
    width: 50px;
}

/* Invoice chip */
.invoice-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #fce7ef;
    color: #9d174d;
    font-size: 12px;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 7px;
    letter-spacing: .3px;
}

/* Customer cell */
.customer-cell {
    display: flex;
    align-items: center;
    gap: 11px;
}

.avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    text-transform: uppercase;
    letter-spacing: .5px;
}

.customer-name {
    font-weight: 500;
    color: var(--text-dark);
    font-size: 14px;
}

/* Date cell */
.date-cell {
    display: flex;
    align-items: center;
    gap: 7px;
    color: var(--text-muted);
    font-size: 13px;
}

.date-cell .bi {
    color: #d1d5db;
    font-size: 14px;
}

/* View button — consistent with sidebar active gradient */
.btn-view {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 16px;
    border-radius: 10px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: #fff;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    border: none;
    transition: .25s ease;
    white-space: nowrap;
    box-shadow: 0 3px 10px rgba(233,30,99,.22);
}

.btn-view:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(233,30,99,.32);
    color: #fff;
    text-decoration: none;
}

/* ── Table footer ───────────────────────────────────── */
.card-footer-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px 24px;
    border-top: 1px solid var(--border);
    background: #fafafa;
    font-size: 13px;
    color: var(--text-muted);
    flex-wrap: wrap;
    gap: 8px;
}

.footer-range {
    display: flex;
    align-items: center;
    gap: 7px;
}

.footer-range .bi {
    color: var(--primary-color);
    font-size: 13px;
}

/* ── Empty state ────────────────────────────────────── */
.empty-state {
    text-align: center;
    padding: 64px 24px;
}

.empty-icon-wrap {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: #fce7ef;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 28px;
    color: var(--primary-color);
}

.empty-state h5 {
    font-size: 17px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 8px;
}

.empty-state p {
    color: var(--text-muted);
    font-size: 14px;
    margin: 0 0 20px;
}

.btn-try-again {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 9px 22px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(233,30,99,.25);
    transition: .2s ease;
}

.btn-try-again:hover {
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 7px 18px rgba(233,30,99,.32);
    text-decoration: none;
}

</style>
</head>

<body>

<!-- ── Header (new system) ───────────────────────────── -->
<?php include_once('includes/header.php'); ?>

<!-- ── Sidebar (new system) ─────────────────────────── -->
<?php include_once('includes/sidebar.php'); ?>

<!-- ── Page content ─────────────────────────────────── -->
<div class="details-wrapper" id="details-wrapper">

    <?php
        $fdate = $_POST['fromdate'];
        $tdate = $_POST['todate'];

        $ret = mysqli_query($con,
            "SELECT DISTINCT tbluser.FirstName, tbluser.LastName,
                             tblinvoice.BillingId, tblinvoice.PostingDate
             FROM tbluser
             JOIN tblinvoice ON tbluser.ID = tblinvoice.Userid
             WHERE DATE(tblinvoice.PostingDate) BETWEEN '$fdate' AND '$tdate'"
        );
        $totalRows = mysqli_num_rows($ret);
    ?>

    <!-- Breadcrumb -->
    <div class="breadcrumb-bar">
        <a href="dashboard.php"><i class="bi bi-house-door"></i> Dashboard</a>
        <i class="bi bi-chevron-right"></i>
        <a href="bwdates-reports-ds.php">Reports</a>
        <i class="bi bi-chevron-right"></i>
        <span class="current">Between Dates</span>
    </div>

    <!-- Page heading -->
    <div class="page-heading">
        <h1>Report Results</h1>
        <p>Invoice records found within the selected date range.</p>
    </div>

    <!-- Summary strip -->
    <div class="summary-strip">
        <div class="date-pill">
            <i class="bi bi-calendar-event"></i>
            From: <?php echo htmlspecialchars($fdate); ?>
        </div>

        <span class="arrow-pill">
            <i class="bi bi-arrow-right"></i>
        </span>

        <div class="date-pill">
            <i class="bi bi-calendar-check"></i>
            To: <?php echo htmlspecialchars($tdate); ?>
        </div>

        <div class="count-wrap">
            <span class="count-bubble"><?php echo $totalRows; ?></span>
            record<?php echo $totalRows !== 1 ? 's' : ''; ?> found
        </div>
    </div>

    <!-- Result card -->
    <div class="result-card">

        <!-- Card header -->
        <div class="result-card-head">
            <div class="head-left">
                <div class="head-icon">
                    <i class="bi bi-receipt-cutoff"></i>
                </div>
                <div>
                    <p class="head-title">Invoice List</p>
                    <p class="head-sub">
                        <?php echo htmlspecialchars($fdate); ?> &mdash;
                        <?php echo htmlspecialchars($tdate); ?>
                    </p>
                </div>
            </div>

            <a href="bwdates-reports-ds.php" class="btn-new-search">
                <i class="bi bi-arrow-left"></i>
                New Search
            </a>
        </div>

        <?php if ($totalRows == 0): ?>

            <!-- Empty state -->
            <div class="empty-state">
                <div class="empty-icon-wrap">
                    <i class="bi bi-search"></i>
                </div>
                <h5>No invoices found</h5>
                <p>
                    No records exist between
                    <strong><?php echo htmlspecialchars($fdate); ?></strong>
                    and
                    <strong><?php echo htmlspecialchars($tdate); ?></strong>.
                </p>
                <a href="bwdates-reports-ds.php" class="btn-try-again">
                    <i class="bi bi-arrow-left"></i>
                    Try a Different Range
                </a>
            </div>

        <?php else: ?>

            <!-- Table -->
            <div class="table-responsive">
                <table class="result-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Invoice ID</th>
                            <th>Customer</th>
                            <th>Posting Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $cnt = 1;
                            mysqli_data_seek($ret, 0);
                            while ($row = mysqli_fetch_array($ret)):
                                $firstName = $row['FirstName'];
                                $lastName  = $row['LastName'];
                                $initials  = strtoupper(
                                    substr($firstName, 0, 1) . substr($lastName, 0, 1)
                                );
                        ?>
                        <tr>
                            <td class="cell-num"><?php echo $cnt; ?></td>

                            <td>
                                <span class="invoice-chip">
                                    <i class="bi bi-hash"></i>
                                    <?php echo htmlspecialchars($row['BillingId']); ?>
                                </span>
                            </td>

                            <td>
                                <div class="customer-cell">
                                    <div class="avatar"><?php echo $initials; ?></div>
                                    <span class="customer-name">
                                        <?php echo htmlspecialchars($firstName . ' ' . $lastName); ?>
                                    </span>
                                </div>
                            </td>

                            <td>
                                <div class="date-cell">
                                    <i class="bi bi-clock"></i>
                                    <?php echo htmlspecialchars($row['PostingDate']); ?>
                                </div>
                            </td>

                            <td>
                                <a href="view-invoice.php?invoiceid=<?php echo htmlspecialchars($row['BillingId']); ?>"
                                   class="btn-view">
                                    <i class="bi bi-eye"></i>
                                    View
                                </a>
                            </td>
                        </tr>
                        <?php $cnt++; endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Card footer -->
            <div class="card-footer-bar">
                <span>
                    Showing <strong><?php echo $totalRows; ?></strong>
                    result<?php echo $totalRows !== 1 ? 's' : ''; ?>
                </span>
                <div class="footer-range">
                    <i class="bi bi-calendar-range"></i>
                    <?php echo htmlspecialchars($fdate); ?> &mdash;
                    <?php echo htmlspecialchars($tdate); ?>
                </div>
            </div>

        <?php endif; ?>

    </div><!-- /.result-card -->

</div><!-- /.details-wrapper -->

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    /*
     * Sync the content wrapper with the sidebar collapse toggle.
     * The sidebar JS (in sidebar.php) toggles .collapsed on #sidebar-wrapper
     * and .full-width on .dashboard-wrapper — we mirror that here for
     * .details-wrapper so the layout stays consistent across all pages.
     */
    const sidebarEl  = document.getElementById('sidebar-wrapper');
    const wrapperEl  = document.getElementById('details-wrapper');
    const toggleBtn  = document.getElementById('showLeftPush');

    if (toggleBtn && sidebarEl && wrapperEl) {
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