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
<title>BPMS | Sales Report Details</title>
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
    --row-hover:       #fdf2f6;
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
.page-heading { margin-bottom: 24px; }
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

/* ── Summary strip ──────────────────────────────────── */
.summary-strip {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 22px;
}
.range-pill {
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
.type-pill {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: #f0fdf4;
    color: #166534;
    font-size: 13px;
    font-weight: 600;
    padding: 7px 16px;
    border-radius: 50px;
    border: 1px solid #bbf7d0;
}
.strip-spacer { margin-left: auto; }
.total-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: 50px;
    padding: 7px 18px;
    font-size: 13px;
    color: var(--text-muted);
}
.total-pill strong {
    color: var(--primary-color);
    font-size: 15px;
    font-weight: 700;
}

/* ── Stat cards ─────────────────────────────────────── */
.stat-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 14px;
    margin-bottom: 22px;
}
.stat-card {
    background: #fff;
    border-radius: 16px;
    padding: 18px 20px;
    border: 1px solid #f0f0f0;
    box-shadow: 0 2px 8px rgba(0,0,0,.04);
}
.stat-label {
    font-size: 12px;
    color: var(--text-muted);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: .5px;
    margin-bottom: 8px;
}
.stat-value {
    font-size: 22px;
    font-weight: 700;
    color: var(--text-dark);
    line-height: 1;
    margin-bottom: 4px;
}
.stat-value.accent { color: var(--primary-color); }
.stat-meta {
    font-size: 12px;
    color: var(--text-muted);
}

/* ── Result card ────────────────────────────────────── */
.result-card {
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,.06);
    border: 1px solid #f0f0f0;
}

.result-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 26px;
    border-bottom: 1px solid var(--border);
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
    font-family: 'Poppins', sans-serif;
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
    padding: 13px 22px;
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
.result-table tbody tr:last-child { border-bottom: none; }
.result-table tbody tr:hover { background: var(--row-hover); }
.result-table tbody td {
    padding: 15px 22px;
    color: var(--text-dark);
    vertical-align: middle;
}
.cell-num {
    color: var(--text-muted);
    font-size: 13px;
    width: 54px;
}

/* Period chip */
.period-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #f0f9ff;
    color: #075985;
    font-size: 12px;
    font-weight: 600;
    padding: 5px 13px;
    border-radius: 7px;
    border: 1px solid #bae6fd;
    letter-spacing: .3px;
}

/* Sales amount */
.sales-amount {
    font-weight: 600;
    color: var(--text-dark);
    font-size: 14px;
}
.sales-amount .currency {
    font-size: 11px;
    color: var(--text-muted);
    font-weight: 500;
    margin-right: 2px;
}

/* Bar indicator */
.bar-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 120px;
}
.bar-track {
    flex: 1;
    height: 6px;
    background: #f3f4f6;
    border-radius: 99px;
    overflow: hidden;
}
.bar-fill {
    height: 100%;
    border-radius: 99px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    transition: width .4s ease;
}
.bar-pct {
    font-size: 11px;
    color: var(--text-muted);
    min-width: 34px;
    text-align: right;
}

/* Total row */
.total-row td {
    background: #fdf2f6 !important;
    font-weight: 700;
    color: var(--text-dark);
    border-top: 2px solid #f9a8c9 !important;
}
.total-label {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: var(--primary-color);
    font-weight: 700;
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
    margin: 0 0 22px;
}
.btn-try-again {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 24px;
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

/* ── Card footer ────────────────────────────────────── */
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
.footer-total {
    display: flex;
    align-items: center;
    gap: 7px;
    font-weight: 600;
    color: var(--primary-color);
}
</style>
</head>

<body>

<?php include_once('includes/header.php'); ?>
<?php include_once('includes/sidebar.php'); ?>

<div class="page-wrapper" id="page-wrapper">

    <?php
        /* ── Pull POST data (logic untouched) ───────────── */
        $fdate   = $_POST['fromdate'];
        $tdate   = $_POST['todate'];
        $rtype   = $_POST['requesttype'];

        $ftotal  = 0;
        $rowsArr = [];

        if ($rtype == 'mtwise') {
            $month1 = strtotime($fdate);
            $month2 = strtotime($tdate);
            $m1 = date("F", $month1);
            $m2 = date("F", $month2);
            $y1 = date("Y", $month1);
            $y2 = date("Y", $month2);

            $rangeLabel   = "$m1 $y1 — $m2 $y2";
            $typeLabel    = "Month Wise";
            $typeIcon     = "bi-calendar3";
            $colHeader    = "Month / Year";

            $ret = mysqli_query($con,
                "SELECT MONTH(PostingDate) AS lmonth,
                        YEAR(PostingDate)  AS lyear,
                        SUM(Cost)          AS totalprice
                 FROM tblinvoice
                 JOIN tblservices ON tblservices.ID = tblinvoice.ServiceId
                 WHERE DATE(tblinvoice.PostingDate) BETWEEN '$fdate' AND '$tdate'
                 GROUP BY lmonth, lyear"
            );

            while ($row = mysqli_fetch_array($ret)) {
                $rowsArr[] = [
                    'period' => $row['lmonth'] . ' / ' . $row['lyear'],
                    'sales'  => $row['totalprice'],
                ];
                $ftotal += $row['totalprice'];
            }

        } else {
            $year1 = strtotime($fdate);
            $year2 = strtotime($tdate);
            $y1 = date("Y", $year1);
            $y2 = date("Y", $year2);

            $rangeLabel   = "$y1 — $y2";
            $typeLabel    = "Year Wise";
            $typeIcon     = "bi-calendar-range";
            $colHeader    = "Year";

            $ret = mysqli_query($con,
                "SELECT YEAR(PostingDate) AS lyear,
                        SUM(Cost)         AS totalprice
                 FROM tblinvoice
                 JOIN tblservices ON tblservices.ID = tblinvoice.ServiceId
                 WHERE DATE(tblinvoice.PostingDate) BETWEEN '$fdate' AND '$tdate'
                 GROUP BY lyear"
            );

            while ($row = mysqli_fetch_array($ret)) {
                $rowsArr[] = [
                    'period' => $row['lyear'],
                    'sales'  => $row['totalprice'],
                ];
                $ftotal += $row['totalprice'];
            }
        }

        $totalPeriods = count($rowsArr);
        $maxSale      = $totalPeriods > 0 ? max(array_column($rowsArr, 'sales')) : 1;
        $avgSale      = $totalPeriods > 0 ? $ftotal / $totalPeriods : 0;
    ?>

    <!-- Breadcrumb -->
    <div class="breadcrumb-bar">
        <a href="dashboard.php"><i class="bi bi-house-door"></i> Dashboard</a>
        <i class="bi bi-chevron-right"></i>
        <a href="sales-reports.php">Sales Reports</a>
        <i class="bi bi-chevron-right"></i>
        <span class="current">Results</span>
    </div>

    <!-- Page heading -->
    <div class="page-heading">
        <h1>Sales Report Results</h1>
        <p>
            <?php echo $typeLabel; ?> breakdown for
            <?php echo htmlspecialchars($rangeLabel); ?>
        </p>
    </div>

    <!-- Summary strip -->
    <div class="summary-strip">
        <div class="range-pill">
            <i class="bi bi-calendar-event"></i>
            <?php echo htmlspecialchars($rangeLabel); ?>
        </div>
        <div class="type-pill">
            <i class="bi <?php echo $typeIcon; ?>"></i>
            <?php echo $typeLabel; ?>
        </div>
        <div class="strip-spacer"></div>
        <div class="total-pill">
            Grand Total: <strong><?php echo number_format($ftotal, 2); ?></strong>
        </div>
    </div>

    <!-- Stat cards -->
    <?php if ($totalPeriods > 0): ?>
    <div class="stat-row">
        <div class="stat-card">
            <div class="stat-label">Grand Total</div>
            <div class="stat-value accent"><?php echo number_format($ftotal, 2); ?></div>
            <div class="stat-meta">across all periods</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Avg per Period</div>
            <div class="stat-value"><?php echo number_format($avgSale, 2); ?></div>
            <div class="stat-meta">per <?php echo $rtype == 'mtwise' ? 'month' : 'year'; ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Peak Sales</div>
            <div class="stat-value"><?php echo number_format($maxSale, 2); ?></div>
            <div class="stat-meta">highest single period</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Periods</div>
            <div class="stat-value"><?php echo $totalPeriods; ?></div>
            <div class="stat-meta"><?php echo $rtype == 'mtwise' ? 'months' : 'years'; ?> with data</div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Result card -->
    <div class="result-card">

        <div class="result-card-head">
            <div class="head-left">
                <div class="head-icon">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div>
                    <p class="head-title"><?php echo $typeLabel; ?> Sales Breakdown</p>
                    <p class="head-sub"><?php echo htmlspecialchars($rangeLabel); ?></p>
                </div>
            </div>
            <a href="sales-reports.php" class="btn-new-search">
                <i class="bi bi-arrow-left"></i>
                New Report
            </a>
        </div>

        <?php if ($totalPeriods == 0): ?>

            <div class="empty-state">
                <div class="empty-icon-wrap">
                    <i class="bi bi-graph-down"></i>
                </div>
                <h5>No sales data found</h5>
                <p>
                    No records exist for
                    <strong><?php echo htmlspecialchars($rangeLabel); ?></strong>.
                    Try a different range or report type.
                </p>
                <a href="sales-reports.php" class="btn-try-again">
                    <i class="bi bi-arrow-left"></i>
                    Try Again
                </a>
            </div>

        <?php else: ?>

            <div class="table-responsive">
                <table class="result-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo $colHeader; ?></th>
                            <th>Sales Amount</th>
                            <th>Share</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rowsArr as $i => $r):
                            $pct = $ftotal > 0 ? ($r['sales'] / $ftotal) * 100 : 0;
                            $barW = $maxSale > 0 ? ($r['sales'] / $maxSale) * 100 : 0;
                        ?>
                        <tr>
                            <td class="cell-num"><?php echo $i + 1; ?></td>

                            <td>
                                <span class="period-chip">
                                    <i class="bi bi-calendar2"></i>
                                    <?php echo htmlspecialchars($r['period']); ?>
                                </span>
                            </td>

                            <td>
                                <span class="sales-amount">
                                    <span class="currency">Rs.</span>
                                    <?php echo number_format($r['sales'], 2); ?>
                                </span>
                            </td>

                            <td>
                                <div class="bar-wrap">
                                    <div class="bar-track">
                                        <div class="bar-fill"
                                             style="width: <?php echo round($barW, 1); ?>%">
                                        </div>
                                    </div>
                                    <span class="bar-pct">
                                        <?php echo number_format($pct, 1); ?>%
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>

                        <!-- Total row -->
                        <tr class="total-row">
                            <td colspan="2">
                                <span class="total-label">
                                    <i class="bi bi-calculator"></i>
                                    Grand Total
                                </span>
                            </td>
                            <td>
                                <span class="sales-amount">
                                    <span class="currency">Rs.</span>
                                    <?php echo number_format($ftotal, 2); ?>
                                </span>
                            </td>
                            <td>
                                <span class="bar-pct" style="color: var(--primary-color); font-weight:700;">
                                    100%
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card-footer-bar">
                <span>
                    <?php echo $totalPeriods; ?>
                    <?php echo $rtype == 'mtwise' ? 'month(s)' : 'year(s)'; ?> with recorded sales
                </span>
                <div class="footer-total">
                    <i class="bi bi-graph-up-arrow"></i>
                    Total: Rs. <?php echo number_format($ftotal, 2); ?>
                </div>
            </div>

        <?php endif; ?>

    </div><!-- /.result-card -->

</div><!-- /.page-wrapper -->

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
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