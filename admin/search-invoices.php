<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid'] == 0)) {
    header('location:logout.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BPMS || Search Invoice</title>

    <!-- Bootstrap 5 — same version as header.php -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons — same as header.php & sidebar.php -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Poppins — matches header.php body font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    /* ── Variables — mirror header.php & sidebar.php ───── */
    :root {
        --primary-color:   #e91e63;
        --secondary-color: #ff4f81;
        --pk-light:        #fce4ec;
        --pk-soft:         #fff5f8;
        --pk-border:       #f8bbd0;
        --card-shadow:     0 4px 24px rgba(233,30,99,.07);
        --radius:          16px;
    }

    body { font-family: 'Poppins', sans-serif; background: #f9f4f7; }

    /* ── Layout — mirrors sidebar.php .page-container logic ─
       sidebar.php JS toggles .collapsed on #sidebar-wrapper
       and .full-width on .dashboard-wrapper                  */
    .dashboard-wrapper {
        margin-left: 280px;        /* clears fixed sidebar         */
        margin-top: 78px;          /* clears fixed .admin-navbar   */
        padding: 32px 28px 52px;
        transition: margin-left .3s ease;
        min-height: calc(100vh - 78px);
    }
    .dashboard-wrapper.full-width { margin-left: 0; }

    @media (max-width: 991px) {
        .dashboard-wrapper { margin-left: 0; padding: 24px 16px 40px; }
    }

    /* ── Page Header ─────────────────────────────────── */
    .page-header {
        display: flex; align-items: center; gap: 14px; margin-bottom: 28px;
    }
    .ph-icon {
        width: 50px; height: 50px; background: var(--pk-light);
        border-radius: 14px; display: flex; align-items: center;
        justify-content: center; font-size: 22px;
        color: var(--primary-color); flex-shrink: 0;
    }
    .page-header h4 { font-size: 1.2rem; font-weight: 700; color: #1a1a2e; margin: 0 0 2px; }
    .page-header p  { font-size: .77rem; color: #888; margin: 0; }
    .breadcrumb { font-size: .74rem; margin-bottom: 4px; padding: 0; background: none; }
    .breadcrumb-item a  { color: var(--primary-color); text-decoration: none; font-weight: 500; }
    .breadcrumb-item.active { color: #999; }

    /* ── Search Card ─────────────────────────────────── */
    .search-card {
        background: #fff; border-radius: var(--radius);
        box-shadow: var(--card-shadow); border: 1px solid #f3e0ea;
        padding: 26px 28px; margin-bottom: 24px;
    }
    .section-label {
        font-size: .7rem; font-weight: 700; letter-spacing: .09em;
        text-transform: uppercase; color: var(--primary-color);
        margin-bottom: 16px; display: flex; align-items: center; gap: 7px;
    }
    .search-group { position: relative; }
    .search-group .s-icon {
        position: absolute; left: 16px; top: 50%;
        transform: translateY(-50%); color: var(--primary-color);
        font-size: 1rem; pointer-events: none; z-index: 5;
    }
    .search-group input {
        padding-left: 46px; height: 50px; border-radius: 12px;
        border: 1.5px solid var(--pk-border); background: var(--pk-soft);
        font-size: .88rem; color: #222; font-family: 'Poppins', sans-serif;
        transition: border-color .2s, box-shadow .2s, background .2s; width: 100%;
    }
    .search-group input:focus {
        border-color: var(--primary-color); background: #fff;
        box-shadow: 0 0 0 4px rgba(233,30,99,.1); outline: none;
    }
    .search-group input::placeholder { color: #bbb; }

    /* helper hint pills */
    .search-tags { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
    .stag {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: .71rem; font-weight: 500; color: #777;
        background: var(--pk-soft); border: 1px solid var(--pk-border);
        border-radius: 20px; padding: 3px 10px;
    }
    .stag i { color: var(--primary-color); }

    /* Search button — gradient matches sidebar active + header */
    .btn-search {
        height: 50px; padding: 0 26px;
        background: linear-gradient(135deg, #e91e63, #ff4f81);
        border: none; border-radius: 12px; color: #fff;
        font-weight: 600; font-size: .88rem; font-family: 'Poppins', sans-serif;
        display: inline-flex; align-items: center; gap: 8px;
        transition: opacity .2s, transform .15s, box-shadow .2s;
        white-space: nowrap; box-shadow: 0 4px 14px rgba(233,30,99,.28);
        cursor: pointer;
    }
    .btn-search:hover {
        opacity: .92; transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(233,30,99,.35); color: #fff;
    }
    .btn-search:active { transform: translateY(0); }

    /* ── Results Card ────────────────────────────────── */
    .results-card {
        background: #fff; border-radius: var(--radius);
        box-shadow: var(--card-shadow); border: 1px solid #f3e0ea; overflow: hidden;
    }
    .results-head {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 10px;
        padding: 17px 22px; background: var(--pk-soft); border-bottom: 1px solid #f3e0ea;
    }
    .results-head h6 {
        font-size: .93rem; font-weight: 700; color: #1a1a2e; margin: 0;
        display: flex; align-items: center; gap: 8px;
    }
    .results-head h6 em { color: var(--primary-color); font-style: italic; }
    .res-badge {
        background: var(--pk-light); color: var(--primary-color);
        font-size: .71rem; font-weight: 700; padding: 4px 14px; border-radius: 20px;
    }

    /* ── Table ───────────────────────────────────────── */
    .inv-table { margin: 0; }
    .inv-table thead th {
        background: linear-gradient(135deg, #e91e63, #ff4f81);
        color: #fff; font-size: .69rem; font-weight: 700;
        letter-spacing: .07em; text-transform: uppercase;
        padding: 13px 18px; border: none; white-space: nowrap;
    }
    .inv-table tbody td {
        padding: 13px 18px; vertical-align: middle;
        font-size: .85rem; color: #333; border-color: #fdf0f4;
    }
    .inv-table tbody tr { transition: background .15s; }
    .inv-table tbody tr:hover { background: var(--pk-soft); }
    .inv-table tbody tr:last-child td { border-bottom: none; }

    /* Row number bubble */
    .row-num {
        width: 30px; height: 30px; background: var(--pk-light);
        color: var(--primary-color); border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .73rem; font-weight: 700;
    }

    /* Invoice ID pill */
    .inv-pill {
        background: #fff3e0; color: #e65100;
        font-size: .73rem; font-weight: 700;
        padding: 4px 11px; border-radius: 8px;
        font-family: 'Courier New', monospace; letter-spacing: .03em;
    }

    /* Customer cell with avatar */
    .cust-cell  { display: flex; align-items: center; gap: 10px; }
    .cust-av {
        width: 36px; height: 36px; border-radius: 50%;
        background: linear-gradient(135deg, #e91e63, #ff4f81);
        color: #fff; font-size: .71rem; font-weight: 700;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .cust-name { font-weight: 600; font-size: .85rem; color: #1a1a2e; }

    /* Date cell */
    .dt-cell {
        display: flex; align-items: center; gap: 5px;
        font-size: .83rem; color: #555; white-space: nowrap;
    }
    .dt-cell .bi    { color: var(--primary-color); }
    .dt-cell strong { color: #1a1a2e; font-weight: 600; }

    /* View button */
    .btn-view {
        display: inline-flex; align-items: center; gap: 5px;
        background: linear-gradient(135deg, #e91e63, #ff4f81);
        color: #fff; border: none; padding: 7px 15px; border-radius: 10px;
        font-size: .77rem; font-weight: 600; font-family: 'Poppins', sans-serif;
        text-decoration: none; transition: opacity .2s, transform .15s;
        box-shadow: 0 3px 10px rgba(233,30,99,.2);
    }
    .btn-view:hover { opacity: .9; color: #fff; transform: translateY(-1px); }

    /* ── Empty State ─────────────────────────────────── */
    .empty-wrap { text-align: center; padding: 60px 24px; }
    .empty-ring {
        width: 76px; height: 76px; background: var(--pk-light); border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 2.1rem; color: var(--primary-color); margin: 0 auto 18px;
    }
    .empty-wrap h5 { font-weight: 700; color: #1a1a2e; margin-bottom: 6px; font-size: 1rem; }
    .empty-wrap p  { font-size: .83rem; color: #888; max-width: 340px; margin: 0 auto; }
</style>
</head>

<body>

    <!-- sidebar.php — renders #sidebar-wrapper, handles its own toggle JS -->
    <?php include_once('includes/sidebar.php'); ?>

    <!-- header.php — renders .admin-navbar fixed at top, loads Bootstrap 5 JS -->
    <?php include_once('includes/header.php'); ?>

    <!-- ── Main content wrapper ──────────────────────────────
         margin-left: 280px  → clears fixed sidebar
         margin-top:  78px   → clears fixed .admin-navbar
         sidebar.php JS toggles .full-width here on desktop collapse
    ─────────────────────────────────────────────────────────── -->
    <div class="dashboard-wrapper" id="dashboard-wrapper">

        <!-- Page Header -->
        <div class="page-header">
            <div class="ph-icon">
                <i class="bi bi-receipt"></i>
            </div>
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="invoices.php">Billing</a></li>
                        <li class="breadcrumb-item active">Search Invoice</li>
                    </ol>
                </nav>
                <h4>Search Invoice</h4>
                <p>Find invoices by invoice number, billing ID, customer name, or mobile number</p>
            </div>
        </div>

        <!-- ── Search Card ──────────────────────────────── -->
        <div class="search-card">
            <div class="section-label">
                <i class="bi bi-search"></i> Search Criteria
            </div>

            <form method="post" action="" name="search">
                <div class="row g-3 align-items-end">

                    <!-- Input -->
                    <div class="col-12 col-md-9 col-lg-10">
                        <div class="search-group">
                            <i class="bi bi-search s-icon"></i>
                            <input
                                type="text"
                                id="searchdata"
                                name="searchdata"
                                class="form-control"
                                placeholder="Type invoice number, billing ID, customer name, or mobile…"
                                value="<?php echo isset($_POST['searchdata']) ? htmlspecialchars($_POST['searchdata']) : ''; ?>"
                                autocomplete="off"
                                required
                            >
                        </div>
                        <!-- Helper hint pills -->
                        <div class="search-tags">
                            <span class="stag"><i class="bi bi-receipt"></i> Invoice / Billing No.</span>
                            <span class="stag"><i class="bi bi-telephone"></i> Mobile Number</span>
                            <span class="stag"><i class="bi bi-person"></i> Customer Name</span>
                        </div>
                    </div>

                    <!-- Button -->
                    <div class="col-12 col-md-3 col-lg-2">
                        <button type="submit" name="search" class="btn-search w-100">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>

                </div>
            </form>
        </div>

        <!-- ── Results ──────────────────────────────────── -->
        <?php if (isset($_POST['search'])) :
            $sdata = $_POST['searchdata'];
            // Original query — untouched
            $ret = mysqli_query($con,
                "SELECT DISTINCT tbluser.FirstName,
                        tblinvoice.BillingId,
                        DATE(tblinvoice.PostingDate) AS invoicedate
                 FROM tbluser
                 JOIN tblinvoice ON tbluser.ID = tblinvoice.Userid
                 WHERE tblinvoice.BillingId   LIKE '%$sdata%'
                    OR tbluser.MobileNumber   LIKE '%$sdata%'
                    OR tbluser.FirstName      LIKE '%$sdata%'"
            );
            $num = mysqli_num_rows($ret);
        ?>

        <div class="results-card">

            <!-- Results header bar -->
            <div class="results-head">
                <h6>
                    <i class="bi bi-list-ul"></i>
                    Results for <em>"<?php echo htmlspecialchars($sdata); ?>"</em>
                </h6>
                <span class="res-badge">
                    <?php echo $num; ?> record<?php echo $num != 1 ? 's' : ''; ?> found
                </span>
            </div>

            <?php if ($num > 0) : ?>
                <div class="table-responsive">
                    <table class="table inv-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Invoice / Billing ID</th>
                                <th>Customer</th>
                                <th>Invoice Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $cnt = 1;
                            while ($row = mysqli_fetch_array($ret)) :
                                // Generate initials for the avatar circle
                                $initials = strtoupper(substr($row['FirstName'], 0, 2));
                            ?>
                            <tr>
                                <!-- # -->
                                <td>
                                    <span class="row-num"><?php echo $cnt; ?></span>
                                </td>

                                <!-- Invoice / Billing ID -->
                                <td>
                                    <span class="inv-pill">
                                        <?php echo htmlspecialchars($row['BillingId']); ?>
                                    </span>
                                </td>

                                <!-- Customer name with avatar -->
                                <td>
                                    <div class="cust-cell">
                                        <div class="cust-av"><?php echo $initials; ?></div>
                                        <span class="cust-name">
                                            <?php echo htmlspecialchars($row['FirstName']); ?>
                                        </span>
                                    </div>
                                </td>

                                <!-- Invoice date -->
                                <td>
                                    <div class="dt-cell">
                                        <i class="bi bi-calendar3"></i>
                                        <strong><?php echo htmlspecialchars($row['invoicedate']); ?></strong>
                                    </div>
                                </td>

                                <!-- View action -->
                                <td>
                                    <a href="view-invoice.php?invoiceid=<?php echo htmlspecialchars($row['BillingId']); ?>"
                                       class="btn-view">
                                        <i class="bi bi-eye-fill"></i> View
                                    </a>
                                </td>
                            </tr>
                            <?php $cnt++; endwhile; ?>
                        </tbody>
                    </table>
                </div>

            <?php else : ?>
                <div class="empty-wrap">
                    <div class="empty-ring">
                        <i class="bi bi-receipt-cutoff"></i>
                    </div>
                    <h5>No Invoices Found</h5>
                    <p>
                        No records matched
                        <strong>"<?php echo htmlspecialchars($sdata); ?>"</strong>.
                        Try a different invoice number, mobile, or customer name.
                    </p>
                </div>
            <?php endif; ?>

        </div><!-- /results-card -->

        <?php endif; ?>

    </div><!-- /dashboard-wrapper -->

    <!--
        Bootstrap 5 JS is already loaded by header.php.
        Kept here as safety fallback — browser deduplicates same-URL scripts.
    -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->

</body>
</html>
<?php } ?>