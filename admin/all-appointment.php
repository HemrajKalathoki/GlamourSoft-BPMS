<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['bpmsaid']==0)) {
    header('location:logout.php');
} else {

/* ── Delete handler — zero logic change ─────────────────── */
if ($_GET['delid']) {
    $sid = $_GET['delid'];
    mysqli_query($con, "delete from tblbook where ID='$sid'");
    echo "<script>alert('Data Deleted');</script>";
    echo "<script>window.location.href='all-appointment.php'</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BPMS | All Appointments</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary:        #e91e63;
            --primary-dark:   #c2185b;
            --secondary:      #ff4f81;
            --primary-light:  #fce4ec;
            --primary-muted:  #fdf0f5;
            --border-soft:    #f3e0ea;
            --text-dark:      #1f2937;
            --text-muted:     #9e9e9e;
            --surface:        #ffffff;
            --page-bg:        #fdf6fb;
            --success:        #10b981;
            --success-light:  #d1fae5;
            --danger:         #ef4444;
            --danger-light:   #fee2e2;
            --warning:        #f59e0b;
            --warning-light:  #fef3c7;
            --blue:           #3b82f6;
            --blue-light:     #dbeafe;
            --purple:         #8b5cf6;
            --purple-light:   #ede9fe;
            --radius-card:    16px;
            --radius-btn:     10px;
            --shadow-card:    0 2px 20px rgba(233,30,99,.07);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--page-bg);
            color: var(--text-dark);
        }

        /* ── Layout ─────────────────────────────────────────── */
        .dashboard-wrapper {
            margin-left: 280px;
            padding-top: 70px;
            min-height: 100vh;
            transition: margin-left .3s;
        }
        .dashboard-wrapper.full-width { margin-left: 0; }
        @media (max-width: 991px) { .dashboard-wrapper { margin-left: 0; } }

        .page-content { padding: 2rem 2rem 3rem; }
        @media (max-width: 768px) { .page-content { padding: 1.25rem 1rem 2rem; } }

        /* ── Breadcrumb ─────────────────────────────────────── */
        .bpms-breadcrumb {
            display: flex; align-items: center; gap: 6px;
            font-size: 12.5px; color: var(--text-muted);
            margin-bottom: 1.5rem;
        }
        .bpms-breadcrumb a { color: var(--primary); text-decoration: none; font-weight: 500; }
        .bpms-breadcrumb a:hover { text-decoration: underline; }
        .bpms-breadcrumb .current { color: var(--primary); font-weight: 600; }
        .bpms-breadcrumb .sep { font-size: .7rem; opacity: .4; }

        /* ── Page header ────────────────────────────────────── */
        .page-header {
            display: flex; align-items: center;
            justify-content: space-between;
            flex-wrap: wrap; gap: 1rem;
            margin-bottom: 1.75rem;
        }
        .page-header-left { display: flex; align-items: flex-start; gap: 1rem; }
        .page-header-icon {
            width: 52px; height: 52px; border-radius: 14px; flex-shrink: 0;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 5px 15px rgba(233,30,99,.3);
        }
        .page-header-icon i { font-size: 1.4rem; color: #fff; }
        .page-header-text h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem; font-weight: 700;
            color: var(--text-dark); margin-bottom: 3px;
        }
        .page-header-text p { font-size: 13px; color: var(--text-muted); }

        /* ── Stat strip ─────────────────────────────────────── */
        .stat-strip {
            display: flex; gap: 12px; flex-wrap: wrap;
            margin-bottom: 1.5rem;
        }
        .stat-chip {
            background: var(--surface);
            border: 1px solid var(--border-soft);
            border-radius: 12px;
            padding: 12px 18px;
            display: flex; align-items: center; gap: 10px;
            box-shadow: var(--shadow-card);
            flex: 1; min-width: 130px;
        }
        .stat-chip-icon {
            width: 38px; height: 38px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; flex-shrink: 0;
        }
        .stat-chip-icon.pink   { background: var(--primary-light); color: var(--primary); }
        .stat-chip-icon.green  { background: var(--success-light);  color: var(--success); }
        .stat-chip-icon.red    { background: var(--danger-light);   color: var(--danger); }
        .stat-chip-icon.amber  { background: var(--warning-light);  color: var(--warning); }
        .stat-chip-icon.blue   { background: var(--blue-light);     color: var(--blue); }
        .stat-chip-val { font-size: 1.3rem; font-weight: 700; color: var(--text-dark); line-height: 1; }
        .stat-chip-lbl { font-size: 11px; color: var(--text-muted); margin-top: 2px; }

        /* ── Toolbar ────────────────────────────────────────── */
        .toolbar {
            display: flex; align-items: center; gap: 10px;
            flex-wrap: wrap; margin-bottom: 1.25rem;
        }
        .search-box {
            position: relative; flex: 1; min-width: 200px; max-width: 360px;
        }
        .search-box i {
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
            color: var(--primary); font-size: .9rem; pointer-events: none;
        }
        .search-box input {
            width: 100%;
            border: 1.5px solid var(--border-soft);
            border-radius: 10px;
            padding: 9px 12px 9px 34px;
            font-size: 13.5px; font-family: 'Poppins', sans-serif;
            color: var(--text-dark); background: var(--surface);
            outline: none; transition: border-color .2s, box-shadow .2s;
        }
        .search-box input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(233,30,99,.09);
        }
        .search-box input::placeholder { color: #cdb5c7; }

        /* Filter tabs */
        .filter-tabs {
            display: flex; gap: 6px; flex-wrap: wrap;
        }
        .filter-tab {
            padding: 7px 14px; border-radius: 20px;
            font-size: 12.5px; font-weight: 600;
            font-family: 'Poppins', sans-serif;
            border: 1.5px solid var(--border-soft);
            background: var(--surface); color: var(--text-muted);
            cursor: pointer; transition: all .2s;
        }
        .filter-tab:hover { border-color: var(--primary); color: var(--primary); }
        .filter-tab.active {
            background: var(--primary); color: #fff;
            border-color: var(--primary);
        }

        .result-count { font-size: 13px; color: var(--text-muted); margin-left: auto; }
        .result-count strong { color: var(--primary); }

        /* ── Card ───────────────────────────────────────────── */
        .bpms-card {
            background: var(--surface);
            border-radius: var(--radius-card);
            box-shadow: var(--shadow-card);
            border: 1px solid var(--border-soft);
            overflow: hidden;
        }
        .bpms-card-header {
            padding: 14px 20px;
            border-bottom: 1px solid var(--border-soft);
            display: flex; align-items: center; gap: 10px;
        }
        .card-header-icon {
            width: 32px; height: 32px; border-radius: 9px;
            background: var(--primary-light);
            display: flex; align-items: center; justify-content: center;
        }
        .card-header-icon i { font-size: .9rem; color: var(--primary); }
        .bpms-card-header h5 {
            font-size: 14px; font-weight: 600;
            color: var(--text-dark); margin: 0; flex: 1;
        }
        .card-badge {
            font-size: 11px; font-weight: 600;
            color: var(--primary); background: var(--primary-light);
            padding: 3px 10px; border-radius: 20px;
        }

        /* ── Table ──────────────────────────────────────────── */
        .bpms-table { width: 100%; border-collapse: collapse; }
        .bpms-table thead tr {
            background: var(--primary-muted);
            border-bottom: 1px solid var(--border-soft);
        }
        .bpms-table thead th {
            padding: 13px 16px;
            font-size: 11px; font-weight: 700;
            letter-spacing: .07em; text-transform: uppercase;
            color: var(--primary); white-space: nowrap;
        }
        .bpms-table tbody tr {
            border-bottom: 1px solid #faf0f5;
            transition: background .18s;
            animation: rowIn .3s ease both;
        }
        .bpms-table tbody tr:nth-child(1)  { animation-delay: .03s; }
        .bpms-table tbody tr:nth-child(2)  { animation-delay: .06s; }
        .bpms-table tbody tr:nth-child(3)  { animation-delay: .09s; }
        .bpms-table tbody tr:nth-child(4)  { animation-delay: .12s; }
        .bpms-table tbody tr:nth-child(5)  { animation-delay: .15s; }
        .bpms-table tbody tr:nth-child(6)  { animation-delay: .18s; }
        .bpms-table tbody tr:nth-child(7)  { animation-delay: .21s; }
        @keyframes rowIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .bpms-table tbody tr:last-child { border-bottom: none; }
        .bpms-table tbody tr:hover { background: #fff8fb; }
        .bpms-table td {
            padding: 13px 16px;
            font-size: 13.5px; color: var(--text-dark);
            vertical-align: middle;
        }

        /* sno */
        .sno-badge {
            width: 30px; height: 30px; border-radius: 8px;
            background: var(--primary-light); color: var(--primary);
            font-size: 12px; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
        }

        /* apt number */
        .apt-number {
            font-size: 12px; font-weight: 700;
            font-family: 'Courier New', monospace;
            background: var(--primary-muted);
            color: var(--primary-dark);
            padding: 4px 10px; border-radius: 8px;
            letter-spacing: .04em;
            display: inline-block;
        }

        /* customer */
        .name-cell { display: flex; align-items: center; gap: 10px; }
        .name-avatar {
            width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff; font-size: 13px; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
        }
        .name-full { font-weight: 600; font-size: 13px; line-height: 1.3; }
        .name-phone { font-size: 11.5px; color: var(--text-muted); }

        /* date/time chip */
        .dt-chip {
            display: inline-flex; align-items: center; gap: 5px;
            background: var(--blue-light); color: #1e40af;
            font-size: 12px; font-weight: 500;
            padding: 4px 10px; border-radius: 20px; white-space: nowrap;
        }
        .dt-chip i { font-size: .75rem; }
        .time-chip {
            display: inline-flex; align-items: center; gap: 5px;
            background: var(--purple-light); color: #5b21b6;
            font-size: 12px; font-weight: 500;
            padding: 4px 10px; border-radius: 20px; white-space: nowrap;
            margin-top: 4px;
        }
        .time-chip i { font-size: .75rem; }

        /* ── Status badges ──────────────────────────────────── */
        .status-badge {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: 11.5px; font-weight: 600;
            padding: 5px 11px; border-radius: 20px; white-space: nowrap;
        }
        .status-badge i { font-size: .65rem; }
        .status-pending  { background: var(--warning-light); color: #92400e; }
        .status-selected { background: var(--success-light);  color: #065f46; }
        .status-rejected { background: var(--danger-light);   color: #991b1b; }

        /* actions */
        .actions { display: flex; align-items: center; gap: 7px; }
        .btn-view {
            display: inline-flex; align-items: center; gap: 5px;
            background: var(--primary-light); color: var(--primary);
            border: none; border-radius: var(--radius-btn);
            padding: 7px 13px; font-size: 12.5px; font-weight: 600;
            font-family: 'Poppins', sans-serif; cursor: pointer;
            text-decoration: none; transition: background .2s, color .2s, transform .15s;
        }
        .btn-view:hover { background: var(--primary); color: #fff; transform: translateY(-1px); }

        .btn-delete {
            display: inline-flex; align-items: center; gap: 5px;
            background: var(--danger-light); color: var(--danger);
            border: none; border-radius: var(--radius-btn);
            padding: 7px 12px; font-size: 12.5px; font-weight: 600;
            font-family: 'Poppins', sans-serif; cursor: pointer;
            text-decoration: none; transition: background .2s, color .2s, transform .15s;
        }
        .btn-delete:hover { background: var(--danger); color: #fff; transform: translateY(-1px); }

        .hidden-row { display: none; }

        /* ── Empty state ────────────────────────────────────── */
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-icon {
            width: 72px; height: 72px; border-radius: 20px;
            background: var(--primary-light); color: var(--primary);
            font-size: 1.8rem;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
        }
        .empty-state h5 { font-size: 16px; font-weight: 600; margin-bottom: 6px; }
        .empty-state p  { font-size: 13px; color: var(--text-muted); }

        /* ── Delete confirm modal ───────────────────────────── */
        .modal-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.45); z-index: 9999;
            align-items: center; justify-content: center;
        }
        .modal-overlay.show { display: flex; }
        .modal-box {
            background: var(--surface); border-radius: 20px;
            padding: 32px 28px; max-width: 380px; width: 90%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,.15);
            animation: popIn .25s ease;
        }
        @keyframes popIn {
            from { transform: scale(.88); opacity: 0; }
            to   { transform: scale(1);  opacity: 1; }
        }
        .modal-icon {
            width: 60px; height: 60px; border-radius: 16px;
            background: var(--danger-light); color: var(--danger);
            font-size: 1.6rem;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
        }
        .modal-box h5 { font-size: 17px; font-weight: 700; margin-bottom: 8px; }
        .modal-box p  { font-size: 13.5px; color: var(--text-muted); margin-bottom: 24px; line-height: 1.6; }
        .modal-apt { font-weight: 700; color: var(--primary); font-family: monospace; }
        .modal-actions { display: flex; gap: 10px; }
        .modal-cancel {
            flex: 1; padding: 11px; border-radius: 10px;
            border: 1.5px solid #e5e7eb; background: transparent;
            font-size: 13.5px; font-weight: 600;
            font-family: 'Poppins', sans-serif; cursor: pointer;
            color: var(--text-dark); transition: background .2s;
        }
        .modal-cancel:hover { background: #f5f5f5; }
        .modal-confirm {
            flex: 1; padding: 11px; border-radius: 10px;
            border: none; font-size: 13.5px; font-weight: 600;
            font-family: 'Poppins', sans-serif; cursor: pointer;
            background: var(--danger); color: #fff; transition: background .2s;
        }
        .modal-confirm:hover { background: #dc2626; }
    </style>
</head>
<body>

<?php include_once('includes/header.php'); ?>
<?php include_once('includes/sidebar.php'); ?>

<!-- Delete modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <div class="modal-icon"><i class="bi bi-calendar-x-fill"></i></div>
        <h5>Delete Appointment?</h5>
        <p>Appointment <span class="modal-apt" id="modalAptNum"></span> will be permanently removed. This cannot be undone.</p>
        <div class="modal-actions">
            <button class="modal-cancel" onclick="closeModal()">Cancel</button>
            <a href="#" class="modal-confirm" id="confirmDeleteBtn">Yes, Delete</a>
        </div>
    </div>
</div>

<div class="dashboard-wrapper" id="dashboard-wrapper">
    <div class="page-content">

        <!-- Breadcrumb -->
        <nav class="bpms-breadcrumb">
            <i class="bi bi-house-door" style="font-size:.8rem;"></i>
            <a href="dashboard.php">Dashboard</a>
            <i class="bi bi-chevron-right sep"></i>
            <span>Appointments</span>
            <i class="bi bi-chevron-right sep"></i>
            <span class="current">All Appointments</span>
        </nav>

        <!-- Page header -->
        <div class="page-header">
            <div class="page-header-left">
                <div class="page-header-icon">
                    <i class="bi bi-calendar-check-fill"></i>
                </div>
                <div class="page-header-text">
                    <h1>All Appointments</h1>
                    <p>View, manage and delete all customer appointments.</p>
                </div>
            </div>
        </div>

        <?php
        /* ── Fetch all appointments — zero logic change ───── */
        $ret = mysqli_query($con,
            "SELECT tbluser.FirstName, tbluser.LastName, tbluser.Email,
                    tbluser.MobileNumber, tblbook.ID as bid,
                    tblbook.AptNumber, tblbook.AptDate, tblbook.AptTime,
                    tblbook.Message, tblbook.BookingDate, tblbook.Status
             FROM tblbook
             JOIN tbluser ON tbluser.ID = tblbook.UserID
             ORDER BY tblbook.ID DESC"
        );
        $total = mysqli_num_rows($ret);

        /* quick counts for stat chips */
        $cPending  = 0; $cSelected = 0; $cRejected = 0;
        $rows_data = [];
        while ($r = mysqli_fetch_array($ret)) {
            $rows_data[] = $r;
            $s = $r['Status'];
            if ($s == '')         $cPending++;
            elseif ($s == 'Selected') $cSelected++;
            elseif ($s == 'Rejected') $cRejected++;
        }
        ?>

        <!-- Stat strip -->
        <div class="stat-strip">
            <div class="stat-chip">
                <div class="stat-chip-icon pink"><i class="bi bi-calendar-check"></i></div>
                <div>
                    <div class="stat-chip-val"><?php echo $total; ?></div>
                    <div class="stat-chip-lbl">Total</div>
                </div>
            </div>
            <div class="stat-chip">
                <div class="stat-chip-icon amber"><i class="bi bi-hourglass-split"></i></div>
                <div>
                    <div class="stat-chip-val"><?php echo $cPending; ?></div>
                    <div class="stat-chip-lbl">Pending</div>
                </div>
            </div>
            <div class="stat-chip">
                <div class="stat-chip-icon green"><i class="bi bi-check-circle"></i></div>
                <div>
                    <div class="stat-chip-val"><?php echo $cSelected; ?></div>
                    <div class="stat-chip-lbl">Accepted</div>
                </div>
            </div>
            <div class="stat-chip">
                <div class="stat-chip-icon red"><i class="bi bi-x-circle"></i></div>
                <div>
                    <div class="stat-chip-val"><?php echo $cRejected; ?></div>
                    <div class="stat-chip-lbl">Rejected</div>
                </div>
            </div>
        </div>

        <!-- Toolbar: search + status filter tabs -->
        <div class="toolbar">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput"
                       placeholder="Search by name, apt. no or phone...">
            </div>

            <div class="filter-tabs">
                <button class="filter-tab active" data-filter="all">All</button>
                <button class="filter-tab" data-filter="pending">Pending</button>
                <button class="filter-tab" data-filter="selected">Accepted</button>
                <button class="filter-tab" data-filter="rejected">Rejected</button>
            </div>

            <div class="result-count" id="resultCount">
                Showing <strong><?php echo $total; ?></strong>
                appointment<?php echo $total == 1 ? '' : 's'; ?>
            </div>
        </div>

        <!-- Table card -->
        <div class="bpms-card">
            <div class="bpms-card-header">
                <div class="card-header-icon"><i class="bi bi-table"></i></div>
                <h5>Appointment Records</h5>
                <span class="card-badge"><?php echo $total; ?> records</span>
            </div>

            <div style="overflow-x:auto;">
                <table class="bpms-table" id="aptTable">
                    <thead>
                        <tr>
                            <th style="width:52px;">#</th>
                            <th>Apt. No</th>
                            <th>Customer</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th style="width:155px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">

                    <?php if ($total > 0):
                        $cnt = 1;
                        foreach ($rows_data as $row):
                            $initial   = strtoupper(substr($row['FirstName'], 0, 1));
                            $fullName  = htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']);
                            $status    = $row['Status'];
                            $statusKey = ($status == '')          ? 'pending'
                                       : (($status == 'Selected') ? 'selected' : 'rejected');
                    ?>
                        <tr class="apt-row"
                            data-name="<?php echo strtolower($row['FirstName'].' '.$row['LastName']); ?>"
                            data-apt="<?php echo strtolower($row['AptNumber']); ?>"
                            data-phone="<?php echo $row['MobileNumber']; ?>"
                            data-status="<?php echo $statusKey; ?>">

                            <!-- # -->
                            <td><div class="sno-badge"><?php echo $cnt; ?></div></td>

                            <!-- Apt number -->
                            <td>
                                <span class="apt-number">
                                    <?php echo htmlspecialchars($row['AptNumber']); ?>
                                </span>
                            </td>

                            <!-- Customer -->
                            <td>
                                <div class="name-cell">
                                    <div class="name-avatar"><?php echo $initial; ?></div>
                                    <div>
                                        <div class="name-full"><?php echo $fullName; ?></div>
                                        <div class="name-phone">
                                            <i class="bi bi-telephone" style="font-size:.7rem;color:var(--primary);"></i>
                                            <?php echo htmlspecialchars($row['MobileNumber']); ?>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Date & Time stacked -->
                            <td>
                                <span class="dt-chip">
                                    <i class="bi bi-calendar3"></i>
                                    <?php echo htmlspecialchars($row['AptDate']); ?>
                                </span><br>
                                <span class="time-chip">
                                    <i class="bi bi-clock"></i>
                                    <?php echo htmlspecialchars($row['AptTime']); ?>
                                </span>
                            </td>

                            <!-- Status -->
                            <td>
                                <?php if ($status == ''): ?>
                                    <span class="status-badge status-pending">
                                        <i class="bi bi-circle-fill"></i>
                                        Pending
                                    </span>
                                <?php elseif ($status == 'Selected'): ?>
                                    <span class="status-badge status-selected">
                                        <i class="bi bi-check-circle-fill"></i>
                                        Accepted
                                    </span>
                                <?php else: ?>
                                    <span class="status-badge status-rejected">
                                        <i class="bi bi-x-circle-fill"></i>
                                        Rejected
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Actions -->
                            <td>
                                <div class="actions">
                                    <a href="view-appointment.php?viewid=<?php echo $row['bid']; ?>"
                                       class="btn-view">
                                        <i class="bi bi-eye"></i>
                                        View
                                    </a>
                                    <button class="btn-delete"
                                        onclick="confirmDelete(
                                            'all-appointment.php?delid=<?php echo $row['bid']; ?>',
                                            '<?php echo htmlspecialchars($row['AptNumber'], ENT_QUOTES); ?>'
                                        )">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </td>

                        </tr>
                    <?php $cnt++; endforeach;
                    else: ?>
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="bi bi-calendar-x"></i></div>
                                    <h5>No Appointments Yet</h5>
                                    <p>Appointments booked by customers will appear here.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>

                    </tbody>
                </table>
            </div>
        </div><!-- /bpms-card -->

    </div><!-- /page-content -->
</div><!-- /dashboard-wrapper -->

<?php include_once('includes/footer.php'); ?>

<script>
/* ── Live search + status filter ───────────────────────── */
const searchInput = document.getElementById('searchInput');
const filterTabs  = document.querySelectorAll('.filter-tab');
const rows        = document.querySelectorAll('.apt-row');
const resultCount = document.getElementById('resultCount');

let activeFilter = 'all';

function applyFilters() {
    const q = searchInput.value.toLowerCase().trim();
    let visible = 0;

    rows.forEach(row => {
        const matchSearch =
            row.dataset.name.includes(q)  ||
            row.dataset.apt.includes(q)   ||
            row.dataset.phone.includes(q);

        const matchFilter =
            activeFilter === 'all' ||
            row.dataset.status === activeFilter;

        const show = matchSearch && matchFilter;
        row.classList.toggle('hidden-row', !show);
        if (show) visible++;
    });

    resultCount.innerHTML =
        'Showing <strong>' + visible + '</strong> appointment' + (visible === 1 ? '' : 's');
}

searchInput.addEventListener('input', applyFilters);

filterTabs.forEach(tab => {
    tab.addEventListener('click', function () {
        filterTabs.forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        activeFilter = this.dataset.filter;
        applyFilters();
    });
});

/* ── Delete modal ──────────────────────────────────────── */
function confirmDelete(url, aptNum) {
    document.getElementById('confirmDeleteBtn').href = url;
    document.getElementById('modalAptNum').textContent = aptNum || '';
    document.getElementById('deleteModal').classList.add('show');
}
function closeModal() {
    document.getElementById('deleteModal').classList.remove('show');
}
document.getElementById('deleteModal').addEventListener('click', function (e) {
    if (e.target === this) closeModal();
});
</script>

</body>
</html>
<?php } ?>