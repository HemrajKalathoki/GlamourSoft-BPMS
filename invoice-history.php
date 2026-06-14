<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['bpmsuid'] == 0)) {
    header('location:logout.php');
} else {

    /* ── pre-fetch rows for stat counts ── */
    $userid = $_SESSION['bpmsuid'];

    $query = mysqli_query($con,
        "SELECT DISTINCT
                tbluser.FirstName,
                tbluser.LastName,
                tbluser.MobileNumber,
                tblinvoice.BillingId,
                DATE(tblinvoice.PostingDate) AS PostingDate
         FROM   tbluser
         JOIN   tblinvoice ON tbluser.ID = tblinvoice.Userid
         WHERE  tbluser.ID = '$userid'
         ORDER  BY tblinvoice.ID DESC"
    );

    $rows       = [];
    $thisMonth  = 0;
    $thisYear   = 0;
    $curMonth   = (int) date('m');
    $curYear    = (int) date('Y');

    while ($row = mysqli_fetch_assoc($query)) {
        $rows[] = $row;

        if (!empty($row['PostingDate'])) {
            $rMonth = (int) date('m', strtotime($row['PostingDate']));
            $rYear  = (int) date('Y', strtotime($row['PostingDate']));
            if ($rYear === $curYear && $rMonth === $curMonth) $thisMonth++;
            if ($rYear === $curYear)                          $thisYear++;
        }
    }

    $total = count($rows);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice History | GlamourSoft</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <style>
        /* ─── TOKENS ──────────────────────────────────── */
        :root {
            --pink:           #e91e63;
            --pink-mid:       #ff4f81;
            --pink-light:     #fce4ec;
            --pink-dark:      #c2185b;
            --gradient:       linear-gradient(135deg, #e91e63 0%, #ff4f81 100%);

            --teal:           #0891b2;
            --teal-light:     #e0f2fe;
            --violet:         #7c3aed;
            --violet-light:   #ede9fe;
            --emerald:        #059669;
            --emerald-light:  #d1fae5;
            --blue:           #3b82f6;
            --blue-light:     #dbeafe;

            --surface:        #fdf5f8;
            --card:           #ffffff;
            --text-dark:      #1a1a1a;
            --text-mid:       #555;
            --text-muted:     #999;
            --border:         #f0eaee;

            --radius-xl:      24px;
            --radius-md:      14px;
            --radius-sm:      10px;
            --shadow-card:    0 8px 40px rgba(233,30,99,.07);
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--surface);
            color: var(--text-dark);
            margin: 0;
        }

        /* ─── HERO ─────────────────────────────────────── */
        .apt-hero {
            background: var(--gradient);
            padding: 72px 0 52px;
            position: relative;
            overflow: hidden;
        }

        .apt-hero .bubble {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,.06);
            pointer-events: none;
        }
        .b1 { width:340px; height:340px; top:-80px;    right:-60px; }
        .b2 { width:180px; height:180px; bottom:-60px; left:6%;     }
        .b3 { width: 70px; height: 70px; top:28px;     left:42%;    }

        .hero-icon-ring {
            width: 62px;
            height: 62px;
            background: rgba(255,255,255,.18);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            backdrop-filter: blur(4px);
        }

        .apt-hero h1 {
            font-size: clamp(1.55rem, 3vw, 2.3rem);
            font-weight: 700;
            color: #fff;
            margin-bottom: 3px;
            line-height: 1.2;
        }
        .apt-hero .hero-sub {
            color: rgba(255,255,255,.82);
            font-size: .92rem;
            font-weight: 300;
            margin: 0;
        }

        .apt-hero .breadcrumb      { background: transparent; margin: 12px 0 0; padding: 0; }
        .apt-hero .breadcrumb-item a          { color: rgba(255,255,255,.72); text-decoration: none; font-size: .8rem; }
        .apt-hero .breadcrumb-item a:hover    { color: #fff; }
        .apt-hero .breadcrumb-item.active     { color: rgba(255,255,255,.95); font-size: .8rem; }
        .apt-hero .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,.45); }

        /* ─── STATS ────────────────────────────────────── */
        .stats-section { padding: 40px 0 0; }

        .stat-card {
            background: var(--card);
            border-radius: var(--radius-md);
            padding: 22px 24px;
            box-shadow: var(--shadow-card);
            display: flex;
            align-items: center;
            gap: 16px;
            transition: transform .2s, box-shadow .2s;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 36px rgba(233,30,99,.11);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.25rem;
        }
        .stat-icon.total   { background: var(--blue-light);    color: var(--blue);    }
        .stat-icon.month   { background: var(--pink-light);    color: var(--pink);    }
        .stat-icon.year    { background: var(--violet-light);  color: var(--violet);  }

        .stat-number {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1;
            margin-bottom: 2px;
        }
        .stat-label {
            font-size: .72rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .9px;
        }

        /* ─── TABLE CARD ───────────────────────────────── */
        .table-section { padding: 28px 0 80px; }

        .t-card {
            background: var(--card);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-card);
            overflow: hidden;
        }

        .t-card-header {
            padding: 24px 28px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border);
            flex-wrap: wrap;
            gap: 12px;
        }

        .t-card-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .t-card-title .bi { color: var(--pink); }

        /* TABLE */
        .inv-table { width: 100%; border-collapse: collapse; }

        .inv-table thead tr { background: var(--surface); }
        .inv-table thead th {
            padding: 13px 20px;
            font-size: .7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        .inv-table tbody tr {
            border-bottom: 1px solid #faf0f4;
            transition: background .18s;
        }
        .inv-table tbody tr:last-child { border-bottom: none; }
        .inv-table tbody tr:hover      { background: #fff5f8; }

        .inv-table tbody td {
            padding: 15px 20px;
            font-size: .86rem;
            color: var(--text-mid);
            vertical-align: middle;
        }

        /* serial */
        .td-serial {
            font-size: .76rem;
            font-weight: 600;
            color: var(--text-muted);
        }

        /* invoice id chip */
        .inv-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: var(--teal-light);
            color: var(--teal);
            border-radius: 8px;
            padding: 4px 10px;
            font-size: .76rem;
            font-weight: 700;
            letter-spacing: .4px;
        }

        /* customer avatar + name */
        .customer-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .avatar-ring {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--gradient);
            color: #fff;
            font-size: .72rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            letter-spacing: .5px;
        }
        .customer-name {
            font-weight: 600;
            color: var(--text-dark);
            font-size: .86rem;
        }

        /* phone */
        .phone-cell {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: .84rem;
        }
        .phone-cell .bi { color: var(--pink); font-size: .82rem; }

        /* date */
        .date-cell {
            display: flex;
            align-items: center;
            gap: 7px;
            font-weight: 500;
            color: var(--text-dark);
            font-size: .84rem;
        }
        .date-cell .bi { color: var(--pink); font-size: .82rem; }

        /* view button */
        .btn-view {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--teal-light);
            color: var(--teal);
            border: none;
            border-radius: 8px;
            padding: 7px 14px;
            font-family: 'Poppins', sans-serif;
            font-size: .78rem;
            font-weight: 600;
            text-decoration: none;
            transition: background .2s, color .2s, transform .2s;
        }
        .btn-view:hover {
            background: var(--teal);
            color: #fff;
            transform: translateY(-1px);
        }

        /* ─── EMPTY STATE ───────────────────────────────── */
        .empty-state {
            text-align: center;
            padding: 64px 24px;
        }
        .empty-icon-ring {
            width: 80px;
            height: 80px;
            background: var(--teal-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        .empty-icon-ring .bi { font-size: 2rem; color: var(--teal); }
        .empty-state h6 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 6px;
        }
        .empty-state p {
            font-size: .85rem;
            color: var(--text-muted);
            margin-bottom: 0;
        }

        /* ─── BACK TO TOP ───────────────────────────────── */
        #movetop {
            display: none;
            position: fixed;
            bottom: 28px;
            right: 28px;
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: var(--gradient);
            color: #fff;
            border: none;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(233,30,99,.35);
            font-size: 1.05rem;
            transition: transform .2s;
            z-index: 999;
        }
        #movetop:hover { transform: translateY(-3px); }

        /* ─── RESPONSIVE ────────────────────────────────── */
        @media (max-width: 767px) {
            .apt-hero { padding: 56px 0 38px; }
            .inv-table thead th,
            .inv-table tbody td { padding: 12px 12px; }
        }
        @media (max-width: 575px) {
            .col-serial, .col-phone { display: none; }
        }
    </style>
</head>
<body>

<?php include_once('includes/header.php'); ?>

<!-- ══ HERO ════════════════════════════════════════════════ -->
<section class="apt-hero">
    <div class="bubble b1"></div>
    <div class="bubble b2"></div>
    <div class="bubble b3"></div>

    <div class="container">
        <div class="d-flex align-items-center gap-3 mb-2">
            <div class="hero-icon-ring">
                <i class="bi bi-receipt fs-3 text-white"></i>
            </div>
            <div>
                <h1>Invoice History</h1>
                <p class="hero-sub">All your billing records in one place.</p>
            </div>
        </div>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Invoice History</li>
            </ol>
        </nav>
    </div>
</section>

<!-- ══ STATS ═══════════════════════════════════════════════ -->
<section class="stats-section">
    <div class="container">
        <div class="row g-3">

            <div class="col-12 col-md-4">
                <div class="stat-card">
                    <div class="stat-icon total">
                        <i class="bi bi-receipt-cutoff"></i>
                    </div>
                    <div>
                        <div class="stat-number"><?php echo $total; ?></div>
                        <div class="stat-label">Total Invoices</div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="stat-card">
                    <div class="stat-icon month">
                        <i class="bi bi-calendar-month"></i>
                    </div>
                    <div>
                        <div class="stat-number"><?php echo $thisMonth; ?></div>
                        <div class="stat-label">This Month</div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="stat-card">
                    <div class="stat-icon year">
                        <i class="bi bi-calendar-range"></i>
                    </div>
                    <div>
                        <div class="stat-number"><?php echo $thisYear; ?></div>
                        <div class="stat-label">This Year</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ══ TABLE ═══════════════════════════════════════════════ -->
<section class="table-section">
    <div class="container">
        <div class="t-card">

            <!-- Card Header -->
            <div class="t-card-header">
                <h5 class="t-card-title">
                    <i class="bi bi-list-columns"></i>
                    Invoice Records
                </h5>
                <span style="font-size:.78rem;color:var(--text-muted);">
                    <i class="bi bi-info-circle me-1"></i>
                    Click <strong>View</strong> on any row to see full invoice details.
                </span>
            </div>

            <!-- Table or Empty State -->
            <?php if ($total > 0) { ?>

            <div class="table-responsive">
                <table class="inv-table">
                    <thead>
                        <tr>
                            <th class="col-serial">#</th>
                            <th>Invoice ID</th>
                            <th>Customer</th>
                            <th class="col-phone">Mobile</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cnt = 1;
                        foreach ($rows as $row) {
                            /* initials for avatar */
                            $initials = strtoupper(
                                substr($row['FirstName'], 0, 1) .
                                substr($row['LastName'],  0, 1)
                            );

                            /* formatted date */
                            $fDate = !empty($row['PostingDate'])
                                ? date('d M Y', strtotime($row['PostingDate']))
                                : '—';
                        ?>
                        <tr>
                            <td class="td-serial col-serial"><?php echo $cnt; ?></td>

                            <td>
                                <span class="inv-chip">
                                    <i class="bi bi-hash"></i>
                                    <?php echo htmlspecialchars($row['BillingId']); ?>
                                </span>
                            </td>

                            <td>
                                <div class="customer-cell">
                                    <div class="avatar-ring"><?php echo $initials; ?></div>
                                    <span class="customer-name">
                                        <?php echo htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']); ?>
                                    </span>
                                </div>
                            </td>

                            <td class="col-phone">
                                <div class="phone-cell">
                                    <i class="bi bi-telephone-fill"></i>
                                    <?php echo htmlspecialchars($row['MobileNumber']); ?>
                                </div>
                            </td>

                            <td>
                                <div class="date-cell">
                                    <i class="bi bi-calendar3"></i>
                                    <?php echo $fDate; ?>
                                </div>
                            </td>

                            <td>
                                <a href="view-invoice.php?invoiceid=<?php echo urlencode($row['BillingId']); ?>"
                                   class="btn-view">
                                    <i class="bi bi-eye"></i>
                                    View
                                </a>
                            </td>
                        </tr>
                        <?php $cnt++; } ?>
                    </tbody>
                </table>
            </div>

            <?php } else { ?>

            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-icon-ring">
                    <i class="bi bi-receipt"></i>
                </div>
                <h6>No Invoices Found</h6>
                <p>Your invoice records will appear here once billing has been processed by our team.</p>
            </div>

            <?php } ?>

        </div>
    </div>
</section>

<?php include_once('includes/footer.php'); ?>

<!-- Back to top -->
<button onclick="topFunction()" id="movetop" title="Go to top">
    <i class="bi bi-arrow-up"></i>
</button>

<script>
    window.onscroll = function () {
        document.getElementById('movetop').style.display =
            (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20)
                ? 'block' : 'none';
    };
    function topFunction() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }
</script>

</body>
</html>
<?php } ?>