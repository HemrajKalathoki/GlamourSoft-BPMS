<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid'] == 0)) {
    header('location:logout.php');
} else {

    /* ── DELETE handler — LOGIC UNCHANGED ── */
    if ($_GET['delid']) {
        $sid = $_GET['delid'];
        mysqli_query($con, "delete from tblbook where ID ='$sid'");
        echo "<script>alert('Appointment Deleted Successfully!');</script>";
        echo "<script>window.location.href='rejected-appointment.php'</script>";
    }
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BPMS || Rejected Appointments</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <style>
        /* ── Base ── */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Poppins', sans-serif;
            background: #fdf6f9;
            color: #3d2233;
        }

        /* ── Layout wrapper ── */
        .dashboard-wrapper {
            margin-left: 280px;
            padding-top: 70px;
            min-height: 100vh;
        }

        .page-content { padding: 32px 28px; }

        /* ── Page header ── */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .page-header-left h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.7rem;
            font-weight: 700;
            color: #e11d48;
            margin-bottom: 2px;
        }

        .page-header-left p {
            font-size: 0.85rem;
            color: #9e6b7e;
            margin: 0;
        }

        .breadcrumb-nav { font-size: 0.8rem; color: #9e6b7e; }
        .breadcrumb-nav a { color: #e91e63; text-decoration: none; font-weight: 500; }
        .breadcrumb-nav a:hover { text-decoration: underline; }

        /* ── Stat strip ── */
        .stat-strip {
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .stat-pill {
            background: #fff;
            border: 1px solid #f3e0ea;
            border-radius: 50px;
            padding: 8px 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #3d2233;
            box-shadow: 0 2px 12px rgba(233,30,99,.06);
        }

        .dot { width: 10px; height: 10px; border-radius: 50%; }
        .dot-rejected { background: #e11d48; }
        .stat-pill span.count { color: #e11d48; font-size: 1rem; }

        /* ── Main card ── */
        .card-bpms {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #ffe4e6;
            box-shadow: 0 2px 20px rgba(225,29,72,.07);
            overflow: hidden;
        }

        /* ── Card header — red tone for "rejected" context ── */
        .card-bpms-header {
            background: linear-gradient(135deg, #be123c 0%, #e11d48 100%);
            padding: 18px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .card-bpms-header h5 {
            color: #fff;
            font-weight: 600;
            font-size: 1rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .header-link {
            background: rgba(255,255,255,.18);
            color: #fff;
            border: 1.5px solid rgba(255,255,255,.35);
            border-radius: 8px;
            padding: 5px 14px;
            font-size: 0.78rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: background .2s;
        }

        .header-link:hover { background: rgba(255,255,255,.30); color: #fff; }

        /* ── Filter bar ── */
        .filter-bar {
            padding: 16px 20px;
            background: #fff1f2;
            border-bottom: 1px solid #fecdd3;
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-box {
            position: relative;
            flex: 1;
            min-width: 200px;
        }

        .search-box i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #e11d48;
            font-size: 0.9rem;
        }

        .search-box input {
            width: 100%;
            padding: 9px 12px 9px 36px;
            border: 1.5px solid #fecdd3;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.85rem;
            color: #3d2233;
            background: #fff;
            transition: border-color .2s;
            outline: none;
        }

        .search-box input:focus { border-color: #e11d48; }

        .filter-bar select {
            padding: 9px 14px;
            border: 1.5px solid #fecdd3;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.85rem;
            color: #3d2233;
            background: #fff;
            outline: none;
            cursor: pointer;
            transition: border-color .2s;
        }

        .filter-bar select:focus { border-color: #e11d48; }

        /* ── Table ── */
        .table-wrapper { padding: 0; }
        .table-responsive { border-radius: 0 0 16px 16px; }

        table.bpms-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }

        table.bpms-table thead tr { background: #fff1f2; }

        table.bpms-table thead th {
            padding: 14px 16px;
            font-weight: 600;
            font-size: 0.78rem;
            color: #9f1239;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            white-space: nowrap;
        }

        table.bpms-table tbody tr {
            border-bottom: 1px solid #fff1f2;
            transition: background .15s;
        }

        table.bpms-table tbody tr:last-child { border-bottom: none; }
        table.bpms-table tbody tr:hover { background: #fff5f6; }

        table.bpms-table tbody td {
            padding: 14px 16px;
            vertical-align: middle;
            color: #3d2233;
            border: none;
        }

        .sno-cell { width: 40px; color: #9e6b7e; font-size: 0.8rem; font-weight: 600; }

        /* ── Apt badge — red tint ── */
        .apt-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #fff1f2;
            color: #e11d48;
            font-size: 0.78rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 6px;
            letter-spacing: 0.3px;
            border: 1px solid #fecdd3;
        }

        /* ── Customer cell ── */
        .customer-cell { display: flex; align-items: center; gap: 10px; }

        .avatar-circle {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            /* Muted red-rose gradient for rejected appointments */
            background: linear-gradient(135deg, #be123c, #e11d48);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            flex-shrink: 0;
            opacity: 0.85;
        }

        .customer-name { font-weight: 600; font-size: 0.88rem; }

        /* ── Phone / date / time cells ── */
        .phone-cell, .date-cell, .time-cell {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.84rem;
            color: #5c3a4e;
            white-space: nowrap;
        }

        .phone-cell i { color: #e11d48; }
        .date-cell  i { color: #e91e63; }
        .time-cell  i { color: #ff4f81; }

        /* ── Status badge ── */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .status-pill.rejected {
            background: #fff1f2;
            color: #e11d48;
            border: 1px solid #fecdd3;
        }

        /* Strike-through X icon pulse */
        .status-pill.rejected .x-icon {
            width: 14px;
            height: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #e11d48;
            color: #fff;
            border-radius: 50%;
            font-size: 0.6rem;
            font-weight: 900;
            flex-shrink: 0;
        }

        /* ── Action buttons ── */
        .action-cell { display: flex; gap: 6px; align-items: center; }

        .btn-view {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 14px;
            background: linear-gradient(135deg, #e91e63, #ff4f81);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.78rem;
            font-weight: 600;
            text-decoration: none;
            transition: opacity .2s, transform .15s;
            cursor: pointer;
        }

        .btn-view:hover { opacity: .88; color: #fff; transform: translateY(-1px); }

        .btn-delete {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 14px;
            background: #fff0f3;
            color: #e91e63;
            border: 1.5px solid #f3c6d9;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.78rem;
            font-weight: 600;
            text-decoration: none;
            transition: all .2s;
            cursor: pointer;
        }

        .btn-delete:hover {
            background: #e91e63;
            color: #fff;
            border-color: #e91e63;
            transform: translateY(-1px);
        }

        /* ── Empty state ── */
        .empty-state { text-align: center; padding: 60px 20px; }

        .empty-state .empty-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #fff1f2;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem;
            color: #e11d48;
        }

        .empty-state h5 { font-weight: 600; color: #3d2233; margin-bottom: 8px; }
        .empty-state p  { color: #9e6b7e; font-size: 0.88rem; margin: 0; }

        /* ── Responsive ── */
        @media (max-width: 991px) { .dashboard-wrapper { margin-left: 0; } }
        @media (max-width: 576px) {
            .page-content { padding: 20px 14px; }
            .action-cell { flex-direction: column; }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <?php include_once('includes/sidebar.php'); ?>

    <!-- Header / Navbar -->
    <?php include_once('includes/header.php'); ?>

    <!-- Main Content -->
    <div class="dashboard-wrapper">
        <div class="page-content">

            <!-- ── Page Header ── -->
            <div class="page-header">
                <div class="page-header-left">
                    <h2><i class="bi bi-calendar-x me-2"></i>Rejected Appointments</h2>
                    <p>All declined and rejected appointment requests</p>
                </div>
                <nav class="breadcrumb-nav">
                    <a href="dashboard.php"><i class="bi bi-house-door me-1"></i>Dashboard</a>
                    <span class="mx-2">/</span>
                    <span>Rejected Appointments</span>
                </nav>
            </div>

            <?php
            /* ── Count rejected appointments for stat strip ── */
            $count_result   = mysqli_query($con, "SELECT COUNT(*) as total FROM tblbook WHERE Status='Rejected'");
            $count_row      = mysqli_fetch_assoc($count_result);
            $total_rejected = $count_row['total'];
            ?>

            <!-- ── Stat Strip ── -->
            <div class="stat-strip">
                <div class="stat-pill">
                    <span class="dot dot-rejected"></span>
                    Rejected Appointments:
                    <span class="count"><?php echo $total_rejected; ?></span>
                </div>
                <div class="stat-pill">
                    <i class="bi bi-x-circle-fill" style="color:#e11d48;"></i>
                    Status: Declined / Rejected
                </div>
            </div>

            <!-- ── Main Card ── -->
            <div class="card-bpms">

                <!-- Card Header — deep red for rejected context -->
                <div class="card-bpms-header">
                    <h5>
                        <i class="bi bi-x-octagon-fill"></i>
                        Rejected Appointment List
                    </h5>
                    <a href="all-appointment.php" class="header-link">
                        <i class="bi bi-grid-3x2-gap"></i> All Appointments
                    </a>
                </div>

                <!-- Filter Bar -->
                <div class="filter-bar">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" id="searchInput" placeholder="Search by name, phone, or appointment no…">
                    </div>
                    <select id="sortSelect">
                        <option value="">Sort by Date</option>
                        <option value="asc">Date: Oldest First</option>
                        <option value="desc">Date: Newest First</option>
                    </select>
                </div>

                <!-- Table -->
                <div class="table-wrapper">
                    <div class="table-responsive">
                        <table class="bpms-table" id="appointmentTable">
                            <thead>
                                <tr>
                                    <th class="sno-cell">#</th>
                                    <th><i class="bi bi-hash me-1"></i>Apt No.</th>
                                    <th><i class="bi bi-person me-1"></i>Customer</th>
                                    <th><i class="bi bi-telephone me-1"></i>Mobile</th>
                                    <th><i class="bi bi-calendar3 me-1"></i>Date</th>
                                    <th><i class="bi bi-clock me-1"></i>Time</th>
                                    <th><i class="bi bi-info-circle me-1"></i>Status</th>
                                    <th><i class="bi bi-gear me-1"></i>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            /* ── Original query: Status = 'Rejected' — UNCHANGED ── */
                            $ret = mysqli_query($con, "SELECT tbluser.FirstName, tbluser.LastName, tbluser.Email,
                                        tbluser.MobileNumber, tblbook.ID as bid, tblbook.AptNumber,
                                        tblbook.AptDate, tblbook.AptTime, tblbook.Message,
                                        tblbook.BookingDate, tblbook.Status
                                   FROM tblbook
                                   JOIN tbluser ON tbluser.ID = tblbook.UserID
                                   WHERE tblbook.Status='Rejected'");

                            $cnt      = 1;
                            $has_rows = false;

                            while ($row = mysqli_fetch_array($ret)) :
                                $has_rows = true;

                                /* Avatar initials */
                                $initials = strtoupper(
                                    substr($row['FirstName'], 0, 1) .
                                    substr($row['LastName'],  0, 1)
                                );
                            ?>
                                <tr>
                                    <td class="sno-cell"><?php echo $cnt; ?></td>

                                    <td>
                                        <span class="apt-badge">
                                            <i class="bi bi-tag-fill"></i>
                                            <?php echo htmlspecialchars($row['AptNumber']); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <div class="customer-cell">
                                            <div class="avatar-circle"><?php echo $initials; ?></div>
                                            <div class="customer-name">
                                                <?php echo htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']); ?>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="phone-cell">
                                            <i class="bi bi-telephone-fill"></i>
                                            <?php echo htmlspecialchars($row['MobileNumber']); ?>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="date-cell">
                                            <i class="bi bi-calendar-event-fill"></i>
                                            <?php echo htmlspecialchars($row['AptDate']); ?>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="time-cell">
                                            <i class="bi bi-clock-fill"></i>
                                            <?php echo htmlspecialchars($row['AptTime']); ?>
                                        </div>
                                    </td>

                                    <td>
                                        <!-- Status is always 'Rejected' on this page — mirrors original logic -->
                                        <span class="status-pill rejected">
                                            <span class="x-icon">✕</span>
                                            <?php
                                            echo !empty($row['Status'])
                                                ? htmlspecialchars($row['Status'])
                                                : 'Not Updated Yet';
                                            ?>
                                        </span>
                                    </td>

                                    <td>
                                        <div class="action-cell">
                                            <a href="view-appointment.php?viewid=<?php echo $row['bid']; ?>"
                                               class="btn-view">
                                                <i class="bi bi-eye-fill"></i> View
                                            </a>
                                            <!-- Bug fix: original pointed to all-appointment.php?delid but handler is on this page -->
                                            <a href="rejected-appointment.php?delid=<?php echo $row['bid']; ?>"
                                               class="btn-delete"
                                               onclick="return confirm('Are you sure you want to delete this appointment?')">
                                                <i class="bi bi-trash3-fill"></i> Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                                $cnt++;
                            endwhile;

                            if (!$has_rows) :
                            ?>
                                <tr>
                                    <td colspan="8">
                                        <div class="empty-state">
                                            <div class="empty-icon">
                                                <i class="bi bi-calendar-x"></i>
                                            </div>
                                            <h5>No Rejected Appointments</h5>
                                            <p>There are no rejected appointments on record. All looks good!</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div><!-- /.table-wrapper -->

            </div><!-- /.card-bpms -->

        </div><!-- /.page-content -->

        <!-- Footer -->
        <?php include_once('includes/footer.php'); ?>
    </div><!-- /.dashboard-wrapper -->

    <!-- Live Search & Sort JS -->
    <script>
        /* ── Live search across all visible table row text ── */
        document.getElementById('searchInput').addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            const rows  = document.querySelectorAll('#appointmentTable tbody tr');
            rows.forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(query) ? '' : 'none';
            });
        });

        /* ── Sort by appointment date (col index 4) ── */
        document.getElementById('sortSelect').addEventListener('change', function () {
            const order = this.value;
            if (!order) return;
            const tbody = document.querySelector('#appointmentTable tbody');
            const rows  = Array.from(tbody.querySelectorAll('tr'));
            rows.sort((a, b) => {
                const dateA = new Date(a.cells[4]?.innerText?.trim() || '');
                const dateB = new Date(b.cells[4]?.innerText?.trim() || '');
                return order === 'asc' ? dateA - dateB : dateB - dateA;
            });
            rows.forEach(r => tbody.appendChild(r));
        });
    </script>

</body>
</html>
<?php } ?>