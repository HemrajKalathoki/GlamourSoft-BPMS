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
    echo "<script>window.location.href='readenq.php'</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BPMS | Read Enquiries</title>
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
            --danger:         #ef4444;
            --danger-light:   #fee2e2;
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

        /* ── Layout — matches sidebar (280px) + navbar (70px) ── */
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
            min-width: 160px;
        }
        .stat-chip-icon {
            width: 38px; height: 38px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; flex-shrink: 0;
        }
        .stat-chip-icon.pink { background: var(--primary-light); color: var(--primary); }
        .stat-chip-icon.green { background: #d1fae5; color: var(--success); }
        .stat-chip-val { font-size: 1.35rem; font-weight: 700; color: var(--text-dark); line-height: 1; }
        .stat-chip-lbl { font-size: 11.5px; color: var(--text-muted); margin-top: 2px; }

        /* ── Search / filter bar ────────────────────────────── */
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

        .result-count {
            font-size: 13px; color: var(--text-muted); margin-left: auto;
        }
        .result-count strong { color: var(--primary); }

        /* ── Card wrapper ───────────────────────────────────── */
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
        }
        .bpms-table tbody tr:last-child { border-bottom: none; }
        .bpms-table tbody tr:hover { background: #fff8fb; }
        .bpms-table td {
            padding: 14px 16px;
            font-size: 13.5px; color: var(--text-dark);
            vertical-align: middle;
        }

        /* sno badge */
        .sno-badge {
            width: 30px; height: 30px; border-radius: 8px;
            background: var(--primary-light); color: var(--primary);
            font-size: 12px; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
        }

        /* avatar + name */
        .name-cell { display: flex; align-items: center; gap: 10px; }
        .name-avatar {
            width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff; font-size: 13px; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
        }
        .name-full { font-weight: 600; font-size: 13.5px; }
        .name-email { font-size: 12px; color: var(--text-muted); }

        /* email link */
        .email-link {
            color: var(--text-dark); text-decoration: none;
            font-size: 13.5px;
            display: inline-flex; align-items: center; gap: 6px;
        }
        .email-link:hover { color: var(--primary); }
        .email-link i { font-size: .8rem; color: var(--primary); }

        /* date chip */
        .date-chip {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--primary-muted);
            color: #7b1e4a; font-size: 12px; font-weight: 500;
            padding: 4px 10px; border-radius: 20px;
            white-space: nowrap;
        }
        .date-chip i { font-size: .75rem; }

        /* status badge */
        .status-read {
            display: inline-flex; align-items: center; gap: 5px;
            background: #d1fae5; color: #065f46;
            font-size: 11.5px; font-weight: 600;
            padding: 4px 10px; border-radius: 20px;
        }
        .status-read i { font-size: .75rem; }

        /* action buttons */
        .actions { display: flex; align-items: center; gap: 8px; }

        .btn-view {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--primary-light); color: var(--primary);
            border: none; border-radius: var(--radius-btn);
            padding: 7px 14px; font-size: 12.5px; font-weight: 600;
            font-family: 'Poppins', sans-serif; cursor: pointer;
            text-decoration: none;
            transition: background .2s, transform .15s;
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
            transition: background .2s, transform .15s;
        }
        .btn-delete:hover {
            background: var(--danger); color: #fff;
            transform: translateY(-1px);
        }

        /* ── Empty state ────────────────────────────────────── */
        .empty-state {
            text-align: center; padding: 60px 20px;
        }
        .empty-icon {
            width: 72px; height: 72px; border-radius: 20px;
            background: var(--primary-light); color: var(--primary);
            font-size: 1.8rem;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
        }
        .empty-state h5 {
            font-size: 16px; font-weight: 600; color: var(--text-dark); margin-bottom: 6px;
        }
        .empty-state p { font-size: 13px; color: var(--text-muted); }

        /* ── Hidden row (JS filter) ─────────────────────────── */
        .hidden-row { display: none; }

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
        .modal-box p  { font-size: 13.5px; color: var(--text-muted); margin-bottom: 24px; }
        .modal-actions { display: flex; gap: 10px; justify-content: center; }
        .modal-cancel {
            flex: 1; padding: 10px; border-radius: 10px;
            border: 1.5px solid var(--border-soft);
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
        <p>This action is permanent and cannot be undone. Are you sure you want to delete this enquiry?</p>
        <div class="modal-actions">
            <button class="modal-cancel" onclick="closeModal()">Cancel</button>
            <a href="#" class="modal-confirm" id="confirmDeleteBtn">Yes, Delete</a>
        </div>
    </div>
</div>

<!-- Dashboard wrapper -->
<div class="dashboard-wrapper" id="dashboard-wrapper">
    <div class="page-content">

        <!-- Breadcrumb -->
        <nav class="bpms-breadcrumb">
            <i class="bi bi-house-door" style="font-size:.8rem;"></i>
            <a href="dashboard.php">Dashboard</a>
            <i class="bi bi-chevron-right sep"></i>
            <span>Enquiry</span>
            <i class="bi bi-chevron-right sep"></i>
            <span class="current">Read Enquiries</span>
        </nav>

        <!-- Page header -->
        <div class="page-header">
            <div class="page-header-left">
                <div class="page-header-icon">
                    <i class="bi bi-envelope-open-fill"></i>
                </div>
                <div class="page-header-text">
                    <h1>Read Enquiries</h1>
                    <p>All enquiries that have been reviewed and marked as read.</p>
                </div>
            </div>
            <a href="unreadenq.php" class="btn-view" style="padding:10px 18px;font-size:13px;">
                <i class="bi bi-envelope-fill"></i>
                View Unread
            </a>
        </div>

        <?php
        /* Fetch read enquiries */
        $ret = mysqli_query($con, "SELECT * FROM tblcontact WHERE IsRead='1' ORDER BY ID DESC");
        $totalRead = mysqli_num_rows($ret);
        ?>

        <!-- Stat strip -->
        <div class="stat-strip">
            <div class="stat-chip">
                <div class="stat-chip-icon pink"><i class="bi bi-envelope-open"></i></div>
                <div>
                    <div class="stat-chip-val"><?php echo $totalRead; ?></div>
                    <div class="stat-chip-lbl">Read Enquiries</div>
                </div>
            </div>
            <div class="stat-chip">
                <div class="stat-chip-icon green"><i class="bi bi-check-circle"></i></div>
                <div>
                    <div class="stat-chip-val">✓</div>
                    <div class="stat-chip-lbl">All Reviewed</div>
                </div>
            </div>
        </div>

        <!-- Toolbar -->
        <div class="toolbar">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Search by name or email...">
            </div>
            <div class="result-count" id="resultCount">
                Showing <strong><?php echo $totalRead; ?></strong> enquir<?php echo $totalRead == 1 ? 'y' : 'ies'; ?>
            </div>
        </div>

        <!-- Table card -->
        <div class="bpms-card">
            <div class="bpms-card-header">
                <div class="card-header-icon"><i class="bi bi-table"></i></div>
                <h5>Enquiry List</h5>
                <span class="card-badge"><?php echo $totalRead; ?> records</span>
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

                    <?php if ($totalRead > 0):
                        $cnt = 1;
                        /* reset pointer — already fetched above */
                        mysqli_data_seek($ret, 0);
                        while ($row = mysqli_fetch_array($ret)):
                            $initial = strtoupper(substr($row['FirstName'], 0, 1));
                            $fullName = htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']);
                    ?>
                        <tr class="enquiry-row"
                            data-name="<?php echo strtolower($row['FirstName'].' '.$row['LastName']); ?>"
                            data-email="<?php echo strtolower($row['Email']); ?>">

                            <td>
                                <div class="sno-badge"><?php echo $cnt; ?></div>
                            </td>

                            <td>
                                <div class="name-cell">
                                    <div class="name-avatar"><?php echo $initial; ?></div>
                                    <div>
                                        <div class="name-full"><?php echo $fullName; ?></div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <a href="mailto:<?php echo htmlspecialchars($row['Email']); ?>" class="email-link">
                                    <i class="bi bi-envelope"></i>
                                    <?php echo htmlspecialchars($row['Email']); ?>
                                </a>
                            </td>

                            <td>
                                <span class="date-chip">
                                    <i class="bi bi-calendar3"></i>
                                    <?php echo htmlspecialchars($row['EnquiryDate']); ?>
                                </span>
                            </td>

                            <td>
                                <span class="status-read">
                                    <i class="bi bi-check-circle-fill"></i>
                                    Read
                                </span>
                            </td>

                            <td>
                                <div class="actions">
                                    <a href="view-enquiry.php?viewid=<?php echo $row['ID']; ?>" class="btn-view">
                                        <i class="bi bi-eye"></i>
                                        View
                                    </a>
                                    <button class="btn-delete"
                                        onclick="confirmDelete('readenq.php?delid=<?php echo $row['ID']; ?>')">
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
                                    <div class="empty-icon"><i class="bi bi-inbox"></i></div>
                                    <h5>No Read Enquiries</h5>
                                    <p>There are no enquiries marked as read yet.<br>Check the unread section for new messages.</p>
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
/* ── Live search filter ────────────────────────────────── */
const searchInput  = document.getElementById('searchInput');
const rows         = document.querySelectorAll('.enquiry-row');
const resultCount  = document.getElementById('resultCount');

searchInput.addEventListener('input', function () {
    const q = this.value.toLowerCase().trim();
    let visible = 0;

    rows.forEach(row => {
        const name  = row.dataset.name;
        const email = row.dataset.email;
        const match = name.includes(q) || email.includes(q);
        row.classList.toggle('hidden-row', !match);
        if (match) visible++;
    });

    resultCount.innerHTML =
        'Showing <strong>' + visible + '</strong> enquir' + (visible === 1 ? 'y' : 'ies');
});

/* ── Custom delete modal ───────────────────────────────── */
let deleteTarget = '';

function confirmDelete(url) {
    deleteTarget = url;
    document.getElementById('confirmDeleteBtn').href = url;
    document.getElementById('deleteModal').classList.add('show');
}

function closeModal() {
    document.getElementById('deleteModal').classList.remove('show');
}

/* Close on backdrop click */
document.getElementById('deleteModal').addEventListener('click', function (e) {
    if (e.target === this) closeModal();
});
</script>

</body>
</html>
<?php } ?>