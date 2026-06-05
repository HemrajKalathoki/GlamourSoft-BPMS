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
    mysqli_query($con, "delete from tblcontact where ID='$sid'");
    echo "<script>alert('Data Deleted');</script>";
    echo "<script>window.location.href='unreadenq.php'</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BPMS | Unread Enquiries</title>
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
            --danger:         #ef4444;
            --danger-light:   #fee2e2;
            --warning:        #f59e0b;
            --warning-light:  #fef3c7;
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
            background: linear-gradient(135deg, var(--warning), #fb923c);
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 5px 15px rgba(245,158,11,.35);
        }
        .page-header-icon i { font-size: 1.4rem; color: #fff; }
        .page-header-text h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem; font-weight: 700;
            color: var(--text-dark); margin-bottom: 3px;
        }
        .page-header-text p { font-size: 13px; color: var(--text-muted); }

        .btn-switch {
            display: inline-flex; align-items: center; gap: 7px;
            background: var(--primary-light); color: var(--primary);
            border: none; border-radius: var(--radius-btn);
            padding: 10px 18px; font-size: 13px; font-weight: 600;
            font-family: 'Poppins', sans-serif; cursor: pointer;
            text-decoration: none; transition: background .2s, transform .15s;
        }
        .btn-switch:hover {
            background: var(--primary); color: #fff;
            transform: translateY(-1px);
        }

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
            min-width: 160px;
        }
        .stat-chip-icon {
            width: 38px; height: 38px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; flex-shrink: 0;
        }
        .stat-chip-icon.amber  { background: var(--warning-light); color: var(--warning); }
        .stat-chip-icon.pink   { background: var(--primary-light); color: var(--primary); }
        .stat-chip-val { font-size: 1.35rem; font-weight: 700; color: var(--text-dark); line-height: 1; }
        .stat-chip-lbl { font-size: 11.5px; color: var(--text-muted); margin-top: 2px; }

        /* ── Attention banner (shown when unread > 0) ───────── */
        .attention-banner {
            display: flex; align-items: center; gap: 12px;
            background: var(--warning-light);
            border-left: 3px solid var(--warning);
            border-radius: 0 12px 12px 0;
            padding: 12px 16px;
            margin-bottom: 1.25rem;
            font-size: 13px; color: #92400e;
            font-weight: 500;
        }
        .attention-banner i { font-size: 1.1rem; color: var(--warning); flex-shrink: 0; }

        /* ── Toolbar ────────────────────────────────────────── */
        .toolbar {
            display: flex; align-items: center; gap: 10px;
            flex-wrap: wrap; margin-bottom: 1.25rem;
        }
        .search-box {
            position: relative; flex: 1; min-width: 200px; max-width: 340px;
        }
        .search-box i {
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
            color: var(--primary); font-size: .9rem; pointer-events: none;
        }
        .search-box input {
            width: 100%;
            border: 1.5px solid var(--border-soft);
            border-radius: 10px;
            padding: 8px 12px 8px 34px;
            font-size: 13.5px; font-family: 'Poppins', sans-serif;
            color: var(--text-dark); background: var(--surface);
            outline: none; transition: border-color .2s, box-shadow .2s;
        }
        .search-box input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(233,30,99,.09);
        }
        .search-box input::placeholder { color: #cdb5c7; }
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
            background: var(--warning-light);
            display: flex; align-items: center; justify-content: center;
        }
        .card-header-icon i { font-size: .9rem; color: var(--warning); }
        .bpms-card-header h5 {
            font-size: 14px; font-weight: 600;
            color: var(--text-dark); margin: 0; flex: 1;
        }
        .card-badge {
            font-size: 11px; font-weight: 600;
            padding: 3px 10px; border-radius: 20px;
        }
        .card-badge.amber { color: #92400e; background: var(--warning-light); }
        .card-badge.pink  { color: var(--primary); background: var(--primary-light); }

        /* ── Table ──────────────────────────────────────────── */
        .bpms-table { width: 100%; border-collapse: collapse; }
        .bpms-table thead tr {
            background: var(--warning-light);
            border-bottom: 1px solid #fde68a;
        }
        .bpms-table thead th {
            padding: 13px 16px;
            font-size: 11px; font-weight: 700;
            letter-spacing: .07em; text-transform: uppercase;
            color: #92400e; white-space: nowrap;
        }
        .bpms-table tbody tr {
            border-bottom: 1px solid #fdf6fb;
            transition: background .18s;
            animation: rowIn .35s ease both;
        }
        .bpms-table tbody tr:nth-child(1) { animation-delay: .03s; }
        .bpms-table tbody tr:nth-child(2) { animation-delay: .06s; }
        .bpms-table tbody tr:nth-child(3) { animation-delay: .09s; }
        .bpms-table tbody tr:nth-child(4) { animation-delay: .12s; }
        .bpms-table tbody tr:nth-child(5) { animation-delay: .15s; }
        @keyframes rowIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .bpms-table tbody tr:last-child { border-bottom: none; }
        .bpms-table tbody tr:hover { background: #fffbf0; }
        .bpms-table td {
            padding: 14px 16px;
            font-size: 13.5px; color: var(--text-dark);
            vertical-align: middle;
        }

        /* sno */
        .sno-badge {
            width: 30px; height: 30px; border-radius: 8px;
            background: var(--warning-light); color: var(--warning);
            font-size: 12px; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
        }

        /* unread dot */
        .unread-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: var(--warning);
            display: inline-block;
            box-shadow: 0 0 0 3px rgba(245,158,11,.2);
            flex-shrink: 0;
        }

        /* name cell */
        .name-cell { display: flex; align-items: center; gap: 10px; }
        .name-avatar {
            width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0;
            background: linear-gradient(135deg, var(--warning), #fb923c);
            color: #fff; font-size: 14px; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
        }
        .name-full  { font-weight: 600; font-size: 13.5px; line-height: 1.3; }
        .name-sub   { font-size: 11.5px; color: var(--text-muted); }

        /* email */
        .email-link {
            color: var(--text-dark); text-decoration: none;
            font-size: 13.5px;
            display: inline-flex; align-items: center; gap: 6px;
        }
        .email-link i { font-size: .8rem; color: var(--primary); }
        .email-link:hover { color: var(--primary); }

        /* date chip */
        .date-chip {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--primary-muted); color: #7b1e4a;
            font-size: 12px; font-weight: 500;
            padding: 4px 10px; border-radius: 20px; white-space: nowrap;
        }
        .date-chip i { font-size: .75rem; }

        /* status badge */
        .status-unread {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--warning-light); color: #92400e;
            font-size: 11.5px; font-weight: 600;
            padding: 4px 10px; border-radius: 20px;
        }
        .status-unread i { font-size: .75rem; }

        /* actions */
        .actions { display: flex; align-items: center; gap: 8px; }

        .btn-view {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--primary-light); color: var(--primary);
            border: none; border-radius: var(--radius-btn);
            padding: 7px 14px; font-size: 12.5px; font-weight: 600;
            font-family: 'Poppins', sans-serif; cursor: pointer;
            text-decoration: none;
            transition: background .2s, transform .15s, color .2s;
        }
        .btn-view:hover {
            background: var(--primary); color: #fff;
            transform: translateY(-1px);
        }

        .btn-delete {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--danger-light); color: var(--danger);
            border: none; border-radius: var(--radius-btn);
            padding: 7px 14px; font-size: 12.5px; font-weight: 600;
            font-family: 'Poppins', sans-serif; cursor: pointer;
            text-decoration: none;
            transition: background .2s, transform .15s, color .2s;
        }
        .btn-delete:hover {
            background: var(--danger); color: #fff;
            transform: translateY(-1px);
        }

        /* hidden row (search) */
        .hidden-row { display: none; }

        /* ── Empty state ────────────────────────────────────── */
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-icon {
            width: 72px; height: 72px; border-radius: 20px;
            background: var(--warning-light); color: var(--warning);
            font-size: 1.8rem;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
        }
        .empty-state h5 { font-size: 16px; font-weight: 600; color: var(--text-dark); margin-bottom: 6px; }
        .empty-state p  { font-size: 13px; color: var(--text-muted); }
        .empty-check {
            display: inline-flex; align-items: center; gap: 7px;
            background: #d1fae5; color: #065f46;
            font-size: 12.5px; font-weight: 600;
            padding: 6px 14px; border-radius: 20px;
            margin-top: 16px;
        }

        /* ── Delete modal ───────────────────────────────────── */
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
        .modal-box p  { font-size: 13.5px; color: var(--text-muted); margin-bottom: 24px; }
        .modal-actions { display: flex; gap: 10px; justify-content: center; }
        .modal-cancel {
            flex: 1; padding: 10px; border-radius: 10px;
            border: 1.5px solid #e5e7eb;
            background: transparent; font-size: 13.5px; font-weight: 600;
            font-family: 'Poppins', sans-serif; cursor: pointer;
            color: var(--text-dark); transition: background .2s;
        }
        .modal-cancel:hover { background: #f5f5f5; }
        .modal-confirm {
            flex: 1; padding: 10px; border-radius: 10px;
            border: none; font-size: 13.5px; font-weight: 600;
            font-family: 'Poppins', sans-serif; cursor: pointer;
            background: var(--danger); color: #fff;
            transition: background .2s;
        }
        .modal-confirm:hover { background: #dc2626; }
    </style>
</head>

<body>

<?php include_once('includes/header.php'); ?>
<?php include_once('includes/sidebar.php'); ?>

<!-- Delete confirm modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <div class="modal-icon"><i class="bi bi-trash3-fill"></i></div>
        <h5>Delete Enquiry?</h5>
        <p>This action cannot be undone. Are you sure you want to permanently delete this enquiry?</p>
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
            <span>Enquiry</span>
            <i class="bi bi-chevron-right sep"></i>
            <span class="current">Unread Enquiries</span>
        </nav>

        <!-- Page header -->
        <div class="page-header">
            <div class="page-header-left">
                <div class="page-header-icon">
                    <i class="bi bi-envelope-exclamation-fill"></i>
                </div>
                <div class="page-header-text">
                    <h1>Unread Enquiries</h1>
                    <p>New customer enquiries awaiting your review and response.</p>
                </div>
            </div>
            <a href="readenq.php" class="btn-switch">
                <i class="bi bi-envelope-open"></i>
                View Read
            </a>
        </div>

        <?php
        /* Fetch unread enquiries — zero logic change */
        $ret      = mysqli_query($con, "SELECT * FROM tblcontact WHERE IsRead IS NULL ORDER BY ID DESC");
        $totalUnread = mysqli_num_rows($ret);
        ?>

        <!-- Stat strip -->
        <div class="stat-strip">
            <div class="stat-chip">
                <div class="stat-chip-icon amber"><i class="bi bi-envelope-fill"></i></div>
                <div>
                    <div class="stat-chip-val"><?php echo $totalUnread; ?></div>
                    <div class="stat-chip-lbl">Unread Enquiries</div>
                </div>
            </div>
            <div class="stat-chip">
                <div class="stat-chip-icon pink"><i class="bi bi-clock-history"></i></div>
                <div>
                    <div class="stat-chip-val">Pending</div>
                    <div class="stat-chip-lbl">Awaiting Review</div>
                </div>
            </div>
        </div>

        <!-- Attention banner — only when there are unread messages -->
        <?php if ($totalUnread > 0): ?>
        <div class="attention-banner">
            <i class="bi bi-exclamation-triangle-fill"></i>
            You have <strong><?php echo $totalUnread; ?></strong> unread enquir<?php echo $totalUnread == 1 ? 'y' : 'ies'; ?> waiting. Click <strong>View</strong> on any row to read and mark it as reviewed.
        </div>
        <?php endif; ?>

        <!-- Toolbar -->
        <div class="toolbar">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Search by name or email...">
            </div>
            <div class="result-count" id="resultCount">
                Showing <strong><?php echo $totalUnread; ?></strong>
                unread enquir<?php echo $totalUnread == 1 ? 'y' : 'ies'; ?>
            </div>
        </div>

        <!-- Table card -->
        <div class="bpms-card">
            <div class="bpms-card-header">
                <div class="card-header-icon"><i class="bi bi-envelope-exclamation"></i></div>
                <h5>Unread Enquiry List</h5>
                <?php if ($totalUnread > 0): ?>
                    <span class="card-badge amber"><?php echo $totalUnread; ?> pending</span>
                <?php else: ?>
                    <span class="card-badge pink">All clear</span>
                <?php endif; ?>
            </div>

            <div style="overflow-x:auto;">
                <table class="bpms-table" id="enquiryTable">
                    <thead>
                        <tr>
                            <th style="width:56px;">#</th>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th style="width:160px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">

                    <?php if ($totalUnread > 0):
                        $cnt = 1;
                        mysqli_data_seek($ret, 0);
                        while ($row = mysqli_fetch_array($ret)):
                            $initial  = strtoupper(substr($row['FirstName'], 0, 1));
                            $fullName = htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']);
                    ?>
                        <tr class="enquiry-row"
                            data-name="<?php echo strtolower($row['FirstName'].' '.$row['LastName']); ?>"
                            data-email="<?php echo strtolower($row['Email']); ?>">

                            <!-- S.No -->
                            <td>
                                <div class="sno-badge"><?php echo $cnt; ?></div>
                            </td>

                            <!-- Customer -->
                            <td>
                                <div class="name-cell">
                                    <div class="name-avatar"><?php echo $initial; ?></div>
                                    <div>
                                        <div class="name-full">
                                            <span class="unread-dot" style="margin-right:5px;"></span>
                                            <?php echo $fullName; ?>
                                        </div>
                                        <div class="name-sub">New enquiry</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Email -->
                            <td>
                                <a href="mailto:<?php echo htmlspecialchars($row['Email']); ?>" class="email-link">
                                    <i class="bi bi-envelope"></i>
                                    <?php echo htmlspecialchars($row['Email']); ?>
                                </a>
                            </td>

                            <!-- Date -->
                            <td>
                                <span class="date-chip">
                                    <i class="bi bi-calendar3"></i>
                                    <?php echo htmlspecialchars($row['EnquiryDate']); ?>
                                </span>
                            </td>

                            <!-- Status -->
                            <td>
                                <span class="status-unread">
                                    <i class="bi bi-circle-fill" style="font-size:.5rem;"></i>
                                    Unread
                                </span>
                            </td>

                            <!-- Actions -->
                            <td>
                                <div class="actions">
                                    <a href="view-enquiry.php?viewid=<?php echo $row['ID']; ?>" class="btn-view">
                                        <i class="bi bi-eye"></i>
                                        View
                                    </a>
                                    <button class="btn-delete"
                                        onclick="confirmDelete('unreadenq.php?delid=<?php echo $row['ID']; ?>')">
                                        <i class="bi bi-trash3"></i>
                                        Delete
                                    </button>
                                </div>
                            </td>

                        </tr>
                    <?php
                        $cnt++;
                        endwhile;
                    else: ?>
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="bi bi-inbox-fill"></i></div>
                                    <h5>All Caught Up!</h5>
                                    <p>There are no unread enquiries right now.<br>New customer messages will appear here.</p>
                                    <div class="empty-check">
                                        <i class="bi bi-check-circle-fill"></i>
                                        Inbox is clear
                                    </div>
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
/* ── Live search ───────────────────────────────────────── */
const searchInput = document.getElementById('searchInput');
const rows        = document.querySelectorAll('.enquiry-row');
const resultCount = document.getElementById('resultCount');

searchInput.addEventListener('input', function () {
    const q = this.value.toLowerCase().trim();
    let visible = 0;

    rows.forEach(row => {
        const match = row.dataset.name.includes(q) || row.dataset.email.includes(q);
        row.classList.toggle('hidden-row', !match);
        if (match) visible++;
    });

    resultCount.innerHTML =
        'Showing <strong>' + visible + '</strong> unread enquir' + (visible === 1 ? 'y' : 'ies');
});

/* ── Custom delete modal ───────────────────────────────── */
function confirmDelete(url) {
    document.getElementById('confirmDeleteBtn').href = url;
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