<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['bpmsuid'] == 0)) {
    header('location:logout.php');
} else {

    $invid = intval($_GET['invoiceid']);

    /* ── customer details ── */
    $retC = mysqli_query($con,
        "SELECT DISTINCT
                DATE(tblinvoice.PostingDate) AS invoicedate,
                tbluser.FirstName,
                tbluser.LastName,
                tbluser.Email,
                tbluser.MobileNumber,
                tbluser.RegDate
         FROM   tblinvoice
         JOIN   tbluser ON tbluser.ID = tblinvoice.Userid
         WHERE  tblinvoice.BillingId = '$invid'"
    );
    $customer = mysqli_fetch_assoc($retC);

    /* ── services + grand total ── */
    $retS = mysqli_query($con,
        "SELECT tblservices.ServiceName, tblservices.Cost
         FROM   tblinvoice
         JOIN   tblservices ON tblservices.ID = tblinvoice.ServiceId
         WHERE  tblinvoice.BillingId = '$invid'"
    );
    $services = [];
    $gtotal   = 0;
    while ($svc = mysqli_fetch_assoc($retS)) {
        $services[] = $svc;
        $gtotal    += $svc['Cost'];
    }
    $svcCount = count($services);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?php echo $invid; ?> | GlamourSoft</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <style>
        /* ─── TOKENS ──────────────────────────────── */
        :root {
            --pink:          #e91e63;
            --pink-mid:      #ff4f81;
            --pink-light:    #fce4ec;
            --pink-dark:     #c2185b;
            --gradient:      linear-gradient(135deg, #e91e63 0%, #ff4f81 100%);

            --teal:          #0891b2;
            --teal-light:    #e0f2fe;

            --surface:       #fdf5f8;
            --card:          #ffffff;
            --text-dark:     #1a1a1a;
            --text-mid:      #555;
            --text-muted:    #999;
            --border:        #f0eaee;

            --radius-xl:     24px;
            --radius-md:     14px;
            --radius-sm:     10px;
            --shadow-card:   0 8px 40px rgba(233,30,99,.07);
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--surface);
            color: var(--text-dark);
            margin: 0;
        }

        /* ─── HERO ─────────────────────────────────── */
        .apt-hero {
            background: var(--gradient);
            padding: 64px 0 46px;
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
            font-size: clamp(1.4rem, 3vw, 2.1rem);
            font-weight: 700;
            color: #fff;
            margin-bottom: 3px;
        }
        .apt-hero .hero-sub {
            color: rgba(255,255,255,.82);
            font-size: .9rem;
            font-weight: 300;
            margin: 0;
        }
        .apt-hero .breadcrumb      { background: transparent; margin: 12px 0 0; padding: 0; }
        .apt-hero .breadcrumb-item a          { color: rgba(255,255,255,.72); text-decoration: none; font-size: .8rem; }
        .apt-hero .breadcrumb-item a:hover    { color: #fff; }
        .apt-hero .breadcrumb-item.active     { color: rgba(255,255,255,.95); font-size: .8rem; }
        .apt-hero .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,.45); }

        /* ─── MAIN ──────────────────────────────────── */
        .inv-main { padding: 44px 0 80px; }

        /* ─── INVOICE CARD ──────────────────────────── */
        .invoice-card {
            background: var(--card);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-card);
            overflow: hidden;
        }

        /* ── branded top bar ── */
        .inv-topbar {
            padding: 30px 36px 26px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            border-bottom: 1px solid var(--border);
            flex-wrap: wrap;
            gap: 20px;
        }

        .inv-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .inv-brand-icon {
            width: 48px;
            height: 48px;
            background: var(--gradient);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        .inv-brand-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--pink);
            line-height: 1.1;
        }
        .inv-brand-name span { color: #111; font-weight: 600; }
        .inv-brand-tag {
            font-size: .68rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1.1px;
            font-weight: 500;
        }

        .inv-id-block { text-align: right; }
        .inv-id-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: var(--teal-light);
            color: var(--teal);
            border-radius: 10px;
            padding: 6px 14px;
            font-size: .8rem;
            font-weight: 700;
            letter-spacing: .4px;
            margin-bottom: 6px;
        }
        .inv-id-date {
            font-size: .8rem;
            color: var(--text-muted);
        }
        .inv-id-date strong { color: var(--text-dark); font-weight: 600; }

        /* ── info two-column grid ── */
        .inv-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            border-bottom: 1px solid var(--border);
        }
        .inv-info-col {
            padding: 28px 36px;
        }
        .inv-info-col:first-child { border-right: 1px solid var(--border); }

        .col-section-label {
            font-size: .67rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.3px;
            color: var(--pink);
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .irow {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 12px;
        }
        .irow:last-child { margin-bottom: 0; }

        .irow-icon {
            width: 30px;
            height: 30px;
            background: var(--pink-light);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .irow-icon .bi { color: var(--pink); font-size: .78rem; }

        .irow-lbl {
            font-size: .67rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: var(--text-muted);
            margin-bottom: 1px;
        }
        .irow-val {
            font-size: .875rem;
            color: var(--text-dark);
            font-weight: 500;
        }

        /* ── services section header ── */
        .svc-section-header {
            padding: 18px 36px 14px;
            font-size: .67rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.3px;
            color: var(--pink);
            display: flex;
            align-items: center;
            gap: 7px;
            border-bottom: 1px solid var(--border);
        }

        /* ── services table ── */
        .svc-table { width: 100%; border-collapse: collapse; }

        .svc-table thead tr { background: var(--surface); }
        .svc-table thead th {
            padding: 12px 20px;
            font-size: .69rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
        }
        .svc-table thead th.th-cost { text-align: right; }

        .svc-table tbody tr {
            border-bottom: 1px solid #faf0f4;
            transition: background .15s;
        }
        .svc-table tbody tr:last-child { border-bottom: none; }
        .svc-table tbody tr:hover { background: #fff5f8; }

        .svc-table tbody td {
            padding: 15px 20px;
            font-size: .875rem;
            color: var(--text-mid);
            vertical-align: middle;
        }

        .td-serial { font-size: .74rem; font-weight: 600; color: var(--text-muted); width: 50px; }

        .svc-name-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .svc-bullet {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--gradient);
            flex-shrink: 0;
        }
        .svc-name-text { font-weight: 500; color: var(--text-dark); }

        .td-cost {
            text-align: right;
            font-weight: 600;
            color: var(--text-dark);
            font-size: .88rem;
        }

        /* ── grand total ── */
        .grand-total-bar {
            background: var(--surface);
            border-top: 2px solid var(--border);
            padding: 20px 36px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .gt-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .gt-icon {
            width: 40px;
            height: 40px;
            background: var(--pink-light);
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .gt-icon .bi { color: var(--pink); font-size: 1rem; }
        .gt-label {
            font-size: .8rem;
            font-weight: 700;
            color: var(--text-mid);
            text-transform: uppercase;
            letter-spacing: .9px;
        }
        .gt-sub {
            font-size: .72rem;
            color: var(--text-muted);
        }

        .gt-amount-wrap { text-align: right; }
        .gt-currency { font-size: .85rem; font-weight: 500; color: var(--text-muted); }
        .gt-amount {
            font-size: 1.7rem;
            font-weight: 700;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.1;
        }

        /* ── action bar ── */
        .action-bar {
            padding: 20px 36px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top: 1px solid var(--border);
            flex-wrap: wrap;
            gap: 12px;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: transparent;
            color: var(--pink);
            border: 1.5px solid var(--pink-light);
            border-radius: 10px;
            padding: 9px 18px;
            font-family: 'Poppins', sans-serif;
            font-size: .82rem;
            font-weight: 600;
            text-decoration: none;
            transition: background .2s, border-color .2s;
        }
        .btn-back:hover {
            background: var(--pink-light);
            border-color: var(--pink-light);
            color: var(--pink-dark);
        }

        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: var(--gradient);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 9px 20px;
            font-family: 'Poppins', sans-serif;
            font-size: .82rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(233,30,99,.25);
            transition: transform .2s, box-shadow .2s;
        }
        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(233,30,99,.35);
        }

        /* ─── BACK TO TOP ───────────────────────────── */
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

        /* ─── RESPONSIVE ────────────────────────────── */
        @media (max-width: 767px) {
            .inv-topbar,
            .inv-info-col,
            .grand-total-bar,
            .action-bar    { padding: 22px 18px; }
            .svc-table thead th,
            .svc-table tbody td { padding: 12px 14px; }
            .svc-section-header { padding: 16px 18px 12px; }
        }

        @media (max-width: 575px) {
            .inv-info-grid { grid-template-columns: 1fr; }
            .inv-info-col:first-child { border-right: none; border-bottom: 1px solid var(--border); }
            .inv-id-block { text-align: left; }
        }

        /* ─── PRINT ─────────────────────────────────── */
        @media print {
            .navbar-custom,
            .apt-hero,
            footer,
            .action-bar,
            #movetop         { display: none !important; }

            body             { background: #fff !important; }
            .inv-main        { padding: 0 !important; }

            .invoice-card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
                border-radius: 8px !important;
            }

            .gt-amount {
                -webkit-text-fill-color: #e91e63 !important;
                color: #e91e63 !important;
            }
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
                <i class="bi bi-file-earmark-text fs-3 text-white"></i>
            </div>
            <div>
                <h1>Invoice #<?php echo $invid; ?></h1>
                <p class="hero-sub">Full billing details for this invoice.</p>
            </div>
        </div>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="invoice-history.php">Invoice History</a></li>
                <li class="breadcrumb-item active">Invoice #<?php echo $invid; ?></li>
            </ol>
        </nav>
    </div>
</section>

<!-- ══ INVOICE ═════════════════════════════════════════════ -->
<section class="inv-main">
    <div class="container">
        <div class="invoice-card">

            <!-- ── Branded Top Bar ── -->
            <div class="inv-topbar">
                <div class="inv-brand">
                    <div class="inv-brand-icon">
                        <i class="bi bi-scissors"></i>
                    </div>
                    <div>
                        <div class="inv-brand-name">
                            Glamour<span>Soft</span>
                        </div>
                        <div class="inv-brand-tag">Beauty Parlour Management</div>
                    </div>
                </div>

                <div class="inv-id-block">
                    <div>
                        <span class="inv-id-chip">
                            <i class="bi bi-receipt"></i>
                            Invoice #<?php echo $invid; ?>
                        </span>
                    </div>
                    <div class="inv-id-date">
                        Date:&nbsp;
                        <strong>
                            <?php
                            echo (!empty($customer['invoicedate']))
                                ? date('d M Y', strtotime($customer['invoicedate']))
                                : '—';
                            ?>
                        </strong>
                    </div>
                </div>
            </div>

            <!-- ── Customer + Invoice Info Grid ── -->
            <?php if ($customer): ?>
            <div class="inv-info-grid">

                <!-- Billed To -->
                <div class="inv-info-col">
                    <div class="col-section-label">
                        <i class="bi bi-person-fill"></i> Billed To
                    </div>

                    <div class="irow">
                        <div class="irow-icon"><i class="bi bi-person"></i></div>
                        <div>
                            <div class="irow-lbl">Full Name</div>
                            <div class="irow-val">
                                <?php echo htmlspecialchars($customer['FirstName'] . ' ' . $customer['LastName']); ?>
                            </div>
                        </div>
                    </div>

                    <div class="irow">
                        <div class="irow-icon"><i class="bi bi-envelope"></i></div>
                        <div>
                            <div class="irow-lbl">Email</div>
                            <div class="irow-val"><?php echo htmlspecialchars($customer['Email']); ?></div>
                        </div>
                    </div>

                    <div class="irow">
                        <div class="irow-icon"><i class="bi bi-telephone"></i></div>
                        <div>
                            <div class="irow-lbl">Mobile</div>
                            <div class="irow-val"><?php echo htmlspecialchars($customer['MobileNumber']); ?></div>
                        </div>
                    </div>
                </div>

                <!-- Invoice Details -->
                <div class="inv-info-col">
                    <div class="col-section-label">
                        <i class="bi bi-file-earmark-text"></i> Invoice Details
                    </div>

                    <div class="irow">
                        <div class="irow-icon"><i class="bi bi-hash"></i></div>
                        <div>
                            <div class="irow-lbl">Invoice Number</div>
                            <div class="irow-val">#<?php echo $invid; ?></div>
                        </div>
                    </div>

                    <div class="irow">
                        <div class="irow-icon"><i class="bi bi-calendar3"></i></div>
                        <div>
                            <div class="irow-lbl">Invoice Date</div>
                            <div class="irow-val">
                                <?php
                                echo (!empty($customer['invoicedate']))
                                    ? date('d M Y', strtotime($customer['invoicedate']))
                                    : '—';
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="irow">
                        <div class="irow-icon"><i class="bi bi-person-check"></i></div>
                        <div>
                            <div class="irow-lbl">Member Since</div>
                            <div class="irow-val">
                                <?php
                                echo (!empty($customer['RegDate']))
                                    ? date('d M Y', strtotime($customer['RegDate']))
                                    : '—';
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <?php endif; ?>

            <!-- ── Services Section ── -->
            <div class="svc-section-header">
                <i class="bi bi-scissors"></i>
                Services Rendered
            </div>

            <div class="table-responsive">
                <table class="svc-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Service</th>
                            <th class="th-cost">Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cnt = 1;
                        foreach ($services as $svc):
                        ?>
                        <tr>
                            <td class="td-serial"><?php echo $cnt; ?></td>

                            <td>
                                <div class="svc-name-cell">
                                    <div class="svc-bullet"></div>
                                    <span class="svc-name-text">
                                        <?php echo htmlspecialchars($svc['ServiceName']); ?>
                                    </span>
                                </div>
                            </td>

                            <td class="td-cost">
                                Rs.&nbsp;<?php echo number_format((float)$svc['Cost'], 2); ?>
                            </td>
                        </tr>
                        <?php $cnt++; endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- ── Grand Total ── -->
            <div class="grand-total-bar">
                <div class="gt-left">
                    <div class="gt-icon"><i class="bi bi-calculator"></i></div>
                    <div>
                        <div class="gt-label">Grand Total</div>
                        <div class="gt-sub">
                            <?php echo $svcCount; ?> service<?php echo $svcCount !== 1 ? 's' : ''; ?> included
                        </div>
                    </div>
                </div>
                <div class="gt-amount-wrap">
                    <span class="gt-currency">Rs.&nbsp;</span><span class="gt-amount"><?php echo number_format((float)$gtotal, 2); ?></span>
                </div>
            </div>

            <!-- ── Actions ── -->
            <div class="action-bar">
                <a href="invoice-history.php" class="btn-back">
                    <i class="bi bi-arrow-left"></i>
                    Back to Invoices
                </a>
                <button class="btn-print" onclick="window.print()">
                    <i class="bi bi-printer"></i>
                    Print Invoice
                </button>
            </div>

        </div>
    </div>
</section>

<?php include_once('includes/footer.php'); ?>

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