<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['bpmsuid'] == 0)) {
    header('location:logout.php');
} else {

    /* ── pre-fetch all rows so we can build stat counts ── */
    $userid = $_SESSION['bpmsuid'];

    $query = mysqli_query($con,
        "SELECT tbluser.FirstName, tbluser.LastName,
                tblbook.ID        AS bid,
                tblbook.AptNumber,
                tblbook.AptDate,
                tblbook.AptTime,
                tblbook.Message,
                tblbook.BookingDate,
                tblbook.Status
         FROM   tblbook
         JOIN   tbluser ON tbluser.ID = tblbook.UserID
         WHERE  tbluser.ID = '$userid'
         ORDER  BY tblbook.ID DESC"
    );

    $rows     = [];
    $pending  = 0;
    $accepted = 0;
    $rejected = 0;

    while ($row = mysqli_fetch_assoc($query)) {
        $rows[] = $row;
        $s      = strtolower(trim((string) $row['Status']));
        if ($s === '')                         $pending++;
        elseif (strpos($s, 'accept') !== false) $accepted++;
        elseif (strpos($s, 'reject') !== false) $rejected++;
    }

    $total = count($rows);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking History | GlamourSoft</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <style>
        /* ─── TOKENS ──────────────────────────────── */
        :root {
            --pink:        #e91e63;
            --pink-mid:    #ff4f81;
            --pink-light:  #fce4ec;
            --pink-dark:   #c2185b;
            --gradient:    linear-gradient(135deg, #e91e63 0%, #ff4f81 100%);

            --amber:       #f59e0b;
            --amber-light: #fef3c7;
            --green:       #10b981;
            --green-light: #d1fae5;
            --red:         #ef4444;
            --red-light:   #fee2e2;
            --blue:        #3b82f6;
            --blue-light:  #dbeafe;

            --surface:     #fdf5f8;
            --card:        #ffffff;
            --text-dark:   #1a1a1a;
            --text-mid:    #555;
            --text-muted:  #999;
            --border:      #f0eaee;

            --radius-xl:   24px;
            --radius-md:   14px;
            --radius-sm:   10px;
            --shadow-card: 0 8px 40px rgba(233,30,99,.07);
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--surface);
            color: var(--text-dark);
            margin: 0;
        }

        /* ─── HERO ──────────────────────────────────── */
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

        /* ─── STATS ROW ──────────────────────────────── */
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

        .stat-icon.all      { background: var(--blue-light);   color: var(--blue);  }
        .stat-icon.pending  { background: var(--amber-light);  color: var(--amber); }
        .stat-icon.accepted { background: var(--green-light);  color: var(--green); }
        .stat-icon.rejected { background: var(--red-light);    color: var(--red);   }

        .stat-number {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1;
            margin-bottom: 2px;
        }
        .stat-label {
            font-size: .75rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .8px;
        }

        /* ─── TABLE CARD ──────────────────────────────── */
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

        .btn-new-apt {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: var(--gradient);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 9px 18px;
            font-family: 'Poppins', sans-serif;
            font-size: .82rem;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(233,30,99,.25);
            transition: transform .2s, box-shadow .2s;
        }
        .btn-new-apt:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(233,30,99,.35);
            color: #fff;
        }

        /* TABLE */
        .bh-table { width: 100%; border-collapse: collapse; }

        .bh-table thead tr {
            background: var(--surface);
        }
        .bh-table thead th {
            padding: 13px 20px;
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        .bh-table tbody tr {
            border-bottom: 1px solid #faf0f4;
            transition: background .18s;
        }
        .bh-table tbody tr:last-child { border-bottom: none; }
        .bh-table tbody tr:hover { background: #fff5f8; }

        .bh-table tbody td {
            padding: 15px 20px;
            font-size: .86rem;
            color: var(--text-mid);
            vertical-align: middle;
        }

        /* serial number */
        .td-serial {
            font-size: .78rem;
            font-weight: 600;
            color: var(--text-muted);
        }

        /* apt number chip */
        .apt-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--pink-light);
            color: var(--pink-dark);
            border-radius: 8px;
            padding: 4px 10px;
            font-size: .78rem;
            font-weight: 600;
            letter-spacing: .3px;
        }
        .apt-chip .bi { font-size: .7rem; }

        /* date + time cells */
        .td-date {
            font-weight: 500;
            color: var(--text-dark);
        }
        .td-time {
            font-weight: 500;
            color: var(--text-dark);
        }

        /* status badges */
        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            border-radius: 20px;
            padding: 5px 12px;
            font-size: .74rem;
            font-weight: 600;
            white-space: nowrap;
        }
        .badge-status.pending  { background: var(--amber-light); color: #92400e; }
        .badge-status.accepted { background: var(--green-light); color: #065f46; }
        .badge-status.rejected { background: var(--red-light);   color: #991b1b; }

        /* view button */
        .btn-view {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--pink-light);
            color: var(--pink);
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
            background: var(--pink);
            color: #fff;
            transform: translateY(-1px);
        }

        /* ─── EMPTY STATE ──────────────────────────── */
        .empty-state {
            text-align: center;
            padding: 64px 24px;
        }
        .empty-icon-ring {
            width: 80px;
            height: 80px;
            background: var(--pink-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        .empty-icon-ring .bi { font-size: 2rem; color: var(--pink); }
        .empty-state h6 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 6px;
        }
        .empty-state p {
            font-size: .85rem;
            color: var(--text-muted);
            margin-bottom: 20px;
        }

        /* ─── BACK TO TOP ──────────────────────────── */
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

        /* ─── RESPONSIVE ───────────────────────────── */
        @media (max-width: 767px) {
            .apt-hero { padding: 56px 0 38px; }
            .bh-table thead th,
            .bh-table tbody td { padding: 12px 12px; }
        }
        @media (max-width: 575px) {
            /* hide serial + date on very small screens */
            .bh-table .col-serial,
            .bh-table .col-date   { display: none; }
        }
    </style>
</head>
<body>

<?php include_once('includes/header.php'); ?>

<!-- ══ HERO ═══════════════════════════════════════════════ -->
<section class="apt-hero">
    <div class="bubble b1"></div>
    <div class="bubble b2"></div>
    <div class="bubble b3"></div>

    <div class="container">
        <div class="d-flex align-items-center gap-3 mb-2">
            <div class="hero-icon-ring">
                <i class="bi bi-clock-history fs-3 text-white"></i>
            </div>
            <div>
                <h1>Booking History</h1>
                <p class="hero-sub">Track all your past and upcoming appointments.</p>
            </div>
        </div>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Booking History</li>
            </ol>
        </nav>
    </div>
</section>

<!-- ══ STATS ══════════════════════════════════════════════ -->
<section class="stats-section">
    <div class="container">
        <div class="row g-3">

            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="stat-icon all">
                        <i class="bi bi-calendar3"></i>
                    </div>
                    <div>
                        <div class="stat-number"><?php echo $total; ?></div>
                        <div class="stat-label">Total</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="stat-icon pending">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <div class="stat-number"><?php echo $pending; ?></div>
                        <div class="stat-label">Pending</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="stat-icon accepted">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div>
                        <div class="stat-number"><?php echo $accepted; ?></div>
                        <div class="stat-label">Accepted</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="stat-icon rejected">
                        <i class="bi bi-calendar-x"></i>
                    </div>
                    <div>
                        <div class="stat-number"><?php echo $rejected; ?></div>
                        <div class="stat-label">Rejected</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ══ TABLE ══════════════════════════════════════════════ -->
<section class="table-section">
    <div class="container">
        <div class="t-card">

            <!-- Card Header -->
            <div class="t-card-header">
                <h5 class="t-card-title">
                    <i class="bi bi-list-ul"></i>
                    Appointment Records
                </h5>
                <a href="book-appointment.php" class="btn-new-apt">
                    <i class="bi bi-plus-circle"></i>
                    New Appointment
                </a>
            </div>

            <!-- Table -->
            <?php if ($total > 0) { ?>

            <div class="table-responsive">
                <table class="bh-table">
                    <thead>
                        <tr>
                            <th class="col-serial">#</th>
                            <th>Apt. Number</th>
                            <th class="col-date">Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cnt = 1;
                        foreach ($rows as $row) {
                            $rawStatus = trim((string) $row['Status']);
                            $sLow      = strtolower($rawStatus);

                            if ($sLow === '') {
                                $badgeClass  = 'pending';
                                $badgeIcon   = 'bi-hourglass-split';
                                $badgeLabel  = 'Pending';
                            } elseif (strpos($sLow, 'accept') !== false) {
                                $badgeClass  = 'accepted';
                                $badgeIcon   = 'bi-check-circle';
                                $badgeLabel  = 'Accepted';
                            } elseif (strpos($sLow, 'reject') !== false) {
                                $badgeClass  = 'rejected';
                                $badgeIcon   = 'bi-x-circle';
                                $badgeLabel  = 'Rejected';
                            } else {
                                $badgeClass  = 'pending';
                                $badgeIcon   = 'bi-info-circle';
                                $badgeLabel  = htmlspecialchars($rawStatus);
                            }
                        ?>
                        <tr>
                            <td class="td-serial col-serial"><?php echo $cnt; ?></td>

                            <td>
                                <span class="apt-chip">
                                    <i class="bi bi-hash"></i>
                                    <?php echo htmlspecialchars($row['AptNumber']); ?>
                                </span>
                            </td>

                            <td class="td-date col-date">
                                <i class="bi bi-calendar3 me-1" style="color:var(--pink);font-size:.8rem;"></i>
                                <?php echo htmlspecialchars($row['AptDate']); ?>
                            </td>

                            <td class="td-time">
                                <i class="bi bi-clock me-1" style="color:var(--pink);font-size:.8rem;"></i>
                                <?php echo htmlspecialchars($row['AptTime']); ?>
                            </td>

                            <td>
                                <span class="badge-status <?php echo $badgeClass; ?>">
                                    <i class="bi <?php echo $badgeIcon; ?>"></i>
                                    <?php echo $badgeLabel; ?>
                                </span>
                            </td>

                            <td>
                                <a href="appointment-detail.php?aptnumber=<?php echo urlencode($row['AptNumber']); ?>"
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
                    <i class="bi bi-calendar-x"></i>
                </div>
                <h6>No Appointments Yet</h6>
                <p>You haven't booked any appointments. Ready to get started?</p>
                <a href="book-appointment.php" class="btn-new-apt">
                    <i class="bi bi-plus-circle"></i>
                    Book Your First Appointment
                </a>
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